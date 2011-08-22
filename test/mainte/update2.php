<?php
$body_onload_func = "history.go(-1);";
include 'header.php';

$mid = $_REQUEST['id'];
$ssid = $_REQUEST['ssid'];

$state = DLookUp("mainte_state", "mainte_h", "ID=$mid");
if ($state==3) return_error("更新できません。");

//update_list("mainte_d");
$table = "mainte_d";
read_list_request();
if (is_array($list)) {
	foreach ($list as $key => $values) {
		$id = $keys[$key];
		if ($id=='') return_error();
		$where = "ID=".db_value($id, $keyType);
		$where .= " and hid=$mid";
		if ($dels[$key]=='1') {
			db_delete($conn, $table, $where);
		} else {
			db_update($conn, $table, $fields, $types, $values, $where);
		}
	}
}

$addnew = $_REQUEST["addnew"];
if ($addnew<>"") {
	if ($ssid=="" or $ssid==-1) $ssid=0;

	$sql = "SELECT * FROM mainte_d WHERE hid=$mid";
	if ($debug) echo $sql.'<br>';
	$rs = db_exec($conn, $sql);

	$sql1 = "INSERT INTO mainte_d (company,hid,sort,ssid) ";
	$sql2 = "SELECT $company as company,hid,max(sort)+1,".db_value($ssid, "int")." FROM mainte_d WHERE hid=$mid GROUP BY hid";
	$sql3 = "VALUES ($company, $mid,1,".db_value($ssid, "int").")";
	if (db_fetch_row($rs)) {
		$sql = $sql1.$sql2;
	} else {
		$sql = $sql1.$sql3;
	}
	if ($debug) echo $sql.'<br>';
	$rs = db_exec($conn, $sql);

	$sql = $sql1.$sql2;
	for ($i=0; $i<4; $i++) {
		if ($debug) echo $sql.'<br>';
		$rs = db_exec($conn, $sql);
	}
}

include 'footer.php';
?>