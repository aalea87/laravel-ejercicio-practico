<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {

        $user = "User".random_int(0,100);
        $response = $this->post('/api/register',[
            'name'       => "$user",
            'email'      => "$user@gmail.com",
            'password'   => 'Awrfddsk.2342',
            'confirmPassword'  => 'Awrfddsk.2342',
        ]);

        $response->assertSee("Se ha registrado correctamente");

        $response->assertStatus(200);
    }
}
