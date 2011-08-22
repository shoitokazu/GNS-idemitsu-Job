<?php
$body_onload_func = "history.go(-1)";
include 'header.php';
?>

<?php
$field = $_REQUEST['field'];

switch ($field) {
case "staff":
case "title":
case "keeping":
case "payment":
case "print_title":
case "print_name":
case "distination":
case "igroup":
case "ordering_item":
case "ordering_method":
case "igroup":
	break;
default:
//	return_error();
}

$table = "choices";
read_list_request();
if (is_array($list)) {
	foreach ($list as $key => $values) {
		$id = $keys[$key];
		if ($id!='') {
			$where = "field=".db_value($field, "str");
			$where .= " and ID=".db_value($id, "int");
			if ($dels[$key]=='1') {
				db_delete($conn, $table, $where);
			} else {
				db_update($conn, $table, $fields, $types, $values, $where);
			}
		}
	}
}

$addnew = $_REQUEST['addnew'];
if ($addnew!="") {
	$fields[0] = "field";
	$types [0] = "str";
	$values[0] = $field;
	$fields[1] = "value";
	$types [1] = "str";
	$values[1] = $addnew;
	db_insert($conn, $table, $fields, $types, $values);
}
?>

<?php include 'footer.php'; ?>
