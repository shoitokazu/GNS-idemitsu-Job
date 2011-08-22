<?php
$body_onload_func = "history.go(-2);";
include 'header.php';
?>

<?php
$table = "environment";
update_form($table);
?>

<?php include 'footer.php'; ?>
