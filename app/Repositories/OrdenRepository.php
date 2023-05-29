<?php

namespace App\Repositories;

use App\Models\Orden;
use App\Models\OrdenProducto;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class OrdenRepository {

    public function getOrden($user_id) : ?Orden{

        $orden = Orden::where([
            'user_id' => $user_id,
            'estado'  => Orden::ESTADO_PENDIENTE
        ])->first();

        return $orden;
    }

    public function getOrdenPendiente($user_id) : Orden{

        $orden = Orden::where([
            'user_id' => $user_id,
            'estado'  => Orden::ESTADO_PENDIENTE
        ])->first();

        if($orden)
            return $orden;
        else{
            return Orden::create([
                'importe_total' => 0.00,
                'user_id'       => $user_id,
                'estado'        => Orden::ESTADO_PENDIENTE
            ]);
        }
    }

    public function getOrdenProducto($orden_id, $producto_id) : ?OrdenProducto {

       return OrdenProducto::where([
            'orden_id'      => $orden_id,
            'producto_id'   => $producto_id,
        ])->first();
    }

    public function getOrdenProductos($orden_id) {

        return OrdenProducto::where(['orden_id' => $orden_id]);
    }

    public function getArticuloVendidos() {

        return  Producto::
                select(
                    'productos.id',
                    'productos.nombre',
                    'productos.descripcion',
                    'productos.categoria',
                    'productos.etiqueta',
                    'productos.info_adicional',
                    'productos.valoracion',
                    'productos.sku',
                    DB::raw('count(orden_productos.cantidad) as cantidad')
                )
                ->join('orden_productos', 'productos.id', '=', 'orden_productos.producto_id')
                ->join('ordenes', 'ordenes.id', '=', 'orden_productos.orden_id')
                ->groupBy('productos.id')
                ->having('ordenes.estado', Orden::ESTADO_PAGADA)
                ->get();
    }

    public function getGananciaTotal(){
        return Orden::where('estado', Orden::ESTADO_PAGADA)->sum('importe_total');
    }
}