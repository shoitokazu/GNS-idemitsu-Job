<?php
include 'header.php';
include '../include/config_fields.php';

$debug = false;

$filename = "mainte_item.csv";

header ("Content-Disposition: attachment; filename=$filename");
header ("Content-type: application/octet-stream;name=$filename");

$id = $_REQUEST['id'];
$ssid = $_REQUEST['ssid'];

$sql = "SELECT * FROM mainte_d";
$sql .= " WHERE hid=$id";
if ($ssid<>-1) $sql .= " and ssid=$ssid";
$sql .= " ORDER BY group1,sort,itype,icode"; 
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

include 'footer.php';
?>