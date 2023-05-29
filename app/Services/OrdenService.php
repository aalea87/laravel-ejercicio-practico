<?php

namespace App\Services;

use App\Models\Orden;
use App\Models\OrdenProducto;
use App\Models\Producto;
use App\Repositories\OrdenRepository;
use Illuminate\Support\Facades\DB;

class OrdenService {

    private OrdenRepository $ordenRepository;

    public function __construct(OrdenRepository $ordenRepository) {
        $this->ordenRepository = $ordenRepository;
    }

    public function getOrdenPendiente($user_id): ?Orden {
        return $this->ordenRepository->getOrdenPendiente($user_id);
    }

    public function createCarrito($user_id, $producto_id, $cantidad){

        $orden = $this->getOrdenPendiente($user_id);

        if($orden == null)
            return ['success' => false, 'msg' => "Ocurrió un problema al crear la orden."];

        $ordenProducto = $this->ordenRepository->getOrdenProducto($orden->id, $producto_id);

        if($ordenProducto == null){

            $producto = Producto::find( $producto_id );

            if($producto){

                if($producto->cantidad >= $cantidad){

                    $ordenProducto = OrdenProducto::create([
                        'orden_id'      => $orden->id,
                        'producto_id'   => $producto_id,
                        'importe'       => $producto->precio,
                        'cantidad'      => $cantidad,
                    ]);

                    $producto->decrement('cantidad', $cantidad);

                    $orden->increment('importe_total', $producto->precio * $cantidad);

                }else
                    return ['success' => false, 'orden_id' => $orden->id, 'msg' => "El producto no tiene existencia."];

            }else
                return ['success' => false, 'orden_id' => $orden->id, 'msg' => "El producto no es correcto."];

            return ['success' => true, 'orden_id' => $orden->id, 'msg' => "El Artículo $producto->nombre se agrego correctamente.", 'items' => $this->getOrdenData($orden)];

        }else
            return ['success' => false, 'orden_id' => $orden->id, 'msg' => "Ya existe ese artículo, solo se permite uno cada compra.", 'items' => $this->getOrdenData($orden)];

    }

    public function destroyCarrito($user_id, $producto_id){

        $orden = $this->getOrdenPendiente($user_id);

        if($orden == null)
            return ['success' => false, 'msg' => "Ocurrió un problema al crear la orden."];

        $deleted = false;

        $ordenProductos = OrdenProducto::where([
            'orden_id'      => $orden->id,
            'producto_id'   => $producto_id
        ])->first();

        if($ordenProductos){

            $ordenProductos->producto->increment('cantidad', $ordenProductos->cantidad);

            $orden->decrement('importe_total', $ordenProductos->importe * $ordenProductos->cantidad);

            $deleted = $ordenProductos->delete();

            if($deleted)
                return ['success' => true, 'msg' => "Se ha eliminado correctamente."];
        }

        return ['success' => false, 'msg' => "No existe ese producto en la orden."];
    }

    public function sellCarrito($user_id) : array{

        $orden = $this->getOrdenPendiente($user_id);

        if($orden == null)
            return ['success' => false, 'msg' => "Ocurrió un problema al crear la orden."];

        if(count($orden->ordenProductos) == 0)
            return ['success' => false, 'msg' => "El carrito de compra se encuentra vacio."];

        $orden->estado = Orden::ESTADO_PAGADA;
        $pagada = $orden->save();

        if($pagada){
            $data = $this->getOrdenData($orden);
            return ['success' => true, 'msg' => "La Orden se ha pagado correctamente.", 'data' => $data];
        }else
            return ['success' => false, 'msg' => "No se ha podido ejecutar el pago."];
    }

    public function getOrdenData($orden) : array {

        $data = [
            "importe"   => $orden->importe_total,
            "estado"    => $orden->nombreEstado(),
            'productos' => array()
        ];

        foreach ($orden->ordenProductos as $ordenProducto) {
            array_push(
                $data['productos'], [
                    'id'        => $ordenProducto->producto_id,
                    'nombre'    => $ordenProducto->producto->nombre,
                    'importe'   => $ordenProducto->importe,
                    'cantidad'  => $ordenProducto->cantidad
            ]);
        }
        return $data;
    }

    public function ArticuloVendidos(){
        return  $this->ordenRepository->getArticuloVendidos();
    }

    public function getGananciaTotal(){
        return  $this->ordenRepository->getGananciaTotal();
    }
}