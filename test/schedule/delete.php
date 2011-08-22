<?php
$body_onload_func = "history.go(-2);";
include 'header.php';

$table = "schedule";
$id = $_REQUEST['id'];

db_delete($conn, "schedule_date", "hid=$id", true);
db_delete($conn, "schedule_user", "hid=$id", true);
db_delete($conn, $table, "ID=$id", true);

include 'footer.php';
?>