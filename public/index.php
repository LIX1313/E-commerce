<?php

/**
 * FRONT CONTROLLER (index.php)
 * 
 * Este archivo es el punto de entrada principal de la aplicación.
 * 
 * RESPONSABILIDADES:
 * - Capturar la URL solicitada
 * - Determinar qué recurso se debe cargar (API o vista)
 * - Redirigir al controlador correspondiente
 * 
 * Este enfoque es un "router manual" (sin framework).
 * Simula el comportamiento de frameworks como Laravel o Express.

Usuario entra → index.php (Front Controller)
              ↓
        Se analiza la URL
              ↓
 ┌────────────┴────────────┐
 │                         │
API                    VISTA
 │                         │
Controller           PHP/HTML
 │
Modelo (DB)

 */

require_once __DIR__ . '/../app/controllers/ProductosController.php';

/**
 * OBTENER LA URI ACTUAL
 * 
 * $_SERVER['REQUEST_URI'] puede incluir:
 * - ruta
 * - query params (?id=1)
 * 
 * parse_url(..., PHP_URL_PATH):
 * - Extrae solo la ruta (sin parámetros GET)
 * 
 * rtrim(..., '/'):
 * - Elimina la diagonal final para evitar duplicidad:
 *   /api/products/ → /api/products
 */
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');


/**
 * RUTAS API
 * 
 * Estas rutas devuelven JSON
 */


/**
 * ENDPOINT: /api/products
 * 
 * FUNCIONALIDAD:
 * - Lista productos
 * - Aplica filtros (nombre, precio, talla, color)
 * - Incluye paginación
 * 
 * MÉTODO: GET
 */
if ($uri === '/api/products') {
    header('Content-Type: application/json');

    (new ProductosController())->index();

    exit; // Importante: evita que se siga ejecutando el script
}


/**
 * ENDPOINT: /api/filtros
 * 
 * FUNCIONALIDAD:
 * - Devuelve colores y tallas disponibles
 * - Se usa para poblar filtros en frontend
 */
if ($uri === '/api/filtros') {
    header('Content-Type: application/json');

    (new ProductosController())->filtros();

    exit;
}


/**
 * ENDPOINT: /api/detalles
 * 
 * FUNCIONALIDAD:
 * - Devuelve información de un producto específico
 * - Recibe ?id=1
 */
if ($uri === '/api/detalles') {
    header('Content-Type: application/json');

    (new ProductosController())->index_producto();

    exit;
}


/**
 * RUTAS DE VISTAS (HTML)
 */

/**
 * VISTA: /detalles
 * 
 * Muestra la página de detalle del producto
 * (consumirá luego /api/detalles vía fetch)
 */
if ($uri === '/detalles') {
    require_once __DIR__ . '/../views/productos/detalles.php';
    exit;
}


/**
 * RUTA POR DEFECTO
 * 
 * Si no coincide con ninguna ruta anterior:
 * - Se carga la vista principal (listado de productos)
 */
require_once __DIR__ . '/../views/productos/index.php';