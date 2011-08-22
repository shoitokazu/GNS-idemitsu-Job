<?php
$body_onload_func = "next()";
include 'header.php';
?>

<?php
$table = "article";
//$id = update_form($table);
$id = read_form_request();
if ($id=='') {
	$id = db_insert($conn, $table, $fields, $types, $values);
} else {
	$where = "ID=".db_value($id, "int");
	db_update($conn, $table, $fields, $types, $values, $where, true);
}
$code = setCode($table, $id, "acode");

$target_table = $table;
$table = "list_select";
$fields = array();
$types = array();
$values = array();
$fields[0] = "table";
$types [0] = "str";
$values[0] = $target_table;
$fields[1] = "rid";
$types [1] = "int";
$values[1] = $id;
$fields[2] = "uid";
$types [2] = "int";
$values[2] = $uid;
$fields[3] = "selected";
$types [3] = "bool";
$values[3] = $_REQUEST['selected'];
$where = "`table`='$target_table' and rid=$id and uid=$uid";
$sql = "delete from $table where $where";
db_exec($conn, $sql);
db_insert($conn, $table, $fields, $types, $values);
?>

<script language="javaScript">
function next() {
<?php if ($_REQUEST['decide']!=null) { ?>
<?php 	if ($target[1]=="sales") { ?>
	window.opener.document.form1.addnew1.value="ON";
//	window.opener.document.form1.submit();
<?php 	} ?>
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
