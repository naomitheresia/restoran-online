<?php
require_once __DIR__ . '/../inc/functions.php';
require_role(['admin']);

global $conn;
$user = current_user();

$stats = [
    'menu'      => $conn->query("SELECT COUNT(*) c FROM menu")->fetch_assoc()['c'],
    'pelanggan' => $conn->query("SELECT COUNT(*) c FROM users WHERE role='pelanggan'")->fetch_assoc()['c'],
    'order'     => $conn->query("SELECT COUNT(*) c FROM transaksi")->fetch_assoc()['c']
];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">

    <style>
        body{background:#f5f7fa;font-family:Arial}
        .box,.card,.sys{border-radius:12px;border:1px solid #e5e5e5;padding:20px;margin-bottom:20px;background:#fff}
        .wrap{width:92%;margin:20px auto}
        .grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
        .grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:15px}
        .welcome{background:linear-gradient(90deg,#d7f8e6,#f7fffc);display:flex;justify-content:space-between;align-items:center}
        .role{background:#16a34a;color:#fff;padding:4px 10px;border-radius:8px;font-size:13px}
        .action-btn{padding:15px;text-align:center;border-radius:10px;color:#fff;font-weight:bold;text-decoration:none}
        .green{background:#059669}.blue{background:#3b82f6}.purple{background:#8b5cf6}.orange{background:#f97316}
        .sys{background:#059669;color:#fff;display:flex;justify-content:space-between}
    </style>
</head>

<body>
<?php include __DIR__ . '/../_nav.php'; ?>

<div class="wrap">

    <!-- WELCOME -->
    <div class="box welcome">
        <div>
            <h2>Selamat datang, <?= htmlspecialchars($user['fullname']) ?></h2>
            <span class="role">Role: Admin</span>
        </div>
        <div><strong>Tanggal:</strong><br><?= date('l, d F Y') ?></div>
    </div>

    <!-- STAT CARDS -->
    <div class="grid3">
        <div class="card"><div>Menu</div><h2><?= $stats['menu'] ?></h2></div>
        <div class="card"><div>Pelanggan</div><h2><?= $stats['pelanggan'] ?></h2></div>
        <div class="card"><div>Pesanan</div><h2><?= $stats['order'] ?></h2></div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="box">
        <h3>Quick Actions</h3>
        <div class="grid4">
            <a href="/restoran_fix_v2/admin/users.php" class="action-btn green">Kelola Users</a>
            <a href="/restoran_fix_v2/admin/menu.php" class="action-btn blue">Kelola Menu</a>
            <a href="/restoran_fix_v2/admin/kategori.php" class="action-btn purple">Kategori</a>
            <a href="/restoran_fix_v2/admin/reports.php" class="action-btn orange">Reports</a>
        </div>
    </div>

    <!-- SYSTEM -->
    <div class="sys">
        <div>
            <h3>Sistem Berjalan Normal</h3>
            Semua modul berfungsi baik.
        </div>
        <div style="text-align:right">
            <div><?= $stats['menu'] ?> Menu Aktif</div>
            <div>1 User Aktif</div>
            <div>100% Uptime</div>
        </div>
    </div>

</div>
</body>
</html>
