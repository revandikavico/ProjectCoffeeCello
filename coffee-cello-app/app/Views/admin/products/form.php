
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= esc($title) ?></h2>
        <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-secondary">
            Kembali
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>Periksa kembali input kamu:</strong>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?php
        $isEdit = !empty($product);
        $action = $isEdit
            ? site_url('admin/products/update/' . $product['id'])
            : site_url('admin/products/store');
    ?>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= esc(old('name', $product['name'] ?? '')) ?>"
                        required maxlength="150">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']) ?>"
                                <?= (string) old('category_id', $product['category_id'] ?? '') === (string) $category['id'] ? 'selected' : '' ?>>
                                <?= esc($category['name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"><?= esc(old('description', $product['description'] ?? '')) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control"
                            min="0" step="1"
                            value="<?= esc(old('price', $product['price'] ?? '')) ?>"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" class="form-control"
                            min="0" step="1"
                            value="<?= esc(old('stock', $product['stock'] ?? '0')) ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Produk (maks. 2 MB)</label>
                    <input type="file" name="image" class="form-control"
                        accept="image/jpeg,image/png,image/webp">

                    <?php if ($isEdit && !empty($product['image'])): ?>
                        <div class="mt-2">
                            <img
                                src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>"
                                alt="Foto produk"
                                style="max-width:160px;max-height:140px;object-fit:cover">
                            <p class="small text-muted">Foto saat ini. Kosongkan jika tidak ingin menggantinya.</p>
                        </div>
                    <?php endif ?>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="is_available" value="1"
                        class="form-check-input" id="is_available"
                        <?= old('is_available', $product['is_available'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_available">
                        Produk tersedia untuk dijual
                    </label>
                </div>

                <button type="submit" class="btn btn-dark">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Produk' ?>
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
