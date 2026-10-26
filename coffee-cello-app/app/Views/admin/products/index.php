
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="<?= site_url('/') ?>">Coffee Cello</a>
    <form action="<?= site_url('admin/logout') ?>" method="post">
        <?= csrf_field() ?>
        <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
    </form>
</nav>

<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="mb-1">Kelola Produk</h2>
            <p class="text-muted mb-0">Dashboard admin Coffee Cello</p>
        </div>
        <a href="<?= site_url('admin/products/create') ?>" class="btn btn-dark">
            + Tambah Produk
        </a>
        <a href="<?= site_url('admin/orders') ?>" class="btn btn-outline-dark">
            Daftar Pesanan
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif ?>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Total Produk</div>
                    <h3><?= count($products) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Total Kategori</div>
                    <h3><?= count($categories) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Daftar Produk</div>
        <div class="card-body">
            <?php if (empty($products)): ?>
                <div class="text-center py-4">
                    <p class="text-muted">Belum ada produk.</p>
                    <a href="<?= site_url('admin/products/create') ?>" class="btn btn-dark">
                        Tambahkan Produk Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img
                                            src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>"
                                            alt="<?= esc($product['name']) ?>"
                                            style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                                    <?php else: ?>
                                        <span class="text-muted small">Belum ada foto</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <strong><?= esc($product['name']) ?></strong>
                                    <div class="small text-muted"><?= esc($product['slug']) ?></div>
                                </td>
                                <td><?= esc($categories[$product['category_id']] ?? 'Tidak diketahui') ?></td>
                                <td>Rp<?= number_format((float) $product['price'], 0, ',', '.') ?></td>
                                <td><?= esc($product['stock']) ?></td>
                                <td>
                                    <?php if ($product['is_available']): ?>
                                        <span class="badge text-bg-success">Tersedia</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-secondary">Tidak tersedia</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= site_url('admin/products/edit/' . $product['id']) ?>"
                                           class="btn btn-sm btn-outline-primary">Edit</a>

                                        <form action="<?= site_url('admin/products/delete/' . $product['id']) ?>"
                                              method="post"
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

</body>
</html>
