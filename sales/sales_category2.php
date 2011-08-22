<?php require 'header.php';?>

<?php
$area = $_REQUEST['area'];
if ($area<>'') $where_area = " and sales_h.area='$area'";

$y = $_REQUEST['year'];
$m = $_REQUEST['month'];
if ($y=='') {
	$y = date('Y');
}
if ($m=='') {
	$m = date('m');
}
$date = mktime(0,0,0,$m+1,0,$y);
if ($m<=6) {
	$label = "上期";
	$start_date = mktime(0,0,0,1,1,$y);
	$end_date = mktime(0,0,0,7,0,$y);
} else {
	$label = "下期";
	$start_date = mktime(0,0,0,7,1,$y);
	$end_date = mktime(0,0,0,13,0,$y);
}
$where0 = "between '".date('Y/m/d', $start_date)."' and '".date('Y/m/d', $end_date)."'";
$where1 = "between '".date('Y/m/d', $start_date)."' and '".date('Y/m/d', $date)."'";

$where0 .= $where_area;
$where1 .= $where_area;
//$where2 .= $where_area;

//$conn = odbc_connect("ybs", "", "");

$table = "sales_h";
$table = "($table) left join sales_d on sales_h.ID=sales_d.hid";
$from = " FROM $table";

$groupBy = " GROUP BY sales_category";

$title = "半期";
$query0 = "SELECT sales_category, Sum(num*price) AS $title"."額, Sum(cost*num) AS $title"."原価, Sum(num) AS $title"."数";
$query0 .= $from;
//$query0 .= " WHERE 案件.計画日 $where0";
$query0 .= " WHERE due_date $where0";
$query0 .= " and sales_state=5";
$query0 .= $groupBy;
$query0 = "($query0) AS $title";

$title = "計画";
$query1 = "SELECT sales_category, Sum(num*price) AS $title"."額, Sum(cost*num) AS $title"."原価, Sum(num) AS $title"."数";
$query1 .= $from;
//$query1 .= " WHERE 案件.$title"."日 $where1";
$query1 .= " WHERE due_date $where1";
$query1 .= " and sales_state=5";
$query1 .= $groupBy;
$query1 = "($query1) AS $title";

/*
$title = "見通";
$query2 = "SELECT 売上区分, Sum(iif([数量] is null,[単価],[単価]*[数量])) AS $title"."額, Sum(iif([数量] is null,[原価],[原価]*[数量])) AS $title"."原価, Sum(明細.数量) AS $title"."数";
$query2 .= $from;
$query2 .= " WHERE 案件.売上予定日 $where2";
$query2 .= $groupBy;
$query2 = "($query2) AS $title";
*/

$title = "売上";
$query3 = "SELECT sales_category, Sum(num*price) AS $title"."額, Sum(cost*num) AS $title"."原価, Sum(num) AS $title"."数";
$query3 .= $from;
//$query3 .= " WHERE 案件.売上予定日 $where1";
$query3 .= " WHERE due_date $where1";
$query3 .= " and (sales_state=0 or sales_state=1 or sales_state=2 or sales_state=3)";
$query3 .= $groupBy;
$query3 = "($query3) AS $title";

$title = "半期売上";
$query4 = "SELECT sales_category, Sum(num*price) AS $title"."額, Sum(cost*num) AS $title"."原価, Sum(num) AS $title"."数";
$query4 .= $from;
//$query4 .= " WHERE 案件.売上予定日 $where0";
$query4 .= " WHERE due_date $where0";
$query4 .= " and (sales_state=0 or sales_state=1 or sales_state=2 or sales_state=3)";
$query4 .= $groupBy;
$query4 = "($query4) AS $title";

$table = "(SELECT DISTINCT value as category_code,name as category_name FROM choices WHERE field='sales_category') AS tmp_group";
$table = "($table) LEFT JOIN $query0 ON tmp_group.category_code=半期.sales_category";
$table = "($table) LEFT JOIN $query1 ON tmp_group.category_code=計画.sales_category";
//$table = "($table) LEFT JOIN $query2 ON グループ.売上区分=見通.sales_category";
$table = "($table) LEFT JOIN $query3 ON tmp_group.category_code=売上.sales_category";
$table = "($table) LEFT JOIN $query4 ON tmp_group.category_code=半期売上.sales_category";

$sql = "SELECT * FROM $table ORDER BY LPAD(tmp_group.category_code,3,'0')";

$rs = db_exec($conn, $sql);

?>
<br>
<p>販売管理表（累計）</p>
<form action="sales_category2.php" method="GET">
<select size="1" name="year">
    <option><?php echo $y-1 ?></option>
    <option selected><?php echo $y ?></option>
    <option><?php echo $y+1 ?></option>
</select>年
<select size="1" name="month">
<?php	for ($i=1;$i<13;$i++) { ?>
    <option<?php if ($m==$i) echo ' selected' ?>><?php echo $i ?></option>
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

<font size="2">

<TABLE border=1 CELLSPACING=0>

<TR bgcolor="#C0C0C0">
<TH colspan=2><?php echo $area ?></TH>
<TH colspan=3 align=left><?php echo $y ?>年</TH>
<TH colspan=15 align=left><?php echo ($label=='上期') ? 1 : 7 ?>月～<?php echo $m ?>月</TH>
</TR>
<TR bgcolor="#C0C0C0">
<TH colspan=2>単位：千円</TH>
<TH colspan=3><?php echo $label ?>計画</TH>
<TH colspan=3>累計計画</TH>
<TH colspan=3>見通し</TH>
<TH colspan=3>達成率</TH>
<TH colspan=3>期末注残見通し</TH>
<TH colspan=3>進捗率</TH>
</TR>
<TR bgcolor="#C0C0C0">
<TH colspan=2>カテゴリー</TH>

<?php	for ($i=0;$i<6;$i++) { ?>
<TH>隻</TH>
<TH>金額</TH>
<TH>荒利</TH>
<?php	} ?>

</TR>
<?php
$n = 12;
for ($i=0;$i<$n;$i++) {
	$s[$i] = 0;
}
for ($i=0;$i<$n;$i++) {
	$a[$i] = 0;
}
function echo_gaku($v) {
	if ($v<>0) {
		echo number_format($v/1000);
	} else {
		echo '　';
	}
}
function echo_num($v) {
	if ($v<>0) {
		echo number_format($v);
	} else {
		echo '　';
	}
}
function echo_percent($v1, $v2) {
	if ($v2<>0 and $v2<>'') {
		echo number_format($v1/$v2*100,1)."%";
	} else {
		echo '　';
	}
}
function echo_row($a) {
	global $n;
	for ($i=0;$i<9;$i+=3) { ?>
<TD align=center><?php echo_num($a[$i]) ?></TD>
<TD align=right><?php echo_gaku($a[$i+1]) ?></TD>
<TD align=right><?php echo_gaku($a[$i+2]) ?></TD>
<?php	}
	for ($i=0;$i<3;$i++) { ?>
<TD align=right><?php echo_percent($a[6+$i], $a[3+$i]) ?></TD>
<?php	} ?>
<TD align=center><?php echo_num($a[9]-$a[6]) ?></TD>
<TD align=right><?php echo_gaku($a[9+1]-$a[6+1]) ?></TD>
<TD align=right><?php echo_gaku($a[9+2]-$a[6+2]) ?></TD>
<?php	for ($i=0;$i<3;$i++) { ?>
<TD align=right><?php echo_percent($a[6+$i], $a[9+$i]) ?></TD>
<?php	}
}
while (db_fetch_row($rs)) {
	$no = db_result($rs, 'category_code');
	$category = db_result($rs, 'category_name');
	if ($category == '原価調整') continue;
	if ($category == 'その他売上') continue;
	if ($area=='横浜' and $category == 'ＳＣ関空') continue;
	if ($area=='横浜' and $category == 'ＳＣ新西宮') continue;
	if ($area=='大阪' and $category == 'ＳＣ横浜') continue;
	if ($area=='大阪' and $category == 'ＳＣベイサイド') continue;
	$g[0] = db_result($rs, '半期数');
	$g[1] = db_result($rs, '半期額');
	$g[2] = $g[1]-db_result($rs, '半期原価');
	$g[3] = db_result($rs, '計画数');
	$g[4] = db_result($rs, '計画額');
	$g[5] = $g[4]-db_result($rs, '計画原価');
	$g[6] = db_result($rs, '売上数');
	$g[7] = db_result($rs, '売上額');
	$g[8] = $g[7]-db_result($rs, '売上原価');
	$g[9] = db_result($rs, '半期売上数');
	$g[10] = db_result($rs, '半期売上額');
	$g[11] = $g[10]-db_result($rs, '半期売上原価');
	switch ($category) {
	case '特ギ':
	case '値引き':
	case '中古艇':
	case 'ＳＣ関空':
	case 'ＳＣ新西宮':
	case 'ＳＣ横浜':
	case 'ＳＣベイサイド':
		$g[0] = null;
		$g[3] = null;
		$g[6] = null;
		$g[9] = null;
	}
	for ($i=0;$i<$n;$i++) {
		$s[$i] += $g[$i];
	}
	for ($i=0;$i<$n;$i++) {
		$a[$i] += $g[$i];
	}

?>
<TR><TD></TD><TD><?php echo $no.":".$category ?></TD>
<?php		echo_row($g); ?>
</TR>

<?php	if ($category=='値引き') { ?>
<TR bgcolor="#C0C0C0"><TD colspan=2>【ヤマハ計】</TD>
<?php		echo_row($s); ?>
</TR>
<?php	} ?>

<?php	if ($category=='中古艇') { ?>
<TR bgcolor="#C0C0C0"><TD colspan=2>【本体計】</TD>
<?php		echo_row($s); ?>
<?php		for ($i=0;$i<$n;$i++) $s[$i]=0; ?>
</TR>
<?php	} ?>

<?php
}
?>

<TR bgcolor="#C0C0C0"><TD colspan=2>【サービス計】</TD>
<?php		echo_row($s); ?>
</TR>

<TR bgcolor="#C0C0C0"><TH colspan=2 align=left>【オフィス計】</TH>
<?php		echo_row($a); ?>
</TR>

<TR bgcolor="#C0C0C0"><TH colspan=2 align=left>【荒利率】</TH>
<?php		for ($i=0;$i<3;$i++) { ?>
<TD colspan=3 align=center><?php echo_percent($a[$i*3+2], $a[$i*3+1]) ?></TD>
<?php		} ?>
<TD colspan=3>　</TD>
<TD colspan=3 align=center><?php echo_percent($a[11]-$a[8], $a[10]-$a[7]) ?></TD>
<TD colspan=3>　</TD>
</TR>

</TABLE>

</font>

<?php require 'footer.php';?>
