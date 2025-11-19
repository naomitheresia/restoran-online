<?php
require_once __DIR__.'/../inc/config.php';
require_once __DIR__.'/../inc/functions.php';

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password']; 
  $confirm = $_POST['confirm'];
  $fullname = $conn->real_escape_string($_POST['fullname']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $role = $_POST['role'] ?? 'pelanggan';

  if($password !== $confirm) $errors[]='Password dan konfirmasi tidak cocok.';
  if(strlen($password) < 4) $errors[]='Password minimal 4 karakter.';

  $stmt = $conn->prepare('SELECT id FROM users WHERE username=?');
  $stmt->bind_param('s',$username); 
  $stmt->execute();

  if($stmt->get_result()->num_rows>0) 
      $errors[]='Username sudah dipakai.';

  if(empty($errors)){
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (username,password,fullname,email,phone,role) VALUES (?,?,?,?,?,?)');
    $stmt->bind_param('ssssss',$username,$hash,$fullname,$email,$phone,$role);

    if($stmt->execute()){ 
      header('Location: /restoran_fix_v2/auth/login.php'); 
      exit; 
    } else { 
      $errors[]='Gagal: '.$conn->error; 
    }
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>

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
    }
    .register-card {
        width: 420px;
        background: #fff;
        border-radius: 15px;
        padding: 25px 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,.2);
        animation: fadeIn .5s ease;
        border-top: 5px solid #27ae60;
    }
    .register-card h2 {
        font-weight: 600;
        color: #27ae60;
    }
    .btn-primary {
        background-color: #27ae60;
        border: none;
    }
    .btn-primary:hover {
        background-color: #219150;
    }
    a {
        color: #27ae60;
        text-decoration: none;
    }
    a:hover {
        color: #1f7d49;
        text-decoration: underline;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

</head>
<body>

<div class="register-card">
    <h2 class="text-center mb-3">Register</h2>

    <?php if($errors): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', $errors) ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input name="fullname" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input name="confirm" type="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-control">
                <option value="pelanggan">Pelanggan</option>
                <option value="kasir">Kasir</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button class="btn btn-primary w-100" type="submit">Daftar</button>
    </form>

    <p class="text-center mt-3">
        Sudah punya akun? <a href="/restoran_fix_v2/auth/login.php">Login</a>
    </p>
</div>

</body>
</html>
