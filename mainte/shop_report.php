<?php require 'header.php'; ?>

<?php
$area = $_REQUEST['area'];

$kind = $_REQUEST['kind'];
if ($kind=="") $kind="確認日";

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

?>
<div class="no_print">
<form action="#" method="GET">
日付
<select size="1" name="kind">
    <option value="依頼日"<?php if ($kind=='依頼日') echo ' selected' ?>>依頼日</option>
    <option value="確認日"<?php if ($kind=='確認日') echo ' selected' ?>>確認日</option>
    <option value="完了納期"<?php if ($kind=='完了納期') echo ' selected' ?>>完了予定日</option>
    <option value="完了日"<?php if ($kind=='完了日') echo ' selected' ?>>完了日</option>
    <option value="売上日"<?php if ($kind=='売上日') echo ' selected' ?>>売上日</option>
    <option value="未確認"<?php if ($kind=='未確認') echo ' selected' ?>>未確認</option>
    <option value="作業中"<?php if ($kind=='作業中') echo ' selected' ?>>作業中</option>
    <option value="未計上"<?php if ($kind=='未計上') echo ' selected' ?>>未計上</option>
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

発行元
<select size="1" name="area">
    <option value="">すべて</option>
<?php
//$list = DListUp("value", "choices", "field='scenter'", "sort,value");
$list = DListUp("name", "stamp", "", "sort");
echo_option($list, $area);
?>
</select>

<?php
$mainte1 = $_REQUEST['mainte1'];
?>
伝票区分
<select name="mainte1" id="mainte1"><option value=""></option>
<?php
$sql = "select distinct value from choices where field='mainte1' order by sort,value";
$rs = db_exec($conn, $sql);
while (db_fetch_row($rs)) {
	$v = db_result($rs, "value");
	if ($v==$mainte1) {
		echo "<option selected>";
	} else {
		echo "<option>";
	}
	echo $v;
	echo "</option>";
}
?>
</select>

<input type="submit" value="表示">
</form>

</div>

<font size="1">
<?php
$table = "mainte_h";
//$table = "$table left join sales_d on sales_d.ID=mainte_h.did";
//$table = "($table) left join sales_h on sales_h.ID=sales_d.hid";

$table = "($table) left join mainte_shop on mainte_h.ID=mainte_shop.mid";
$table = "($table) left join sshop on mainte_shop.ssid=sshop.ID";

$table = "($table) left join mainte_d on mainte_shop.mid=mainte_d.hid and mainte_shop.ssid=mainte_d.ssid";

$order = "ORDER BY mainte_h.ID desc";

$where = "not mainte_shop.ssid is null";
$where .= " and mainte_state<>9";
$date_field = "";
switch ($kind) {
case '未確認':
	$where .= " and mainte_shop.shop1 is null";
	break;
case '作業中':
	$where .= " and mainte_shop.shop2 is null";
	$where .= " and mainte_shop.shop1 is not null";
	break;
case '未計上':
	$where .= " and mainte_shop.shop3 is null";
	$where .= " and mainte_shop.shop2 is not null";
	break;
case '依頼日':
	$date_field = "mainte_h.mainte7";
	break;
case '確認日':
	$date_field = "mainte_shop.shop1";
	break;
case '完了納期':
	$date_field = "mainte_shop.shop4";
	break;
case '完了日':
	$date_field = "mainte_shop.shop2";
	break;
case '売上日':
	$date_field = "mainte_shop.shop3";
	break;
}
if ($date_field<>"") {
	$where .= " and (".$date_field." between '".date('Y/m/d', $date1)."' and '".date('Y/m/d', $date2)."')";
}
if ($area<>'') $where .= " and mainte_h.scenter='$area'";
if ($mainte1<>'') $where .= " and mainte1='$mainte1'";

if ($where <> '') $sqlwhere = 'WHERE '.$where;

$field = "mainte_h.ID, mainte_h.mcode, mainte_h.scenter";
$field .= ", mainte_shop.ssid, sshop.name";
$field .= ", cname, aname, mname, sshop.sname";
$field .= ", mainte_shop.shop1, mainte_shop.shop2, mainte_shop.shop3";
$field .= ", mainte_shop.shop4";
$order_field = $field;
$field .= ", mainte_h.mainte7 as mainte_date";
$field .= ", mainte_h.mainte10 as YBS売上日";
$order_field .= ", mainte_h.mainte7";
$order_field .= ", mainte_h.mainte10";
$sum_field = "sum(mainte_d.cost2) as 仕切金額";

$order = " order by mainte_h.scenter, mainte_shop.ssid";
$sql = "select $field, $sum_field from $table $sqlwhere group by $order_field $order";
?>
<form name="csv" method="POST" action="../csv_list.php">
	<input type="hidden" name="sql" value="<?php echo $sql ?>"> 
</form>

<table width="<?php echo $w ?>" border="1" cellspacing="0">
<thead>
<TR bgcolor="#C0C0C0">
<TH colspan=1><?php echo $area ?></TH>
<TH colspan=12 align="left"><?php echo $y1 ?>年<?php echo $m1 ?>月<?php echo ($y1<>$y or $m1<>$m) ? '～'.$y.'年'.$m.'月' : '' ?></TH>
<TH colspan=1 align="right">単位：円</TH>
</TR>
<TR bgcolor="#C0C0C0">
<th colspan=4>伝票番号</th>
<th>顧客名</th>
<th>船名</th>
<th>案件名</th>
<th>依頼日</th>
<th>確認日</th>
<th>完了予定日</th>
<th>完了日</th>
<th>売上日</th>
<th>YBS売上日</th>
<th>金額</th>
</tr>
</thead>

<tbody>
<?php
$rs = db_exec($conn, $sql);

$cols = 14;
init_report_group("scenter");
init_report_group("name");
init_report_group("mainte_state");
while (db_fetch_row($rs)) {
	$i++;

	$id = db_result($rs, "ID");
	echo_report_group($rs);

	echo '<tr>';
?>
<td colspan=4><a href="form1.php?id=<?=$id?>"><?=db_result($rs, "mcode")?></a></td>
<?php
//	echo_report_td($rs, "略称", "str");

	echo_report_td($rs, "cname", "str");
	echo_report_td($rs, "aname", "str");
	echo_report_td($rs, "mname", "str");
	echo_report_td($rs, "mainte_date", "date");
	echo_report_td($rs, "shop1", "date");
	echo_report_td($rs, "shop4", "date");
	echo_report_td($rs, "shop2", "date");
	echo_report_td($rs, "shop3", "date");
	echo_report_td($rs, "YBS売上日", "date");
	echo_report_td($rs, "仕切金額", "int");
	echo "</tr>\r\n";
}
echo_report_group(null);

function init_report_group($field) {
	global $group, $is_first_line;
	$group[]['field'] = $field;
	$is_first_line = true;
}
function echo_report_group($rs) {
	global $group, $cols, $is_first_line, $rn;

	$color[0] = "#909090";
	$color[1] = "#B0B0B0";
	$color[2] = "#F0F0F0";
	$n = count($group);
	for ($i=0; $i<$n; $i++) {
		$group[$i]['value'] = db_result($rs, $group[$i]['field']);
	}
	if ($is_first_line) {
		$is_first_line=false;
	} else {
		for ($i=0; $i<$n; $i++) {
			if ($group[$i]['value']<>$group[$i]['pre'] or $rs==null) {
				for ($j=$n-1; $j>=$i; $j--) {
					if ($j==2) continue;
					if ($j==1) $sum_title = "小計";
					if ($j==0) $sum_title = "合計";
					echo '<tr bgcolor="'.$color[$j].'">';
					if ($j>0) echo '<td colspan='.$j.'><br></td>';
					echo '<td colspan='.($cols-$j-1).' align=left>'.$sum_title.'</td>';
					echo '<td align=right>'.html_format($group[$j]['sum'], "int").'</td>';
					echo '</tr>'.$rn;
					$group[$j]['sum'] = 0;
				}
				break;
			}
		}
	}
	if ($rs==null) return;
	for ($i=0; $i<$n; $i++) {
		if ($group[$i]['value']<>$group[$i]['pre']) {
			for ($j=$i; $j<$n; $j++) {
				echo '<tr bgcolor="'.$color[$j].'">';
				if ($j>0) echo '<td colspan='.$j.'><br></td>';
				echo '<td colspan='.($cols-$j).'>'.$group[$j]['value'].'<br></td>';
				echo '</tr>'.$rn;
			}
			break;
		}
	}
	for ($i=0; $i<$n; $i++) {
		$group[$i]['pre'] = $group[$i]['value'];
		if ($group[2]['value']<>9) {
			$group[$i]['sum'] += db_result($rs, "仕切金額");
		}
	}
}
?>
</tbody>
</table>
</font>

<?php require 'footer.php'; ?>
