<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PokemonTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function pokemon_images_are_loaded()
    {
        $user = User::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/register')
                ->type('first_name', fake()->firstName())
                ->type('last_name', fake()->lastName())
                ->type('email', fake()->email())
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('#register')
                ->waitForLocation('/login', 10)
                ->assertPathIs('/login')
                ->assertTitle('Login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->keys('.v-field__input', '{enter}')
                ->waitForLocation('/', 10)
                ->assertPathIs('/')
                ->waitFor('.pokemon-list', 10)
                ->waitFor('.pokemon-img', 10);
        });
    }
}
