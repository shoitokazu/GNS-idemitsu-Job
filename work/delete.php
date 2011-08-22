<?php
$body_onload_func = "history.go(-2);";
include 'header.php';

$table = "work_h";
$hid = $_REQUEST['id'];

/*
$v = DLookUp("work_state", $table, "ID=$hid");
if ($v[0] != 0) return_error("状態によっては、削除はできません。");
*/

//db_delete($conn, "work_d", "hid=$hid");
//db_delete($conn, $table, "ID=$hid");
$sql = "update $table set work_state=9 where ID=$hid";
db_exec($conn, $sql);

include 'footer.php';
?>