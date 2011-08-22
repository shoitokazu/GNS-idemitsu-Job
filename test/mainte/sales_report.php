<?php require 'header.php'; ?>

<?php
set_page_tag("accounting");

$base = "mainte_h";

add_where('trans_sc', 'str');
add_where('transfer', 'str');
$date1 = add_where('mainte10', 'date');
$date2 = $_REQUEST['mainte10_2'];

echo '<p>売上集計表 [出力年月日：'.date('Y/m/d').']</p>';
echo '<font size="3">';

if ($where=="") return_error("条件は必ず指定してください。");

$where .= " and mainte_state<>9";

$table = $base;
$table = "$table left join mainte_d on mainte_h.ID=mainte_d.hid";
$order = "ORDER BY $base.ID desc";

if ($where <> '') $sqlwhere = 'WHERE '.$where;

$field = "$base.ID, mcode, transfer, mainte10, trans_sc";
$field .= ", cname, ccode, aname, model_name, engine_name, mainte4";
$sum_field = "sum((itype<>4)*round(num*price*rate/100)) as 売上金額";
$sum_field .= ", sum(cost1+cost2) as 売上原価";
$sum_field .= ", sum((itype=4)*round(num*price*rate/100)) as 非課税金額";

$order = " order by trans_sc, transfer";
$sql = "select $field, $sum_field from $table $sqlwhere group by $field $order";

$cols = 15;

echo "<br>";
echo "<p>売上期間：$date1 ～ $date2</p>";
//echo "<p>拠点名：$sc</p>";
?>

<table border="1" cellspacing="0">
<thead>
<?php
function echo_group_header($title) {
	global $cols;
?>
<tr><td colspan=<?=$cols?>>売上先：<?=$title?></td></tr>
<TR bgcolor="#C0C0C0">
<th>売上明細</th>
<th>売上確定日</th>
<th>整備伝票No</th>
<th>顧客名</th>
<th>コード</th>
<th>船名</th>
<th>艇種</th>
<th>機種</th>
<th>依頼内容</th>
<th>売上金額（税抜）</th>
<th>売上原価</th>
<th>非課税請求金額</th>
<th>消費税５％</th>
<th>売上金額（税込）</th>
<th>総請求金額</th>
</tr>
<?php
}
?>
</thead>

<tbody>
<?php
//if ($debug) echo $sql.'<br>';
$rs = db_exec($conn, $sql);

init_report_group("trans_sc");
init_report_group("transfer");
init_report_group("cname");
while (db_fetch_row($rs)) {
	$no++;

	$id = db_result($rs, "ID");
	$v[0] = db_result($rs, "売上金額");
	$v[1] = db_result($rs, "売上原価");
	$v[2] = db_result($rs, "非課税金額");
	$v[3] = round($v[0]*0.05);
	$v[4] = $v[0] + $v[3];
	$v[5] = $v[4] + $v[2];
	echo_report_group($rs);

	echo '<tr>';
	echo "<td align=center>$no</td>";
	echo_report_td($rs, "mainte10", "date");
?>
<td><a href="../mainte/form1.php?id=<?=$id?>"><?=db_result($rs, "mcode")?></a></td>
<?php
	echo_report_td($rs, "cname", "str");
	echo_report_td($rs, "ccode", "str");
	echo_report_td($rs, "aname", "str");
	echo_report_td($rs, "model_name", "str");
	echo_report_td($rs, "engine_name", "str");
	echo_report_td($rs, "mainte4", "str");
	for ($j=0; $j<6; $j++) {
		echo_report_td(null, "", "int", $v[$j], "default");
	}
	echo "</tr>\r\n";
}
echo_report_group(null);

function init_report_group($field) {
	global $group, $is_first_line;
	$group[]['field'] = $field;
	$is_first_line = true;
}
function echo_report_group($rs) {
	global $group, $cols, $is_first_line, $rn, $v, $no;

	$color[0] = "#909090";
	$color[1] = "#B0B0B0";
	$color[2] = "#F0F0F0";
	$n = count($group);
	for ($i=0; $i<$n; $i++) {
		$group[$i]['value'] = db_result($rs, $group[$i]['field']);
	}
	if ($is_first_line) {
		$is_first_line=false;
		if ($group[1]['value']=="") $group[1]['pre'] = "null";
	} else {
		for ($i=0; $i<$n; $i++) {
			if ($group[$i]['value']<>$group[$i]['pre'] or $rs==null) {
				for ($j=$n-1; $j>=$i; $j--) {
					if ($j==2) {
						if ($group[1]['pre']<>"") continue;
						$sum_title = "小計";
						$no=1;
					}
					if ($j==1) {
						$sum_title = "合計";
						$no=1;
					}
					if ($j==0) continue;
					echo '<tr bgcolor="'.$color[$j].'">';
					echo '<td colspan='.($cols-6).' align=left>'.$sum_title.'</td>';
					for ($k=0; $k<6; $k++) {
						echo '<td align=right>'.html_format($group[$j]['sum'][$k], "int").'</td>';
						$group[$j]['sum'][$k] = 0;
					}
					echo '</tr>'.$rn;
					if ($rs==null) continue;
					echo "<tr><td colspan=$cols><br></td></tr>";
				}
				break;
			}
		}
	}
	if ($rs==null) return;
	for ($i=0; $i<$n; $i++) {
		if ($group[$i]['value']<>$group[$i]['pre']) {
			for ($j=$i; $j<$n; $j++) {
				if ($j==1) {
					$title = $group[$j]['value'];
					echo_group_header($title);
					continue;
				}
				if ($j==2 and $group[1]['value']<>"") continue;
				if ($j==0) $field_title = "拠点名";
				if ($j==2) $field_title = "顧客名";
				echo '<tr bgcolor="'.$color[$j].'">';
				echo '<td colspan='.($cols).'>'.$field_title.'：'.$group[$j]['value'].'<br></td>';
				echo '</tr>'.$rn;
			}
			break;
		}
	}
	for ($i=0; $i<$n; $i++) {
		$group[$i]['pre'] = $group[$i]['value'];
		for ($j=0; $j<6; $j++) {
			$group[$i]['sum'][$j] += $v[$j];
		}
	}
}
?>
</tbody>
</table>
</font>

<?php require 'footer.php'; ?>
