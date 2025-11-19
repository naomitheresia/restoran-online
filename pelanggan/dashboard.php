<?php
require_once __DIR__ . '/../inc/functions.php';
require_role(['pelanggan']);

$user = current_user();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pelanggan</title>
    <link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../_nav.php'; ?>
    
    <div class="container">
        <div class="card">
            <h2>Welcome, <?= htmlspecialchars($user['fullname']) ?></h2>
            <p>Lihat menu dan pesan makanan.</p>
            <a class="btn" href="/restoran_fix_v2/pelanggan/orders_create.php">Buat Pesanan</a>
        </div>
    </div>
</body>
</html>