
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Coffee Cello</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #f4eee7;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(55, 35, 23, .12);
        }

        .brand {
            color: #6f4228;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .btn-coffee {
            background: #6f4228;
            color: #fff;
            border: none;
        }

        .btn-coffee:hover {
            background: #4d2d1c;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h2 class="brand">COFFEE CELLO</h2>
        <p class="text-secondary mb-0">Admin Dashboard</p>
        <p class="small text-secondary">Silakan login untuk melanjutkan</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('admin/login') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email admin</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                value="<?= esc(old('email') ?? '') ?>"
                placeholder="admin@coffeecello.local"
                required
                autocomplete="username"
            >
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
                autocomplete="current-password"
            >
        </div>

        <button type="submit" class="btn btn-coffee w-100 py-2">
            Login
        </button>
    </form>

    <p class="text-center text-secondary small mt-4 mb-0">
        &copy; <?= date('Y') ?> Coffee Cello
    </p>
</div>

</body>
</html>
