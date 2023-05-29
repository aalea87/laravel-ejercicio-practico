<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductoDeleteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $userService = new UserService();

        $token = $userService->createToken($userService->getUserAdminTest());

        $producto = Producto::get()->last();

        if(!$producto){
            \App\Models\Producto::factory(1)->create();
            $producto = Producto::get()->first();
        }

        $response = $this->withToken($token)->delete('/api/productos/'.$producto->id);

        $response->assertSee("Se ha eliminado el producto");

        $response->assertStatus(202);
    }
}
