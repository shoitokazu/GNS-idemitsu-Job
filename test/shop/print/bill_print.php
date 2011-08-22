<?php
$batch = $_REQUEST['batch'];
if ($batch<>"") {
	include "batch_print.php";
	exit();
}
$body_onload_func = "setTotal();";
require 'header.php';
$debug=false;
?>

<?php
$ids = $_REQUEST['id'];
if (!is_array($ids)) return_error("伝票がありません。");
$checks = $_REQUEST['check'];
$stamp = $_REQUEST['stamp'];
if ($stamp=="") return_error("請求先を限定してください。");

$print_date = $_REQUEST['print_date'];

$type=$_REQUEST['type'];

$ssid = $uid;
$stamp_html = DLookUp("html_from", "sshop", "ID=$ssid");
$footer_html = DLookUp("html_footer", "sshop", "ID=$ssid");
$addressee_html = DLookUp("html_to", "stamp", "name=".db_value($stamp, "str"));

/*
$stamp_html = "<p>stamp area</p>";
$footer_html = "<p>footer area</p>";
$addressee_html = "<p>addressee area</p>";
*/

include 'bill_format1.php';

switch ($type) {
default:
case 1:
	$title = "請求書";
	$dtitle[1] = "請求書発行日";
	$date[1] = $print_date;
	$page_footer = true;
	$ctitle[1] = "請求金額";
	$ctitle[2] = "非課税請求額";
	break;
}

$i=1;
$line=0;
$page=1;
echo_page_header("start");
echo_report_header();
echo_detail_header();
$line += $header_line;

function page_check() {
	global $line, $page_line, $page;
	if ($line >= $page_line) {
		echo_detail_footer();
		echo_page_footer();
		$page++;
		echo_page_header();
		echo_detail_header();
		$line=0;
	}
}

$base = "mainte_h";
$table = $base;
//$table = "$base inner join mainte_d on mainte_h.ID=mainte_d.hid";
$table = "($table) left join mainte_shop on mainte_h.ID=mainte_shop.mid";
$table = "($table) left join mainte_d on mainte_shop.mid=mainte_d.hid and mainte_shop.ssid=mainte_d.ssid";
$where = "mainte_shop.ssid=$ssid";
$field = "mcode";
$field .= ",cname";
$field .= ",aname";
$field .= ",mname";
$sums = "sum(mainte_d.cost2) as 仕切金額";
$sums .= ", sum((itype=4)*cost2) as 非課税金額";

foreach ($ids as $i => $id) {

	$sql = "select $sums,$field from $table where $where and mainte_h.ID=$id group by mainte_h.ID";
	$row = db_row($conn, $sql);

	if ($row<>null) {
		$sql = "update mainte_shop set shop5=".db_value($print_date, "date")." where mid=$id";
		db_exec($conn, $sql);
	}

	page_check();
//	$did = db_result($rs, '整備明細ID');
	$f[1] = $row[2];
	$f[2] = $row[3];
	$f[3] = $row[5];
	$f[4] = $row[0]-$row[1];
	$f[5] = $row[1];

	$sum[1] += $f[4];
	$sum[2] += $f[5];

	$line += echo_detail();
	$i++;
}
page_check();

$t[1] = $sum[1];
$t[2] = $sum[2];
$t[3] = round($t[1]*0.05);
$t[4] = $t[1]+$t[2]+$t[3];

if ($line + $footer_line > $page_line) {
	$line += echo_null_line($page_line - $line);
}
page_check();

$line += echo_null_line($page_line-$footer_line-$line);

$total = echo_report_footer();

echo_page_footer("end");

$page_max = $page;
?>

<form name="form1">
<?php	foreach ($total as $key => $t) { ?>
<input type="hidden" name="total_value_<?=$key?>" value="<?=number_format($t) ?>">
<?php 	} ?>
<input type="hidden" name="page_max" value="<?=$page_max ?>">
</form>

<script language="JavaScript">
function setTotal() {
<?php	foreach ($total as $key => $t) { ?>
	document.all.total_print_<?=$key?>.innerHTML = document.form1.total_value_<?=$key?>.value;
<?php 	} ?>
<?php for ($p=1; $p<=$page_max; $p++) { ?>
	document.all.page_max_<?=$p?>.innerHTML = document.form1.page_max.value;
<?php } ?>
<?php if ($_REQUEST['auto']<>"") echo "window.print();window.close();"; ?>
}
</script>

<?php require 'footer.php' ?>
