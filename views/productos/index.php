<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid p-0">


    <!-- 
         HERO
     -->
    <div class="hero">
        <div>
            <h1>STUDIO</h1>
            <p>Home / Studio</p>
        </div>
    </div>

    <!-- 
         BARRA STICKY MOBILE
     -->
    <div class="d-flex d-md-none justify-content-between align-items-center mobile-bar">
        <span id="mostrar_productos_mobile" class="mobile-count-text">Cargando...</span>
        <button class="btn-filtros-mobile" data-bs-toggle="offcanvas" data-bs-target="#filtrosMobile" aria-label="Abrir filtros">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3z" />
            </svg>
            Filtros
            <span class="badge-filtros d-none" id="badgeFiltrosMobile">0</span>
        </button>
    </div>

    <!-- 
         CONTENIDO PRINCIPAL
     -->
    <div class="container mt-4 mb-5">
        <div class="row g-4">
            <p class="filtros-titulo">Filtros</p>

            <!-- FILTROS DESKTOP -->
            <div class="col-md-3 d-none d-md-block">
                <div class="filtros">

                    <!-- Slider precio (se mueve via JS) -->
                    <div id="sliderContainer" class="filtro-box">
                        <h6>Precio</h6>
                        <hr>
                        <div class="price-slider">
                            <div class="slider">
                                <div class="progress" id="sliderProgress"></div>
                            </div>
                            <div class="range-input">
                                <input type="range" id="precioMin" min="0" max="20000" value="0" step="100">
                                <input type="range" id="precioMax" min="0" max="20000" value="20000" step="100">
                            </div>
                            <div class="price-input d-flex justify-content-between mt-2">
                                <span id="precioMinLabel">$0</span>
                                <span id="precioMaxLabel">$20,000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Talla -->
                    <div class="filtro-box">
                        <h6>Talla</h6>
                        <hr>
                        <select class="form-select filtro-select" id="talla"></select>
                    </div>

                    <!-- Color -->
                    <div class="filtro-box">
                        <h6>Color</h6>
                        <hr>
                        <div id="color" class="color-lista"></div>
                    </div>

                    <!-- Buscar -->
                    <div class="filtro-box">
                        <h6>Buscar</h6>
                        <hr>
                        <div class="input-buscar-wrap">
                            <input type="text" class="form-control filtro-input" id="buscar" placeholder="Nombre del producto…">
                            <span class="input-buscar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PRODUCTOS -->
            <div class="col-md-9">

                <!-- Barra superior desktop -->
                <div class="d-none d-md-flex justify-content-between align-items-center mb-3">
                    <span id="mostrar_productos" class="text-muted" style="font-size:.88rem;">Cargando...</span>
                    <select class="form-select w-auto filtro-select" id="orden">
                        <option value="desc">Precio mayor</option>
                        <option value="asc">Precio menor</option>
                    </select>
                </div>

                <!-- Grid de productos -->
                <div class="row g-3" id="resultados"></div>
                <div id="paginacion" class="mt-4 d-flex justify-content-center flex-wrap gap-1"></div>

            </div>
        </div>
    </div>
</div>

<!-- 
     OFFCANVAS FILTROS MOBILE
 -->
<div class="offcanvas offcanvas-end offcanvas-filtros" tabindex="-1" id="filtrosMobile" aria-labelledby="filtrosMobileLabel">

    <div class="offcanvas-header">
        <div class="d-flex align-items-center gap-2">
            <span class="offcanvas-title" id="filtrosMobileLabel">Filtros</span>
            <span class="badge-filtros d-none" id="badgeFiltrosCanvas">0</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn-limpiar" id="btnLimpiarFiltros">Limpiar todo</button>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
    </div>

    <div class="offcanvas-body" id="offcanvasBody">

        <!-- El sliderContainer se mueve aquí en mobile via moverSlider() -->

        <!-- ORDEN mobile -->
        <div class="filtro-seccion-mobile">
            <p class="filtro-label-mobile">Ordenar por</p>
            <hr>
            <select class="form-select filtro-select" id="ordenMobile">
                <option value="desc">Precio mayor</option>
                <option value="asc">Precio menor</option>
            </select>
        </div>

        <!-- TALLA mobile (chips) -->
        <div class="filtro-seccion-mobile">
            <p class="filtro-label-mobile">Talla</p>
            <hr>
            <div class="chips-container" id="chipsContainer"></div>
        </div>

        <!-- COLOR mobile -->
        <div class="filtro-seccion-mobile">
            <p class="filtro-label-mobile">Color</p>
            <hr>
            <div class="color-lista-mobile" id="colorMobile"></div>
        </div>

        <!-- BUSCAR mobile -->
        <div class="filtro-seccion-mobile">
            <p class="filtro-label-mobile">Buscar</p>
            <hr>
            <div class="input-buscar-wrap">
                <input type="text" class="form-control filtro-input" id="buscarMobile" placeholder="Nombre del producto…">
                <span class="input-buscar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </span>
            </div>
        </div>

    </div>

    <!-- Ver resultados -->
    <div class="offcanvas-footer-aplicar">
        <button class="btn-aplicar-filtros" data-bs-dismiss="offcanvas" id="btnVerResultados">
            Ver resultados
        </button>
    </div>

</div>

<!-- WHATSAPP FAB -->
<a href="https://wa.me/521XXXXXXXXXX" target="_blank" class="whatsapp" aria-label="WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
    </svg>
</a>

<?php require __DIR__ . '/../layouts/footer.php'; ?>