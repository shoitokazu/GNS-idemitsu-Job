<?php
$body_onload_func = "next()";
include 'header.php';
?>

<?php
$table = "mainte_h";
$id = read_form_request();
if ($id=='') return_error();
$where = "ID=".db_value($id, "int");
$where .= " and mainte_state<>3";
db_update($conn, $table, $fields, $types, $values, $where);

$type = $_REQUEST['type'];
if ($type==0) {
?>
<script language="javaScript">
function next() {
	history.back();
}
</script>
<?php
} else {
	$stamp = $_REQUEST['stamp'];
?>
<script language="javaScript">
function next() {
	location.replace("../print/mainte_print.php?id=<?=$id?>&type=<?=$type?>&stamp=<?=urlencode($stamp)?>");
}
</script>
<?php
}
include 'footer.php';
?>
