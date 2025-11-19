<?php
require_once __DIR__ . '/../inc/functions.php';
require_role(['admin']);

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM users WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    flash('msg', 'User dihapus');
    header('Location:/restoran_fix_v2/admin/users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $role = $_POST['role'];
    
    $stmt = $conn->prepare('INSERT INTO users (username,password,fullname,email,phone,role) VALUES (?,?,?,?,?,?)');
    $stmt->bind_param('ssssss', $username, $password, $fullname, $email, $phone, $role);
    $stmt->execute();
    flash('msg', 'User ditambahkan');
    header('Location:/restoran_fix_v2/admin/users.php');
    exit;
}

$res = $conn->query('SELECT * FROM users ORDER BY id DESC');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users</title>
    <link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../_nav.php'; ?>
    
    <div class="container card">
        <h2>Manajemen User</h2>
        
        <?php if ($m = flash('msg')) echo '<div class="alert">' . $m . '</div>'; ?>
        
        <form method="post">
            <label>Nama Lengkap</label>
            <input name="fullname" required>
            
            <label>Username</label>
            <input name="username" required>
            
            <label>Password</label>
            <input name="password" required>
            
            <label>Email</label>
            <input name="email" type="email" required>
            
            <label>Phone</label>
            <input name="phone" required>
            
            <label>Role</label>
            <select name="role">
                <option value="pelanggan">Pelanggan</option>
                <option value="kasir">Kasir</option>
                <option value="admin">Admin</option>
            </select>
            
            <button class="btn" type="submit">Tambah User</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Fullname</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($u = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['fullname']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['phone']) ?></td>
                        <td><?= $u['role'] ?></td>
                        <td><a class="btn danger" href="?delete=<?= $u['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>