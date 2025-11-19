<?php 
require_once __DIR__.'/../inc/functions.php'; 
require_role(['pelanggan']); 

$user = current_user();

$stmt = $conn->prepare('
    SELECT * FROM transaksi 
    WHERE user_id=? 
    ORDER BY created_at DESC
');
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Riwayat Pesanan</title>
<link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">

<style>
    body {
        background: #f2fff5;
        font-family: "Poppins", sans-serif;
    }
    .container {
        max-width: 1000px;
        margin: 40px auto;
    }
    .card {
        background: #ffffff;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(0,0,0,.05);
        animation: fadeIn .4s ease;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
        font-size: 15px;
    }
    table thead tr {
        background: #27ae60;
        color: #fff;
    }
    th, td {
        padding: 12px;
    }
    tr:nth-child(even) {
        background: #f8fff9;
    }
    tr:hover td {
        background: #ecfff1;
    }
    .badge {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        text-transform: capitalize;
    }
    .badge-pending { background: #f1c40f; }
    .badge-proses { background: #2980b9; }
    .badge-selesai { background: #27ae60; }
    .btn-sm {
        padding: 8px 14px;
        background: #1e8f46;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-sm:hover {
        background: #146d32;
    }
    h2 {
        color: #1e8f46;
        margin-top: 0;
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

        <h2>Riwayat Pesanan</h2>

        <?php if($m = flash('msg')): ?>
            <div class="alert"><?= $m ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Kode Order</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php while($o = $orders->fetch_assoc()): 
                // Tentukan warna badge
                $statusClass = 
                    $o['status'] == 'selesai' ? 'badge-selesai' :
                    ($o['status'] == 'proses' ? 'badge-proses' : 'badge-pending');
            ?>
                <tr>
                    <td><?= $o['order_code'] ?></td>
                    <td>Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
                    <td><span class="badge <?= $statusClass ?>"><?= $o['status'] ?></span></td>
                    <td><?= $o['created_at'] ?></td>
                    <td>
                        <a class="btn-sm" href="/restoran_fix_v2/kasir/order_view.php?id=<?= $o['id'] ?>">
                            Lihat
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>

        </table>
    </div>
</div>

</body>
</html>
