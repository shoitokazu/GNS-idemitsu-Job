<?php
$body_onload_func = "history.go(-1);";
include 'header.php';
?>

<?php
$table = "schedule";
//$id = update_form($table);
$id = read_form_request();
if ($id=='') {
	$id = db_insert($conn, $table, $fields, $types, $values);
} else {
	$where = "ID=".db_value($id, "int");
	db_update($conn, $table, $fields, $types, $values, $where, true);
}
?>

<?php include 'footer.php'; ?>
