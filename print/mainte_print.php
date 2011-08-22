<?php
$body_onload_func = "setTotal();";
require 'header.php';
$debug = false;
?>

<?php
$id = $_REQUEST['id'];
if ($id=='') exit();

$type=$_REQUEST['type'];

//$stamp_name = DLookUp('scenter', 'mainte_h', "ID=$id");
$stamp_name = $_REQUEST['stamp'];
$stamp = DLookUp('html_from', 'stamp', "name=".db_value($stamp_name, "str"));
$footer_html = DLookUp('html_footer', 'stamp', "name=".db_value($stamp_name, "str"));
$transfer_name = DLookUp('transfer', 'mainte_h', "ID=$id");
$transfer = DLookUp('html_name', 'transfer', "name=".db_value($transfer_name, "str"));

$table = "mainte_h";
$table = "$table left join mainte_d on mainte_d.hid=mainte_h.ID";
$sql = "SELECT * FROM $table WHERE mainte_h.ID=".$id;
$rs = db_exec($conn, $sql);
if (!db_fetch_row($rs)) return_error("<p>存在しません</p>");

$hrs = $rs;

if ($type==1) {
	$sql = "update mainte_h set mainte14=".db_value(date('Y/m/d'), "date")." where ID=$id";
	$rs = db_exec($conn, $sql);
}

switch ($type) {
case 1:
	$title = "請　求　書";
	$date[1] = html_format(db_result($hrs, "mainte10"), "date");
	$date[2] = html_format(db_result($hrs, "mainte7"), "date");
	$date[3] = html_format(db_result($hrs, "mainte9"), "date");
	$page_footer = true;
	$staff_type = 3;
	$customer_type = 0;
	$col[1] = true;
	$col[2] = false;
	$col[3] = false;
	include 'mainte_format1.php';
	break;
case 2:
	$title = "整備売上明細書（経理控）";
	$date[1] = html_format(db_result($hrs, "mainte10"), "date");
	$date[2] = html_format(db_result($hrs, "mainte7"), "date");
	$date[3] = html_format(db_result($hrs, "mainte9"), "date");
	$page_footer = false;
	$staff_type = 2;
	$customer_type = 1;
	$sum_type = 3;
	$col[1] = true;
	$col[2] = true;
	$col[3] = true;
	include 'mainte_format1.php';
	break;
case 3:
	$title = "整備明細書（振替先）";
	$date[1] = html_format(db_result($hrs, "mainte10"), "date");
	$date[2] = html_format(db_result($hrs, "mainte7"), "date");
	$date[3] = html_format(db_result($hrs, "mainte9"), "date");
	$page_footer = false;
	$staff_type = 3;
	$customer_type = 1;
	$col[1] = true;
	$col[2] = true;
	$col[3] = false;
	include 'mainte_format1.php';
	break;
case 4:
	$title = "請求明細書";
	$date[1] = html_format(db_result($hrs, "mainte10"), "date");
	$date[2] = html_format(db_result($hrs, "mainte7"), "date");
	$date[3] = html_format(db_result($hrs, "mainte9"), "date");
	$page_footer = false;
	$staff_type = 3;
	$customer_type = 0;
	$col[1] = true;
	$col[2] = false;
	$col[3] = false;
	include 'mainte_format1.php';
	break;
case 5:
	$title = "納　品　書";
	$date[1] = html_format(db_result($hrs, "mainte10"), "date");
	$date[2] = html_format(db_result($hrs, "mainte7"), "date");
	$date[3] = html_format(db_result($hrs, "mainte9"), "date");
	$page_footer = false;
	$staff_type = 0;
	$customer_type = 0;
	$col[1] = true;
	$col[2] = false;
	$col[3] = false;
	include 'mainte_format1.php';
	break;
case 6:
	$title = "お見積書";
	$dtitle[1] = "見積年月日";
	$dtitle[2] = "納期";
	$dtitle[3] = "完了予定日";
	$dtitle[4] = "見積有効期限";
	$stitle = "お見積金額";
	$date[1] = html_format(db_result($hrs, "mainte11"), "date");
	$date[2] = db_result($hrs, "mainte12");
	$date[3] = db_result($hrs, "mainte13");
	$date[4] = html_format(db_result($hrs, "mainte18"), "date");
	$page_footer = false;
	$staff_type = 3;
	$customer_type = 0;
	$col[1] = true;
	$col[2] = false;
	$col[3] = false;
	$ctitle[1] = "見積金額";
	include 'mainte_format1.php';
	break;
case 7:
	$title = "納　品　書";
	$date[1] = html_format(db_result($hrs, "mainte10"), "date");
	$date[2] = html_format(db_result($hrs, "mainte7"), "date");
	$date[3] = html_format(db_result($hrs, "mainte9"), "date");
	$page_footer = false;
	$staff_type = 0;
	$customer_type = 1;
	$col[1] = true;
	$col[2] = true;
	$col[3] = false;
	include 'mainte_format1.php';
	break;
default:
	exit();
	break;
}
$table = "mainte_d";
$sql = "SELECT * FROM $table WHERE hid=".$id; 
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
//	$f[6] = round($f[3] * $f[4] * db_result($rs, "rate") / 100);
	$f[6] = db_result($rs, "amount");
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
<!--
function setTotal() {
<?php	foreach ($total as $key => $t) { ?>
	document.all.total_print_<?=$key?>.innerHTML = document.form1.total_value_<?=$key?>.value;
<?php 	} ?>
<?php for ($p=1; $p<=$page_max; $p++) { ?>
	document.all.page_max_<?=$p?>.innerHTML = document.form1.page_max.value;
<?php } ?>
}
// -->
</script>

<?php require 'footer.php' ?>
