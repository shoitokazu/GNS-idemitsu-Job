<?php
$body_onload_func = "next();";
include 'header.php';
?>

<?php
$table = "sales_h";
$fields[0] = "making_date";
$types [0] = "date";
$values[0] = date('Y/m/d');
$fields[1] = "uid";
$types [1] = "int";
$values[1] = $uid;
//$fields[2] = "acceptance_date";
//$types [2] = "date";
//$values[2] = date('Y/m/d');
$fields[2] = "due_date";
$types [2] = "date";
$values[2] = date('Y/m/d');
$wid = $_REQUEST['wid'];
if ($wid<>"") {
	$fields[] = "wid";
	$types [] = "int";
	$values[] = $wid;
	$fields[] = "wstaff";
	$types [] = "str";
	$values[] = DLookUp("wstaff", "work_h", "ID=$wid");
	$fields[] = "ccode";
	$types [] = "str";
	$values[] = DLookUp("ccode", "work_h", "ID=$wid");
	$fields[] = "cname";
	$types [] = "str";
	$values[] = DLookUp("cname", "work_h", "ID=$wid");
}
$id = db_insert($conn, $table, $fields, $types, $values);
setCode($table, $id, "scode");

$mid = $_REQUEST['mid'];
if ($mid<>"") {
	$f1[0] = "hid";
	$t1[0] = "int";
	$v1[0] = $id;
	$f1[1] = "sales_category";
	$t1[1] = "int";
	$v1[1] = 103;
	$f1[2] = "name";
	$t1[2] = "str";
	$v1[2] = "整備";
	$f1[3] = "price";
	$t1[3] = "int";
	$v1[3] = 0;
	$f1[4] = "cost";
	$t1[4] = "int";
	$v1[4] = 0;
	$f1[5] = "num";
	$t1[5] = "int";
	$v1[5] = 1;
	$did = db_insert($conn, "sales_d", $f1, $t1, $v1);
	$sql = "update mainte_h set did=$did where ID=$mid";
	db_exec($conn, $sql);
}
?>
<script language="javascript">
function next() {
	location.replace('form1.php?id=<?=$id?>');
}
</script>

<?php include 'footer.php'; ?>
