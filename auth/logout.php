<?php
require_once __DIR__.'/../inc/config.php';
session_destroy();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Logout</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #2ecc71, #1e8f46);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        color: white;
    }
    .logout-box {
        text-align: center;
        animation: fadeIn .6s ease;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
        margin-bottom: 15px;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to   {opacity: 1; transform: translateY(0);}
    }
</style>

<!-- Redirect otomatis ke login -->
<meta http-equiv="refresh" content="1.5; URL=/restoran_fix_v2/auth/login.php">

</head>
<body>

<div class="logout-box">
    <div class="spinner-border text-light"></div>
    <h3>Keluar...</h3>
    <p>Mengarahkan ke halaman login</p>
</div>

</body>
</html>
