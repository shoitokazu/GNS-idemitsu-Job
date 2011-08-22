<?php require 'header.php'; ?>

<?php
$ssid = $uid;
$kind = $_REQUEST['kind'];
if ($kind=="") $kind="売上日";
?>
<div class="no_print">
<br>
<form action="#" method="GET">
抽出
<select size="1" name="kind">
    <option value="売上日"<?php if ($kind=='売上日') echo ' selected' ?>>すべて</option>
    <option value="未請求"<?php if ($kind=='未請求') echo ' selected' ?>>未請求</option>
    <option value="未発行"<?php if ($kind=='未発行') echo ' selected' ?>>未発行</option>
</select>

売上日
<?php
$y1 = $_REQUEST['year1'];
$m1 = $_REQUEST['month1'];
$d1 = $_REQUEST['day1'];
$y2 = $_REQUEST['year2'];
$m2 = $_REQUEST['month2'];
$d2 = $_REQUEST['day2'];
if ($y1=='') $y1 = date('Y');
if ($m1=='') $m1 = date('m');
if ($d1=='') $d1 = 1;
$date1 = mktime(0,0,0,$m1,$d1,$y1);
$y = $y2;
$m = $m2;
$d = $d2;
if ($y=='') $y = $y1;
if ($m=='') $m = $m1;
if ($d==0) $m++;
$date2 = mktime(0,0,0,$m,$d,$y);
?>
<select size="1" name="year1">
    <option><?php echo $y1-1 ?></option>
    <option selected><?php echo $y1 ?></option>
    <option><?php echo $y1+1 ?></option>
</select>年
<select size="1" name="month1">
<?php	for ($i=1;$i<13;$i++) { ?>
    <option<?php if ($m1==$i) echo ' selected' ?>><?php echo $i ?></option>
<?php	} ?>
</select>月
<select size="1" name="day1">
<?php	for ($i=1;$i<31;$i++) { ?>
    <option<?php if ($d1==$i) echo ' selected' ?>><?php echo $i ?></option>
<?php	} ?>
</select>日
～
<select size="1" name="year2">
<?php	if ($y2=="") { ?>
    <option></option>
    <option><?php echo $y1-1 ?></option>
    <option><?php echo $y1 ?></option>
    <option><?php echo $y1+1 ?></option>
<?php	} else { ?>
    <option></option>
    <option><?php echo $y2-1 ?></option>
    <option selected><?php echo $y2 ?></option>
    <option><?php echo $y2+1 ?></option>
<?php	} ?>
</select>年
<select size="1" name="month2">
    <option></option>
<?php	for ($i=1;$i<13;$i++) { ?>
    <option<?php if ($m2==$i) echo ' selected' ?>><?php echo $i ?></option>
<?php	} ?>
</select>月
<select size="1" name="day2">
<?php	for ($i=1;$i<31;$i++) { ?>
    <option<?php if ($d2==$i) echo ' selected' ?>><?php echo $i ?></option>
<?php	} ?>
<?php	if ($d2==0) $str=" selected";?>
    <option value=0<?=$str?>>末</option>
</select>日

<?php
$area = $_REQUEST['area'];
?>

請求先
<select size="1" name="area">
    <option value="">すべて</option>
<?php
//$list = DListUp("value", "choices", "field='scenter'");
$list = DListUp("name", "stamp", "", "sort");
echo_option($list, $area);
?>
</select>

<input type="submit" value="表示">
</form>

</div>

<form action="../print/bill_print.php" method="POST">
<input type="hidden" name="stamp" value="<?=$area?>">

<div class="no_print">
<p>請求日：<input type="text" name="print_date" value="<?=date('Y/m/d')?>" size=12>
<input name="bill" type="submit" value="請求書印刷">
※表示している、すべての伝票を印刷します。
印刷した伝票には、請求日が記録（上書き）されます。
</p>
<p><input name="batch" type="submit" value="明細一括印刷">
※選択した伝票を印刷します。
印刷した伝票には、明細印刷日が記録（上書き）されます。
</p>
</div>

<div class="report">
<?php
$base = "mainte_h";
$table = $base;
//$table = "$base inner join mainte_d on mainte_h.ID=mainte_d.hid";

$table = "($table) left join mainte_shop on mainte_h.ID=mainte_shop.mid";
$table = "($table) left join sshop on mainte_shop.ssid=sshop.ID";

$table = "($table) left join mainte_d on mainte_shop.mid=mainte_d.hid and mainte_shop.ssid=mainte_d.ssid";

//$order = "ORDER BY mainte_h.ID";
$order = "ORDER BY mainte_h.ID desc";

$where = "mainte_shop.ssid=$ssid";
$where .= " and mainte_state<>9";
$date_field = "mainte_shop.shop3";
$where .= " and (".$date_field." between ".db_value(date('Y/m/d', $date1),"date")." and ".db_value(date('Y/m/d', $date2), "date").")";
$title = $y1."年".$m1."月".$d1."日 ～ ".date('Y', $date2)."年".date('n', $date2)."月".date('j', $date2)."日";

switch ($kind) {
case '未請求':
	$where .= " and mainte_shop.shop5 is null";
	$title .= $kind;
	break;
case '未発行':
	$where .= " and mainte_shop.shop6 is null";
	$title .= $kind;
	break;
default:
	break;
}
if ($area<>'') $where .= " and scenter='$area'";

if ($where <> '') $sqlwhere = 'WHERE '.$where;

$field = "$base.*";
$field .= ", mainte_shop.shop3";
$field .= ", mainte_shop.shop5";
$field .= ", mainte_shop.shop6";
$sums = "sum(cost2) as 仕切金額";
$sums .= ", sum((itype=4)*cost2) as 非課税金額";


$order = " order by scenter, shop3 desc, mainte_state, ID";
$sql = "select $field, $sums from $table $sqlwhere group by mainte_h.ID $order";
?>
<table border="1" cellspacing="0">
<thead>
<TR bgcolor="#C0C0C0">
<TH colspan=7 align="left"><?=$title?></TH>
<TH colspan=1 align="right">単位：円</TH>
<TH rowspan=2>選択</TH>
</TR>
<TR bgcolor="#C0C0C0">
<th>伝票番号</th>
<th>顧客名</th>
<th>船名</th>
<th>案件名</th>
<th>売上日</th>
<th>請求日</th>
<th>明細印刷日</th>
<th>請求金額</th>
</tr>
</thead>

<tbody>
<?php
//if ($debug) echo $sql.'<br>';
$rs = db_exec($conn, $sql);

$cols = 10;
init_report_group("scenter");
//init_report_group("サービスショップ名");
//init_report_group("伝票区分");
$i=0;
while (db_fetch_row($rs)) {
	$i++;

	$id = db_result($rs, "ID");
	$all = db_result($rs, "仕切金額");
	$kubun4 = db_result($rs, "非課税金額");
	$amount = round(($all-$kubun4)*1.05) + $kubun4;
	echo_report_group($rs);

	echo '<tr>';
?>
<td><a href="../mainte/form1.php?id=<?=$id?>"><?=db_result($rs, "mcode")?></a></td>
<?php
//	echo_report_td($rs, "略称", "str");

	echo_report_td($rs, "cname", "str");
	echo_report_td($rs, "aname", "str");
	echo_report_td($rs, "mname", "str");
	echo_report_td($rs, "shop3", "date");
	echo_report_td($rs, "shop5", "date");
	echo_report_td($rs, "shop6", "date");
	echo_report_td(null, "", "int", $amount, "default");
?>
<td align="center" bgcolor="#ffffff"><input type="checkbox" name="check[<?=$i?>]" value=1></td>
<input type="hidden" name="id[<?=$i?>]" value="<?=$id?>">
<?php
	echo "</tr>\r\n";
}
echo_report_group(null);

function init_report_group($field) {
	global $group, $is_first_line;
	$group[]['field'] = $field;
	$is_first_line = true;
}
function echo_report_group($rs) {
	global $group, $cols, $is_first_line, $rn, $amount;

	$color[0] = "#CCFFFF";
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
					echo '<td colspan='.($cols-$j-3).' align=left>'.$sum_title.'</td>';
					echo '<td align=right>'.html_format($group[$j]['sum'], "int").'</td>';
					echo '<td><br></td>';
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
			$group[$i]['sum'] += $amount;
		}
	}
}
?>
</tbody>
</table>
</div>
</form>

<?php require 'footer.php'; ?>
