<?php

/**
 * Clase Producto
 * 
 * Esta clase se encarga de gestionar todas las operaciones relacionadas con
 * los productos en la base de datos, incluyendo:
 * - Obtención de colores y tallas
 * - Consultas de precios máximos
 * - Filtrado dinámico de productos
 * - Paginación y ordenamiento
 */
class Producto
{
    /**
     * @var mysqli $db
     * Conexión a la base de datos
     */
    private $db;

    /**
     * Constructor de la clase
     * 
     * @param mysqli $conn Conexión activa a la base de datos
     */
    public function __construct($conn)
    {
        $this->db = $conn;
    }

    /**
     * Ejecuta una consulta SQL y devuelve los resultados como arreglo asociativo
     * 
     * Este método asume que la consulta siempre devuelve resultados.
     * En producción se recomienda validar errores.
     * 
     * @param string $sql Consulta SQL a ejecutar
     * @return array Resultado en formato asociativo
     */
    private function query($sql)
    {
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene todos los colores disponibles
     * 
     * @return array Lista de colores (id y nombre)
     */
    public function getColores()
    {
        return $this->query("SELECT id_color, name_color FROM color");
    }

    /**
     * Obtiene todas las tallas disponibles
     * 
     * @return array Lista de tallas (id y nombre)
     */
    public function getTallas()
    {
        return $this->query("SELECT id_size, name_size FROM size");
    }

    /**
     * Obtiene el precio máximo global de todos los productos
     * 
     * @return float|int Precio máximo o 0 si no hay resultados
     */
    public function getPrecioMaxGlobal()
    {
        $result = $this->query("SELECT MAX(price) as max_global FROM products");
        return $result[0]['max_global'] ?? 0;
    }

    /**
     * Obtiene la información completa de un producto por ID
     * 
     * Incluye:
     * - Datos del producto
     * - Categoría
     * - Talla
     * - Colores (si tiene)
     * 
     * @param int $id ID del producto
     * @return array|null Datos del producto o null si no existe
     */
    public function index_producto($id)
    {
        $result = $this->query("
            SELECT * 
            FROM products p
            JOIN category c ON p.id_category = c.id_category
            JOIN size t ON p.id_size = t.id_size
            LEFT JOIN products_colors pc ON p.id = pc.id_product
            LEFT JOIN color col ON pc.id_color = col.id_color
            WHERE id = $id
        ");

        return $result ?? null;
    }

    /**
     * Obtiene el precio máximo basado en filtros dinámicos
     * 
     * Este método se usa típicamente para ajustar sliders de precio en el frontend.
     * 
     * Filtros soportados:
     * - nombre (LIKE)
     * - talla (id_size)
     * - color (array de ids)
     * 
     * @param array $filtros
     * @return float|int Precio máximo filtrado
     */
    public function obtenerPrecioMaximo($filtros = [])
    {
        // Base del WHERE (permite concatenar condiciones dinámicamente)
        $where = "WHERE 1=1";

        // Filtro por nombre (búsqueda parcial)
        if (!empty($filtros['nombre'])) {
            $where .= " AND p.name LIKE '%{$filtros['nombre']}%'";
        }

        // Filtro por talla
        if (!empty($filtros['talla'])) {
            $where .= " AND p.id_size = {$filtros['talla']}";
        }

        // Filtro por múltiples colores
        if (!empty($filtros['color'])) {
            $ids = implode(",", $filtros['color']); // Convierte array a "1,2,3"
            $where .= " AND pc.id_color IN ($ids)";
        }

        // Consulta final
        $sql = "
            SELECT MAX(p.price) as max
            FROM products p
            LEFT JOIN products_colors pc ON p.id = pc.id_product
            $where
        ";

        $result = $this->query($sql);

        return $result[0]['max'] ?? 0;
    }

    /**
     * Filtra productos con múltiples criterios, incluyendo paginación
     * 
     * FUNCIONALIDAD:
     * - Búsqueda por nombre
     * - Rango de precios
     * - Filtro por talla
     * - Filtro por múltiples colores
     * - Ordenamiento por precio (ASC/DESC)
     * - Paginación
     * 
     * @param array $filtros
     * @return array Resultado con:
     *  - data: lista de productos
     *  - meta: información de paginación
     */
    public function filtrar($filtros)
    {
        $where = "WHERE 1=1";

        /**
         * Construcción dinámica del WHERE
         * 
         * Se agregan condiciones solo si el filtro existe,
         * permitiendo consultas flexibles sin múltiples métodos.
         */

        if ($filtros['nombre']) {
            $where .= " AND p.name LIKE '%{$filtros['nombre']}%'";
        }

        if ($filtros['precio_min']) {
            $where .= " AND p.price >= {$filtros['precio_min']}";
        }

        if ($filtros['precio_max']) {
            $where .= " AND p.price <= {$filtros['precio_max']}";
        }

        if ($filtros['talla']) {
            $where .= " AND p.id_size = {$filtros['talla']}";
        }

        if (!empty($filtros['color'])) {
            $colors = implode(',', $filtros['color']);
            $where .= " AND pc.id_color IN ($colors)";
        }

        /**
         * TOTAL GENERAL (sin filtros)
         */
        $total = $this->query("SELECT COUNT(*) as total FROM products")[0]['total'];

        /**
         * TOTAL FILTRADO
         * 
         * DISTINCT es importante porque un producto puede tener múltiples colores
         * y generar duplicados en el JOIN.
         */
        $filtrados = $this->query("
            SELECT COUNT(DISTINCT p.id) as total, MAX(price) as max
            FROM products p
            LEFT JOIN products_colors pc ON p.id = pc.id_product
            $where
        ")[0]['total'];

        /**
         * PAGINACIÓN
         */
        $pagina = $filtros['pagina'];
        $porPagina = $filtros['por_pagina'];

        // OFFSET: desde qué registro empezar
        $offset = ($pagina - 1) * $porPagina;

        /**
         * ORDENAMIENTO
         * 
         * Por defecto DESC si no es 'asc'
         */
        $order = $filtros['orden'] === 'asc' ? 'ASC' : 'DESC';

        /**
         * CONSULTA PRINCIPAL
         * 
         * DISTINCT evita duplicados por JOIN con colores
         */
        $productos = $this->query("
            SELECT DISTINCT p.id, p.name, p.price, p.imagen
            FROM products p
            LEFT JOIN products_colors pc ON p.id = pc.id_product
            $where
            ORDER BY p.price $order
            LIMIT $porPagina OFFSET $offset
        ");

        /**
         * RESPUESTA FINAL
         */
        return [
            "data" => $productos,
            "meta" => [
                "total" => (int)$total, // Total sin filtros
                "filtrados" => (int)$filtrados, // Total con filtros
                "pagina" => $pagina, // Página actual
                "por_pagina" => $porPagina, // Cantidad por página
                "total_paginas" => ceil($filtrados / $porPagina) // Total de páginas
            ]
        ];
    }
}