# Sistema Web de Listado y Gestión de Productos

![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4?logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?logo=javascript&logoColor=black)
![MySQL](https://img.shields.io/badge/MySQL-Opcional-4479A1?logo=mysql&logoColor=white)

Sistema web para la visualización y filtrado dinámico de productos. Permite explorar un catálogo mediante distintos criterios sin necesidad de recargar la página.

---

## Tabla de contenidos

- [Funcionalidades](#funcionalidades)
- [Stack tecnológico](#stack-tecnológico)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración de base de datos](#configuración-de-base-de-datos-opcional)
- [Uso del sistema](#uso-del-sistema)

---

## Funcionalidades

- Búsqueda en tiempo real por nombre de producto
- Filtro por color
- Filtro por talla
- Filtro por rango de precios (slider interactivo)
- Paginación de resultados
- Diseño completamente responsive (desktop y mobile)
- Consumo de API mediante AJAX (Fetch)
- Vista de detalle de producto con zoom

---

## Stack tecnológico

| Capa | Tecnología |
|------|------------|
| Frontend | HTML5, CSS3, JavaScript (Vanilla), Bootstrap 5 |
| Backend | PHP (arquitectura basada en controladores) |
| Comunicación | Fetch API (AJAX) |
| Base de datos | MySQL *(opcional)* |

---

## Estructura del proyecto
![Captura de la estructura.](/public/img/img_1.png)

---

## Requisitos

- PHP `>= 7.4`
- Servidor local: XAMPP, WAMP, Laragon o similar
- Navegador web moderno
- MySQL *(opcional, según implementación)*

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/LIX1313/E-commerce.git
```

### 2. Ubicar el proyecto en el servidor

Mueve o clona el proyecto dentro del directorio raíz de tu servidor local:
C:\xampp\htdocs\tu-proyecto     # Windows (XAMPP)
/opt/lampp/htdocs/tu-proyecto   # Linux (XAMPP)

### 3. Iniciar el servidor

Este proyecto utiliza la carpeta `public/` como punto de entrada (front controller), por lo que se recomienda ejecutar el servidor embebido de PHP.

#### Opción recomendada (Servidor embebido de PHP)

Ejecuta el siguiente comando en la raíz del proyecto:

```bash
php -S localhost:8000 -t public
```
### Luego abre tu navegador en:

```bash
http://localhost:8000
```

### Esto asegura que:

1. index.php dentro de /public sea la raíz
2. Las rutas funcionen correctamente (como /api/products, /detalles, etc.)

## Configuración de base de datos *(opcional)*

Si tu implementación utiliza MySQL, sigue estos pasos:

1. Crea una base de datos en MySQL
2. Importa el archivo `.sql` incluido en el proyecto
3. Configura las credenciales en el archivo de conexión

**Ejemplo de configuración:**

```php
$config = [
    'host'     => 'localhost',
    'user'     => 'root',
    'password' => '',
    'database' => 'nombre_bd'
];
```

---

## Uso del sistema

1. Accede a la página principal desde `http://localhost/tu-proyecto/public`
2. Aplica los filtros disponibles según tus necesidades:
   - **Nombre** — búsqueda en tiempo real
   - **Precio** — slider de rango
   - **Talla** — selector por categoría
   - **Color** — selector visual
3. Los resultados se actualizan dinámicamente sin recargar la página
4. Haz clic en cualquier producto para ver su detalle con zoom

---