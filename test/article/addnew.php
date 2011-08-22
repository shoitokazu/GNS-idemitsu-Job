<?php
$body_onload_func = "next();";
include 'header.php';
?>

<?php
$type = $_REQUEST['type'];
if ($type=="") $type=0;

$table = "article";
$fields[0] = "making_date";
$types [0] = "date";
$values[0] = date('Y/m/d');
$fields[1] = "atype";
$types [1] = "int";
$values[1] = $type;
if ($type==1) {
	$fields[] = "sales_category";
	$types [] = "str";
	$values[] = "006";
}
$id = db_insert($conn, $table, $fields, $types, $values);
setCode($table, $id, "acode");
?>
<script language="javascript">
function next() {
	location.replace('form.php?id=<?=$id?>&<?=$select_arg?>');
}
</script>

<?php include 'footer.php'; ?>
