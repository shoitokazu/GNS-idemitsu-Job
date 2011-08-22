<?php
$body_onload_func = "history.go(-1);";
include 'header.php';
?>

<?php
$table = "company";
read_list_request();
if (is_array($list)) {
	foreach ($list as $key => $values) {
		$id = $keys[$key];
		if ($id!='') {
			$where = "ID=".db_value($id, "int");
			db_update($conn, $table, $fields, $types, $values, $where);
			$sql = "update choices set name=".db_value($values[$fno['name']], "str");
			$sql .= ",sort=".db_value($values[$fno['sort']], "int");
			$sql .= " where field='company' and value='$id'";
			db_exec($conn, $sql);
		}
	}
}

$addnew = $_REQUEST['addnew'];
if ($addnew<>"") {
	$f1[0] = "name";
	$t1[0] = "str";
	$v1[0] = "";
	$id = db_insert($conn, $table, $f1, $t1, $v1);
	$sql = "insert choices (field,value)";
	$sql .= " values ('company','$id')";
	db_exec($conn, $sql);
}
?>

<?php include 'footer.php'; ?>
