<?php
$body_onload_func = "next();";
include 'header.php';
?>

<?php
$table = "mainte_h";
$id = read_form_request();
if ($id=='') return_error();
$where = "ID=".db_value($id, "int");
$where .= " and mainte_state<>3";
db_update($conn, $table, $fields, $types, $values, $where);
setCode($table, $id, "mcode");

$copy = $_REQUEST['copy'];
if ($copy!=null) {
	$nid = db_copy($conn, $table, $id);
	$f1[0] = "mainte_state";
	$t1[0] = "int";
	$v1[0] = 9;
	$f1[1] = "mcode";
	$t1[1] = "str";
	$v1[1] = "";
	$f1[2] = "making_date";
	$t1[2] = "date";
	$v1[2] = date('Y/m/d');
	$f1[3] = "company";
	$t1[3] = "int";
	$v1[3] = $company;
	db_update($conn, $table, $f1, $t1, $v1, "ID=$nid", true);
	setCode($table, $nid, "mcode");
//	db_copy_data($conn, "mainte_d", "hid=$id", "ID,company,hid,ssid", "0,$company,$nid,0");
	db_copy_data($conn, "mainte_d", "hid=$id", "ID,hid", "0,$nid");
	db_copy_data($conn, "mainte_shop", "mid=$id", "ID,mid", "0,$nid");
}
$mid = $_REQUEST['sales_addnew'];
if ($mid>0) {
	$next = "../sales/addnew.php?mid=".$mid;
}
?>
<script language="javaScript">
function next() {
<?php if ($nid<>0) { ?>
	location.replace("form1.php?id=<?=$nid?>");
<?php } elseif ($next<>"") { ?>
	location.replace('<?=$next?>');
<?php } else { ?>
	history.go(-1);
<?php } ?>
}
</script>

<?php include 'footer.php'; ?>
