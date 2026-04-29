<!DOCTYPE html>
<html lang="es">

<head>
    <!-- 
         CONFIGURACIÓN BÁSICA DEL DOCUMENTO
     -->

    <meta charset="UTF-8">

    <!-- Responsive: adapta el diseño a dispositivos móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Productos</title>

    <!-- CSS principal del proyecto -->
    <link rel="stylesheet" href="/css/main.css">

    <!-- Bootstrap (framework UI para componentes como dropdown, offcanvas, etc.) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fuente Poppins (usada en todo el sitio) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

<header>

    <!-- 
         TOP BAR (barra superior)
         
         - Información rápida (contacto)
         - Acceso a cuenta
         - Redes sociales
     -->
    <div class="top-bar">
        <div class="top-container">

            <!-- Texto informativo -->
            <div class="top-left">
                CONTACTATE CON NOSOTROS
            </div>

            <!-- Acciones + redes -->
            <div class="top-right">

                <!-- Login / registro -->
                <a href="#">INICIAR SESIÓN / REGÍSTRATE</a>

                <!-- Iconos sociales (convertidos a blanco con CSS filter) -->
                <img src="/img/instagram-svgrepo-com.svg" alt="Instagram">
                <img src="/img/facebook-svgrepo-com.svg" alt="Facebook">
            </div>

        </div>
    </div>


    <!-- 
         NAV PRINCIPAL (DESKTOP)
         
         Layout tipo:
         [ vacío ] [ menú + logo ] [ iconos ]
         
         - Grid en desktop
         - Flex en mobile (definido en CSS)
     -->
    <nav class="main-nav">

        <!-- BOTÓN HAMBURGUESA (solo visible en mobile vía CSS) -->
        <button class="menu-toggle">☰</button>

        <!-- COLUMNA IZQUIERDA (vacía para centrar contenido) -->
        <div></div>

        <!-- COLUMNA CENTRAL -->
        <div class="nav-center">

            <!-- DROPDOWN 1 (Bootstrap) -->
            <div class="dropdown">

                <!-- data-bs-toggle activa el dropdown de Bootstrap -->
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    Atelier
                </a>

                <!-- Menú desplegable -->
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Acción</a></li>
                </ul>
            </div>

            <!-- LOGO PRINCIPAL -->
            <img src="/img/logo-david-salomon.webp" class="logo" alt="Logo principal">

            <!-- DROPDOWN 2 -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    Studio
                </a>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Acción</a></li>
                </ul>
            </div>

        </div>

        <!-- COLUMNA DERECHA (iconos) -->
        <div class="nav-right">

            <!-- Búsqueda -->
            <img src="/img/search-svgrepo-com.svg" alt="Buscar">

            <!-- Carrito -->
            <img src="/img/cart-ico.svg" alt="Carrito">
        </div>

    </nav>

</header>


<!-- 
     OVERLAY (fondo oscuro)
     
     - Se activa cuando el menú mobile está abierto
     - Bloquea interacción con el contenido detrás
     - Controlado por JS (clase .visible)
 -->
<div id="mobileMenuOverlay" class="mobile-menu-overlay"></div>


<!-- 
     MENÚ MOBILE (FULLSCREEN)
     
     - Se abre con el botón hamburguesa
     - Animación: slide desde la izquierda
     - Controlado con clases:
         .active → visible
         .visible → overlay activo
 -->
<div id="mobileMenu" class="mobile-menu">

    <div class="mobile-menu-inner">

        <!-- 
             HEADER DEL MENÚ MOBILE
             
             - Logo
             - Botón cerrar (X)
         -->
        <div class="mobile-menu-header">

            <img src="/img/logo-david-salomon.webp" class="mobile-logo" alt="Logo mobile">

            <!-- Botón cerrar -->
            <button id="mobileMenuClose" class="mobile-menu-close">
                ✕
            </button>
        </div>


        <!-- 
             NAVEGACIÓN MOBILE
             
             - Dropdowns personalizados (no Bootstrap)
             - Controlados con JS (toggle de clase .open)
         -->
        <div class="mobile-nav-links">

            <!-- DROPDOWN MOBILE -->
            <div class="mobile-dropdown">

                <!-- Trigger -->
                <div class="mobile-nav-item dropdown-toggle-mobile">
                    ATELIER
                </div>

                <!-- Submenú -->
                <div class="mobile-submenu">
                    <a href="#">Acción</a>
                </div>
            </div>

            <div class="mobile-dropdown">
                <div class="mobile-nav-item dropdown-toggle-mobile">
                    STUDIO
                </div>

                <div class="mobile-submenu">
                    <a href="#">Acción</a>
                </div>
            </div>

        </div>


        <!-- 
             FOOTER DEL MENÚ MOBILE
             
             - Acciones de usuario (login / registro)
         -->
        <div class="mobile-menu-actions">

            <!-- Separador visual -->
            <div class="mobile-menu-divider"></div>

            <!-- Sección de cuenta -->
            <div class="mobile-menu-auth">

                <p class="mobile-menu-contact-label">CUENTA</p>

                <a href="#" class="mobile-auth-link">INICIAR SESIÓN</a>
                <span class="mobile-auth-sep">/</span>
                <a href="#" class="mobile-auth-link">REGISTRARSE</a>

            </div>

        </div>

    </div>
</div>