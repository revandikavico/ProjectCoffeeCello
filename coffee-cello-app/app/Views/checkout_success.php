
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Berhasil | Coffee Cello</title>
    <style>
        body {
            margin: 0;
            padding: 30px 16px;
            background: #f6f1e9;
            color: #34251e;
            font-family: Arial, sans-serif;
        }
        main {
            max-width: 520px;
            margin: 60px auto;
            padding: 32px;
            text-align: center;
            background: white;
            border-radius: 16px;
        }
        .number {
            padding: 14px;
            background: #f2e8dc;
            border-radius: 8px;
            font-weight: bold;
        }
        a {
            display: inline-block;
            margin-top: 16px;
            padding: 12px 18px;
            border-radius: 8px;
            background: #503629;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<main>
    <h1>Pesanan Berhasil! ☕</h1>
    

    <p>Atas nama:</p>
    <?= esc(session()->getFlashdata('customer_name') ?? 'Pelanggan') ?> </div>

    <p>Status: Menunggu diproses</p>
    <p>Status pembayaran: Belum dibayar</p>

    <a href="<?= site_url('/') ?>">Kembali ke menu</a>
</main>

<script>
    sessionStorage.removeItem('coffeecello_cart');
</script>
</body>
</html>
