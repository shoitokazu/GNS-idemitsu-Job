<?php require 'header.php';?>

<?php
$area = $_REQUEST['area'];
if ($area<>'') $where_area = " and sales_h.area='$area'";
$kind = $_REQUEST['kind'];
switch ($kind) {
case '受注':
default:
	$df = 'acceptance_date';
	break;
case '実販':
	$df = 'due_date';
	break;
case '完了':
	$df = 'sales_date';
	break;
}

$y1 = $_REQUEST['year1'];
$m1 = $_REQUEST['month1'];
if ($y1=='') {
	$y1 = date('Y');
}
if ($m1=='') {
	$m1 = date('m');
}
$date1 = mktime(0,0,0,$m1,1,$y1);

$y2 = $_REQUEST['year2'];
$m2 = $_REQUEST['month2'];
if ($y2=='') {
	$y = $y1;
} else {
	$y = $y2;
}
if ($m2=='') {
	$m = $m1;
} else {
	$m = $m2;
}
$date2 = mktime(0,0,0,$m+1,0,$y);
$where1 = "between '".date('Y/m/d', $date1)."' and '".date('Y/m/d', $date2)."'";
$where1 .= $where_area;
$where1 .= " and (sales_state=1 or sales_state=2 or sales_state=3)";

$table = "sales_h";
$table = "($table) left join sales_d on sales_h.ID=sales_d.hid";
$table = "($table) left join choices on sales_d.sales_category=choices.value and field='sales_category'";

$field = "sales_h.ID as sid";
$field .= ",sales_h.scode";
$field .= ",sales_h.acceptance_date";
$field .= ",sales_h.due_date";
$field .= ",sales_h.sales_date";
$field .= ",sales_h.ccode";
$field .= ",sales_h.cname";
$field .= ",sales_h.wstaff";
$field .= ",choices.name as category_name";
//$field .= ",CONCAT('[',sales_h.ccode,']',sales_h.cname) as customer";
//$field .= ",CONCAT('[',sales_d.sales_category,']',choices.name) as category";
$field .= ",price*num as a1";
$field .= ",cost*num as a2";
$field .= ",price*num-cost*num as a3";

$sql = "SELECT sales_d.*,$field FROM $table WHERE $df $where1";
$sql .= " ORDER BY ccode,sales_category, $df";

$rs = db_exec($conn, $sql);
?>
<br>
<p>販売実績（顧客別）</p>
<form action="#" method="GET">
表示実績
<select size="1" name="kind">
    <option<?php if ($kind=='受注') echo ' selected' ?> value="受注">受注(受注日)</option>
    <option<?php if ($kind=='実販') echo ' selected' ?> value="実販">実販(売上予定日)</option>
    <option<?php if ($kind=='完了') echo ' selected' ?> value="完了">完了(売上日)</option>
</select>

<select size="1" name="year1">
    <option><?php echo $y1-1 ?></option>
    <option selected><?php echo $y1 ?></option>
    <option><?php echo $y1+1 ?></option>
</select>年
<select size="1" name="month1">
<?php	for ($i=1;$i<13;$i++) { ?>
    <option<?php if ($m1==$i) echo ' selected' ?>><?php echo $i ?></option>
<?php	} ?>
</select>月～
<select size="1" name="year2">
    <option<?php if ($y2=='') echo ' selected' ?>></option>
    <option<?php if ($y2==$y-1) echo ' selected' ?>><?php echo $y-1 ?></option>
    <option<?php if ($y2==$y) echo ' selected' ?>><?php echo $y ?></option>
    <option<?php if ($y2==$y+1) echo ' selected' ?>><?php echo $y+1 ?></option>
</select>年
<select size="1" name="month2">
    <option></option>
<?php	for ($i=1;$i<13;$i++) { ?>
    <option<?php if ($m2==$i) echo ' selected' ?>><?php echo $i ?></option>
<?php	} ?>
</select>月

エリア
<select size="1" name="area">
    <option value="">すべて</option>
    <option<?php if ($area=='横浜') echo ' selected' ?>>横浜</option>
    <option<?php if ($area=='大阪') echo ' selected' ?>>大阪</option>
</select>

<span class="no_print"><input type="submit" value="表示"></span>
</form>

<font size="1">

<?php
$cols = 12;
?>
<TABLE border=1 CELLSPACING=0>
<TR bgcolor="#C0C0C0">
<TH colspan=2><?php echo $area ?></TH>
<TH colspan=7 align="left"><?php echo $y1 ?>年<?php echo $m1 ?>月<?php echo ($y1<>$y or $m1<>$m) ? '～'.$y.'年'.$m.'月' : '' ?></TH>
<TH colspan=3 align="right">単位：千円</TH>
</TR>
<TR bgcolor="#b0b0b0"><th colspan=<?=$cols?> align=left>顧客コード</th></tr>
<TR bgcolor="#d0d0d0"><th><br></th><th colspan=<?=$cols-1?> align=left>カテゴリーコード</th></tr>
<TR bgcolor="#f0f0f0">
<TH colspan=1>顧客名</TH>
<TH colspan=1>カテゴリー</TH>
<TH>売上番号</TH>
<TH nowrap>　受注日．</TH>
<TH nowrap>売上予定日</TH>
<TH nowrap>　売上日．</TH>
<TH>担当</TH>
<TH>受注商品</TH>
<TH>数</TH>
<TH>金額</TH>
<TH>原価</TH>
<TH>荒利</TH>
</TR>
<?php
init_common_report_group("ccode");
init_common_report_group("sales_category");
while (db_fetch_row($rs)) {
	$id = db_result($rs, "sid");
	echo_common_report_group($rs, "num,a1,a2,a3");
	echo '<tr>';
//	echo_report_td($rs, "ccode", "str");
	echo_report_td($rs, "cname", "str");
//	echo_report_td($rs, "sales_category", "int");
	echo_report_td($rs, "category_name", "str");
?>
<td colspan=1><a href="form1.php?id=<?=$id?>"><?=db_result($rs, "scode")?></a></td>
<?php
	echo_report_td($rs, "acceptance_date", "date");
	echo_report_td($rs, "due_date", "date");
	echo_report_td($rs, 'sales_date', "date");
	echo_report_td($rs, 'wstaff', "str");
	echo_report_td($rs, 'name', "str");
	echo_report_td($rs, 'num', "int");
	echo_report_td($rs, 'a1', "int");
	echo_report_td($rs, 'a2', "int");
	echo_report_td($rs, "a3", "int");
	echo "</tr>$rn";
}
echo_common_report_group(null, "num,a1,a2,a3");
?>
</tbody>
</table>
</font>

<?php include "footer.php"; ?>
