<?php
require "header.php";

$sql = strip_mqg($_REQUEST['sql']);
$filename = "list.csv";

$debug = false;
echo_csv_header($filename);

$list = DListUp("value,name", "choices", "field='mainte_category'");
$names = array();
foreach ($list as $v) {
	$names[$v[0]] = $v[1];
}

$rs = db_exec($conn, $sql);
$fields = db_fields_name($rs);
$types = db_fields_type($rs);

$header_str = "区分名称,依頼先,伝票区分,売上日,顧客名,商品名,船名,案件名,合計金額,売上合計,合計原価１,合計原価２,請求元,振替先,サービスセンター";
$header_str .= $rn;
//$header_str = '"'.implode('","', $fields).'"'.$rn;
echo mb_convert_encoding($header_str, $csv_charset, $html_charset);

function get_db($f) {
	global $rs, $types;
	return csv_format(db_result($rs, $f), $types[$f]);
}

while (db_fetch_row($rs)) {
	$cols = array();
	$mc = db_result($rs, "mainte_category");
	$cols[] = csv_format($names[$mc], "str");
	$cols[] = get_db("mainte3");
	$cols[] = get_db("mainte1");
	$cols[] = get_db("mainte10");
	$cols[] = get_db("cname");
	$cols[] = get_db("model_name");
	$cols[] = get_db("aname");
	$cols[] = get_db("mname");
//合計金額,売上合計,合計原価１,合計原価２
	$hid = db_result($rs, "ID");
	$sum_field = "sum(amount)";
	$sum_field .= ",sum((itype<>4)*amount)";
	$sum_field .= ",sum(cost1)";
	$sum_field .= ",sum(cost2)";
	$sum_sql = "select $sum_field from mainte_d where hid=".$hid;
	$row = db_row($conn, $sum_sql);
	$cols[] = csv_format($row[0], "int");
	$cols[] = csv_format($row[1], "int");
	$cols[] = csv_format($row[2], "int");
	$cols[] = csv_format($row[3], "int");
	$cols[] = get_db("scenter");
	$cols[] = get_db("transfer");
	$cols[] = get_db("trans_sc");
//	foreach ($fields as $f) {
//		$cols[] = csv_format(db_result($rs, $f), $types[$f]);
//	}
	$row_str = implode(",", $cols).$rn;
	echo mb_convert_encoding($row_str, $csv_charset, $html_charset);
}
require "footer.php";
?>