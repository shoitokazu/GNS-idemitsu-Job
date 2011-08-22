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
//見通しの伝票も集計に入れてみた
//$where1 .= " and (sales_state=0 or sales_state=1 or sales_state=2 or sales_state=3)";

$table = "sales_h";
$table = "($table) left join sales_d on sales_h.ID=sales_d.hid";
//$table = "($table) left join company on sales_h.company=company.ID";
//$table = "($table) left join work_h on sales_h.wid=work_h.ID";
//$table = "($table) left join account on sales_h.uid=account.ID";

$field = "sales_h.ID as sid";
$field .= ",sales_h.scode";
$field .= ",sales_h.acceptance_date";
$field .= ",sales_h.due_date";
$field .= ",sales_h.sales_date";
//$field .= ",account.name as staff";
$field .= ",sales_h.cname";
$field .= ",sales_h.wstaff";
$query = "(SELECT sales_d.*,$field FROM $table WHERE $df $where1) AS 実績明細";

$table = "(SELECT DISTINCT value as category_code,name as category_name FROM choices WHERE field='sales_category') AS tmp_group";
$table = "($table) LEFT JOIN $query ON tmp_group.category_code=実績明細.sales_category";

$sql = "SELECT * FROM $table ORDER BY LPAD(tmp_group.category_code,3,'0'), 実績明細.$df";

$rs = db_exec($conn, $sql);
?>
<br>
<p>販売実績（商品グループ別）</p>
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

<TABLE border=1 CELLSPACING=0>
<TR bgcolor="#C0C0C0">
<TH colspan=2><?php echo $area ?></TH>
<TH colspan=8 align="left"><?php echo $y1 ?>年<?php echo $m1 ?>月<?php echo ($y1<>$y or $m1<>$m) ? '～'.$y.'年'.$m.'月' : '' ?></TH>
<TH colspan=3 align="right">単位：千円</TH>
</TR>
<TR bgcolor="#C0C0C0">
<TH colspan=2>カテゴリー</TH>
<TH>売上番号</TH>
<TH nowrap>　受注日．</TH>
<TH nowrap>売上予定日</TH>
<TH nowrap>　売上日．</TH>
<TH>顧客名</TH>
<TH>担当</TH>
<TH>受注商品</TH>
<TH>数</TH>
<TH>金額</TH>
<TH>原価</TH>
<TH>荒利</TH>
</TR>
<?php
function echo_gaku($v) {
	if ($v<>'') {
		echo number_format($v/1000);
	} else {
		echo '　';
	}
}
function echo_str($v) {
	if ($v<>'') {
		echo $v;
	} else {
		echo '　';
	}
}
function echo_row($a) {
	global $m;
	echo '<TD align="center">'.$a[0].'</TD>';
	for ($i=1;$i<$m;$i++) {
		echo '<TD align="right">';
		echo_gaku($a[$i]);
		echo '</TD>';
	}
}
$n = 11;
$m = 4;
for ($i=0;$i<$m;$i++) $s[$i] = 0;
for ($i=0;$i<$m;$i++) $a[$i] = 0;
$pre_no = 1;
$no = 1;
$first = True;
while (db_fetch_row($rs)) {
	$id = db_result($rs, 'sid');
	$pre_no = $no;
	$no = db_result($rs, 'category_code');
	if ($first) {
		$pre_no = $no;
	}
	$category = db_result($rs, 'category_name');
	if ($category == '原価調整') continue;
	if ($category == 'ＳＣ横浜') continue;
	if ($category == 'ＳＣベイサイド') continue;
	if ($category == 'ＳＣ関空') continue;
	if ($category == 'ＳＣ新西宮') continue;
	$g[0] = db_result($rs, 'scode');
	$g[1] = substr(db_result($rs, 'acceptance_date'),2,8);
	$g[2] = substr(db_result($rs, 'due_date'),2,8);
	$g[3] = substr(db_result($rs, 'sales_date'),2,8);
	$g[4] = db_result($rs, 'cname');
	$g[5] = db_result($rs, 'wstaff');
	$g[6] = db_result($rs, 'name');
	$g[7] = db_result($rs, 'num');
	$g[8] = db_result($rs, 'price')*$g[7];
	$g[9] = db_result($rs, 'cost')*$g[7];
	$g[10] = $g[8]-$g[9];

	if ($no<>$pre_no) {
?>
<TR bgcolor="#C0C0C0"><TD bgcolor="#FFFFFF"></TD><TD>小計</TD><TD colspan=7>　</TD>
<?php		echo_row($s); ?>
</TR>
<?php		for ($i=0;$i<$m;$i++) $s[$i] = 0;
	}
	for ($i=0;$i<$m;$i++) $s[$i] += $g[7+$i];
	for ($i=0;$i<$m;$i++) $a[$i] += $g[7+$i];
?>
<TR><TD colspan=2><?php if ($no<>$pre_no or $first) echo $no.":".$category ?></TD>
<TD><a href="../sales/form1.php?id=<?php echo $id ?>"><?php echo_str($g[0]) ?></a></TD>
<?php	for ($i=1;$i<$n-$m;$i++) { ?>
<TD><?php echo_str($g[$i]) ?></TD>
<?php	} ?>
<TD align="center"><?php echo_str($g[$n-$m]) ?></TD>
<?php	for ($i=$n-$m+1;$i<$n;$i++) { ?>
<TD align="right"><?php echo_gaku($g[$i]) ?></TD>
<?php	} ?>
</TR>
<?php
	$first = False;
}
?>
<TR bgcolor="#C0C0C0"><TD bgcolor="#FFFFFF"></TD><TH>小計</TH><TH colspan=7>　</TH>
<?php echo_row($s); ?>
</TR>
<TR bgcolor="#C0C0C0"><TH colspan=2>総合計</TH><TH colspan=7>　</TH>
<?php echo_row($a); ?>
</TR>

</TABLE>

</font>

<?php include "footer.php"; ?>
