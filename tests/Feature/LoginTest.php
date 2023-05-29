<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_login(): void
    {
        $response = $this->post('/api/login',[
            'email'      => 'admin@gmail.com',
            'password'   => 'Clave.1234'
        ]);

        $response->assertSee("Bienvenido");

        $response->assertStatus(200);
    }
}
