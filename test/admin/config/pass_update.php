<?php
$body_onload_func = "history.go(-2);";
include 'header.php';
?>

<?php
$id = $_REQUEST['id'];
$pass1 = $_REQUEST['pass1'];
$pass2 = $_REQUEST['pass2'];

if ($pass1<>$pass2) return_error("確認パスワードが違う");

$encode = $_REQUEST['encode'];
if ($encode=="1") {
	$pass1 = md5($pass1);
} else {
	$encode = "0";
}
$sql = "update account set pass=".db_value($pass1, "str").",encode=$encode where ID=$id";
db_exec($conn, $sql);
?>

<?php include 'footer.php'; ?>
