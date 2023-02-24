<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('first_name', fake()->firstName())
                ->type('last_name', fake()->lastName())
                ->type('email', fake()->email())
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('#register')
                ->waitForLocation('/login', 10)
                ->assertPathIs('/login');
        });
    }
}
