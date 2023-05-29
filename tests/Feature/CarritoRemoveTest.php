<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Services\UserService;
use Tests\TestCase;

class CarritoRemoveTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_client_remove_item_cart(): void
    {
        $userService = new UserService();

        $token = $userService->createToken($userService->getUserClientTest());

        $producto = Producto::get()->first();

        if(!$producto){
            \App\Models\Producto::factory(1)->create();
            $producto = Producto::get()->first();
        }

        $response = $this->withToken($token)->delete('/api/carrito/'.$producto->id);

        $response->assertSee("Se ha eliminado correctamente");

        $response->assertStatus(202);
    }
}
