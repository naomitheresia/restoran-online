<?php
require_once __DIR__.'/../inc/config.php';
require_once __DIR__.'/../inc/functions.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password'];
  $stmt = $conn->prepare('SELECT * FROM users WHERE username=? LIMIT 1');
  $stmt->bind_param('s',$username); $stmt->execute(); $res = $stmt->get_result();
  if($res->num_rows===1){
    $u = $res->fetch_assoc();
    if (password_verify($password, $u['password']) || $password === $u['password'] || md5($password) === $u['password']) {
      $_SESSION['user_id'] = $u['id']; $_SESSION['role']=$u['role'];
      if($u['role']=='admin') header('Location: /restoran_fix_v2/admin/dashboard.php');
      elseif($u['role']=='kasir') header('Location: /restoran_fix_v2/kasir/dashboard.php');
      else header('Location: /restoran_fix_v2/pelanggan/dashboard.php');
      exit;
    } else { $error='Password salah.'; }
  } else { $error='Username tidak ditemukan.'; }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css"></head><body>
<div class="auth-box card"><h2>Login</h2><?php if($error) echo '<div class="alert">'.$error.'</div>'; ?>
<form method="post"><label>Username</label><input name="username" required><label>Password</label><input name="password" type="password" required><button class="btn" type="submit">Login</button></form>
<p>Belum punya akun? <a href="/restoran_fix_v2/auth/register.php">Daftar</a></p></div></body></html>
