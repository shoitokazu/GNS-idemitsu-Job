<?php 
$body_onload_func = "opener.document.form1.submit();window.close()";
include '../include/script_header.php';

$mid = $_REQUEST['mid'];

//複数同時選択したサービスショップを整備ショップに追加
$c = $_REQUEST['check1'];
$table = "mainte_shop";

if ($c<>'') {
	foreach ($c as $v) {
		$sql = "select ID from $table WHERE mid=$mid AND ssid=$v";
		$row = db_row($conn, $sql);
		if ($row==false) {
			$sql = "INSERT INTO mainte_shop (mid, ssid, company) VALUES ($mid, $v, $company)";
			$rs = db_exec($conn, $sql);
		}
	}
}

include '../include/script_footer.php';
?>