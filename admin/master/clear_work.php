<?php include 'header.php';?>

<h1>見切り案件の消去</h1>

<?php
$confirm = $_REQUEST['confirm'];
if ($confirm<>'実行') {
?>
<form action="#" method="post">
<input type="submit" name="confirm" value="実行">
<p><a href="javascript:history.back()">戻る</a></p>
</form>
<?php
	include 'footer.php';
	exit();
}
?>

<?php
$table = "work_h";
$hid = $_REQUEST['id'];

/*
$v = DLookUp("work_state", $table, "ID=$hid");
if ($v[0] != 0) return_error("状態によっては、削除はできません。");
*/

//db_delete($conn, "work_d", "hid=$hid");
//db_delete($conn, $table, "ID=$hid");
$sql = "update $table set work_state=9 where ID=$hid";
db_exec($conn, $sql);

include 'footer.php';
?>