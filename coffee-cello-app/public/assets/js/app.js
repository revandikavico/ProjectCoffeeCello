
document.addEventListener('DOMContentLoaded', () => {
    const menuGrid = document.getElementById('menuGrid');
    const categoryList = document.getElementById('categoryList');

    const cartDrawer = document.getElementById('cartDrawer');
    const cartOverlay = document.getElementById('cartOverlay');
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const cartTitleCount = document.getElementById('cartTitleCount');
    const cartSubtotal = document.getElementById('cartSubtotal');
    const toast = document.getElementById('toast');

    const openCartButton = document.getElementById('openCart');
    const closeCartButton = document.getElementById('closeCart');
    const checkoutButton = document.getElementById('checkoutButton');

    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    const cart = new Map();
    let toastTimer;

    const formatRupiah = (value) =>
        'Rp' + Number(value).toLocaleString('id-ID');

    function showToast(message) {
        if (!toast) {
            alert(message);
            return;
        }

        toast.textContent = message;
        toast.classList.add('show');

        clearTimeout(toastTimer);

        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 2500);
    }

    function openCart() {
        cartDrawer?.classList.add('active');
        cartOverlay?.classList.add('active');
        cartDrawer?.setAttribute('aria-hidden', 'false');
        document.body.classList.add('cart-open');
    }

function closeCart() {
    // Pindahkan fokus sebelum menyembunyikan keranjang.
    if (cartDrawer?.contains(document.activeElement)) {
        openCartButton?.focus();
    }

    cartDrawer?.classList.remove('active');
    cartOverlay?.classList.remove('active');
    cartDrawer?.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('cart-open');
}

    
function addToCart(productCard) {
    const id = productCard.dataset.id;
    const name = productCard.dataset.name;
    const price = Number(productCard.dataset.price);

    // Validasi data produk
    if (!id || !name || !Number.isFinite(price) || price < 0) {
        showToast('Data produk tidak valid.');
        return;
    }

    // Tambahkan produk ke keranjang
    if (cart.has(id)) {
        cart.get(id).quantity += 1;
    } else {
        cart.set(id, {
            id: id,
            name: name,
            price: price,
            quantity: 1
        });
    }

    // Perbarui tampilan keranjang
    renderCart();

    // Tampilkan notifikasi
    showToast(name + ' ditambahkan ke keranjang.');
}


    function changeQuantity(id, change) {
        const item = cart.get(id);
        if (!item) return;

        item.quantity += change;

        if (item.quantity <= 0) {
            cart.delete(id);
        }

        renderCart();
    }

    function removeItem(id) {
        cart.delete(id);
        renderCart();
        showToast('Produk dihapus dari keranjang.');
    }

    function renderCart() {
        const items = Array.from(cart.values());

        const totalQuantity = items.reduce(
            (sum, item) => sum + item.quantity,
            0
        );

        const subtotal = items.reduce(
            (sum, item) => sum + item.price * item.quantity,
            0
        );

        if (cartCount) {
            cartCount.textContent = totalQuantity;
        }

        if (cartTitleCount) {
            cartTitleCount.textContent = '(' + totalQuantity + ')';
        }

        if (cartSubtotal) {
            cartSubtotal.textContent = formatRupiah(subtotal);
        }

        if (!cartItems) return;

        cartItems.replaceChildren();

        if (items.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'empty-cart';

            const icon = document.createElement('span');
            icon.textContent = '☕';

            const heading = document.createElement('h3');
            heading.textContent = 'Your bag is waiting.';

            const description = document.createElement('p');
            description.textContent =
                'Tambahkan kopi favoritmu untuk memulai.';

            empty.append(icon, heading, description);
            cartItems.appendChild(empty);
            return;
        }

        items.forEach((item) => {
            const row = document.createElement('div');
            row.className = 'cart-item';

            const info = document.createElement('div');
            info.className = 'cart-item-info';

            const name = document.createElement('h3');
            name.textContent = item.name;

            const price = document.createElement('p');
            price.textContent = formatRupiah(item.price) + ' / item';

            const lineTotal = document.createElement('strong');
            lineTotal.textContent =
                formatRupiah(item.price * item.quantity);

            info.append(name, price, lineTotal);

            const controls = document.createElement('div');
            controls.className = 'cart-item-controls';

            const minus = document.createElement('button');
            minus.type = 'button';
            minus.textContent = '−';
            minus.setAttribute('aria-label', 'Kurangi jumlah ' + item.name);
            minus.addEventListener('click', () => {
                changeQuantity(item.id, -1);
            });

            const quantity = document.createElement('span');
            quantity.textContent = item.quantity;

            const plus = document.createElement('button');
            plus.type = 'button';
            plus.textContent = '+';
            plus.setAttribute('aria-label', 'Tambah jumlah ' + item.name);
            plus.addEventListener('click', () => {
                changeQuantity(item.id, 1);
            });

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'cart-remove';
            remove.textContent = 'Hapus';
            remove.addEventListener('click', () => {
                removeItem(item.id);
            });

            controls.append(minus, quantity, plus, remove);
            row.append(info, controls);
            cartItems.appendChild(row);
        });
    }

    // Tambah produk ke keranjang.
    menuGrid?.addEventListener('click', (event) => {
        const button = event.target.closest('.quick-add');

        if (!button || button.disabled) return;

        const productCard = button.closest('.product-card');

        if (productCard) {
            addToCart(productCard);
        }
    });

    // Filter kategori.
    categoryList?.addEventListener('click', (event) => {
        const button = event.target.closest('.category-btn');

        if (!button) return;

        const selectedCategory = button.dataset.category;

        categoryList.querySelectorAll('.category-btn').forEach((btn) => {
            btn.classList.toggle('active', btn === button);
        });

        menuGrid?.querySelectorAll('.product-card').forEach((card) => {
            card.hidden = !(
                selectedCategory === 'all' ||
                card.dataset.category === selectedCategory
            );
        });
    });

    // Buka dan tutup keranjang.
    openCartButton?.addEventListener('click', openCart);
    closeCartButton?.addEventListener('click', closeCart);
    cartOverlay?.addEventListener('click', closeCart);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeCart();
    });

    // Navigasi mobile.
    menuToggle?.addEventListener('click', () => {
        navLinks?.classList.toggle('active');
    });

    navLinks?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            navLinks?.classList.remove('active');
        });
    });

   // Checkout: hanya satu event listener.
if (!checkoutButton) {
    console.error('Tombol Checkout tidak ditemukan.');
} else {
    checkoutButton.addEventListener('click', () => {
        console.log('Tombol Checkout berhasil diklik!');
        console.log('Jumlah item:', cart.size);

        if (cart.size === 0) {
            showToast('Keranjang masih kosong.');
            return;
        }

        const cartData = Array.from(cart.values()).map((item) => ({
            id: Number(item.id),
            quantity: Number(item.quantity)
        }));

        sessionStorage.setItem(
            'coffeecello_cart',
            JSON.stringify(cartData)
        );

        const checkoutUrl =
            document.body.dataset.checkoutUrl || '/checkout';

        console.log('URL tujuan:', checkoutUrl);

        window.location.href = checkoutUrl;
    });
}

    
console.log('Tombol Checkout diklik');
console.log('Jumlah item:', cart.size);
console.log('URL tujuan:', document.body.dataset.checkoutUrl);
    renderCart();
});