<?php
require_once __DIR__ . '/../inc/functions.php';
require_role(['pelanggan']);

$user = current_user();

$stmt = $conn->prepare('SELECT * FROM transaksi WHERE user_id=? ORDER BY created_at DESC');
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat</title>
    <link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../_nav.php'; ?>
    
    <div class="container card">
        <h2>Riwayat Pesanan</h2>
        
        <?php if ($m = flash('msg')) echo '<div class="alert">' . $m . '</div>'; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tgl</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($o = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?= $o['order_code'] ?></td>
                        <td>Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
                        <td><?= $o['status'] ?></td>
                        <td><?= $o['created_at'] ?></td>
                        <td><a class="btn" href="/restoran_fix_v2/kasir/order_view.php?id=<?= $o['id'] ?>">Lihat</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>