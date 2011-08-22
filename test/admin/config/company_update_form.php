<?php
$body_onload_func = "history.go(-1);";
include 'header.php';
?>

<?php
$table = "company";
read_form_request();
if (is_array($fields)) {
	if ($id=='') {
		$id = db_insert($conn, $table, $fields, $types, $values, true);
	} else {
		$where = "ID=$id";
		db_update($conn, $table, $fields, $types, $values, $where, true);
	}
}
?>

<?php include 'footer.php'; ?>
