<?php
require_once __DIR__.'/inc/functions.php';
$user = current_user();
?>
<header class="topbar">
  <div class="brand">RestoOnline</div>
  <nav class="navlinks">
    <?php if($user && $user['role']=='admin'): ?>
      <a href="/restoran_fix_v2/admin/dashboard.php">Dashboard</a>
      <a href="/restoran_fix_v2/admin/users.php">Users</a>
      <a href="/restoran_fix_v2/admin/master_menu.php">Master Menu</a>
      <a href="/restoran_fix_v2/admin/kategori.php">Kategori</a>
      <a href="/restoran_fix_v2/admin/reports.php">Reports</a>
    <?php elseif($user && $user['role']=='kasir'): ?>
      <a href="/restoran_fix_v2/kasir/dashboard.php">Dashboard</a>
      <a href="/restoran_fix_v2/kasir/orders.php">Pesanan</a>
    <?php else: ?>
      <a href="/restoran_fix_v2/pelanggan/dashboard.php">Home</a>
      <a href="/restoran_fix_v2/pelanggan/orders_create.php">Pesan</a>
      <a href="/restoran_fix_v2/pelanggan/my_orders.php">Riwayat</a>
    <?php endif; ?>
    <?php if($user): ?><a href="/restoran_fix_v2/auth/logout.php" class="btn">Logout</a><?php else: ?><a href="/restoran_fix_v2/auth/login.php" class="btn">Login</a><?php endif; ?>
  </nav>
</header>
