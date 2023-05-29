<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = "productos";

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'etiqueta',
        'cantidad',
        'precio',
        'info_adicional',
        'valoracion',
        'sku'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function imagenes() : HasMany {
        return $this->hasMany(Imagen::class, 'producto_id', 'id');
    }
}
