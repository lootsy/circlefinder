<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\CleanCookies;

class AdminLoginTest extends DuskTestCase
{
    use DatabaseMigrations;
    use CleanCookies;

    public function test_admin_can_login()
    {
        $admin = factory(\App\Admin::class)->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/admin')
                    ->type('email', $admin->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs("/admin");
        });
    }
}
