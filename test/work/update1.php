<?php
$body_onload_func = "next();";
include 'header.php';
?>

<?php
$table = "work_h";
//$id = update_form($table);

$id = read_form_request();
if ($id=='') {
	$id = db_insert($conn, $table, $fields, $types, $values);
} else {
	$where = "ID=".db_value($id, "int");
	$where .= " and work_state<>3";
	db_update($conn, $table, $fields, $types, $values, $where);
}
$code = setCode($table, $id, "wcode");

$wid = $_REQUEST['sales_addnew'];
if ($wid>0) {
	$next = "../sales/addnew.php?wid=".$wid;
}
?>
<script>
function next() {
<?php if ($_REQUEST['decide']!=null) { ?>
	setSelectValue('<?=$code?>');
	window.close();
<?php } elseif ($next<>"") { ?>
	location.replace('<?=$next?>');
<?php } else { ?>
	history.back();
<?php } ?>
}
</script>

<?php include 'footer.php'; ?>
