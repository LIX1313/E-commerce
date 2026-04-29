# E-commerce — Sistema Web de Listado y Gestión de Productos

![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4?logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?logo=javascript&logoColor=black)
![MySQL](https://img.shields.io/badge/MySQL-Opcional-4479A1?logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Estado-Activo-brightgreen)

---

## Tabla de contenidos

- [Descripción del proyecto](#descripción-del-proyecto)
- [Stack tecnológico](#stack-tecnológico)
- [Instalación y configuración](#instalación-y-configuración)

---

## Descripción del proyecto

**E-commerce** es un sistema web orientado a la gestión y visualización de catálogos de productos. Está construido sobre una arquitectura PHP basada en controladores y un frontend liviano con JavaScript y Bootstrap 5.

El sistema permite a los usuarios explorar productos mediante filtros combinables en tiempo real, sin recargar la página, gracias al uso de la Fetch API (AJAX).

### Vista general

![Vista previa del sistema](/public/assets/img/vista_general.gif)

> [!NOTE]
> Tarda unos segundos en cargar el gif.

### Funcionalidades

- **Búsqueda en tiempo real** por nombre de producto
- **Filtro por color** mediante selector visual
- **Filtro por talla** con selector por categoría
- **Filtro por rango de precios** con slider interactivo
- **Paginación de resultados** dinámica
- **Diseño completamente responsive** (desktop, tablet y mobile)
- **Comunicación asíncrona** mediante Fetch API (AJAX)
- **Vista de detalle de producto** con zoom interactivo (desktop y mobile)

### Estructura del proyecto

```
E-commerce/
├── public/                        # Punto de entrada (Front Controller)
│   ├── index.php                  # Router principal
│   └── assets/                    # Recursos estáticos
│       ├── css/                   # Estilos
│       ├── js/                    # Scripts JS
│       └── img/                   # Imágenes
│
├── app/                           # Lógica de negocio (Backend)
│   ├── controllers/               # Controladores
│   ├── models/                    # Modelos (acceso a datos)
│   └── config/                    # Configuración (DB, constantes)
│
├── views/                         # Vistas (Frontend)
│   ├── layouts/                   # Header y footer
│   └── productos/                 # Vistas de productos (listado, detalle)
│
├── database.sql                   # Script de base de datos
└── README.md                      # Documentación del proyecto
```

---

## Stack tecnológico

| Capa | Tecnología |
|------|------------|
| Frontend | HTML5, CSS3, JavaScript, Bootstrap 5 |
| Backend | PHP ≥ 7.4 (arquitectura basada en controladores) |
| Comunicación | Fetch API (AJAX) |
| Base de datos | MySQL *(opcional)* |
| Servidor | PHP Built-in Server / XAMPP / WAMP / Laragon |

---

## Instalación y configuración

### Requisitos previos

Antes de comenzar, asegúrate de tener instalado:

- **PHP** `>= 7.4`
- **Servidor local**: [XAMPP](https://www.apachefriends.org/), [WAMP](https://www.wampserver.com/) o [Laragon](https://laragon.org/)
- **Navegador web moderno** (Chrome, Firefox, Edge)
- **MySQL** *(opcional, según implementación)*
- **Git** para clonar el repositorio

### 1. Clonar el repositorio

```bash
git clone https://github.com/LIX1313/E-commerce.git
cd E-commerce
```

### 2. Ubicar el proyecto en el servidor

Mueve o clona el proyecto dentro del directorio raíz de tu servidor local:

```bash
# Windows (XAMPP)
C:\xampp\htdocs\E-commerce

# Linux (XAMPP)
/opt/lampp/htdocs/E-commerce
```

### 3. Iniciar el servidor

Este proyecto utiliza la carpeta `public/` como punto de entrada. Se recomienda el servidor embebido de PHP:

#### Opción recomendada — Servidor embebido de PHP

Ejecuta el siguiente comando desde la **raíz del proyecto**:

```bash
php -S localhost:8000 -t public
```

Luego abre tu navegador en:

```
http://localhost:8000
```

> **¿Por qué esta opción?** Garantiza que `index.php` dentro de `/public` sea la raíz y que todas las rutas funcionen correctamente (como `/api/products`, `/detalles`, etc.).

#### Opción alternativa — Servidor local (XAMPP / WAMP)

Accede directamente desde tu servidor configurado:

```
http://localhost/E-commerce/public/
```

### 4. Configuración de base de datos *(opcional)*

El proyecto incluye soporte para MySQL con las siguientes tablas:

**Tablas principales:**

| Tabla | Descripción |
|-------|-------------|
| `products` | Catálogo de productos |
| `size` | Tallas disponibles |
| `colors` | Colores disponibles |
| `category` | Categorías de productos |

**Tabla relacional:**

| Tabla | Descripción |
|-------|-------------|
| `products_colors` | Vincula productos con sus colores disponibles |

**Paso 1** — Crea una base de datos en MySQL:

```sql
CREATE DATABASE `e-commerce` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Paso 2** — Importa el archivo `.sql` incluido en el proyecto:

```bash
mysql -u root -p e-commerce < database.sql
```

O desde **phpMyAdmin**: selecciona la base de datos → pestaña *Importar* → sube `database.sql`.

**Paso 3** — Configura las credenciales en el archivo de conexión:

```php
$config = [
    'host'     => 'localhost',
    'user'     => 'root',
    'password' => '',
    'database' => 'e-commerce'
];
```

### Diagrama de la base de datos

![Diagrama de tablas](/public/assets/img/Schema_tablas_(productos).png)

---