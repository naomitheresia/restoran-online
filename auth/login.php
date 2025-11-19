<?php
require_once __DIR__.'/../inc/config.php';
require_once __DIR__.'/../inc/functions.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password'];
  $stmt = $conn->prepare('SELECT * FROM users WHERE username=? LIMIT 1');
  $stmt->bind_param('s',$username); 
  $stmt->execute(); 
  $res = $stmt->get_result();

  if($res->num_rows===1){
    $u = $res->fetch_assoc();
    if (password_verify($password, $u['password']) || 
         $password === $u['password'] || 
         md5($password) === $u['password']) {

      $_SESSION['user_id'] = $u['id']; 
      $_SESSION['role']=$u['role'];

      if($u['role']=='admin') 
        header('Location: /restoran_fix_v2/admin/dashboard.php');
      elseif($u['role']=='kasir') 
        header('Location: /restoran_fix_v2/kasir/dashboard.php');
      else 
        header('Location: /restoran_fix_v2/pelanggan/dashboard.php');
      exit;

    } else { 
      $error='Password salah.'; 
    }
  } else { 
    $error='Username tidak ditemukan.'; 
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>

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
    .login-card {
        width: 380px;
        background: #fff;
        border-radius: 15px;
        padding: 25px 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,.2);
        animation: fadeIn .5s ease;
        border-top: 5px solid #27ae60;
    }
    .login-card h2 {
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

<div class="login-card">
    <h2 class="text-center mb-3">Login</h2>

    <?php if(isset($error) && $error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100" type="submit">Login</button>
    </form>

    <p class="text-center mt-3">
        Belum punya akun?
        <a href="/restoran_fix_v2/auth/register.php">Daftar</a>
    </p>
</div>

</body>
</html>
