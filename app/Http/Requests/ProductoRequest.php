<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre'        => "required|max:255",
            'precio'        => "required|min:0.1",
            'cantidad'      => "required|min:0",
            'categoria'     => "required",
            'etiqueta'      => "required",
            'descripcion'   => "required",
            'info'          => "required|max:255",
            'valoracion'    => "required",
            'sku'           => "required|unique:producto,sku",
            'imagenes'      => "required",
        ];
    }

    public function attributes()
    {
        return [
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
    }

    public function messages(){

        return [
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
    }
}
