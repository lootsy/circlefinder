<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginRegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function testGuestSeesLoginForm()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
    }

    public function test_guest_home_redirects_to_login()
    {
        $response = $this->get(route('home'));
        
        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_login()
    {
        $params = [
            'email' => 'fake@mail.boo',
            'password' => 'ABCDEFG123'
        ];

        $response = $this->post('/login', $params);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_guest_can_register()
    {
        $user_data = [
            'name' => 'Test Testman',
            'email' => 'test@testing.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];

        $response = $this->post('/register', $user_data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'email' => $user_data['email']
        ]);

        $this->assertAuthenticated();

        $user = \App\User::where('email', $user_data['email'])->first();

        $this->assertAuthenticatedAs($user);
    }

    public function test_guest_cannot_register_if_registered()
    {
        $user = factory(\App\User::class)->create();

        $user_data = [
            'name' => 'dont care',
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];

        $response = $this->post('/register', $user_data);

        $response->assertStatus(302);

        $response->assertSessionHasErrors();
        
        $this->assertGuest();
    }

    public function test_user_can_login()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_wrong_pass()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(302);

        $this->assertGuest();
    }
    
    public function test_user_can_logout()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(302);
        
        $this->assertGuest();
    }

    public function test_admin_login_redicrects_to_dashboard()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get('/login');
        
        $response->assertStatus(302);

        $response->assertRedirect(route('home'));
    }

    public function test_guest_can_see_reset_password()
    {
        $response = $this->get('/password/reset');
        
        $response->assertStatus(200);
    }

    public function test_guest_can_reset_password()
    {
        # TODO: Perform the complete test
        $response = $this->get('/password/reset/abcdefg');
        
        $response->assertStatus(200);
    }
}
