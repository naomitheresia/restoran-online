<?php
require_once __DIR__.'/config.php';
function is_logged_in(){ return isset($_SESSION['user_id']); }
function current_user(){
    global $conn;
    if(!is_logged_in()) return null;
    $id = intval($_SESSION['user_id']);
    $stmt = $conn->prepare('SELECT id,username,fullname,email,phone,role FROM users WHERE id=?');
    $stmt->bind_param('i',$id); $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}
function require_role($roles=[]){
    if(!is_logged_in()){ header('Location: /restoran_fix_v2/auth/login.php'); exit; }
    $u = current_user();
    if(!in_array($u['role'], (array)$roles)){
        echo 'Akses ditolak.'; exit;
    }
}
function flash($name='', $message=''){
    if($message===''){ if(isset($_SESSION[$name])){ $m = $_SESSION[$name]; unset($_SESSION[$name]); return $m; } return ''; }
    $_SESSION[$name] = $message;
}
?>