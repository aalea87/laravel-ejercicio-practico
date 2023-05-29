<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\Producto;
use App\Services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProductoController extends Controller
{
    private ProductoService $productoService;

    public function __construct(ProductoService $productoService) {

        $this->middleware('ability:producto_read')->only('get');
        $this->middleware('ability:producto_read')->only('show');
        $this->middleware('ability:producto_create')->only('create');
        $this->middleware('ability:producto_edit')->only('update');
        $this->middleware('ability:producto_delete')->only('destroy');

        $this->productoService = $productoService;
    }

    public function get(Request $request) {
        try{

            $listado = $this->productoService->getProductoPorLimite($request);

            return response()->json(['success' => true, 'data'=> $listado], Response::HTTP_OK);

        }catch(\Exception $ex){
            return response()->json(['success' => false, 'msg'=> $ex->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function count(Request $request) {
        try{

            $cantidad = $this->productoService->getCantidadProducto($request);

            return response()->json(['success' => true, 'count'=> $cantidad], Response::HTTP_OK);

        }catch(\Exception $ex){
            return response()->json(['success' => false, 'msg'=> $ex->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(),$this->productoService->getValidateRules(),$this->productoService->getValidateMessage(),$this->productoService->getValidateAttributes());

        if ($validator->fails())
            return response()->json(['success' => false, 'msg' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);

        $producto = $this->productoService->save($request);

        if($producto)
            return response()->json(['success' => true, 'msg'=> "Se ha creado el producto $request->nombre correctamente."], Response::HTTP_CREATED);
        else
            return response()->json(['success' => false, 'msg'=> "Ocurrió un problema al crear el producto."], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function update(Request $request, string $id) {

        $producto = Producto::find($id);

        if($producto){

            $validator = Validator::make($request->all(), $this->productoService->getValidateRules($id),$this->productoService->getValidateMessage(),$this->productoService->getValidateAttributes());

            if ($validator->fails())
                return response()->json(['success' => false, 'msg' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);

            $success = $this->productoService->save($request, $producto);

            if($success)
                return response()->json(['success' => true, 'msg'=> "Se ha actualizado el producto $request->nombre correctamente."], Response::HTTP_OK);
            else
                return response()->json(['success' => false, 'msg'=> "Ocurrió un problema al actualizar el producto."], Response::HTTP_OK);
        }else
            return response()->json(['success' => false, 'msg'=> "El producto es incorrecto."], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function updatePatch(Request $request, string $id) {

        $producto = Producto::find($id);

        if($producto){

            $success = $this->productoService->save($request, $producto);

            if($success)
                return response()->json(['success' => true, 'msg'=> "Se ha actualizado el producto $request->nombre correctamente."], Response::HTTP_OK);
            else
                return response()->json(['success' => false, 'msg'=> "Ocurrió un problema al actualizar el producto."], Response::HTTP_OK);
        }else
            return response()->json(['success' => false, 'msg'=> "El producto es incorrecto."], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function destroy(string $id) {

        $producto = Producto::find($id);

        if($producto){

            if($producto->delete())
                return response()->json(['success' => true, 'msg'=> "Se ha eliminado el producto correctamente."], Response::HTTP_ACCEPTED);
            else
                return response()->json(['success' => false, 'msg'=> "Ocurrió un problema al eliminar el producto."], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else
            return response()->json(['success' => false, 'msg'=> "El producto es incorrecto."], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function show(string $sku) {

        $producto = Producto::where('sku',$sku)->first();

        if($producto){

            $data = $producto;
            $data['imagenes'] = $producto->imagenes;

            return response()->json(['success' => true, 'data'=> $data], Response::HTTP_OK);
        }else
            return response()->json(['success' => false, 'msg'=> "El SKU del producto es incorrecto."], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function sell(string $id) {

        $producto = Producto::find($id);

        $data = $producto;
        $data['imagenes'] = $producto->imagenes;

        if($producto)
            return response()->json(['success' => true, 'data'=> $data], Response::HTTP_OK);
        else
            return response()->json(['success' => false, 'msg'=> "El producto es incorrecto."], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
