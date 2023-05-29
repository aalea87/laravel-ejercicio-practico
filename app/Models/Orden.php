<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orden extends Model
{
    use HasFactory;

    const ESTADO_PENDIENTE = 1;
    const ESTADO_PAGADA = 2;

    private $estadoNombre = [1 => 'Pendiente', 2 => 'Pagada'];

    protected $table = "ordenes";

    protected $fillable = [
        'importe_total',
        'user_id',
        'estado'
    ];

    protected $attributes = [
        'estado' => self::ESTADO_PENDIENTE
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ordenProductos(): HasMany {
        return $this->hasMany(OrdenProducto::class, 'orden_id', 'id');
    }

    public function isPendiente() : bool
    {
        return $this->estado == self::ESTADO_PENDIENTE;
    }

    public function isPagada() : bool
    {
        return $this->estado == self::ESTADO_PAGADA;
    }

    public function nombreEstado() : string
    {
        return $this->estadoNombre[$this->estado];
    }

    public function pagada()
    {
        return $this->estado = self::ESTADO_PAGADA;
    }

}
