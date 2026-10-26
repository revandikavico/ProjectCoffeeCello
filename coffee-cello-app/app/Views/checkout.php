
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Coffee Cello</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f1e9;
            color: #38251d;
        }

        header {
            padding: 22px 8%;
            background: #38251d;
            color: white;
            font-size: 22px;
            font-weight: bold;
        }

        .container {
            width: min(900px, 92%);
            margin: 40px auto;
        }

        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 0.8fr;
            gap: 24px;
        }

        .panel {
            padding: 28px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px #38251d0d;
        }

        h1 {
            margin-top: 0;
            font-size: 30px;
        }

        h2 {
            font-size: 20px;
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd2c6;
            border-radius: 8px;
            font: inherit;
        }

        textarea {
            resize: vertical;
        }

        .hint {
            color: #796b60;
            font-size: 14px;
            line-height: 1.6;
        }

        .submit-btn, .back-btn {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #684633;
            color: white;
            font-size: 15px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .back-btn {
            background: #eee6dc;
            color: #38251d;
        }

        @media (max-width: 650px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }

            .container {
                margin: 24px auto;
            }
        }
    </style>
</head>

<body>
    <header>☕ Coffee Cello</header>

    <main class="container">
        <h1>Checkout</h1>
        <p class="hint">
            Satu langkah lagi menuju kopi favoritmu.
            Lengkapi data pesanan di bawah ini.
        </p>

        <?php if (session()->getFlashdata('error')): ?>
            <p style="color: #b42318;">
                <?= esc(session()->getFlashdata('error')) ?>
            </p>
        <?php endif; ?>

        <div class="checkout-layout">
            <section class="panel">
                <h2>Informasi Pelanggan</h2>

                <form action="<?= site_url('checkout/place') ?>"
                      method="post" id="checkoutForm">
                    <?= csrf_field() ?>

                    <label for="customer_name">Nama</label>
                    <input
                        id="customer_name"
                        name="customer_name"
                        type="text"
                        maxlength="100"
                        value="<?= esc(old('customer_name') ?? '') ?>"
                        required
                    >

                    <label for="customer_phone">Nomor telepon</label>
                    <input
                        id="customer_phone"
                        name="customer_phone"
                        type="tel"
                        maxlength="30"
                        value="<?= esc(old('customer_phone') ?? '') ?>"
                        placeholder="08xxxxxxxxxx"
                    >

                    <label for="order_type">Jenis pesanan</label>
                    <select id="order_type" name="order_type" required>
                        <option value="takeaway">Takeaway — Dibawa pulang</option>
                        <option value="dine_in">Dine in — Makan di tempat</option>
                    </select>

                    <div id="tableNumberField" hidden>
                        <label for="table_number">Nomor meja</label>
                        <input
                            id="table_number"
                            name="table_number"
                            type="text"
                            maxlength="20"
                        >
                    </div>

                    <label for="notes">Catatan pesanan</label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        placeholder="Contoh: tanpa gula"
                    ><?= esc(old('notes') ?? '') ?></textarea>

                    <input type="hidden" name="cart_data" id="cartData">

                    <button class="submit-btn" type="submit">
                        Buat Pesanan
                    </button>
                </form>

                <a class="back-btn" href="<?= site_url('/') ?>#menu">
                    ← Kembali ke menu
                </a>
            </section>

            <aside class="panel">
                <h2>Ringkasan Pesanan</h2>
                <div id="orderSummary" class="hint">
                    Memuat keranjang...
                </div>
                <p class="hint">
                    Pembayaran online belum tersedia.
                    Konfirmasi pembayaran mengikuti proses toko.
                </p>
            </aside>
        </div>
    </main>

    <script>
        const cartDataInput = document.getElementById('cartData');
        const orderSummary = document.getElementById('orderSummary');
        const orderType = document.getElementById('order_type');
        const tableField = document.getElementById('tableNumberField');
        const tableInput = document.getElementById('table_number');

        function rupiah(value) {
            return 'Rp' + Number(value).toLocaleString('id-ID');
        }

        function updateTableField() {
            const dineIn = orderType.value === 'dine_in';
            tableField.hidden = !dineIn;
            tableInput.required = dineIn;
        }

        orderType.addEventListener('change', updateTableField);
        updateTableField();

        try {
            const cart = JSON.parse(
                sessionStorage.getItem('coffeecello_cart') || '[]'
            );

            cartDataInput.value = JSON.stringify(cart);

            if (!cart.length) {
                orderSummary.textContent =
                    'Keranjang kosong. Kembali ke menu untuk memilih produk.';
            } else {
                orderSummary.replaceChildren();
                let total = 0;

                cart.forEach(item => {
                    const quantity = Number(item.quantity);
                    const line = document.createElement('p');

                    // Harga diambil dari katalog server saat pesanan diproses.
                    line.textContent =
                        'Produk ID ' + item.id + ' × ' + quantity;

                    orderSummary.appendChild(line);
                });

                const note = document.createElement('p');
                note.textContent =
                    'Total harga akan dihitung berdasarkan harga produk di server.';
                orderSummary.appendChild(note);
            }
        } catch (error) {
            cartDataInput.value = '[]';
            orderSummary.textContent =
                'Data keranjang tidak terbaca. Silakan kembali ke menu.';
        }

        document.getElementById('checkoutForm').addEventListener('submit', event => {
            if (!cartDataInput.value ||
                JSON.parse(cartDataInput.value).length === 0) {
                event.preventDefault();
                alert('Keranjang kosong. Silakan pilih produk terlebih dahulu.');
            }
        });
    </script>
</body>
</html>
