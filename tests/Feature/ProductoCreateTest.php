<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductoCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_admin_or_editor_can_create_product(): void
    {
        $userService = new UserService();

        $token = $userService->createToken($userService->getUserAdminTest());

        $response = $this->post('/api/productos',[
                'nombre'        => 'Producto Test',
                'precio'        => '5.50',
                'cantidad'      => 50,
                'categoria'     => 'Juguetes',
                'etiqueta'      => 'Para Niños',
                'descripcion'   => 'Descripcion.....',
                'info'          => 'Informacion adicional',
                'valoracion'    => '1.8',
                'sku'           => random_int(1,1000),
                'imagenes'      => 'imagen.png,imagen2.png'
            ],[
                'Accept'        => 'application/json',
                'Authorization' => "Bearer ".$token,
        ]);

        $response->assertSee("Se ha creado el producto");

        $response->assertStatus(201);
    }
}
