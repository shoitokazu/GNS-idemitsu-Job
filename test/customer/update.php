<?php
$body_onload_func = "next()";
include 'header.php';
?>

<?php
$table = "customer";
//$id = update_form($table);
$id = read_form_request();
if ($id=='') {
	$id = db_insert($conn, $table, $fields, $types, $values);
} else {
	$where = "ID=".db_value($id, "int");
	db_update($conn, $table, $fields, $types, $values, $where, true);
}
$code = setCode($table, $id, "ccode");

$table = "customer_group";
$fields = array();
$types = array();
$values = array();
$fields[0] = "cid";
$types[0] = "int";
$fields[1] = "gid";
$types[1] = "int";
$fields[2] = "selected";
$types[2] = "bool";
$where = limitCompany("cid=$id", $table);
$sql = "delete from $table where $where";
db_exec($conn, $sql);
for ($i=1; $i<=5; $i++) {
	$values[0] = $id;
	$values[1] = $i;
	$values[2] = $_REQUEST['g'.$i];
	db_insert($conn, $table, $fields, $types, $values);
}

$table = "list_select";
$fields = array();
$types = array();
$values = array();
$fields[0] = "table";
$types [0] = "str";
$values[0] = "customer";
$fields[1] = "rid";
$types [1] = "int";
$values[1] = $id;
$fields[2] = "uid";
$types [2] = "int";
$values[2] = $uid;
$fields[3] = "selected";
$types [3] = "bool";
$values[3] = $_REQUEST['selected'];
$where = "`table`='customer' and rid=$id and uid=$uid";
$sql = "delete from $table where $where";
db_exec($conn, $sql);
db_insert($conn, $table, $fields, $types, $values);
?>
<script language="javaScript">
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
