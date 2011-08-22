<?php
$body_onload_func = "history.go(-1);";
include 'header.php';
?>

<?php
$table = "account";
read_list_request();
if (is_array($list)) {
	foreach ($list as $key => $values) {
		$id = $keys[$key];
		if ($id!='') {
			$where = "ID=".db_value($id, "int");
			db_update($conn, $table, $fields, $types, $values, $where, true);
		}
	}
}

$addnew = $_REQUEST['addnew'];
if ($addnew<>"") {
	$table = "account";
	$f1[0] = "auth";
	$t1[0] = "int";
	$v1[0] = 0;
	$id = db_insert($conn, $table, $f1, $t1, $v1);
	setCode($table, $id, "user");
	db_exec($conn, "update account set company=$id where ID=$id");
}
?>

<?php include 'footer.php'; ?>
