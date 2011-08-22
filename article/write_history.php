<?php
//$body_onload_func = "history.back()";
$body_onload_func = "window.close()";
include 'header.php';
//$debug = true;

$where = $_REQUEST['where'];
if (get_magic_quotes_gpc()) $where = stripslashes($where);
$remarks = $_REQUEST['remarks'];
$base = "article";
$table = $base;
$sql = "insert into history";
$field = "0 as ID, '$base' as `table`, ID as rid";
$field .= ", null as date, ".db_value($remarks, "str")." as remarks";
$sql .= " select $field from $table";
if ($where<>"") $sql .= " where $where";
db_exec($conn, $sql);

include 'footer.php';
?>
