<?php
$body_onload_func = "history.go(-2);";
include 'header.php';

$table = "customer";
$id = $_REQUEST['id'];

db_delete($conn, $table, "ID=$id", true);

include 'footer.php';
?>