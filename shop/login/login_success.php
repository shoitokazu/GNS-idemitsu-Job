<?php
$back = $_REQUEST['back'];
if ($back<>"") {
	$body_onload_func = "history.go(-".$back.")";
} else {
	$body_onload_func = "location.replace('../home/index.php')";
}
require 'header.php';
?>

<p>ログインしました。</p>
<p><a href="../home/index.php">メニュー</a></p>

<?php require 'footer.php'; ?>
