<?php 
require_once __DIR__.'/../inc/functions.php'; 
require_role(['pelanggan']); 
$user = current_user();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard Pelanggan</title>
<link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">

<style>
    body {
        background: #f2fff4;
        font-family: "Poppins", sans-serif;
    }
    .container {
        max-width: 900px;
        margin: 40px auto;
        padding: 10px;
    }
    .welcome-box {
        background: #ffffff;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(0,0,0,.08);
        border-left: 6px solid #1e8f46;
        animation: fadeInDown .4s ease;
    }
    h2 {
        margin: 0;
        color: #1e8f46;
        font-size: 26px;
        font-weight: 600;
    }
    p {
        margin-top: 8px;
        font-size: 15px;
        color: #444;
    }
    .btn-main {
        display: inline-block;
        margin-top: 15px;
        background: #1e8f46;
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: .2s;
    }
    .btn-main:hover {
        background: #146b34;
    }
    @keyframes fadeInDown {
        from {opacity:0; transform: translateY(-10px);}
        to {opacity:1; transform: translateY(0);}
    }
</style>

</head>
<body>

<?php include __DIR__.'/../_nav.php'; ?>

<div class="container">
    <div class="welcome-box">
        <h2>Halo, <?= htmlspecialchars($user['fullname']); ?> 👋</h2>
        <p>Silakan pesan makanan favorit Anda dari menu yang tersedia.</p>

        <a class="btn-main" href="/restoran_fix_v2/pelanggan/orders_create.php">
            ➕ Buat Pesanan
        </a>
    </div>
</div>

</body>
</html>
