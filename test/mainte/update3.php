<?php
$body_onload_func = "history.go(-1);";
include 'header.php';

$mid = $_REQUEST['id'];

$state = DLookUp("mainte_state", "mainte_h", "ID=$mid");
if ($state==3) return_error("更新できません。");

$table = "mainte_shop";
read_list_request();
if (is_array($list)) {
	foreach ($list as $key => $values) {
		$id = $keys[$key];
		if ($id=='') return_error();
		$where = "ID=".db_value($id, $keyType);
		$where .= " and mid=$mid";
		if ($dels[$key]=='1') {
			db_delete($conn, $table, $where);
		} else {
			db_update($conn, $table, $fields, $types, $values, $where);
		}
	}
}

include 'footer.php';
?>
