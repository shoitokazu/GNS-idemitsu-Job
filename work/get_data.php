<?php
$body_onload_func = "history.go(-1);";
include 'header.php';

$table = "work_h";
$id = $_REQUEST['id'];

$sql = "update $table set company=$company where company=0 and ID=$id";
db_exec($conn, $sql);

include 'footer.php';
?>