<?php
$body_onload_func = "history.go(-1);";
include 'header.php';

$mid = $_REQUEST["mid"];
$id = read_form_request();

$state = DLookUp("mainte_state", "mainte_h", "ID=$mid");
if (!($state==0 or $state==1)) return_error();

$base = "mainte_shop";
$where = "ID=$id";
$where .= " and mid=$mid";
$where .= " and ssid=$uid";
$table = $base;
$field = "shop_lock";
$field .= ",shop1";
$field .= ",shop2";
$sql = "select $field from $table where $where";
$row = db_row($conn, $sql);

if ($row==false) return_error("自社の伝票ではありません。");
if ($row[0]==true) return_error("確定済みです");

if (isset($_REQUEST['confirm']) and $row[1]==null) $sets = "shop1=".db_value(date('Y/m/d'), "date");
if (isset($_REQUEST['unconfirm']) and $row[2]==null) $sets = "shop1=".db_value(null, "date");
if (isset($_REQUEST['complete']) and $row[2]==null) $sets = "shop2=".db_value(date('Y/m/d'), "date");
if (isset($_REQUEST['uncomplete'])) $sets = "shop2=".db_value(null, "date");

if ($sets<>"") {
	$sql = "update $table set ".$sets." where $where";
	db_exec($conn, $sql);
}

if ($row[2]==null) {
	$sql = "update $table set shop4=".db_value($values[$fno['shop4']], "date")." where $where";
	db_exec($conn, $sql);
}

include 'footer.php';
?>
