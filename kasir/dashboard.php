<?php 
require_once __DIR__.'/../inc/functions.php'; 
require_role(['kasir']); 
$user = current_user(); 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Kasir</title>
<link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">

<style>
    body {
        background: #f2fff4;
        font-family: 'Poppins', sans-serif;
    }
    .container {
        max-width: 900px;
        margin: 40px auto;
    }
    .card {
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 5px 18px rgba(0,0,0,.08);
        animation: fadeIn .4s ease;
    }
    .card h2 {
        margin: 0;
        color: #1e8f46;
        font-weight: 600;
    }
    .btn {
        display: inline-block;
        padding: 12px 20px;
        background: #27ae60;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        transition: .2s;
        text-align: center;
        font-weight: 500;
    }
    .btn:hover {
        background: #1e8f46;
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

    <!-- Header Card -->
    <div class="card">
        <h2>Kasir - <?= htmlspecialchars($user['fullname']) ?></h2>
        <p style="margin-top:10px;">
            Selamat datang! Silakan gunakan menu berikut untuk mengelola transaksi.
        </p>
    </div>

    <!-- Menu Aksi -->
    <div class="card">
        <a class="btn" href="/restoran_fix_v2/kasir/orders.php">
            Kelola Pesanan
        </a>
    </div>

</div>

</body>
</html>
