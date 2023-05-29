<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'        => $this->faker->sentence(5),
            'descripcion'   => $this->faker->paragraph(),
            'categoria'     => $this->faker->randomElement(['Juguetes','Ropa','Zapatos','Cabello','Perfumes']),
            'etiqueta'      => $this->faker->randomElement(['Para Niños','Para Niñas', 'Para Bebes']),
            'cantidad'      => $this->faker->numberBetween(20,80),
            'precio'        => $this->faker->randomFloat(2,0,50),
            'info_adicional'=> $this->faker->text(100),
            'valoracion'    => $this->faker->randomFloat(2,0,5),
            'sku'           => substr($this->faker->uuid(),0,8),
        ];
    }
}
