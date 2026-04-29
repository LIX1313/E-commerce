<!-- 
     FOOTER PRINCIPAL
     
     Este footer está dividido en:
     - 4 columnas de navegación (links organizados por categoría)
     - Logo de marca
     - Redes sociales
     - Copyright
     
     DISEÑO:
     - En desktop: grid de 4 columnas
     - En tablet: 2 columnas
     - En mobile: 1 columna centrada
 -->

<footer class="main-footer">

    <!-- 
         CONTENEDOR PRINCIPAL (GRID)
         
         Estructura:
         4 columnas con enlaces organizados
     -->
    <div class="footer-container">

        <!-- COLUMNA 1: ATELIER
             Representa la parte de diseño / productos exclusivos -->
        <div class="footer-col">
            <h4>ATELIER</h4>
            <ul>
                <!-- Links de navegación (actualmente placeholders '#') -->
                <li><a href="#">Colecciones</a></li>
                <li><a href="#">Alta costura</a></li>
                <li><a href="#">Pedidos especiales</a></li>
            </ul>
        </div>

        <!-- COLUMNA 2: STUDIO
             Contenido visual / branding / marketing -->
        <div class="footer-col">
            <h4>STUDIO</h4>
            <ul>
                <li><a href="#">Editorial</a></li>
                <li><a href="#">Campañas</a></li>
                <li><a href="#">Galería</a></li>
            </ul>
        </div>

        <!-- COLUMNA 3: INFORMACIÓN
             Información útil para el usuario -->
        <div class="footer-col">
            <h4>INFORMACIÓN</h4>
            <ul>
                <li><a href="#">Contacto</a></li>
                <li><a href="#">Envíos</a></li>
                <li><a href="#">Políticas</a></li>
            </ul>
        </div>

        <!-- COLUMNA 4: CUENTA
             Acciones relacionadas con autenticación -->
        <div class="footer-col">
            <h4>CUENTA</h4>
            <ul>
                <li><a href="#">Iniciar sesión</a></li>
                <li><a href="#">Registrarse</a></li>
            </ul>
        </div>

    </div>


    <!-- 
         LOGO DE MARCA
         
         - Refuerza identidad visual
         - Se recomienda usar versión en blanco (ya aplicada en CSS con filter)
     -->
    <div class="footer-logo">
        <img src="/assets/img/logo-david-salomon.webp" alt="Logo de la marca">
    </div>


    <!-- 
         REDES SOCIALES
         
         - Iconos SVG
         - Estilizados en CSS (blanco + opacidad)
         - Se recomienda envolver en <a> para enlaces reales
     -->
    <div class="footer-social">
        <img src="/assets/img/instagram-svgrepo-com.svg" alt="Instagram">
        <img src="/assets/img/facebook-svgrepo-com.svg" alt="Facebook">
    </div>


    <!-- 
         COPYRIGHT
         
         - Información legal básica
         - Año dinámico recomendado en producción
     -->
    <div class="footer-bottom">
        <p>© 2026 David Salomon. Todos los derechos reservados.</p>
    </div>

</footer>


<!-- 
     SCRIPTS
     
     - Bootstrap Bundle incluye:
       * Popper.js
       * JS de componentes (offcanvas, modal, etc.)
     
     - productos.js:
       Lógica de:
       * filtros dinámicos
       * paginación
       * consumo de API
       
       defer:
     - Cargar un archivo JavaScript externo sin bloquear la carga del HTML
     -->

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/productos.js" defer></script>

</html>