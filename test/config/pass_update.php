<?php
$body_onload_func = "history.go(-2);";
include 'header.php';
?>

<?php
//$id = $_REQUEST['id'];
$id = $uid;
$pass1 = $_REQUEST['pass1'];
$pass2 = $_REQUEST['pass2'];

if ($pass1<>$pass2) return_error("確認パスワードが違う");

$sql = "update account set pass=".db_value(md5($pass1), "str").",encode=1 where ID=$id";
db_exec($conn, $sql);
?>

<?php include 'footer.php'; ?>
