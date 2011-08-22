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
$between = "between '".date('Y/m/d', $date1)."' and '".date('Y/m/d', $date2)."'";
$where1 = "$df $between $where_area";
$where1 .= " and (sales_state=1 or sales_state=2 or sales_state=3)";

$table = "sales_h";
$table = "($table) left join sales_d on sales_h.ID=sales_d.hid";
$table = "($table) left join company on sales_h.company=company.ID";
$tmp_group = "(SELECT DISTINCT value as category_code,name as category_name,category as category_group FROM choices WHERE field='sales_category') AS tmp_group";
$table = "($table) left join $tmp_group on tmp_group.category_code=sales_d.sales_category";

$title = "艇体";
$query = "SELECT sales_h.ID,min(sales_d.name) AS $title"."名称,sum(num*price) AS $title"."金額,sum(num) AS $title"."数 FROM $table WHERE $where1";
$query1 = "($query and category_group='艇体' GROUP BY sales_h.ID) AS $title";
$title = "エンジン";
$query = "SELECT sales_h.ID,min(sales_d.name) AS $title"."名称,sum(num*price) AS $title"."金額 FROM $table WHERE $where1";
$query2 = "($query and category_group='エンジン' GROUP BY sales_h.ID) AS $title";
$title = "特ギ";
$query = "SELECT sales_h.ID,sum(num*price) AS $title"."金額 FROM $table WHERE $where1";
$query3 = "($query and category_code='100' GROUP BY sales_h.ID) AS $title";
$title = "値引";
$query = "SELECT sales_h.ID,sum(num*price) AS $title"."金額 FROM $table WHERE $where1";
$query4 = "($query and category_code='101' GROUP BY sales_h.ID) AS $title";

$title = "合計";
$query = "SELECT sales_h.ID,sum(num*price) AS 金額合計,sum(num*cost) AS 原価合計 FROM $table WHERE $where1";
$query5 = "($query GROUP BY sales_h.ID) AS $title";

$table = "sales_h";
//$table = "($table) left join work_h on sales_h.wid=work_h.ID";
//$table = "($table) left join company on sales_h.company=company.ID";
//$table = "($table) left join customer on sales_h.ccode=customer.ccode";
//$table = "($table) left join account on sales_h.uid=account.ID";
$table = "($table) left join account on sales_h.wstaff=account.name";
//$table = "(SELECT sales_h.*,account.name as staff,account.sort as ssort FROM $table WHERE $where1) AS tmp";
$table = "(SELECT sales_h.*,account.sort as ssort FROM $table WHERE $where1) AS tmp";
$table = "($table) left join $query1 on 艇体.ID=tmp.ID";
$table = "($table) left join $query2 on エンジン.ID=tmp.ID";
$table = "($table) left join $query3 on 特ギ.ID=tmp.ID";
$table = "($table) left join $query4 on 値引.ID=tmp.ID";
$table = "($table) left join $query5 on 合計.ID=tmp.ID";

//$sql = "SELECT * FROM $table ORDER BY tmp.ssort, tmp.$df, tmp.ID";
$sql = "SELECT * FROM $table ORDER BY tmp.ssort, tmp.wstaff, tmp.$df, tmp.ID";

$rs = db_exec($conn, $sql);

?>
<br>
<p>販売実績（担当者別）</p>
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
<TH colspan=1><?php echo $area ?></TH>
<TH colspan=7 align="left"><?php echo $y1 ?>年<?php echo $m1 ?>月<?php echo ($y1<>$y or $m1<>$m) ? '～'.$y.'年'.$m.'月' : '' ?></TH>
<TH colspan=8 align="right">単位：千円</TH>
</TR>
<TR bgcolor="#C0C0C0">
<TH rowspan=2>No</TH>
<TH rowspan=2 nowrap>　受注日．</TH>
<TH rowspan=2 nowrap>売上予定日</TH>
<TH rowspan=2 nowrap>　売上日．</TH>
<TH rowspan=2>顧客名</TH>
<TH colspan=2>受注商品</TH>
<TH rowspan=2>数</TH>
<TH rowspan=2>艇体</TH>
<TH rowspan=2>エンジン</TH>
<TH rowspan=2>特ギ</TH>
<TH rowspan=2>値引き</TH>
<TH rowspan=2>合計</TH>
<TH rowspan=2>原価計</TH>
<TH rowspan=2>荒利</TH>
<TH rowspan=2>率</TH>
</TR>
<TR bgcolor="#C0C0C0">
<TH>(艇体)</TH>
<TH>(エンジン)</TH>
</TR>
<?php
function echo_gaku($v) {
	if ($v<>'') {
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
function echo_str($v) {
	if ($v<>'') {
		echo $v;
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
function echo_sum($a) {
	global $m;
	echo '<TD align=center>';
	echo_num($a[0]);
	echo '</TD>';
	for ($i=1;$i<$m;$i++) {
		echo '<TD align=right>';
		echo_gaku($a[$i]);
		echo '</TD>';
	}
	echo '<TD align=right>';
	echo_percent($a[7], $a[5]);
	echo '</TD>';
}
$n = 16;
$h = 5;
$m = 8;
for ($i=0;$i<$m;$i++) $s[$i] = 0;
for ($i=0;$i<$m;$i++) $a[$i] = 0;
$id=0;
$first = True;
while (db_fetch_row($rs)) {
	$pre_id = $id;
	$id = db_result($rs, 'ID');
	$pre_no = $no;
	$no = db_result($rs, 'wstaff');
	if ($first) {
		$pre_no = $no;
	}
//	$category = DLookUp("ID", "account", "name='$no'");
	$g[0] = db_result($rs, 'scode');
	$g[1] = substr(db_result($rs, 'acceptance_date'),2,8);
	$g[2] = substr(db_result($rs, 'due_date'),2,8);
	$g[3] = substr(db_result($rs, 'sales_date'),2,8);
	$g[4] = db_result($rs, 'cname');
	$g[5] = db_result($rs, '艇体名称');
	$g[6] = db_result($rs, 'エンジン名称');
	$g[7] = db_result($rs, '艇体数');
	$g[8] = db_result($rs, '艇体金額');
	$g[9] = db_result($rs, 'エンジン金額');
	$g[10] = db_result($rs, '特ギ金額');
	$g[11] = db_result($rs, '値引金額');
	$g[12] = db_result($rs, '金額合計');
	$g[13] = db_result($rs, '原価合計');
	$g[14] = $g[12]-$g[13];
	if ($g[12]<>0) {
		$g[15] = number_format($g[14]/$g[12]*100, 1).'%';
	} else {
		$g[15] = '';
	}

//小計の出力
	if ($no<>$pre_no) { ?>
<TR bgcolor="#C0C0C0"></TD><TD colspan=7>小計</TD><?php echo_sum($s) ?></TR>
<?php		for ($i=0;$i<$m;$i++) $s[$i] = 0;
	}
	if (($no<>$pre_no) or $first) { ?>
<TR><TD colspan=16><?php echo $no.":".$category ?></TD></TR>
<?php	}
?>
<TR><TD><a href="../sales/form1.php?id=<?php echo $id ?>"><?php echo $g[0] ?></a></TD>
<?php	for ($i=1;$i<$n-$m-1;$i++) { ?>
<TD><?php echo_str($g[$i]) ?></TD>
<?php	} ?>
<TD align="center"><?php echo_num($g[$n-$m-1]) ?></TD>
<?php	for ($i=$n-$m;$i<$n-1;$i++) { ?>
<TD align="right"><?php echo_gaku($g[$i]) ?></TD>
<?php	} ?>
<TD align="right"><?php echo_percent($g[14], $g[12]) ?></TD>
</TR>
<?php
	for ($i=0;$i<$m;$i++) $s[$i] += $g[$i+7];
	for ($i=0;$i<$m;$i++) $a[$i] += $g[$i+7];
	$first = False;
}
?>
<TR bgcolor="#C0C0C0"></TD><TD colspan=7>小計</TD><?php echo_sum($s) ?></TR>
<TR bgcolor="#C0C0C0"></TD><TD colspan=7>合計</TD><?php echo_sum($a) ?></TR>

</TABLE>

</font>

<?php include "footer.php"; ?>
