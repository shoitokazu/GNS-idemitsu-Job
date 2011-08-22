<?php
$body_onload_func = "history.back();";
include 'header.php';

$table = "work_h";
$id = $_REQUEST['id'];
if ($id=='') return_error();
$fields[0] = "work_state";
$types[0] = "int";
$values[0] = 2;
$where = "ID=$id";
$where .= " and work_state=3";
db_update($conn, $table, $fields, $types, $values, $where);

include 'footer.php';
?>