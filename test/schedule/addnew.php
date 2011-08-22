<?php
$body_onload_func = "next()";
include '../include/script_header.php';

$type = $_REQUEST['type'];
if ($type=="") {
	$type = $_SESSION['schedule_type'];
}
$_SESSION['schedule_type'] = $type;

$wcode = $_REQUEST['wcode'];

/*
$uid = $_SESSION['uid'];
$sid = $_REQUEST['sid'];
if ($sid<>'') {
	$user = $uid;
	$uid = $sid;
	$sid = $user;
}
*/

$kind = $_REQUEST['kind'];
if ($kind=='') {
	if ($type==0) $kind = 'スケジュール';
	if ($type==1) $kind = '来店';
}

$date = $_REQUEST['date'];
if ($date == '') $date = date('Y/m/d');
$time = date('Y/m/d H:i:s');

$table = "schedule";
$fields[0] = "uid";
$types [0] = "int";
$values[0] = $uid;
$fields[1] = "schedule_type";
$types [1] = "int";
$values[1] = $type;
$fields[2] = "schedule3_".$type;
$types [2] = "str";
$values[2] = $kind;
$fields[3] = "wcode";
$types [3] = "str";
$values[3] = $wcode;
$fields[4] = "date";
$types [4] = "date";
$values[4] = $date;
$fields[5] = "schedule5";
$types [5] = "datetime";
$values[5] = $time;
$id = db_insert($conn, $table, $fields, $types, $values);

/*
if ($sid<>'' and $sid<>$uid) {
	$sql = "INSERT INTO schedule_user (hid, uid) VALUES ($id, $sid)";
	$rs = db_exec($conn, $sql);
}
*/
?>

<script language="JavaScript">
function next() {
	window.opener.location.href='form.php?id=<?=$id ?>';
	window.close();
}
</script>

<?php
include '../include/script_footer.php';
?>
