<p align="center"><a href="https://laravel.com" target="_blank">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

# LARAVEL REST API, EJERCICIO PRACTICO USANDO SANCTUM
Una simple RESTAPI en Laravel, basado en un ejercicio práctico de una tienda.

Laravel Versión ...................................................................... 10.12.1

PHP Versión ........................................................................... 8.1.5

Gestor de Base de datos................................................................ MYSQL

## Installation
```bash
git clone https://github.com/aalea87/laravel-ejercicio-practico.git
cd laravel-rest-api
```
Abrir el archivo .env en el directorio principal y edita los detalles de su base datos.

Vaya a su consola preferida y ejecute los siguientes comandos:
```bash
composer install
php artisan migrate:fresh --seed
php artisan serve
```

Para ejecutar la batería de pruebas
```bash
php artisan test
```

#### **En el directorio principal está presente el archivo "postman_collection.json" para importar la colección de endpoints a Postman para mayor agilidad.**


## Endpoints:

#### Registro de Usuario:
```
POST /api/register
Body => {
    "name": "",
    "email": "",
    "password": "",
    "confirmPassword": ""
}

Response => {
    "success": true,
    "msg": "Se ha registrado correctamente.",
    "access_token": ""
}
```

#### Iniciar Sesion de Usuario:
```
POST /api/login
Body => {
    "email": "",
    "password": ""
}

Response => {
    "success": true,
    "msg": "Bienvenido usuario",
    "access_token": ""
}
```

## **A tener en cuenta:**
A cada petición a continuacion, se le pasa el access_token obtenido en la respuesta de login/register como bearer-token. El token expira en 30 minutos.


* **CRUD Productos**

#### Obtener productos:
```
GET	/api/productos
```
#### Obtener productos filtrados:
```
GET /api/productos?nombre=producto&descripcion=algo&etiqueta=algo&info=algo&cantidad=5&precio=50.20&valoracion=4.2&sku=abcd1234
```

#### Obtener la cantidad productos filtrados:
```
GET /api/productos/cantidad?nombre=producto&descripcion=algo&etiqueta=algo&info=algo&cantidad=5&precio=50.20&valoracion=4.2&sku=abcd1234
```

#### Crear un Producto:
```
POST /api/productos
Body => {
   "nombre": "",
   "precio": "",
   "cantidad": "",
   "categoria": "",
   "etiqueta": "",
   "descripcion": "",
   "info": "",
   "sku": "",
   "imagenes": "" // url imagenes separados por , Ej. imagen.png,imagen2.png
}
```

#### Editar Producto:
```
PUT /api/producto/{id}
Body => {
   "nombre": "",
   "precio": "",
   "cantidad": "",
   "categoria": "",
   "etiqueta": "",
   "descripcion": "",
   "info": "",
   "sku": "",
   "imagenes": "" // url imagenes separados por , Ej. imagen.png,imagen2.png
}

PATCH /api/producto/{id}
Body => {
    "nombre": "",
    "precio": "",
    "descripcion": "",
    "imagenes": "" // url imagenes separados por , Ej. imagen.png,imagen2.png
}
```

#### Eliminar Producto:
```
DELETE /api/producto/{id}
```
#### Mostrar datos de un producto por SKU:
```
GET /api/producto/{id}
```


* **Agregar al Carrito**

#### Agregar producto al carrito de compra:
```
POST /api/carrito
Body => {
   "producto": "", //ID del producto
   "cantidad": ""  //Limitado a 1 por el ejercicio práctico
}

Response => {
   "success": true,
   "orden_id":2, //id de la orden asociada al cliente
   "msg": "El Artículo 'Nombre del producto' se agrego correctamente.",
   "items": {
        "importe": 5.5, //Importe total de la orden
        "estado": "Pendiente", //Estado Pendiente o Pagado
        "productos": [
            {
                "id": 1,
                "nombre": "Nombre del producto",
                "importe": 5.5,
                "cantidad": 1
            }
        ]
    }
}
```

#### Eliminar producto del carrito de compra:
```
DELETE /api/carrito/{id_producto}

Response => {
   "success": true,
   "msg": "Se ha eliminado correctamente."
}
```


#### Mostrar los productos del carrito de compra:
```
GET /api/carrito

Response => {
   "success": true,
   "items": {
        "importe": 5.5, //Importe total de la orden
        "estado": "Pendiente", //Estado Pendiente o Pagado
        "productos": [
            {
                "id": 1,
                "nombre": "Nombre del producto",
                "importe": 5.5,
                "cantidad": 1
            }
        ]
    }
}
```


#### Confirmar la orden de compra pendiente:
```
PUT /api/carrito

Response => {
   "success": true,
   "msg": "La Orden se ha pagado correctamente.",
   "data": {
        "importe": 5.5,
        "estado": "Pagada",
        "productos": [
            {
                "id": 1,
                "nombre": "Nombre del producto",
                "importe": 5.5,
                "cantidad": 1
            }
        ]
    }
}
```

* **Reportes**
#### Reporte de artículos vendidos:
```
GET /api/vendidos

Response => {
    "success": true,
    "total": 1,
    "data": [
        {
            "id": 1,
            "nombre": "Nombre de producto",
            "descripcion": "Descripcion.....",
            "categoria": "Juguetes",
            "etiqueta": "Para Niños",
            "info_adicional": "Informacion adicional",
            "valoracion": 1.8,
            "sku": "AAT12345",
            "cantidad": 1
        },{
            ....
        }
    ]
}
```


#### Reporte de ganancia total:
```
GET /api/ganancias

Response => {
    "success": true,
    "msg": "La ganacia es de 10835.52 en total."
}
```

#### Reporte de productos sin stock:
```
GET /api/vacio

Response => {
    "success": true,
    "total": 1,
    "data": [
        {
            "id": 23,
            "nombre": "Nombre de producto",
            "descripcion": "Descripcion.....",
            "categoria": "Juguetes",
            "etiqueta": "Para Niños",
            "info_adicional": "Informacion adicional",
            "valoracion": 1.8,
            "sku": "AAT12345"
        }
    ]
}
```