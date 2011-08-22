<?php
require "header.php";

if ($auth1<>3) return_error("権限がありません。");

$tag = get_page_tag();
$filename = $tag.".csv";

$debug = false;
echo_csv_header($filename);

$sql = get_paging_sql($tag);
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