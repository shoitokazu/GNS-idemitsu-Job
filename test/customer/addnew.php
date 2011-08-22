<?php
$body_onload_func = "next();";
include 'header.php';
?>

<?php
$table = "customer";
$fields[0] = "making_date";
$types [0] = "date";
$values[0] = date('Y/m/d');
$id = db_insert($conn, $table, $fields, $types, $values);
setCode($table, $id, "ccode");
?>
<script language="javascript">
function next() {
	location.replace('form.php?id=<?=$id?>&<?=$select_arg?>');
}
</script>

<?php include 'footer.php'; ?>
