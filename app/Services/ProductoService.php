<?php

namespace App\Services;

use App\Models\Imagen;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoService {

    private const validate_rules = [
        'nombre'        => "required|max:255",
        'precio'        => "required|min:0.1",
        'cantidad'      => "required|min:0",
        'categoria'     => "required",
        'etiqueta'      => "required",
        'descripcion'   => "required",
        'info'          => "required|max:255",
        'valoracion'    => "required",
        'sku'           => "required|unique:productos,sku",
        'imagenes'      => "required",
    ];

    private const validate_messages = [
        'nombre.required'   => "El :attribute es obligatorio.",

        'precio.required'   => "El :attribute es obligatorio.",
        'precio.min'        => "El :attribute debe ser mayor a 0.1.",

        'cantidad.required' => "La :attribute es obligatorio.",
        'cantidad.min'      => "El :attribute debe ser mayor a 0.",

        'categoria.required'    => "La :attribute es obligatorio.",
        'etiqueta.required'     => "La :attribute es obligatorio.",
        'descripcion.required'  => "La :attribute es obligatorio.",
        'info.required'         => "La :attribute Adicional es obligatorio.",
        'valoracion.required'   => "La :attribute es obligatorio.",
        'sku.required'          => "El :attribute es obligatorio.",
        'sku.unique'            => "El :attribute ya existe.",
        'imagenes.required'     => "Las :attribute es obligatorio.",
    ];

    private const validate_attributes = [
        'nombre'        => 'Nombre del Producto',
        'precio'        => 'Precio',
        'cantidad'      => 'Cantidad',
        'categoria'     => 'Categoría',
        'etiqueta'      => 'Etiqueta',
        'descripcion'   => 'Descripción',
        'info'          => 'Información Adicional',
        'valoracion'    => 'Valoración',
        'sku'           => 'SKU',
        'imagenes'      => 'Imágenes',
    ];

    public function getValidateRules($id = null){
        if($id){
            $rule_update = self::validate_rules;
            $rule_update['sku'] = "required|unique:productos,sku,".$id;
            return $rule_update;
        }else
            return self::validate_rules;
    }

    public function getValidateMessage(){
        return self::validate_messages;
    }

    public function getValidateAttributes(){
        return self::validate_attributes;
    }

    public function save(Request $request, Producto $producto = null) : Producto {

        if($producto == null)
            $producto = new Producto();

        if($request->has('nombre'))     $producto->nombre = $request->nombre;
        if($request->has('precio'))     $producto->precio = $request->precio;
        if($request->has('cantidad'))   $producto->cantidad = $request->cantidad;
        if($request->has('categoria'))  $producto->categoria = $request->categoria;
        if($request->has('categoria'))  $producto->categoria = $request->categoria;
        if($request->has('etiqueta'))   $producto->etiqueta = $request->etiqueta;
        if($request->has('descripcion'))$producto->descripcion = $request->descripcion;
        if($request->has('info'))       $producto->info_adicional = $request->info;
        if($request->has('valoracion')) $producto->valoracion = $request->valoracion;
        if($request->has('sku'))        $producto->sku = $request->sku;
        $producto->save();

        if(isset($params['imagenes'])){
            $imagenes = explode(",", $params['imagenes']);

            if($producto->imagenes != null)
                $producto->imagenes()->delete();

            foreach($imagenes as $img){
                Imagen::create([
                    'producto_id' => $producto->id,
                    'imagen'      => $img
                ]);
            }
        }
        return $producto;
    }


    public function getProductosSinStock(){
       return Producto::where('cantidad',0)->get();
    }

    private function prepareQuery(Request $request){
        $query = Producto::orderBy('created_at','DESC');

        if($request->has('nombre'))
            $query->where('nombre', 'like', "%$request->nombre%");

        if($request->has('descripcion'))
            $query->where('descripcion', 'like', "%$request->descripcion%");

        if($request->has('categoria'))
            $query->where('categoria', 'like', "%$request->categoria%");

        if($request->has('etiqueta'))
            $query->where('etiqueta', 'like', "%$request->etiqueta%");

        if($request->has('info'))
            $query->where('info_adicional', 'like', "%$request->info%");

        if($request->has('cantidad'))
            $query->where('cantidad', $request->cantidad);

        if($request->has('precio'))
            $query->where('precio', $request->precio);

        if($request->has('valoracion'))
            $query->where('valoracion', $request->valoracion);

        if($request->has('sku'))
            $query->where('sku', $request->sku);

        return $query;
    }

    public function getProductoPorLimite(Request $request){

        $query = $this->prepareQuery($request);

        return $query->paginate(10);
    }

    public function getCantidadProducto(Request $request){

        $query = $this->prepareQuery($request);

        return $query->count();
    }

}