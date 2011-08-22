<?php
$body_onload_func = "history.go(-2);";
include 'header.php';

$table = "sales_h";
$hid = $_REQUEST['id'];

$v = DLookUp("sales_state", $table, "ID=$hid");
if ($v[0] == 3) return_error("売上確定したものは、削除はできません。");

/*
db_delete($conn, "sales_d", "hid=$hid");
db_delete($conn, $table, "ID=$hid");
*/
$sql = "update $table set sales_state=9 where ID=$hid";
db_exec($conn, $sql);

include 'footer.php';
?>