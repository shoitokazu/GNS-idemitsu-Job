<?php
include 'header.php';

$debug = false;

$filename = $_REQUEST['filename'];
if ($filename=='') $filename = 'list.csv';

header ("Content-Disposition: attachment; filename=$filename");
header ("Content-type: application/octet-stream;name=$filename");

$sql = $_REQUEST['sql'];
$rs = db_exec($conn, $sql);

$fields = db_fields_name($rs);
$types = db_fields_type($rs);
$headers = array();
foreach ($fields as $f) {
	$headers[] = csv_format($f, "str");
}
$header_str = implode(',', $headers).$rn;
echo $header_str;
while(db_fetch_row($rs)) {
	$cols = array();
	foreach ($fields as $f) {
		$cols[] = csv_format(db_result($rs, $f), $types[$f]);
	}
	$row_str = implode(",", $cols).$rn;
	echo mb_convert_encoding($row_str, $csv_charset, $html_charset);
}

include 'footer.php';
?>