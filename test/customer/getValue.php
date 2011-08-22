<?php
require_once '../include/config.php';
require_once '../include/login_check.php';
require_once '../include/func_db.php';
require_once '../include/func_common.php';
require_once '../include/func_format.php';

$debug = false;

$table = "customer";
$code = $_REQUEST['code'];
if ($code=='') {
	$where = "ID=0";
} else {
	$where = "ccode=".db_value($code, "str");
//	$where = limitCompany($where, $table);
}
$t = $_REQUEST['type'];
if ($t=="1") $t = "";

$field = "cname$t,kana$t,zip$t,address$t,building$t,tel$t,fax$t,mobile$t";
$sql = "select $field from $table where $where";
$row = db_row($conn, $sql);

mb_http_output ( 'UTF-8' );
for ($i=0; $i<10; $i++) {
	if ($i>0) echo ',';
	$t = mb_convert_encoding($row[$i], "UTF-8", $html_charset);
	echo rawurlencode($t);
}
?>