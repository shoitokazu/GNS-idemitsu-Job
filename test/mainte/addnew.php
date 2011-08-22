<?php
$body_onload_func = "next();";
include 'header.php';
?>

<?php
$type = $_REQUEST['type'];
$did = $_REQUEST['did'];

$table = "mainte_h";
$fields[0] = "making_date";
$types [0] = "date";
$values[0] = date('Y/m/d');
$fields[1] = "mainte_state";
$types [1] = "int";
if ($type==1) {
	$values[1] = 9;
} else {
	$values[1] = 0;
}
$fields[2] = "uid";
$types [2] = "int";
$values[2] = $uid;
$fields[3] = "mainte7";
$types [3] = "date";
$values[3] = date('Y/m/d');
if ($did<>'') {
	$fields[] = "did";
	$types [] = "int";
	$values[] = $did;
	$sid = DLookUp("hid", "sales_d", "ID=$did");
	$wid = DLookUp("wid", "sales_h", "ID=$sid");
	$wstaff = DLookUp("wstaff", "work_h", "ID=$wid");
	if ($wstaff!=null) {
		$fields[] = "wstaff";
		$types [] = "str";
		$values[] = $wstaff;
	}
	$wname = DLookUp("wname", "work_h", "ID=$wid");
	if ($wname!="") {
		$fields[] = "mname";
		$types [] = "str";
		$values[] = $wname;
	}
}
$acode = $_REQUEST['acode'];
if ($acode<>'') {
	$fields[] = "acode";
	$types [] = "str";
	$values[] = $acode;
}
$ccode = $_REQUEST['ccode'];
if ($ccode<>"") {
	$fields[] = "ccode";
	$types [] = "str";
	$values[] = $ccode;
}
$fields[] = "mainte1";
$types [] = "str";
if ($type==1) {
	$values[] = "見積";
} else {
	$values[] = "整備";
}

$id = db_insert($conn, $table, $fields, $types, $values);
setCode($table, $id, "mcode");
?>
<script language="javascript">
function next() {
	location.replace('form1.php?id=<?=$id?>');
}
</script>

<?php include 'footer.php'; ?>
