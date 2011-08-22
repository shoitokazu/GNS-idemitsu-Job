<?php
$body_onload_func = "history.go(-1);";
include 'header.php';
?>

<?php
$field = $_REQUEST['field'];
$comp = $_REQUEST['company'];

$table = "choices";
read_list_request();
if (is_array($list)) {
	foreach ($list as $key => $values) {
		$id = $keys[$key];
		if ($id!='') {
			$where = "field=".db_value($field, "str");
			$where .= " and ID=".db_value($id, "int");
			if ($dels[$key]=='1') {
				db_delete($conn, $table, $where, true);
			} else {
				db_update($conn, $table, $fields, $types, $values, $where, true);
			}
		}
	}
}

$addnew = $_REQUEST['addnew'];
if ($addnew!="") {
	$f1[0] = "field";
	$t1[0] = "str";
	$v1[0] = $field;
	$f1[1] = "company";
	$t1[1] = "int";
	$v1[1] = $comp;
	$id = db_insert($conn, $table, $f1, $t1, $v1, true);
}
?>

<?php include 'footer.php'; ?>
