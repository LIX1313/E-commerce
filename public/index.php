<?php

/**
 * FRONT CONTROLLER (index.php)
 *
 * Este archivo es el punto de entrada principal de la aplicación.
 *
 * - Capturar la URL solicitada
 * - Determinar qué recurso se debe cargar (API o vista)
 * - Redirigir al controlador correspondiente
 *
 * Este enfoque es un "router manual" (sin framework).

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
 * MANEJADOR GLOBAL DE EXCEPCIONES
 *
 * Captura cualquier excepción no controlada lanzada por los controladores.
 *
 * - Si la ruta es /api/* → responde JSON con 500
 * - Si es una vista       → responde HTML genérico con 500
 *
 * Evita que PHP exponga stack traces o HTML de error en endpoints JSON.
 */
set_exception_handler(function (Throwable $e) {
    $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    if (str_starts_with($uri, '/api/')) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Error interno del servidor']);
    } else {
        http_response_code(500);
        echo '<p>Error interno del servidor</p>';
    }
});


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
 * Estas rutas devuelven JSON.
 * Cada endpoint valida:
 *  - Método HTTP permitido
 *  - Parámetros requeridos (cuando aplica)
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

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Método no permitido']);
        exit;
    }

    (new ProductosController())->index();
    exit;
}


/**
 * ENDPOINT: /api/filtros
 *
 * FUNCIONALIDAD:
 * - Devuelve colores y tallas disponibles
 * - Se usa para poblar filtros en frontend
 *
 * MÉTODO: GET
 */
if ($uri === '/api/filtros') {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        exit;
    }

    (new ProductosController())->filtros();
    exit;
}


/**
 * ENDPOINT: /api/detalles
 *
 * FUNCIONALIDAD:
 * - Devuelve información de un producto específico
 * - Recibe ?id=1
 *
 * MÉTODO: GET
 * PARÁMETROS: id (entero positivo, requerido)
 */
if ($uri === '/api/detalles') {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        exit;
    }

    $id = $_GET['id'] ?? null;

    // Parámetro ausente
    if ($id === null) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'El parámetro id es requerido']);
        exit;
    }

    // Parámetro inválido (no numérico o negativo)
    // ctype_digit((string)$id) — verifica que el valor sea únicamente dígitos 
    if (!ctype_digit((string)$id) || (int)$id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'El id debe ser un número entero positivo']);
        exit;
    }

    (new ProductosController())->index_producto();
    exit;
}


/**
 * CATCH-ALL DE RUTAS /api/* NO DEFINIDAS
 *
 * Si la URI comienza con /api/ pero no coincidió con ningún endpoint:
 * - Responde 404 en JSON (no HTML)
 *
 * Evita que rutas inexistentes caigan en la vista por defecto.
 */
if (str_starts_with($uri, '/api/')) {
    header('Content-Type: application/json');
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Endpoint no encontrado']);
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
    require_once __DIR__ . '/../views/products/detalles.php';
    exit;
}


/**
 * RUTA POR DEFECTO
 *
 * Si no coincide con ninguna ruta anterior:
 * - Se carga la vista principal (listado de productos)
 */
require_once __DIR__ . '/../views/products/index.php';