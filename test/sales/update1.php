<?php
$body_onload_func = "next();";
include 'header.php';
?>

<?php
$table = "sales_h";
//$hid = update_form($table);

$hid = read_form_request();
if ($hid=='') {
	$hid = db_insert($conn, $table, $fields, $types, $values);
} else {
	$where = "ID=".db_value($hid, "int");
	db_update($conn, $table, $fields, $types, $values, $where, true);
}
setCode($table, $hid, "scode");
$acode = $values[$fno['acode']];
$ccode = $values[$fno['ccode']];

$table = "sales_d";
update_list($table);

$name[1] = "本体";
$category[1] = 0;
$name[2] = "特ギ";
$category[2] = 100;
$name[3] = "値引き";
$category[3] = 101;
$name[4] = "その他売上";
$category[4] = 103;
$name[5] = "原価修正";
$category[5] = 102;
$price = 0;
$cost = 0;
$type=0;
for ($i=1; $i<=5; $i++) {
	if ($_REQUEST["addnew$i"]<>"") $type=$i;
}
if ($type==1) {
	$field = "model_name,sales_category,model_price,model_cost";
	$v = DListUp($field, "article", "acode=".db_value($acode, "str"));
	if (is_array($v)) {
		$name[1] = $v[0][0];
		$category[1] = $v[0][1];
		$price = $v[0][2];
		$cost = $v[0][3];
	}
}
if ($type<>0) {
	$table = "sales_d";
	$f1[0] = "hid";
	$t1[0] = "int";
	$v1[0] = $hid;
	$f1[1] = "name";
	$t1[1] = "str";
	$v1[1] = $name[$type];
	$f1[2] = "sales_category";
	$t1[2] = "int";
	$v1[2] = $category[$type];
	$f1[3] = "price";
	$t1[3] = "int";
	$v1[3] = $price;
	$f1[4] = "cost";
	$t1[4] = "int";
	$v1[4] = $cost;
	$id = db_insert($conn, $table, $f1, $t1, $v1);
}

$did = $_REQUEST['mainte_addnew'];
if ($did>0) {
	$next = "../mainte/addnew.php?did=".$did."&acode=".urlencode($acode)."&ccode=".urlencode($ccode);
}
$did = $_REQUEST['mainte_decide'];
if ($did>0) {
	db_exec($conn, "update mainte_h set mainte_state=3 where ID=$did");
}
$did = $_REQUEST['mainte_cancel'];
if ($did>0) {
	db_exec($conn, "update mainte_h set mainte_state=2 where ID=$did");
}
?>
<script>
function next() {
<?php if ($next<>"") { ?>
	location.replace('<?=$next?>');
<?php } else { ?>
	history.back();
<?php } ?>
}
</script>

<?php include 'footer.php'; ?>
