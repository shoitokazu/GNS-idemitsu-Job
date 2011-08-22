<?php
//$body_onload_func = "history.back()";
$body_onload_func = "window.close()";
include 'header.php';
//$debug = true;

$where = $_REQUEST['where'];
if (get_magic_quotes_gpc()) $where = stripslashes($where);
$sql = "update article_task set atask1=".db_value(date('Y/m/d'), "date");
if ($where<>"") $sql .= " where $where";
db_exec($conn, $sql);

include 'footer.php';
?>
