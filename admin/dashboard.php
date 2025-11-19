<?php require_once __DIR__.'/../inc/functions.php'; require_role(['admin']); $user=current_user(); ?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Dashboard</title><link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css"></head><body><?php include __DIR__.'/../_nav.php'; ?>
<div class="container">
  <div class="card"><h2>Selamat datang, <?=htmlspecialchars($user['fullname'])?></h2><p>Role: Admin</p></div>
  <div class="card">
    <?php $r=$conn->query('SELECT COUNT(*) AS c FROM menu')->fetch_assoc(); $total_menu=$r['c']; $r=$conn->query("SELECT COUNT(*) AS c FROM users WHERE role='pelanggan'")->fetch_assoc(); $total_pelanggan=$r['c']; $r=$conn->query('SELECT COUNT(*) AS c FROM transaksi')->fetch_assoc(); $total_orders=$r['c']; ?>
    <ul><li>Menu: <?=$total_menu?></li><li>Pelanggan: <?=$total_pelanggan?></li><li>Pesanan: <?=$total_orders?></li></ul>
  </div>
</div>
</body></html>