<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imagen extends Model
{
    use HasFactory;

    protected $table = "imagenes";

    protected $fillable = [
        'imagen',
        'producto_id'
    ];

    protected $hidden = [
        'id',
        'producto_id',
        'created_at',
        'updated_at'
    ];

    public function producto(): BelongsTo {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
}
