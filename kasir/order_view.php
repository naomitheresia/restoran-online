<?php 
require_once __DIR__.'/../inc/functions.php'; 
require_role(['admin','kasir','pelanggan']); 

$id = intval($_GET['id']);

$stmt = $conn->prepare('
    SELECT t.*, u.fullname, u.email 
    FROM transaksi t 
    JOIN users u ON t.user_id = u.id 
    WHERE t.id=?
');
$stmt->bind_param('i',$id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if(!$order){
    echo 'Not found'; 
    exit;
}

$items = $conn->prepare('
    SELECT dt.*, m.nama 
    FROM detail_transaksi dt 
    JOIN menu m ON dt.menu_id = m.id 
    WHERE dt.transaksi_id=?
');
$items->bind_param('i',$id);
$items->execute();
$items_res = $items->get_result();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Detail Order</title>
<link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">

<style>
    body {
        background: #f2fff4;
        font-family: "Poppins", sans-serif;
    }
    .container {
        max-width: 950px;
        margin: 40px auto;
    }
    .card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 18px rgba(0,0,0,.08);
        animation: fadeIn .4s ease;
    }
    h2, h3 {
        color: #1e8f46;
        margin-top: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
        font-size: 15px;
    }
    table th {
        background: #27ae60;
        color: white;
        padding: 10px;
        text-align: left;
    }
    table td {
        padding: 10px;
        border-bottom: 1px solid #e8e8e8;
    }
    table tr:hover td {
        background: #ecfff1;
    }
    .total-box {
        margin-top: 18px;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        background: #e7ffeb;
        border: 1px solid #c7f3ce;
        color: #1e8f46;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(6px);}
        to   {opacity: 1; transform: translateY(0);}
    }
</style>

</head>
<body>

<?php include __DIR__.'/../_nav.php'; ?>

<div class="container">
    <div class="card">
        
        <h2>Detail Pesanan - <?= htmlspecialchars($order['order_code']) ?></h2>

        <p style="margin-bottom: 8px;">
            <strong>Pelanggan:</strong> 
            <?= htmlspecialchars($order['fullname']) ?> (<?= $order['email'] ?>)
        </p>

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
            <?php while($it = $items_res->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($it['nama']) ?></td>
                    <td>Rp <?= number_format($it['harga'],0,',','.') ?></td>
                    <td><?= $it['qty'] ?></td>
                    <td>Rp <?= number_format($it['subtotal'],0,',','.') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="total-box">
            Total Pembayaran : Rp <?= number_format($order['total'], 0, ',', '.') ?>
        </div>

    </div>
</div>

</body>
</html>
