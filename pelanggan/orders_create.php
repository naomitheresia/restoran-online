<?php 
require_once __DIR__.'/../inc/functions.php'; 
require_role(['pelanggan']); 

$user = current_user();

$menu_res = $conn->query('SELECT * FROM menu WHERE stok>0 ORDER BY nama');

$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $items = $_POST['items'] ?? [];
    $total = 0;
    $selected = [];

    foreach($items as $menu_id => $qty){
        $menu_id = intval($menu_id);
        $qty = intval($qty);
        if($qty <= 0) continue;

        $stmt = $conn->prepare('SELECT id,nama,harga,stok FROM menu WHERE id=?');
        $stmt->bind_param('i',$menu_id);
        $stmt->execute();
        $m = $stmt->get_result()->fetch_assoc();
        if(!$m) continue;

        if($qty > $m['stok']) 
            $errors[] = 'Stok untuk '.htmlspecialchars($m['nama']).' tidak cukup.';

        $subtotal = $m['harga'] * $qty;
        $selected[] = [
            'menu'=>$m,
            'qty'=>$qty,
            'subtotal'=>$subtotal
        ];
        $total += $subtotal;
    }

    if(empty($selected))
        $errors[] = 'Pilih minimal 1 item.';

    if(empty($errors)){
        $order_code = 'ORD'.time().rand(100,999);
        $stmt = $conn->prepare('INSERT INTO transaksi (order_code,user_id,status,total) VALUES (?,?,"pending",?)');
        $stmt->bind_param('sid',$order_code,$user['id'],$total);
        $stmt->execute();

        $order_id = $stmt->insert_id;

        foreach($selected as $it){
            $stmt = $conn->prepare('
                INSERT INTO detail_transaksi (transaksi_id,menu_id,harga,qty,subtotal) 
                VALUES (?,?,?,?,?)
            ');
            $stmt->bind_param(
                'iiidi',
                $order_id,
                $it['menu']['id'],
                $it['menu']['harga'],
                $it['qty'],
                $it['subtotal']
            );
            $stmt->execute();

            $stmt2 = $conn->prepare('UPDATE menu SET stok=stok-? WHERE id=?');
            $stmt2->bind_param('ii',$it['qty'],$it['menu']['id']);
            $stmt2->execute();
        }

        flash('msg','Order berhasil: '.$order_code);
        header('Location:/restoran_fix_v2/pelanggan/my_orders.php');
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Buat Pesanan</title>
<link rel="stylesheet" href="/restoran_fix_v2/assets/css/style.css">

<style>
    body {
        background: #f1fff3;
        font-family: "Poppins", sans-serif;
    }
    .container {
        max-width: 950px;
        margin: 40px auto;
    }
    .card {
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.07);
        animation: fadeIn .4s ease;
    }
    h2 {
        color: #1e8f46;
        margin-top: 0;
        margin-bottom: 18px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
        font-size: 15px;
    }
    table th {
        background: #27ae60;
        color: #fff;
        padding: 12px;
        text-align: left;
    }
    table td {
        padding: 12px;
        border-bottom: 1px solid #e8e8e8;
    }
    table tr:hover td {
        background: #effff2;
    }
    input[type="number"] {
        width: 70px;
        padding: 8px;
        border: 1px solid #cfcfcf;
        border-radius: 8px;
    }
    .btn {
        margin-top: 14px;
        display: inline-block;
        background: #1e8f46;
        padding: 10px 18px;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: .2s;
    }
    .btn:hover {
        background: #146d32;
    }
    .alert {
        background: #ffeaea;
        padding: 10px 16px;
        border-radius: 8px;
        color: #b30000;
        margin-bottom: 16px;
        font-size: 14px;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(8px);}
        to   {opacity: 1; transform: translateY(0);}
    }
</style>

</head>
<body>

<?php include __DIR__.'/../_nav.php'; ?>

<div class="container">
    <div class="card">

        <h2>Buat Pesanan</h2>

        <?php if($errors): ?>
            <div class="alert">
                <?= implode('<br>', $errors) ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <table>
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Pilih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($m = $menu_res->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['nama']) ?></td>
                        <td>Rp <?= number_format($m['harga'],0,',','.') ?></td>
                        <td><?= $m['stok'] ?></td>
                        <td>
                            <input type="number" 
                                   name="items[<?= $m['id'] ?>]" 
                                   value="0" 
                                   min="0" 
                                   max="<?= $m['stok'] ?>">
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button class="btn" type="submit">Konfirmasi Pesanan</button>

        </form>

    </div>
</div>

</body>
</html>
