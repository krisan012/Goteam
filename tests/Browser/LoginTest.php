<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_login()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->assertTitle('Login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->keys('.v-field__input', '{enter}')
                    ->waitForLocation('/', 10)
                    ->assertPathIs('/');
        });
    }
}
