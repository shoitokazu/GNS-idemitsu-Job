<?php
$body_onload_func = "setTotal();";
require 'header.php'
?>

<?php
$id = $_REQUEST['id'];
if ($id=='') exit();
$ssid = $uid;

$type=$_REQUEST['type'];

$base = "mainte_h";
$table = "$base left join mainte_d on mainte_h.ID=mainte_d.hid";
$where = "$base.ID=".$id;
$sql = "SELECT * FROM $table WHERE $where";
$rs = db_exec($conn, $sql);
if (!db_fetch_row($rs)) return_error("存在しません。");
$hrs = $rs;

$sql = "select * from mainte_shop where mid=$id and ssid=$ssid";
$rs = db_exec($conn, $sql);
if (!db_fetch_row($rs)) return_error("担当明細が存在しません。");

if ($type==1) {
	$sql = "update mainte_shop";
	$sql .= " set shop6=".db_value(date('Y/m/d'), "date");
	$sql .= " where mid=$id and ssid=$ssid";
	db_exec($conn, $sql);
}

$sc_name = db_result($rs, "scenter");
//$sc_html = DLookUp("html_to", "stamp", "name=".db_value($sc_name, "str"));
$sc_html = $sc_name;
$stamp = DLookUp("html_from", 'sshop', "ID=$ssid");
$footer_html = DLookUp('html_footer', 'sshop', "ID=$ssid");
$transfer_name = db_result($rs, "transfer");
$transfer = DLookUp('html_name', 'transfer', "name=".db_value($transfer_name, "str"));

switch ($type) {
case 1:
	$title = "整備明細書";
	$dtitle[1] = "請求日";
	$date[1] = db_result($rs, "shop3");
	$date[2] = db_result($rs, "shop1");
	$date[3] = db_result($rs, "shop2");
//	$page_footer = true;
	$page_footer = false;
	$col[1] = true;
	$col[2] = true;
	$col[3] = false;
	$ctitle[1] = "定価金額";
	$ctitle[2] = "仕切金額";
	include 'mainte_format1.php';
	break;
case 2:
	$title = "納　品　書";
	$date[1] = db_result($rs, "shop2");
	$date[2] = db_result($rs, "shop1");
	$date[3] = db_result($rs, "shop2");
	$page_footer = false;
	$col[1] = true;
	$col[2] = true;
	$col[3] = false;
	$ctitle[1] = "定価金額";
	$ctitle[2] = "仕切金額";
	include 'mainte_format1.php';
	break;
case 3:
	$title = "見　積　書";
	$dtitle[1] = "見積年月日";
	$dtitle[3] = "完了納期";
	$date[1] = db_result($rs, "shop2");
	$date[2] = db_result($rs, "shop1");
	$date[3] = db_result($rs, "shop4");
	$page_footer = false;
	$col[1] = true;
	$col[2] = true;
	$col[3] = false;
	$ctitle[1] = "定価金額";
	$ctitle[2] = "仕切金額";
	include 'mainte_format1.php';
	break;
default:
	exit();
	break;
}
$table = "mainte_d";
$where = "hid=".$id;
$where .= " and ssid=$ssid";
$sql = "SELECT * FROM $table WHERE $where";
$sql .= " ORDER BY group1,sort,itype"; 
$rs = db_exec($conn, $sql); 
$pre=null;
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
while (db_fetch_row($rs)) {
	page_check();
	$did = db_result($rs, 'ID');
	$f[1] = db_result($rs, "icode");
	$f[2] = db_result($rs, "iname");
	$f[3] = db_result($rs, "num");
	$f[4] = db_result($rs, "price");
	$f[5] = round($f[3] * $f[4]);
	$f[6] = db_result($rs, "cost2");
	$f[7] = db_result($rs, "cost1") + db_result($rs, "cost2");

	$it = db_result($rs, "itype");
	$sum[1][$it] += $f[5];
	$sum[2][$it] += $f[6];
	$sum[3][$it] += $f[7];
	$gname = db_result($rs, "group1");
	if ($pre<>$gname) {
		if ($i<>1) {
			$line += echo_group_footer();
			page_check();
		}
		$line += echo_group_header();
		page_check();
		$gsum1 = $f[5];
		$gsum2 = $f[6];
		$gsum3 = $f[7];
	} else {
		$gsum1 += $f[5];
		$gsum2 += $f[6];
		$gsum3 += $f[7];
	}
	$pre = $gname;
	$kubun = db_result($rs, "itype");
	$line += echo_detail();
	$i++;
}
page_check();
$line += echo_group_footer();

for ($i=1; $i<=3; $i++) {
	$t[$i][1] = $sum[$i][1];
	$t[$i][2] = $sum[$i][2];
	$t[$i][3] = $sum[$i][3];
	$t[$i][4] = $sum[$i][1]+$sum[$i][2]+$sum[$i][3];
	$t[$i][5] = round($t[$i][4]*0.05);
	$t[$i][6] = $t[$i][4]+$t[$i][5];
	$t[$i][7] = $sum[$i][4];
	$t[$i][8] = $t[$i][6]+$t[$i][7];
}

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
