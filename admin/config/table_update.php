<?php $body_onload_func = "history.go(-2)"; ?>
<?php include 'header.php'; ?>

<?php
$table = $_REQUEST['table'];
$id = read_form_request();
if ($id=='') {
	$id = db_insert($conn, $table, $fields, $types, $values, true);
} else {
	$where = "ID=".db_value($id, "int");
	db_update($conn, $table, $fields, $types, $values, $where, true);
}

/*
$id = $_REQUEST['id'];
$fields = $_REQUEST['fields'];
$types = $_REQUEST['types'];
$values = $_REQUEST['values'];
if ($id=='') {
	$i=0;
	$id = db_insert($conn, $table, $fields, $types, $values, true);
	echo "新規追加しました。<br>";
} else {
	db_update($conn, $table, $fields, $types, $values, "ID=$id", true);
	echo "更新しました。<br>";
}
*/
?>

<?php include 'footer.php'; ?>
