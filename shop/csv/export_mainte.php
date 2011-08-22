<?php
include "header.php";
include '../../include/config_fields.php';

$debug = false;

$filename = $_REQUEST['filename'];
if ($filename=='') $filename = 'mainte_item.csv';

header ("Content-Disposition: attachment; filename=$filename");
header ("Content-type: application/octet-stream;name=$filename");

$id = $_REQUEST['id'];
$table = "mainte_d";
$where = "hid=$id";
$where .= " and ssid=$uid";
$sort = "group1";
$sort .= ",sort";
$sort .= ",itype";
$sort .= ",icode";
$sql = "select * from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);

$fields = db_fields_name($rs);
$types = db_fields_type($rs);
$headers = array();
foreach ($fields as $f) {
	$f_jp = db2jp($f);
	if ($f_jp=="") continue;
	$headers[] = csv_format($f_jp, "str");
}
$header_str = implode(',', $headers).$rn;
echo mb_convert_encoding($header_str, $csv_charset, $html_charset);
while(db_fetch_row($rs)) {
	$cols = array();
	foreach ($fields as $f) {
		$f_jp = db2jp($f);
		if ($f_jp=="") continue;
		$cols[] = csv_format(db_result($rs, $f), $types[$f]);
	}
	$row_str = implode(",", $cols).$rn;
	echo mb_convert_encoding($row_str, $csv_charset, $html_charset);
}

include "footer.php";
?>