<?php require_once __DIR__ . '/includes/store.php'; $products = store_products(); ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#f7f3ee">
  <title>ANN Studio — Moda y accesorios</title>
  <meta name="description" content="Descubre ropa, lociones, carteras, gafas y accesorios seleccionados por ANN Studio.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Italiana&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="announcement">Envíos a toda Honduras · Atención personalizada por WhatsApp</div>
  <header class="site-header">
    <button class="icon-btn" id="menuButton" aria-label="Abrir menú"><span></span><span></span></button>
    <a class="brand" href="#inicio" aria-label="ANN Studio"><strong>ANN</strong><small>STUDIO</small></a>
    <nav class="desktop-nav"><a href="#novedades">Novedades</a><a href="#catalogo">Catálogo</a><a href="#nosotros">Nosotros</a></nav>
    <div class="header-actions">
      <button class="icon-btn search-toggle" aria-label="Buscar">⌕</button>
      <button class="bag-button" id="bagButton" aria-label="Abrir bolsa">Bolsa <b id="bagCount">0</b></button>
    </div>
  </header>

  <aside class="drawer" id="menuDrawer" aria-hidden="true">
    <div class="drawer-head"><span>Explorar</span><button class="close" data-close="menuDrawer">×</button></div>
    <a href="#inicio">Inicio</a><a href="#novedades">Lo nuevo</a>
    <?php foreach(store_categories() as $category): ?><a href="#catalogo" data-category-link="<?= h($category) ?>"><?= h($category) ?></a><?php endforeach; ?>
    <a href="admin.php" class="admin-link">Administración</a>
  </aside>
  <div class="backdrop" id="backdrop"></div>

  <main>
    <section class="hero" id="inicio">
      <div class="hero-copy"><p class="eyebrow">Nueva colección · 2026</p><h1>Detalles que hablan por ti.</h1><p>Prendas y accesorios seleccionados para elevar lo cotidiano con intención.</p><a class="button" href="#catalogo">Descubrir colección</a></div>
      <div class="hero-art"><div class="arch arch-one"></div><div class="arch arch-two"></div><p>ANN<br><em>edit</em></p></div>
    </section>

    <section class="category-strip" aria-label="Categorías">
      <?php foreach(store_categories() as $i => $category): ?><button class="category-pill <?= $i === 0 ? 'active' : '' ?>" data-filter="<?= h($category) ?>"><?= h($category) ?></button><?php endforeach; ?>
    </section>

    <section class="catalog-section" id="catalogo">
      <div class="section-heading" id="novedades"><div><p class="eyebrow">Selección ANN</p><h2>Encuentra tu próximo favorito</h2></div><p id="resultsCount"></p></div>
      <div class="catalog-tools">
        <label class="search-box"><span>⌕</span><input id="searchInput" type="search" placeholder="Buscar por nombre o código"></label>
        <select id="sortSelect" aria-label="Ordenar productos"><option value="featured">Destacados</option><option value="newest">Más recientes</option><option value="low">Precio: menor a mayor</option><option value="high">Precio: mayor a menor</option></select>
      </div>
      <div class="product-grid" id="productGrid"></div>
      <div class="empty-state" id="emptyState"><h3>No encontramos coincidencias</h3><p>Prueba con otra categoría o palabra.</p></div>
    </section>

    <section class="story" id="nosotros"><p class="eyebrow">El estilo está en la selección</p><h2>Una tienda, todas tus piezas favoritas.</h2><p>ANN Studio reúne moda, lociones, carteras, gafas y accesorios con una curaduría femenina, contemporánea y fácil de llevar.</p></section>
  </main>

  <aside class="bag-drawer" id="bagDrawer" aria-hidden="true">
    <div class="drawer-head"><span>Tu bolsa <small id="bagTitleCount"></small></span><button class="close" data-close="bagDrawer">×</button></div>
    <div class="bag-items" id="bagItems"></div>
    <div class="bag-footer"><div class="bag-total"><span>Total estimado</span><strong id="bagTotal">L 0.00</strong></div><p>La disponibilidad se confirma por WhatsApp. No se realiza ningún cobro en este sitio.</p><button class="button whatsapp" id="checkoutButton">Enviar pedido por WhatsApp</button></div>
  </aside>

  <dialog id="productDialog"><button class="dialog-close" aria-label="Cerrar">×</button><div id="productDetail"></div></dialog>
  <div class="toast" id="toast" role="status"></div>
  <footer><a class="brand footer-brand" href="#inicio"><strong>ANN</strong><small>STUDIO</small></a><p>Moda, belleza y accesorios seleccionados para ti.</p><a href="https://wa.me/50489161389">WhatsApp · +504 8916-1389</a><small>© <?= date('Y') ?> ANN Studio</small></footer>

  <script src="assets/app.js?v=4"></script>
</body>
</html>
