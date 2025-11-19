<?php
require_once __DIR__.'/../inc/config.php';
require_once __DIR__.'/../inc/functions.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password']; $confirm = $_POST['confirm'];
  $fullname = $conn->real_escape_string($_POST['fullname']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $role = $_POST['role'] ?? 'pelanggan';
  if($password !== $confirm) $errors[]='Password dan konfirmasi tidak cocok.';
  if(strlen($password) < 4) $errors[]='Password minimal 4 karakter.';
  $stmt = $conn->prepare('SELECT id FROM users WHERE username=?'); $stmt->bind_param('s',$username); $stmt->execute();
  if($stmt->get_result()->num_rows>0) $errors[]='Username sudah dipakai.';
  if(empty($errors)){
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (username,password,fullname,email,phone,role) VALUES (?,?,?,?,?,?)');
    $stmt->bind_param('ssssss',$username,$hash,$fullname,$email,$phone,$role);
    if($stmt->execute()){ header('Location: /restoran_fix_v2/auth/login.php'); exit; } else $errors[]='Gagal: '.$conn->error;
  }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css"></head><body>
<div class="auth-box card"><h2>Register</h2><?php if($errors) echo '<div class="alert">'.implode('<br>',$errors).'</div>'; ?>
<form method="post"><label>Nama Lengkap</label><input name="fullname" required><label>Username</label><input name="username" required><label>Email</label><input name="email" type="email" required><label>No HP</label><input name="phone" required><label>Password</label><input name="password" type="password" required><label>Konfirmasi</label><input name="confirm" type="password" required><label>Role</label><select name="role"><option value="pelanggan">Pelanggan</option><option value="kasir">Kasir</option><option value="admin">Admin</option></select><button class="btn" type="submit">Daftar</button></form><p>Sudah punya akun? <a href="/restoran_fix_v2/auth/login.php">Login</a></p></div></body></html>
