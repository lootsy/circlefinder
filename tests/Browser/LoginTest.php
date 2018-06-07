<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\CleanCookies;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;
    use CleanCookies;

    private $homePath = '/home';

    public function test_user_can_register()
    {
        $user_data = [
            'name' => 'dont care',
            'email' => 'test@test.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];

        $this->browse(function (Browser $browser) use ($user_data) {
            $browser->visit('/register')
                    ->type('name', $user_data['name'])
                    ->type('email', $user_data['email'])
                    ->type('password', $user_data['password'])
                    ->type('password_confirmation', $user_data['password_confirmation'])
                    ->press('Register')
                    ->assertPathIs($this->homePath);

            $this->assertDatabaseHas('users', [
                'email' => $user_data['email']
            ]);

            $browser->visit('/logout')->assertPathIs('/');
            
            $this->assertGuest();
        });
    }

    public function test_user_can_login()
    {
        $user = factory(\App\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs($this->homePath);
        });
    }
}
