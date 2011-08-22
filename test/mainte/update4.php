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

$type = 0;
if (isset($_REQUEST['p1'])) $type = 1;
if (isset($_REQUEST['p2'])) $type = 2;
if (isset($_REQUEST['p3'])) $type = 3;
if (isset($_REQUEST['p4'])) $type = 4;
if (isset($_REQUEST['p5'])) $type = 5;
if (isset($_REQUEST['p6'])) $type = 6;
if (isset($_REQUEST['p7'])) $type = 7;
if ($type==0) {
?>
<script language="javaScript">
function next() {
	history.back();
}
</script>
<?php
} else {
?>
<script language="javaScript">
function next() {
	location.replace("../print/mainte_print.php?id=<?=$id?>&type=<?=$type?>&stamp=<?=urlencode($values[$fno[$fn['請求元']]])?>&transfer=<?=urlencode($values[$fno[$fn['振替先']]])?>");
}
</script>
<?php
}
include 'footer.php';
?>
