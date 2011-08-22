<?php
require "header.php";

$table = $_REQUEST['table'];
switch ($table) {
case 'customer':
case 'machine':
	break;
default:
	exit();
}

$filename = $table.".csv";

$debug = false;
echo_csv_header($filename);

//$where = limitCompany("", $table);
$sql = "select * from $table";
if ($where<>"") $sql .= " where $where";
$rs = db_exec($conn, $sql);

$fields = db_fields_name($rs);
$types = db_fields_type($rs);
$header_str = '"'.implode('","', $fields).'"'.$rn;
echo mb_convert_encoding($header_str, $csv_charset, $html_charset);
while (db_fetch_row($rs)) {
	$cols = array();
	foreach ($fields as $f) {
		$cols[] = csv_format(db_result($rs, $f), $types[$f]);
	}
	$row_str = implode(",", $cols).$rn;
	echo mb_convert_encoding($row_str, $csv_charset, $html_charset);
}
require "footer.php";
?>