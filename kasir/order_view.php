<?php
require_once __DIR__ . '/../inc/functions.php';
require_role(['admin', 'kasir', 'pelanggan']);

$id = intval($_GET['id']);

$stmt = $conn->prepare('SELECT t.*,u.fullname,u.email FROM transaksi t JOIN users u ON t.user_id=u.id WHERE t.id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo 'Not found';
    exit;
}

$items = $conn->prepare('SELECT dt.*, m.nama FROM detail_transaksi dt JOIN menu m ON dt.menu_id=m.id WHERE dt.transaksi_id=?');
$items->bind_param('i', $id);
$items->execute();
$items_res = $items->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Order</title>
    <link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../_nav.php'; ?>
    
    <div class="container card">
        <h2>Detail <?= $order['order_code'] ?></h2>
        <p>Pelanggan: <?= htmlspecialchars($order['fullname']) ?> (<?= $order['email'] ?>)</p>
        
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($it = $items_res->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($it['nama']) ?></td>
                        <td>Rp <?= number_format($it['harga'], 0, ',', '.') ?></td>
                        <td><?= $it['qty'] ?></td>
                        <td>Rp <?= number_format($it['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <h3>Total: Rp <?= number_format($order['total'], 0, ',', '.') ?></h3>
    </div>
</body>
</html>