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

/*
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
*/

$table = "article_task";
update_list($table);
if ($_REQUEST['addnew0']<>"") {
	$article2 = $values[$fno['article2']];
	$article3 = $values[$fno['article3']];
	if ($article2=="" or $article3=="") {
	} else {
	$f1[0] = "hid";
	$t1[0] = "int";
	$v1[0] = $id;
	$f1[1] = "atask_name";
	$t1[1] = "str";
	$f1[2] = "atask_date";
	$t1[2] = "date";
	$dt = strtotime($article2);
	$y = date('Y', $dt);
	$m = date('m', $dt);
	$d = date('d', $dt);
	$v1[1] = "初回点検";
	$v1[2] = date('Y/m/d', mktime(0,0,0,$m+3,1,$y));
	db_insert($conn, $table, $f1, $t1, $v1);
	$v1[1] = "点検";
	for ($i=0; $i<24; $i++) {
		$v1[2] = date('Y/m/d', mktime(0,0,0,$m,$d,$y+$i));
		db_insert($conn, $table, $f1, $t1, $v1);
	}
	$v1[1] = "船検";
	$dt = strtotime($article3);
	$y = date('Y', $dt);
	$m = date('m', $dt);
	$d = date('d', $dt);
	for ($i=0; $i<8; $i++) {
		$v1[2] = date('Y/m/d', mktime(0,0,0,$m,$d,$y+$i*3));
		db_insert($conn, $table, $f1, $t1, $v1);
	}

	}
}
if ($_REQUEST['addnew1']<>"") {
	$f2[0] = "hid";
	$t2[0] = "int";
	$v2[0] = $id;
	$f2[1] = "atask_date";
	$t2[1] = "date";
	$v2[1] = date('Y/m/d');
	db_insert($conn, $table, $f2, $t2, $v2);
}
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
