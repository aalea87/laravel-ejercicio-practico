<?php

namespace App\Http\Controllers;

use App\Services\OrdenService;
use App\Services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrdenController extends Controller
{

    private OrdenService $ordenService;
    private ProductoService $productoService;

    public function __construct(OrdenService $ordenService, ProductoService $productoService) {
        $this->ordenService = $ordenService;
        $this->productoService = $productoService;
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(),[
            'producto'      => "required|min:0",
            'cantidad'      => "required|numeric|max:1|min:1"
        ],[
            'producto.required' => "El :attribute es obligatorio.",
            'cantidad.required' => "El :attribute es obligatorio.",
            'cantidad.min'      => "El :attribute debe ser mayor a 0.",
            'cantidad.max'      => "El :attribute no debe exceder 1 artículo.",
        ],[
            'producto'        => 'Producto',
            'cantidad'        => 'Cantidad',
        ]);

        if ($validator->fails())
            return response()->json(['success' => false, 'msg' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);

        $array_response = $this->ordenService->createCarrito($request->user()->id,$request->producto,$request->cantidad);

        if($array_response['success'])
            return response()->json($array_response, Response::HTTP_CREATED);
        else
            return response()->json($array_response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function destroy(Request $request, int $producto_id) {


        $array_response = $this->ordenService->destroyCarrito($request->user()->id,$producto_id);

        if($array_response['success'])
            return response()->json($array_response, Response::HTTP_ACCEPTED);
        else
            return response()->json($array_response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function show(Request $request) {

        $orden = $this->ordenService->getOrdenPendiente($request->user()->id);

        if($orden == null)
            return response()->json(['success' => false, 'msg' => "Ocurrió un problema al crear la orden."], Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->ordenService->getOrdenData($orden);

        return response()->json(['success' => true, 'data' => $data], Response::HTTP_ACCEPTED);
    }

    public function sell(Request $request) {

        $array_response = $this->ordenService->sellCarrito($request->user()->id);

        return response()->json($array_response, $array_response['success'] ? Response::HTTP_ACCEPTED : Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function vendidos() {

        $listado_articulos = $this->ordenService->ArticuloVendidos();

        return response()->json([
            'success'   => true,
            'total'     => count($listado_articulos),
            'data'      => $listado_articulos
        ],Response::HTTP_OK);

    }

    public function ganancias() {
        $total = $this->ordenService->getGananciaTotal();

        return response()->json([
            'success'   => true,
            'msg'       => "La ganacia es de $total en total.",
        ],Response::HTTP_OK);
    }

    public function reporteVacios() {

        $listado_productos = $this->productoService->getProductosSinStock();

        return response()->json([
            'success'   => true,
            'total'     => count($listado_productos),
            'data'      => $listado_productos
        ],Response::HTTP_OK);
    }
}
