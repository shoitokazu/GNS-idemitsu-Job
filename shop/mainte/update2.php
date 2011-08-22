<?php
$body_onload_func = "history.go(-1);";
include 'header.php';

$mid = $_REQUEST['id'];
$state = DLookUp("mainte_state", "mainte_h", "ID=$mid");
if (!($state==0 or $state==1 or $state==9)) return_error();
$shop_lock = DLookUp("shop_lock", "mainte_shop", "mid=$mid and ssid=$uid");
if ($shop_lock) return_error("確定済みです。");

$table = "mainte_d";
read_list_request();
if (is_array($list)) {
	foreach ($list as $key => $values) {
		$id = $keys[$key];
		if ($id=='') return_error();
		$where = "ID=".db_value($id, $keyType);
		$where .= " and hid=$mid";
		$where .= " and ssid=".$uid;
		if ($dels[$key]=='1') {
			db_delete($conn, $table, $where, true);
		} else {
			db_update($conn, $table, $fields, $types, $values, $where, true);
		}
	}
}

$addnew = $_REQUEST["addnew"];
if ($addnew<>"") {
	$row = db_row($conn, "select max(sort) from mainte_d where hid=$mid group by hid");
	$max = $row[0];
	$f1[0] = "hid";
	$t1[0] = "int";
	$v1[0] = $mid;
	$f1[1] = "ssid";
	$t1[1] = "int";
	$v1[1] = $uid;
	$f1[2] = "sort";
	$t1[2] = "int";
	for ($i=0; $i<5; $i++) {
		$v1[2] = $max + $i + 1;
		db_insert($conn, "mainte_d", $f1, $t1, $v1);
	}
}
?>

<?php include 'footer.php'; ?>
