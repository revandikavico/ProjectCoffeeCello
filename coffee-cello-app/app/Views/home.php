
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Coffee Cello - Temukan kopi favoritmu, nikmati setiap momen.">
    <title>Coffee Cello | Coffee & Good Moments</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <body data-checkout-url="<?= site_url('checkout') ?>"></body>

<header class="navbar">
    <a href="#home" class="brand">
        <span class="brand-icon">C.</span>
        <span>COFFEE <b>CELLO</b><small>COFFEE & GOOD MOMENTS</small></span>
    </a>

    <button class="menu-toggle" id="menuToggle" aria-label="Buka navigasi">
        ☰
    </button>

    <nav class="nav-links" id="navLinks">
        <a href="#home">Home</a>
        <a href="#menu">Our Menu</a>
        <a href="#about">Our Story</a>
        <a href="#contact">Contact</a>
    </nav>

    <button class="cart-button" id="openCart" type="button">
        <span>Bag</span>
        <span class="cart-count" id="cartCount">0</span>
    </button>
</header>

<main>
    <!-- HERO -->
    <section class="hero" id="home">
        <div class="hero-content">
            <span class="eyebrow">
                <span class="eyebrow-line"></span>
                YOUR DAILY COFFEE RITUAL
            </span>

            <h1>Good coffee.<br>Better <em>moments.</em></h1>

            <p>
                Setiap cangkir punya cerita. Temukan rasa favoritmu,
                nikmati suasana hangat, dan biarkan hari berjalan lebih baik.
            </p>

            <div class="hero-actions">
                <a href="#menu" class="btn btn-primary">
                    Explore Our Menu <span>↗</span>
                </a>
                <a href="#about" class="text-link">
                    Discover our story <span>→</span>
                </a>
            </div>

            <div class="hero-note">
                <div class="avatar-stack">
                    <span>☕</span><span>♡</span><span>✳</span>
                </div>
                <p>
                    <strong>Made with care</strong><br>
                    One cup at a time.
                </p>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-image"></div>

            <div class="image-label">
                <span>01 / SIGNATURE</span>
                <strong>The perfect pour.</strong>
            </div>

            <div class="floating-stamp">GOOD<br>MOOD<br>ONLY ☕</div>
        </div>

        <div class="hero-bottom">
            <span>SCROLL TO DISCOVER</span>
            <span class="scroll-line"></span>
            <span>COFFEE · COMMUNITY · COMFORT</span>
        </div>
    </section>

    <!-- MENU DARI DATABASE -->
    <section class="menu-section section-padding" id="menu">
        <div class="section-heading">
            <div>
                <span class="eyebrow">A LITTLE SOMETHING FOR YOU</span>
                <h2>Find your <em>favorite.</em></h2>
                <p>Diseduh dengan sepenuh hati, untuk menemani setiap harimu.</p>
            </div>

            <a href="#menuGrid" class="text-link desktop-link">
                Explore the menu ↗
            </a>
        </div>

        <div class="menu-toolbar">
            <div class="category-list" id="categoryList">
                <button class="category-btn active" data-category="all" type="button">
                    All Menu
                </button>
                <button class="category-btn" data-category="coffee" type="button">
                    Coffee
                </button>
                <button class="category-btn" data-category="noncoffee" type="button">
                    Non Coffee
                </button>
                <button class="category-btn" data-category="food" type="button">
                    Food
                </button>
            </div>

            <span class="menu-caption">MADE FRESH, JUST FOR YOU</span>
        </div>

        <?php
        // Membuat pemetaan ID kategori ke slug kategori.
        $categorySlugs = [];

        foreach (($categories ?? []) as $category) {
            $slug = strtolower(trim($category['slug'] ?? ''));

            if ($slug === '') {
                $slug = strtolower(trim($category['name'] ?? ''));
            }

            $normalizedSlug = preg_replace('/[^a-z0-9]/', '', $slug);

            // Menyamakan slug kategori dengan filter pada halaman.
            if (in_array($normalizedSlug, ['coffee', 'kopi'], true)) {
                $normalizedSlug = 'coffee';
            } elseif (in_array($normalizedSlug, ['noncoffee', 'nonkopi'], true)) {
                $normalizedSlug = 'noncoffee';
            } elseif (in_array($normalizedSlug, ['food', 'makanan'], true)) {
                $normalizedSlug = 'food';
            }

            $categorySlugs[$category['id']] = $normalizedSlug;
        }
        ?>

        <div class="menu-grid" id="menuGrid">
            <?php if (empty($products)): ?>

                <div class="menu-empty">
                    <h3>Our menu is coming soon.</h3>
                    <p>
                        Menu Coffee Cello sedang disiapkan.
                        Silakan kunjungi kembali nanti.
                    </p>
                </div>

            <?php else: ?>

                <?php foreach ($products as $product): ?>
                    <?php
                    $categoryKey = $categorySlugs[$product['category_id']] ?? 'other';
                    $productName = $product['name'] ?? 'Menu Coffee Cello';
                    $productImage = $product['image'] ?? '';
                    $productPrice = (float) ($product['price'] ?? 0);
                    $productDescription = $product['description'] ?? '';
                    $productAvailable = (int) ($product['is_available'] ?? 0) === 1;
                    ?>

                    <article
                        class="product-card"
                        data-category="<?= esc($categoryKey, 'attr') ?>"
                        data-id="<?= esc((string) $product['id'], 'attr') ?>"
                        data-name="<?= esc($productName, 'attr') ?>"
                        data-price="<?= esc((string) $productPrice, 'attr') ?>">

                        <div
                            class="product-image"
                            <?php if ($productImage !== ''): ?>
                                style="background-image: url('<?= esc(base_url('uploads/products/' . rawurlencode($productImage)), 'attr') ?>'); background-size: cover; background-position: center;"
                            <?php endif; ?>>

                            <?php if ($productImage === ''): ?>
                                <span class="product-placeholder">☕</span>
                            <?php endif; ?>

                            <?php if (!$productAvailable): ?>
                                <span class="product-tag">SOLD OUT</span>
                            <?php endif; ?>

                            <button
                                class="quick-add"
                                type="button"
                                aria-label="Tambah <?= esc($productName, 'attr') ?>"
                                <?= !$productAvailable ? 'disabled' : '' ?>>
                                +
                            </button>
                        </div>

                        <div class="product-info">
                            <div>
                                <h3><?= esc($productName) ?></h3>
                                <p><?= esc($productDescription) ?></p>
                            </div>

                            <strong>
                                Rp<?= number_format($productPrice, 0, ',', '.') ?>
                            </strong>
                        </div>
                    </article>

                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <p class="menu-disclaimer">
            Pilihan kopi, minuman, dan makanan favorit untuk menemani harimu.
        </p>
    </section>

    <!-- OUR STORY -->
    <section class="story-section" id="about">
        <div class="story-photo">
            <div class="story-photo-overlay">
                <span>OUR PHILOSOPHY</span>
                <strong>Slow down.<br>Sip something good.</strong>
            </div>
        </div>

        <div class="story-content">
            <span class="eyebrow">MORE THAN JUST COFFEE</span>
            <h2>A place to pause.<br>A reason to <em>stay.</em></h2>

            <p>
                Coffee Cello hadir untuk menemani obrolan kecil, ide besar,
                dan waktu istirahat yang sering terlupakan. Kami percaya
                bahwa secangkir kopi yang baik bisa membuat momen sederhana
                terasa lebih berarti.
            </p>

            <a href="#contact" class="btn btn-dark">
                Get to know us <span>↗</span>
            </a>

            <div class="story-signature">Good things take time.</div>
        </div>
    </section>

    <!-- QUOTE -->
    <section class="quote-section">
        <span class="quote-mark">“</span>
        <p>Life happens, coffee helps.</p>
        <span>YOUR EVERYDAY COFFEE COMPANION</span>
    </section>

    <!-- CONTACT -->
    <section class="contact-section section-padding" id="contact">
        <div>
            <span class="eyebrow">LET'S MEET OVER COFFEE</span>
            <h2>Your next favorite<br>cup is <em>waiting.</em></h2>
        </div>

        <div class="contact-info">
            <p>Ingin bertanya tentang menu atau melakukan pemesanan?</p>

            <a href="#menu" class="btn btn-primary">
                Order your coffee ↗
            </a>

            <p class="contact-note">
                Alamat, jam operasional, dan kontak resmi dapat ditambahkan di sini.
            </p>
        </div>
    </section>
</main>

<!-- FOOTER -->
<footer class="footer">
    <a href="#home" class="brand footer-brand">
        <span class="brand-icon">C.</span>
        <span>COFFEE <b>CELLO</b><small>COFFEE & GOOD MOMENTS</small></span>
    </a>

    <p>
        Made for your everyday moments. ©
        <span id="currentYear"></span> Coffee Cello.
    </p>

    <a href="#home" class="back-top">BACK TO TOP ↑</a>
</footer>

<!-- CART OVERLAY -->
<div class="cart-overlay" id="cartOverlay"></div>

<!-- CART DRAWER -->
<aside class="cart-drawer" id="cartDrawer" aria-label="Keranjang belanja" aria-hidden="true">
    <div class="cart-header">
        <div>
            <span class="eyebrow">YOUR SELECTION</span>
            <h2>Your bag <span id="cartTitleCount">(0)</span></h2>
        </div>

        <button class="close-cart" id="closeCart" type="button" aria-label="Tutup keranjang">
            ×
        </button>
    </div>

    <div class="cart-items" id="cartItems">
        <div class="empty-cart">
            <span>☕</span>
            <h3>Your bag is waiting.</h3>
            <p>Tambahkan kopi favoritmu untuk memulai.</p>
        </div>
    </div>

  <div class="cart-total">
    <span>Subtotal</span>
    <strong id="cartSubtotal">Rp0</strong>
</div>
        </div>

        <p>Biaya tambahan, jika ada, dihitung saat checkout.</p>

        <button class="btn btn-primary checkout-btn" id="checkoutButton" type="button">
            Continue to checkout <span>→</span>
        </button>
    </div>
</aside>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script>
    // Tahun pada footer.
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>

<script src="<?= base_url('assets/js/app.js') ?>"></script>

</body>
</html>

