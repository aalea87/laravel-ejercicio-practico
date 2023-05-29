<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        'cantidad',
        'importe',
        'producto_id',
        'orden_id'
    ];

    public function producto(): BelongsTo {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

    public function orden(): BelongsTo {
        return $this->belongsTo(Orden::class, 'orden_id', 'id');
    }
}
