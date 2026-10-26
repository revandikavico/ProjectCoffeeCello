<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f5f1eb;
            color: #30251f;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0 0 8px;
            color: #4b2e22;
        }

        .subtitle {
            color: #76665c;
            margin: 0;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 7px;
            background: #4b2e22;
            color: white;
            text-decoration: none;
        }

        .summary {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px #0000000a;
        }

        .summary strong {
            display: block;
            font-size: 28px;
            margin-top: 8px;
            color: #4b2e22;
        }

        .table-wrapper {
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px #0000000a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee6df;
            vertical-align: top;
        }

        th {
            background: #4b2e22;
            color: white;
            font-size: 13px;
        }

        td {
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            background: #eee;
            font-size: 12px;
            white-space: nowrap;
        }

        .pending {
            background: #fff0c2;
            color: #805b00;
        }

        .processing {
            background: #dcecff;
            color: #174d91;
        }

        .completed {
            background: #d9f5df;
            color: #216b35;
        }

        .cancelled {
            background: #ffe0e0;
            color: #9b2525;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #76665c;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <div>
            <h1>Daftar Pesanan ☕</h1>
            <p class="subtitle">Kelola pesanan pelanggan Coffee Cello.</p>
        </div>

        <a href="<?= site_url('admin/products') ?>" class="button">
            Kembali ke Produk
        </a>
    </div>

    <div class="summary">
        Total pesanan tersimpan
        <strong><?= count($orders) ?></strong>
    </div>

    <div class="table-wrapper">
        <?php if (empty($orders)): ?>
            <div class="empty">
                <h3>Belum ada pesanan</h3>
                <p>Pesanan pelanggan akan muncul di halaman ini setelah checkout berhasil.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nomor Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Jenis</th>
                        <th>Total</th>
                        <th>Status Pesanan</th>
                        <th>Pembayaran</th>
                        <th>aksi</th>
                        <th>Waktu</th>
                    </tr>
                </thead>

             
<tbody>
    <?php foreach ($orders as $index => $order): ?>
        <?php
            $status = strtolower((string) ($order['status'] ?? 'pending'));

            $statusLabels = [
                'pending' => 'Menunggu',
                'processing' => 'Diproses',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];

            $statusLabel = $statusLabels[$status] ?? ucfirst($status);
        ?>

        <tr>
            <td><?= $index + 1 ?></td>

            <td>
                <strong><?= esc($order['order_number'] ?? '-') ?></strong>
            </td>

            <td>
                <strong><?= esc($order['customer_name'] ?? '-') ?></strong>
                <br>
                <small><?= esc($order['customer_phone'] ?? '-') ?></small>

                <?php if (!empty($order['notes'])): ?>
                    <br>
                    <small>Catatan: <?= esc($order['notes']) ?></small>
                <?php endif; ?>
            </td>

            <td>
                <?php if (($order['order_type'] ?? '') === 'dine_in'): ?>
                    Makan di tempat
                    <?php if (!empty($order['table_number'])): ?>
                        <br>Meja <?= esc($order['table_number']) ?>
                    <?php endif; ?>
                <?php else: ?>
                    Bawa pulang
                <?php endif; ?>
            </td>

            <td>
                Rp<?= number_format((float) ($order['total_amount'] ?? 0), 0, ',', '.') ?>
            </td>

            <td>
                <span class="badge <?= esc($status) ?>">
                    <?= esc($statusLabel) ?>
                </span>
            </td>

            <td>
                <?= esc(ucfirst((string) ($order['payment_status'] ?? 'unpaid'))) ?>
            </td>

            <td>
                <?= !empty($order['created_at'])
                    ? esc(date('d-m-Y H:i', strtotime($order['created_at'])))
                    : '-' ?>
            </td>

            <td>
                <form
                    action="<?= site_url('admin/orders/update-status/' . $order['id']) ?>"
                    method="post"
                >
                    <?= csrf_field() ?>

                    <select name="status" required>
                        <?php foreach ($statusLabels as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= $status === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Simpan</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
