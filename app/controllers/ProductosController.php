<?php

/**
 * Controlador de Productos
 * 
 * Este controlador actúa como intermediario entre:
 * - El cliente (frontend / API)
 * - El modelo Producto (acceso a base de datos)
 * 
 * RESPONSABILIDADES:
 * - Recibir parámetros HTTP (GET)
 * - Validar y sanitizar datos
 * - Invocar métodos del modelo
 * - Formatear respuestas JSON
 */

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../config/database.php';

class ProductosController
{
    /**
     * @var Producto
     * Instancia del modelo Producto
     */
    private $producto;

    /**
     * Constructor
     * 
     * Inicializa la conexión al modelo usando la conexión global.
     */
    public function __construct()
    {
        global $conn;
        $this->producto = new Producto($conn);
    }

    /**
     * Método principal: listado de productos con filtros
     * 
     * FUNCIONALIDAD:
     * - Obtiene parámetros desde la URL (GET)
     * - Aplica validaciones
     * - Llama al modelo para filtrar productos
     * - Devuelve resultados paginados en formato JSON
     * 
     * ENDPOINT típico:
     * /api/productos?nombre=camisa&precio_min=100&pagina=1
     */
    public function index()
    {
        try {

            /**
             * CAPTURA DE FILTROS
             * 
             * Se usan valores por defecto para evitar errores si no vienen en la URL.
             */
            $filtros = [
                'nombre' => $_GET['nombre'] ?? '',
                'precio_min' => $_GET['precio_min'] ?? null,
                'precio_max' => $_GET['precio_max'] ?? null,
                'talla' => $_GET['talla'] ?? null,
                'color' => $_GET['color'] ?? [],
                'orden' => $_GET['orden'] ?? 'desc',
                'pagina' => $_GET['pagina'] ?? 1,
                'por_pagina' => $_GET['por_pagina'] ?? 12
            ];

            /**
             * VALIDACIONES DE ENTRADA
             */

            // Validación de longitud del nombre (evita consultas pesadas o abusos)
            if (strlen($filtros['nombre']) > 50) {
                throw new Exception("Nombre demasiado largo");
            }

            // Validación de valores numéricos
            if ($filtros['precio_min'] && !is_numeric($filtros['precio_min'])) {
                throw new Exception("precio_min inválido");
            }

            if ($filtros['precio_max'] && !is_numeric($filtros['precio_max'])) {
                throw new Exception("precio_max inválido");
            }

            if ($filtros['talla'] && !is_numeric($filtros['talla'])) {
                throw new Exception("talla inválida");
            }

            /**
             * NORMALIZACIÓN DE DATOS
             */

            // Asegura que color siempre sea un array (para soportar múltiples selecciones)
            if (!is_array($filtros['color'])) {
                $filtros['color'] = [$filtros['color']];
            }

            // Validación de ordenamiento (evita valores inválidos o inyección)
            if (!in_array($filtros['orden'], ['asc', 'desc','nuevo'])) {
                $filtros['orden'] = 'desc';
            }

            // Paginación segura (mínimo 1)
            $filtros['pagina'] = max(1, (int)$filtros['pagina']);
            $filtros['por_pagina'] = max(1, (int)$filtros['por_pagina']);

            /**
             * LLAMADAS AL MODELO
             */

            // Obtiene productos filtrados + metadata
            $resultado = $this->producto->filtrar($filtros);

            // Precio máximo global (sin filtros)
            $maxPrecioGlobal = $this->producto->getPrecioMaxGlobal();

            // Precio máximo basado en filtros actuales
            $precio_max = $this->producto->obtenerPrecioMaximo($filtros);

            /**
             * RESPUESTA EXITOSA
             */
            echo json_encode([
                "status" => "success",
                "data" => $resultado['data'],
                "meta" => $resultado['meta'],
                "config" => [
                    // Se envían como enteros para frontend (sliders, etc.)
                    "precio_max_global" => (int)$maxPrecioGlobal,
                    "precio_max" => (int)$precio_max
                ]
            ]);
        } catch (Exception $e) {

            /**
             * MANEJO DE ERRORES
             */

            // Código HTTP para error de cliente (bad request)
            http_response_code(400);

            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene datos para filtros del frontend
     * 
     * Devuelve:
     * - Lista de colores
     * - Lista de tallas
     * 
     * Ideal para poblar selects, checkboxes o filtros dinámicos.
     */
    public function filtros()
    {
        $colores = $this->producto->getColores();
        $tallas = $this->producto->getTallas();

        echo json_encode([
            "colores" => $colores,
            "tallas" => $tallas
        ]);
    }

    /**
     * Obtiene un producto específico por ID
     * 
     * FUNCIONALIDAD:
     * - Valida el ID recibido
     * - Consulta el modelo
     * - Devuelve detalles del producto
     * 
     * ENDPOINT:
     * /api/producto?id=1
     */
    public function index_producto()
    {
        try {
            $id = $_GET['id'] ?? null;

            // Validación del ID
            if ($id && !is_numeric($id)) {
                throw new Exception("ID inválido");
            }

            // Consulta al modelo
            $resultado = $this->producto->index_producto($id);

            echo json_encode([
                "status" => "success",
                "data" => $resultado
            ]);
        } catch (Exception $e) {

            http_response_code(400);

            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}