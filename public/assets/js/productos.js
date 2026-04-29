/**
 *
 * Consumo de la API (/api/products, /api/filtros, /api/detalles)
 * Manejo de filtros (nombre, talla, color, precio)
 * Sincronización Desktop ↔ Mobile
 * Slider de precio dinámico
 * Paginación
 * Render de productos
 * Menú mobile fullscreen
 * Vista detalle + zoom tipo Mercado Libre
 *
 * Este archivo está diseñado para funcionar en DOS MODOS:
 *
 * 1. LISTADO de productos
 * 2. DETALLE de producto
 *
 * Detecta automáticamente cuál ejecutar.
 */

/* 
   VARIABLES GLOBALES (estado de la aplicación)
 */

/**
 * Endpoint base de la API
 */
const API = "/api/products";

/**
 * Página actual (paginación)
 */
let paginaActual = 1;

/**
 * Precio máximo global (sin filtros)
 * Se obtiene del backend SOLO una vez
 */
let maxGlobal = null;

/**
 * Precio máximo dinámico (con filtros activos)
 */
let maxActual = null;

/**
 * Indica si el usuario ya interactuó con el slider de precio
 * evita que el sistema sobrescriba su selección
 */
let usuarioTocoPrecio = false;

/**
 * Guarda el estado anterior de filtros
 * Se usa para detectar cambios y tomar decisiones inteligentes
 */
let estadoAnterior = { nombre: "", talla: "", colores: [] };

/**
 * Indica si el usuario está arrastrando el slider
 * Evita hacer demasiadas peticiones
 */
let isDragging = false;

/**
 * Timer global para debounce
 */
let debounceTimer = null;

/**
 * Estado de talla en mobile
 */
let tallaMobileActiva = "";

/* 
   INIT (Punto de entrada)
 */
document.addEventListener("DOMContentLoaded", () => {
  const esDetalle = document.getElementById("detalle-producto");

  if (esDetalle) {
    // SOLO DETALLE
    cargarDetalleProducto();
    return;
  }

  // SOLO LISTADO
  cargarFiltros();
  eventos();
  initPrecio();
  moverSlider();
  cargarProductos();
  initMenuMobile();
  initLimpiarFiltros();
  sincronizarOrdenMobile();
});
/**
 * Reubica el slider cuando cambia el tamaño de pantalla
 */
window.addEventListener("resize", moverSlider);

// DEBOUNCE
/**
 * Evita ejecutar una función muchas veces seguidas
 *
 * Ejemplo:
 * - Usuario escribe → no dispara API inmediatamente
 * - Espera 400ms → ejecuta
 */
function debounce(fn, delay = 400) {
  return (...args) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fn(...args), delay);
  };
}
/**
 * Versión debounce para cargar productos
 */
const cargarProductosDebounced = debounce(() => {
  if (!isDragging) cargarProductos(1);
}, 400);

// MENÚ MOBILE FULLSCREEN

function initMenuMobile() {
  const toggle = document.querySelector(".menu-toggle");
  const menu = document.getElementById("mobileMenu");
  const closeBtn = document.getElementById("mobileMenuClose");
  const overlay = document.getElementById("mobileMenuOverlay");

  if (!toggle || !menu || !overlay) return;
  /**
   * Abrir menú
   */
  const openMenu = () => {
    menu.classList.add("active");
    overlay.classList.add("visible");
    document.body.style.overflow = "hidden";
  };
  /**
   * Cerrar menú
   */
  const closeMenu = () => {
    menu.classList.remove("active");
    overlay.classList.remove("visible");
    document.body.style.overflow = "";
  };

  toggle.addEventListener("click", openMenu);
  closeBtn?.addEventListener("click", closeMenu);
  overlay.addEventListener("click", closeMenu);

  /**
   * Cerrar con tecla ESC
   */
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeMenu();
  });

  // DROPDOWN MOBILE
  document.querySelectorAll(".dropdown-toggle-mobile").forEach((item) => {
    item.addEventListener("click", () => {
      const parent = item.parentElement;
      parent.classList.toggle("open");
    });
  });
}

//
// SINCRONIZAR ORDEN DESKTOP ↔ MOBILE
//

function sincronizarOrdenMobile() {
  const ordenDesktop = document.getElementById("orden");
  const ordenMobile = document.getElementById("ordenMobile");

  if (!ordenDesktop || !ordenMobile) return;

  /**
   * Desktop → Mobile
   */

  ordenDesktop.addEventListener("change", () => {
    ordenMobile.value = ordenDesktop.value;
    cargarProductos(1);
  });

  /**
   * Mobile → Desktop
   */
  ordenMobile.addEventListener("change", () => {
    ordenDesktop.value = ordenMobile.value;
    cargarProductos(1);
  });
}

//
// BOTÓN LIMPIAR FILTROS (mobile offcanvas)
//

function initLimpiarFiltros() {
  document
    .getElementById("btnLimpiarFiltros")
    ?.addEventListener("click", () => {
      // Limpiar búsqueda
      const buscarM = document.getElementById("buscarMobile");
      const buscarD = document.getElementById("buscar");
      if (buscarM) buscarM.value = "";
      if (buscarD) buscarD.value = "";

      // Limpiar talla desktop
      const tallaD = document.getElementById("talla");
      if (tallaD) tallaD.value = "";

      // Limpiar chips de talla mobile
      tallaMobileActiva = "";
      document
        .querySelectorAll(".chip-talla")
        .forEach((c) => c.classList.remove("activo"));

      // Limpiar colores
      document
        .querySelectorAll(".color-check, .color-check-mobile")
        .forEach((cb) => {
          cb.checked = false;
        });

      cargarProductos(1);
      actualizarBadge();
    });
}

//
// BADGE DE FILTROS ACTIVOS
//

function actualizarBadge() {
  // Obtiene filtros activos
  const estado = leerFiltrosBase();
  // nombre → suma 1
  // talla → suma 1
  // colores → suma cantidad
  const total =
    (estado.nombre ? 1 : 0) + (estado.talla ? 1 : 0) + estado.colores.length;

  const badges = document.querySelectorAll(".badge-filtros");
  badges.forEach((b) => {
    // Muestra número
    b.textContent = total;
    // Oculta si no hay filtros
    b.classList.toggle("d-none", total === 0);
  });
}

//
// EVENTOS
//

function eventos() {
  /**
   * Cambio de talla
   */
  document.getElementById("talla")?.addEventListener("change", () => {
    cargarProductos(1);
    actualizarBadge();
  });
  /**
   * Búsqueda Desktop (con debounce)
   */
  document.getElementById("buscar")?.addEventListener(
    "input",
    debounce(() => {
      // Sincronizar con mobile
      const val = document.getElementById("buscar").value;

      // sincroniza con mobile
      const buscarM = document.getElementById("buscarMobile");
      if (buscarM) buscarM.value = val;

      cargarProductos(1);
      actualizarBadge();
    }, 400),
  );

  /**
   * Búsqueda Mobile (con debounce)
   */
  document.getElementById("buscarMobile")?.addEventListener(
    "input",
    debounce(() => {
      // Sincronizar con desktop
      const val = document.getElementById("buscarMobile").value;
      const buscarD = document.getElementById("buscar");
      if (buscarD) buscarD.value = val;

      cargarProductos(1);
      actualizarBadge();
    }, 400),
  );
}

//
// SLIDER — listeners
//
/**
 * Inicializa listeners del slider - Precio
 */
function initPrecio() {
  const minInput = document.getElementById("precioMin");
  const maxInput = document.getElementById("precioMax");

  const onInput = () => {
    usuarioTocoPrecio = true;
    /**
     * Actualiza UI sin hacer fetch inmediato
     */
    actualizarUI(maxActual ?? maxGlobal);

    /**
     * Llama con debounce
     */
    cargarProductosDebounced();
  };

  minInput.addEventListener("input", onInput);
  maxInput.addEventListener("input", onInput);
}

//
// SLIDER — actualizarUI
//

function actualizarUI(rangoMax) {
  if (!rangoMax) return;

  const minInput = document.getElementById("precioMin");
  const maxInput = document.getElementById("precioMax");
  const minLabel = document.getElementById("precioMinLabel");
  const maxLabel = document.getElementById("precioMaxLabel");
  const progress = document.getElementById("sliderProgress");

  let minVal = parseInt(minInput.value) || 0;
  let maxVal = parseInt(maxInput.value) || 0;

  /**
   * Validaciones
   */
  minVal = Math.max(0, Math.min(minVal, rangoMax));
  maxVal = Math.max(0, Math.min(maxVal, rangoMax));
  if (minVal > maxVal) minVal = maxVal;

  minInput.value = minVal;
  maxInput.value = maxVal;

  /**
   * Calcular porcentajes
   */
  const percentMin = (minVal / rangoMax) * 100;
  const percentMax = (maxVal / rangoMax) * 100;

  // Mueve inicio de la barra
  progress.style.left = percentMin + "%";
  // Define tamaño del rango activo
  progress.style.width = percentMax - percentMin + "%";

  /**
   * Aplicar al progress bar
   */
  minLabel.textContent = `$${minVal.toLocaleString()}`;
  maxLabel.textContent = `$${maxVal.toLocaleString()}`;
}

//
// SLIDER — aplicarRango
//

/**
 * Aplica un rango específico al slider de precios.
 *
 * Se encarga de:
 *  - Definir límites (min/max) dinámicos
 *  - Asignar valores actuales
 *  - Forzar actualización visual del componente
 *
 * @param {number} minVal - Valor mínimo seleccionado
 * @param {number} maxVal - Valor máximo seleccionado
 * @param {number} rangoMax - Máximo permitido (dinámico o global)
 */
function aplicarRango(minVal, maxVal, rangoMax) {
  // Guardamos el máximo actual (puede cambiar según filtros)
  maxActual = rangoMax;

  // Inputs tipo range
  const minInput = document.getElementById("precioMin");
  const maxInput = document.getElementById("precioMax");

  // Definir límites dinámicos del slider
  minInput.min = 0;
  minInput.max = rangoMax;
  maxInput.min = 0;
  maxInput.max = rangoMax;

  /**
   * Forzamos reflow (recalcular posición de los elementos) del DOM para que el navegador
   * reconozca los cambios de min/max antes de asignar valores.
   *
   * Sin esto, algunos navegadores ignoran el cambio.
   */
  void minInput.offsetWidth;
  void maxInput.offsetWidth;

  // Asignar valores actuales
  minInput.value = minVal;
  maxInput.value = maxVal;

  // Actualizar UI (barra visual + labels)
  actualizarUI(rangoMax);
}

/**
 * Resetea el slider usando el rango GLOBAL de precios.
 *
 * Se usa cuando:
 *  - No hay filtros activos
 *  - Carga inicial
 *
 * @param {number} max - Máximo global recibido del backend
 */
function resetSliderGlobal(max) {
  const rangoMax = max || maxGlobal;
  if (!rangoMax) return;

  // Guardar máximo global del sistema
  maxGlobal = rangoMax;

  // El slider deja de ser dinámico
  maxActual = null;

  // Usuario no ha interactuado
  usuarioTocoPrecio = false;

  // Reset completo: 0 → máximo global
  aplicarRango(0, rangoMax, rangoMax);
}

/**
 * Resetea el slider usando un rango DINÁMICO (filtrado).
 *
 * Se usa cuando:
 *  - Hay filtros activos (talla, color, búsqueda)
 *  - El rango de precios cambia según resultados
 *
 * @param {number} max - Nuevo máximo filtrado
 */
function resetSliderDinamico(max) {
  if (!max) return;

  // Reset de interacción del usuario
  usuarioTocoPrecio = false;

  // Ajustar slider al nuevo rango dinámico
  aplicarRango(0, max, max);
}

/**
 * Ajusta el máximo del slider SIN reiniciar los valores actuales.
 *
 * Cuando el usuario ya movió el slider y cambian los filtros.
 *
 * Ejemplo:
 *  - Usuario selecciona 0–500
 *  - Nuevo filtro reduce max a 300
 *  → Se ajusta sin romper la experiencia
 *
 * @param {number} max - Nuevo máximo permitido
 */
function ajustarMaxSinReset(max) {
  if (!max) return;

  const minInput = document.getElementById("precioMin");
  const maxInput = document.getElementById("precioMax");

  let minVal = parseInt(minInput.value) || 0;
  let maxVal = parseInt(maxInput.value) || 0;

  // Si el max actual excede el nuevo límite → recortarlo
  if (maxVal > max) maxVal = max;

  // Evitar inconsistencia (min > max)
  if (minVal > maxVal) minVal = maxVal;

  // Reaplicar rango sin resetear completamente
  aplicarRango(minVal, maxVal, max);
}
//
// FILTROS — cargar tallas y colores (desktop + mobile)
//
/**
 * Carga los filtros desde la API:
 *  - Tallas
 *  - Colores
 *
 * Genera dinámicamente:
 *  - Select (desktop)
 *  - Chips (mobile)
 *  - Checkboxes sincronizados
 *
 * Mantiene sincronización bidireccional entre desktop y mobile.
 */
function cargarFiltros() {
  fetch("/api/filtros")
    .then((res) => res.json())
    .then((data) => {
      //  TALLA DESKTOP
      let htmlTalla = `<option value="">Any</option>`;
      data.tallas?.forEach((t) => {
        htmlTalla += `<option value="${t.id_size}">${t.name_size}</option>`;
      });
      document.getElementById("talla").innerHTML = htmlTalla;

      //  CHIPS DE TALLA MOBILE
      const chips = document.getElementById("chipsContainer");
      if (chips) {
        // Chip "Any" inicial
        let chipsHTML = `
          <button class="chip-talla activo" data-value="">Any</button>
        `;
        data.tallas?.forEach((t) => {
          chipsHTML += `
            <button class="chip-talla" data-value="${t.id_size}">${t.name_size}</button>
          `;
        });
        chips.innerHTML = chipsHTML;

        /**
         * Evento de selección de talla en mobile
         * - Solo permite una activa (tipo radio)
         * - Sincroniza con desktop
         */
        chips.querySelectorAll(".chip-talla").forEach((chip) => {
          chip.addEventListener("click", () => {
            // Limpiar selección previa
            chips
              .querySelectorAll(".chip-talla")
              .forEach((c) => c.classList.remove("activo"));
            //Activa el seleccionado
            chip.classList.add("activo");

            // Guardar estado
            tallaMobileActiva = chip.dataset.value;

            // Sincronizar con select desktop
            const tallaD = document.getElementById("talla");
            if (tallaD) tallaD.value = tallaMobileActiva;

            cargarProductos(1);
            actualizarBadge();
          });
        });
      }

      //  COLOR DESKTOP
      let htmlColor = "";
      data.colores?.forEach((c) => {
        htmlColor += `
          <label class="d-flex align-items-center gap-2 mb-1">
            <input type="checkbox" class="color-check" value="${c.id_color}">
            ${c.name_color}
          </label>
        `;
      });
      document.getElementById("color").innerHTML = htmlColor;

      /**
       * Sincronización Desktop → Mobile
       */
      document.querySelectorAll(".color-check").forEach((cb) => {
        cb.addEventListener("change", () => {
          const valDesktop = cb.value;
          const cbMobile = document.querySelector(
            `.color-check-mobile[value="${valDesktop}"]`,
          );
          if (cbMobile) cbMobile.checked = cb.checked;

          cargarProductos(1);
          actualizarBadge();
        });
      });

      //  COLOR MOBILE
      const colorMobile = document.getElementById("colorMobile");
      if (colorMobile) {
        let htmlColorM = "";
        data.colores?.forEach((c) => {
          htmlColorM += `
            <div class="color-item-mobile">
              <input type="checkbox" class="color-check-mobile" id="colorM_${c.id_color}" value="${c.id_color}">
              <label for="colorM_${c.id_color}">${c.name_color}</label>
            </div>
          `;
        });
        colorMobile.innerHTML = htmlColorM;

        /**
         * Sincronización Mobile → Desktop
         */
        document.querySelectorAll(".color-check-mobile").forEach((cb) => {
          cb.addEventListener("change", () => {
            const valMobile = cb.value;
            const cbDesktop = document.querySelector(
              `.color-check[value="${valMobile}"]`,
            );
            if (cbDesktop) cbDesktop.checked = cb.checked;

            cargarProductos(1);
            actualizarBadge();
          });
        });
      }
    });
}

//
// FILTROS — helpers
//

/**
 * Obtiene el estado actual de los filtros base.
 *
 * Unifica desktop + mobile en un solo objeto.
 *
 * @returns {Object}
 * {
 *   nombre: string,
 *   talla: string,
 *   colores: array
 * }
 */
function leerFiltrosBase() {
  // Prioridad: el input que tenga valor
  const buscarD = document.getElementById("buscar")?.value?.trim() || "";
  const buscarM = document.getElementById("buscarMobile")?.value?.trim() || "";
  const nombre = buscarD || buscarM;

  // select desktop
  const talla = document.getElementById("talla")?.value || "";

  // Unión de colores (evita inconsistencias)
  const coloresDesktop = [
    ...document.querySelectorAll(".color-check:checked"),
  ].map((c) => c.value);

  const coloresMobile = [
    ...document.querySelectorAll(".color-check-mobile:checked"),
  ].map((c) => c.value);

  /**
   * Set elimina duplicados automáticamente
   */
  const colores = [...new Set([...coloresDesktop, ...coloresMobile])];

  return { nombre, talla, colores };
}

/**
 * Determina si existen filtros activos.
 *
 * @param {Object} estado
 * @returns {boolean}
 */
function hayFiltrosBase(estado) {
  return !!(estado.nombre || estado.talla || estado.colores.length > 0);
}

/**
 * Compara dos estados de filtros.
 *
 * Detecta cambios reales para evitar recargas innecesarias.
 *
 * @param {Object} a
 * @param {Object} b
 * @returns {boolean}
 */
function filtrosBaseIguales(a, b) {
  return (
    a.nombre === b.nombre &&
    a.talla === b.talla &&
    /**
     * Ordenamos arrays para comparación consistente
     */
    JSON.stringify([...a.colores].sort()) ===
      JSON.stringify([...b.colores].sort())
  );
}

//
// PRODUCTOS — cargarProductos
//
/**
 *
 * Maneja:
 * - Filtros
 * - Slider dinámico
 * - Paginación
 * - Estado inteligente
 */
function cargarProductos(pagina = 1) {
  paginaActual = pagina;

  const estadoActual = leerFiltrosBase();
  const tieneBase = hayFiltrosBase(estadoActual);
  const cambiároBase = !filtrosBaseIguales(estadoActual, estadoAnterior);

  // PRE-RESET (CASO 3)

  if (!tieneBase && cambiároBase) {
    const rangoReset = maxGlobal || 0;
    aplicarRango(0, rangoReset, rangoReset);
    maxActual = null;
    usuarioTocoPrecio = false;
  }

  /**
   * Construcción de parámetros GET
   */
  let params = new URLSearchParams();
  params.append("pagina", paginaActual);

  if (estadoActual.nombre) params.append("nombre", estadoActual.nombre);
  if (estadoActual.talla) params.append("talla", estadoActual.talla);

  // Orden: tomar del select visible o del desktop
  const ordenVal =
    document.getElementById("orden")?.value ||
    document.getElementById("ordenMobile")?.value;
  if (ordenVal) params.append("orden", ordenVal);

  const precioMin = document.getElementById("precioMin")?.value;
  const precioMax = document.getElementById("precioMax")?.value;

  if (usuarioTocoPrecio) {
    if (precioMin) params.append("precio_min", precioMin);
    if (precioMax) params.append("precio_max", precioMax);
  }

  /**
   * Colores (array)
   */
  estadoActual.colores.forEach((v) => params.append("color[]", v));

  fetch(`${API}?${params.toString()}`)
    .then((res) => res.json())
    .then((res) => {
      /**
       * Datos de configuración desde backend
       */
      const precioMaxApi = res.config?.precio_max;
      const precioMaxGlobal = res.config?.precio_max_global;

      /**
       * Guardar máximo global una sola vez
       */
      if (precioMaxGlobal && maxGlobal === null) {
        maxGlobal = precioMaxGlobal;
      }

      // CASO 2
      /**
       * LÓGICA INTELIGENTE DEL SLIDER
       */
      if (tieneBase && cambiároBase) {
        // Caso: nuevos filtros
        resetSliderDinamico(precioMaxApi);
      }
      // CASO 2b
      else if (tieneBase && !cambiároBase) {
        // Caso: mismos filtros
        ajustarMaxSinReset(precioMaxApi);
      }
      // CASO 3
      else if (!tieneBase && cambiároBase) {
        resetSliderGlobal(precioMaxApi);
      }
      // CASO 1
      else if (!tieneBase && !cambiároBase && usuarioTocoPrecio) {
        ajustarMaxSinReset(maxGlobal);
      }
      // CARGA INICIAL
      else {
        // Caso: sin filtros
        resetSliderGlobal(precioMaxApi);
      }
      /**
       * Guardar estado actual
       */
      estadoAnterior = {
        nombre: estadoActual.nombre,
        talla: estadoActual.talla,
        colores: [...estadoActual.colores],
      };

      /**
       * Render UI
       */
      renderProductos(res.data || []);
      renderPaginacion(res.meta);
      actualizarContador(res.meta);
      actualizarBadge();
    });
}

// 
// CONTADOR
// 

function actualizarContador(meta) {
  if (!meta) return;
  const inicio = (meta.pagina - 1) * meta.por_pagina + 1;
  const fin = Math.min(meta.pagina * meta.por_pagina, meta.filtrados);
  const texto = `Mostrando ${inicio}–${fin} de ${meta.filtrados} resultados`;

  const elDesktop = document.getElementById("mostrar_productos");
  const elMobile = document.getElementById("mostrar_productos_mobile");
  const elBoton = document.getElementById("btnVerResultados");

  if (elDesktop) elDesktop.textContent = texto;
  if (elMobile) elMobile.textContent = texto;
  if (elBoton)
    elBoton.textContent = `Ver ${meta.filtrados} resultado${meta.filtrados !== 1 ? "s" : ""}`;
}

// 
// PAGINACIÓN
// 

function renderPaginacion(meta) {
  const container = document.getElementById("paginacion");
  if (!container) return;

  if (!meta || meta.total_paginas <= 1) {
    container.innerHTML = "";
    return;
  }

  let html = "";
  for (let i = 1; i <= meta.total_paginas; i++) {
    html += `
      <button
        class="btn btn-sm ${i == meta.pagina ? "btn-dark" : "btn-outline-dark"} mx-1"
        onclick="cargarProductos(${i})">
        ${i}
      </button>
    `;
  }
  container.innerHTML = html;
}

// 
// RENDER PRODUCTOS
// 

function renderProductos(productos) {
  const container = document.getElementById("resultados");
  if (!container) return;

  /**
   * Sin resultados
   */
  if (!productos.length) {
    container.innerHTML = `<p class="text-center col-12 py-5 text-muted">No hay productos disponibles.</p>`;
    return;
  }

  /**
   * Render de tarjetas
   */
  let html = "";
  productos.forEach((p) => {
    html += `
      <div class="col-md-4 col-6 mb-4">
        <div class="product-card">
          <div class="product-img">
            <img src="${p.imagen || '/img/no-image.png'}" alt="${p.name}" loading="lazy" onerror="this.onerror=null; this.src='/img/no-image.png';">
            <div class="no-image-placeholder">
              Sin imagen
            </div>
          </div>
          <div class="product-info">
            <p class="product-name">${p.name}</p>
            <p class="product-price">$${Number(p.price).toLocaleString()}</p>
            <a class="btn-cart" href="/detalles?id=${p.id}">Agregar al carrito</a>
          </div>
        </div>
      </div>
    `;
  });

  container.innerHTML = html;
}

// 
// RESPONSIVE — mover slider entre desktop y mobile
// 

function moverSlider() {
  const slider = document.getElementById("sliderContainer");
  if (!slider) return;

  const desktop = document.querySelector(".filtros");
  const mobile = document.querySelector("#offcanvasBody");

  if (!desktop || !mobile) return;

  if (window.innerWidth < 768) {
    if (!mobile.contains(slider)) mobile.prepend(slider);
  } else {
    if (!desktop.contains(slider)) desktop.prepend(slider);
  }
}

// 
// DETALLE DE PRODUCTO
// 

function cargarDetalleProducto() {
  const container = document.getElementById("detalle-producto");
  if (!container) return;

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  /**
   * Validación de ID
   */
  if (!id || isNaN(id)) {
    container.innerHTML = `
      <div class="text-center text-danger py-5">
        <p>ID de producto no válido</p>
      </div>
    `;
    return;
  }

  fetch(`/api/detalles?id=${id}`)
    .then((res) => res.json())
    .then((res) => {
      if (res.status !== "success" || !res.data.length) {
        throw new Error("Producto no encontrado");
      }

      const p = res.data[0];

      container.innerHTML = `
  <div class="row">

    <!-- IMAGEN -->
    <div class="col-md-6 mb-4 d-flex justify-content-center">
      <div class="zoom-container" id="zoomContainer">
      <img src="${p.imagen || '/img/no-image.png'}" id="imagenZoom" class="img-fluid" alt="${p.name}" loading="lazy" onerror="this.onerror=null; this.src='/img/no-image.png';">
        <div class="no-image-placeholder">
                Sin imagen
        </div>
      </div>
    </div>

    <!-- INFO -->
    <div class="col-md-6 mb-4">
      <div class="p-4">

        <div class="mb-3">
          <span class="badge bg-dark">${p.name_category}</span>
          <span class="badge bg-secondary">${p.name_size}</span>
          <span class="badge bg-info">${p.name_color ?? "N/A"}</span>
        </div>

        <h3>${p.name}</h3>

        <p class="lead">
          <span>$${Number(p.price).toLocaleString()}</span>
        </p>

        <strong><p style="font-size: 20px;">Descripción</p></strong>

        <p>${p.description ?? "Sin descripción disponible."}</p>

        <button class="btn btn-dark" type="button">
          Agregar al carrito
        </button>
      </div>
    </div>

  </div>
`;
      initZoomImagen();
    })
    .catch(() => {
      container.innerHTML = `
        <div class="text-center text-danger py-5">
          <p>Error al cargar el producto</p>
        </div>
      `;
    });
}
//ZOOM DE PRODUCTO
function initZoomImagen() {
  const container = document.getElementById("zoomContainer");
  const img = document.getElementById("imagenZoom");

  if (!container || !img) return;

  const esMobile = window.innerWidth < 768;

  // DESKTOP → zoom siguiendo cursor
  if (!esMobile) {
    container.addEventListener("mousemove", (e) => {
      const rect = container.getBoundingClientRect();

      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;

      img.style.transformOrigin = `${x}% ${y}%`;
      img.style.transform = "scale(2)";
    });

    container.addEventListener("mouseleave", () => {
      img.style.transform = "scale(1)";
      img.style.transformOrigin = "center center";
    });
  }

  // MOBILE → zoom con tap (toggle)
  else {
    let zoomActivo = false;

    container.addEventListener("click", () => {
      zoomActivo = !zoomActivo;

      if (zoomActivo) {
        img.style.transform = "scale(2)";
      } else {
        img.style.transform = "scale(1)";
      }
    });
  }
}
