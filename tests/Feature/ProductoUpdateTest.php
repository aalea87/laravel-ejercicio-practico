<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductoUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_admin_or_editor_can_update_product(): void
    {
        $userService = new UserService();

        $token = $userService->createToken($userService->getUserAdminTest());

        $producto = Producto::get()->first();

        if(!$producto){
            \App\Models\Producto::factory(1)->create();
            $producto = Producto::get()->first();
        }

        $response = $this->put('/api/productos/'.$producto->id, [
                'nombre'        => 'Producto Editado',
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

        $response->assertSee("Se ha actualizado el producto");

        $response->assertStatus(200);
    }
}
