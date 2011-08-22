<?php
$body_onload_func = "next();";
include 'header.php';
?>

<?php
$table = "work_h";
$fields[0] = "making_date";
$types [0] = "date";
$values[0] = date('Y/m/d');
$fields[1] = "uid";
$types [1] = "int";
$values[1] = $uid;
$fields[2] = "company";
$types [2] = "int";
$values[2] = $company;

$id = db_insert($conn, $table, $fields, $types, $values);
setCode($table, $id, "wcode");
?>
<script language="javascript">
function next() {
	location.replace('form1.php?id=<?=$id?>&<?=$select_arg?>');
}
</script>

<?php include 'footer.php'; ?>
