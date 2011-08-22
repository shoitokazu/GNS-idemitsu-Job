<?php $body_onload_func = "history.go(-2)"; ?>
<?php include 'header.php'; ?>

<?php
$table = $_REQUEST['table'];
$id = $_REQUEST['id'];
$where = "ID=$id";
$sql = "delete from $table where $where";
db_exec($conn, $sql);
?>
削除しました。<br>

<?php include 'footer.php'; ?>
