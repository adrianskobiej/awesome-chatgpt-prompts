<?php
session_start();
$ADMIN_LOGIN = 'sar3th';
$ADMIN_PASSWORD = 'Gatewayn95!';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $login = $_POST['login'] ?? '';
    $pass = $_POST['password'] ?? '';
    if($login === $ADMIN_LOGIN && $pass === $ADMIN_PASSWORD){
        $_SESSION['is_admin'] = true;
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false]);
    }
}
?>
