<?php
require_once __DIR__ . '/../inc/functions.php';
require_role(['admin']);

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    $stmt = $conn->prepare('SELECT gambar FROM menu WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $old = $stmt->get_result()->fetch_assoc();
    
    if ($old && $old['gambar'] && file_exists(__DIR__ . '/../' . $old['gambar'])) {
        @unlink(__DIR__ . '/../' . $old['gambar']);
    }
    
    $stmt = $conn->prepare('DELETE FROM menu WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    flash('msg', 'Menu dihapus');
    header('Location:/restoran_fix_v2/admin/master_menu.php');
    exit;
}

$res = $conn->query('SELECT m.*, k.nama AS kategori FROM menu m LEFT JOIN kategori k ON m.kategori_id=k.id ORDER BY m.created_at DESC');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Master Menu</title>
    <link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../_nav.php'; ?>
    
    <div class="container">
        <h2>Master Menu</h2>
        
        <a class="btn" href="/restoran_fix_v2/admin/master_menu_add.php">Tambah Menu</a>
        
        <?php if ($m = flash('msg')) echo '<div class="alert">' . $m . '</div>'; ?>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['kode']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td>
                            <?php if ($row['gambar']): ?>
                                <img src="/restoran_fix_v2/<?= htmlspecialchars($row['gambar']) ?>" style="max-height:60px">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn" href="/restoran_fix_v2/admin/master_menu_edit.php?id=<?= $row['id'] ?>">Edit</a>
                            <a class="btn danger" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>