<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Services\UserService;
use Tests\TestCase;

class CarritoCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_client_create_item_cart(): void
    {
        $userService = new UserService();

        $token = $userService->createToken($userService->getUserClientTest());

        $producto = Producto::get()->first();

        if(!$producto){
            \App\Models\Producto::factory(1)->create();
            $producto = Producto::get()->first();
        }

        $response = $this->withToken($token)->post('/api/carrito/',[
            'producto'      => $producto->id,
            'cantidad'      => 1
        ]);

        $response->assertSee("se agrego correctamente");

        $response->assertStatus(201);
    }
}
