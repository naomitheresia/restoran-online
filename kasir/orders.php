<?php 
require_once __DIR__.'/../inc/functions.php'; 
require_role(['kasir']); 

// Update status
if(isset($_GET['setstatus'])){
    $id = intval($_GET['id']);
    $s  = $_GET['setstatus'];

    $stmt = $conn->prepare('UPDATE transaksi SET status=?, updated_at=NOW() WHERE id=?');
    $stmt->bind_param('si',$s,$id);
    $stmt->execute();

    flash('msg','Status pesanan berhasil diperbarui');
    header('Location:/restoran_fix_v2/kasir/orders.php');
    exit;
}

$res = $conn->query('
    SELECT t.*, u.fullname 
    FROM transaksi t 
    JOIN users u ON t.user_id=u.id 
    ORDER BY t.created_at DESC
');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Kelola Pesanan</title>
<link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">

<style>
    body {
        background: #f2fff4;
        font-family: "Poppins", sans-serif;
    }
    .container {
        max-width: 1100px;
        margin: 40px auto;
    }
    h2 {
        color: #1e8f46;
        margin-bottom: 15px;
    }
    .alert {
        padding: 12px;
        border-radius: 8px;
        background: #e2ffe7;
        border: 1px solid #c3f2cb;
        color: #1e8f46;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
        font-size: 15px;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(0,0,0,.06);
    }
    table th {
        background: #27ae60;
        color: #fff;
        padding: 12px;
        text-align: left;
    }
    table td {
        padding: 10px;
        border-bottom: 1px solid #eaeaea;
    }
    table tr:hover td {
        background: #effff3;
    }
    .status {
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }
    .status.pending {
        background: #fff5c2;
        color: #ab8b00;
    }
    .status.proses {
        background: #d7f5ff;
        color: #007bb2;
    }
    .status.selesai {
        background: #dcffd8;
        color: #0d861e;
    }
    .btn {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        background: #1e8f46;
        color: #fff;
        margin-right: 4px;
        display: inline-block;
        transition: .2s;
    }
    .btn:hover {
        background:#12652f;
    }
</style>
</head>
<body>

<?php include __DIR__.'/../_nav.php'; ?>

<div class="container">

    <h2>Kelola Pesanan</h2>

    <?php if($m = flash('msg')): ?>
        <div class="alert"><?= $m ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Pesanan</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while($o = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $o['id'] ?></td>
                <td><?= $o['order_code'] ?></td>
                <td><?= htmlspecialchars($o['fullname']) ?></td>
                <td>Rp <?= number_format($o['total'],0,',','.') ?></td>
                <td>
                    <span class="status <?= $o['status'] ?>">
                        <?= ucfirst($o['status']) ?>
                    </span>
                </td>
                <td><?= $o['created_at'] ?></td>
                <td>
                    <a class="btn" 
                        href="/restoran_fix_v2/kasir/order_view.php?id=<?= $o['id'] ?>">
                        Lihat
                    </a>

                    <?php if($o['status']!='selesai'): ?>
                        <a class="btn" 
                            href="?id=<?= $o['id'] ?>&setstatus=proses">
                            Proses
                        </a>
                        <a class="btn" 
                            href="?id=<?= $o['id'] ?>&setstatus=selesai">
                            Selesai
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>
