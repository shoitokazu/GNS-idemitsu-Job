<?php
/*
$title = "整備明細書";		//伝票タイトル
$dtitle[1] = "発行日";		//日付タイトル
$dtitle[2] = "作業受付日";
$dtitle[3] = "完了引渡日";
$stitle = "ご請求総金額";	//合計金額タイトル
$date[1] = db_result($rs, "mainte10");	//日付値 $rsがないので、取得不可
$date[2] = db_result($rs, "mainte7");
$date[3] = db_result($rs, "mainte9");
$page_footer = false;		//ページフッターの表示
$staff_type = 0;		//スタッフ表示エリアのタイプ(0.印なし 1.検印のみ 2.印あり)
$customer_type = 0;		//顧客表示エリアのタイプ(0.顧客住所＆名称 1.振替先＆顧客名称)
$sum_type = 3;			//金額表示エリアのタイプ(0.その他 3.経理控え用)
$col[1] = true;			//金額列の表示
$col[2] = false;
$col[3] = false;
$ctitle[1] = "請求金額";	//金額列のタイトル
$ctitle[2] = "振替金額";
$ctitle[3] = "売上金額";
*/
$page_line = 45;	//１ページあたりの行数
$footer_line = 7;	//レポートフッターの行数
$header_line = 10;	//レポートヘッダーの行数
$width = 650;		//内側の幅(px)

function print_format($value, $type="int") {
	if ($value==0) return "<br>";
	switch ($type) {
	case "int":
		return number_format($value);
	case "float":
		return number_format($value, 1);
	}
}

function echo_page_header($start="") {
	global $title, $page, $page_max;
//	global $work, $customer, $machine, $mainte;
	global $hrs;
	global $stamp, $transfer, $dtitle, $date, $stamp_area;
	global $width;
?>
<center>
<div style="width:720px;height:1050px;position:relative;">

<table width=<?=$width?>>
<tr>
<td width=100></td>
<td align=center><span style="font-size:20px;"><b><?=$title?></b></span></td>
<td width=100 align=right><?=$page?>　/　<span id="page_max_<?=$page?>">0</span></td>
</tr>
</table>

<?php
	if ($start=="") {
?>
<table width=<?=$width?> style="border:1px solid black">
<tr><td width=400>
<span style="font-size:18px"><b><?=db_result($hrs, "cname")?></b>　様</span>
</td>

<td align=right>

<table>
<tr><td align=right>No.</td><td align=right width=90><?=db_result($hrs, "mcode")?></td></tr>
<tr><td align=right><?=($dtitle[1]=="" ? "発行日" : $dtitle[1])?></td>
<td align=right width=90><?=$date[1]?></td></tr>
</table>

<table>
<tr><td width=80>船名</td><td width=120 nowrap><?=db_result($hrs, "aname")?></td></tr>
<tr><td>艇種</td><td nowrap><?=db_result($hrs, "model_name")?></td></tr>
<tr><td>機種</td><td nowrap><?=db_result($hrs, "engine_name")?></td></tr>
<tr><td>機種</td><td nowrap><?=db_result($hrs, "engine_name2")?></td></tr>
<tr><td nowrap>保管場所</td><td nowrap><?=db_result($hrs, "dock")?></td></tr>
</table>

</td></tr>
</table>
<?php
	}
}
function echo_report_header() {
//	global $work, $customer, $machine, $mainte;
	global $hrs;
	global $stamp, $transfer, $dtitle, $date, $stitle;
	global $staff_type, $customer_type, $sum_type;
	global $width;
?>
<table width=<?=$width?>>
<tr><td>

<?php	switch ($customer_type) {
	default:
?>
<div style="width:310px;height:120px;overflow:hidden;">
〒<?=db_result($hrs, "zip")?><br>
<?=db_result($hrs, "address")?> <?=db_result($hrs, "building")?><br>
<br>
<span style="font-size:18px"><b><?=db_result($hrs, "cname")?></b>　様</span>
</div>
<?php
		break;
	case 1:
?>
<div style="width:310px;height:50px;overflow:hidden;">
<?=$transfer?>
</div>
<div style="width:310px;height:70px;overflow:hidden;">
<span style="font-size:18px"><b><?=db_result($hrs, "cname")?></b>　様</span>
</div>
<?php
		break;
	}
?>

</td><td align=right>

<table>
<tr><td align=right>No.</td><td align=right width=90><?=db_result($hrs, "mcode")?></td></tr>
<tr><td align=right><?=($dtitle[1]=="" ? "発行日" : $dtitle[1])?></td>
<td align=right width=90><?=$date[1]?></td></tr>
<?php if ($dtitle[4]<>"") { ?>
<tr><td align=right><?=$dtitle[4]?></td><td align=right width=90><?=$date[4]?></td></tr>
<?php } ?>
</table>

<div align=left style="width:300px;height:113px;overflow:hidden;">
<?=$stamp?>
</div>

</td></tr>
</table>

<table width=90%>
<tr><td>

<table cellspacing=0 width=300>
<?php if ($sum_type==3) { ?>
<tr><td class="sum">売上金額（税抜）</td>
<td align=right class="sum"><span id="total_print_0">0</span>円</td></tr>
<tr><td class="sum">売上原価</td>
<td align=right class="sum"><span id="total_print_1">0</span>円</td></tr>
<tr><td class="sum">非課税振替（預り金）</td>
<td align=right class="sum"><span id="total_print_2">0</span>円</td></tr>
<?php } else { ?>
<tr><td class="sum"><?=($stitle=="" ? "ご請求総金額" : $stitle)?></td>
<td align=right class="sum"><span id="total_print_0">0</span>円</td></tr>
<?php } ?>
</table>
<br>
<table>
<tr><td width=100><?=($dtitle[2]=="" ? "作業受付日" : $dtitle[2])?></td>
<td><?=$date[2]?></td></tr>
<tr><td><?=($dtitle[3]=="" ? "完了引渡日" : $dtitle[3])?></td>
<td><?=$date[3]?></td></tr>
</table>

</td>

<td align=right>

<?php	switch ($staff_type) {
	case 1:
?>
<table>
<tr><td width=100>営業担当</td>
<td><?=db_result($hrs, "wstaff")?></td></tr>
<tr><td>受付担当</td>
<td><?=db_result($hrs, "scstaff")?></td></tr>
<tr><td>整備担当</td>
<td><?=db_result($hrs, "mstaff")?></td></tr>
</table>
</td>

<td>
<div class="border">
<table>
<tr>
<td width=40 align=center>検印</td>
</tr>
<tr height=40><td></td></tr>
</table>
</div>
<?php
		break;
	case 2:
?>
<div class="border">
<table>
<tr>
<td width=40 align=center>経理</td>
<td width=40 align=center>検印</td>
<td width=40 align=center>担当</td>
<td width=40 align=center>担当</td>
</tr>
<tr height=40>
<td></td><td></td><td></td>
<td><?=db_result($hrs, "wstaff")?></td>
</tr>
</table>
</div>
<?php
		break;
	case 3:
?>
<table>
<tr><td width=100>営業担当</td>
<td><?=db_result($hrs, "wstaff")?></td></tr>
<tr><td>受付担当</td>
<td><?=db_result($hrs, "scstaff")?></td></tr>
<tr><td>整備担当</td>
<td><?=db_result($hrs, "mstaff")?></td></tr>
</table>
</td>

<td>
<div class="border">
<table>
<tr>
<td width=40 align=center>検印</td>
<td width=40 align=center>担当</td>
</tr>
<tr height=40><td></td><td></td></tr>
</table>
</div>
<?php
		break;
	default:
?>
<table>
<tr><td width=100>営業担当</td>
<td><?=db_result($hrs, "wstaff")?></td></tr>
<tr><td>受付担当</td>
<td><?=db_result($hrs, "scstaff")?></td></tr>
<tr><td>整備担当</td>
<td><?=db_result($hrs, "mstaff")?></td></tr>
</table>
<?php
		break;
	}
?>

</td>
</tr>
</table>

<table width=<?=$width?> style="border:1px solid black">
<tr><td>

<div style="width:400px;height:100px;overflow:hidden;">
<p>ご依頼内容</p>
<pre><?=db_result($hrs, "mainte4")?></pre>
</div>

</td><td>

<table>
<tr><td width=80>船名</td><td width=120 nowrap><?=db_result($hrs, "aname")?></td></tr>
<tr><td>艇種</td><td nowrap><?=db_result($hrs, "model_name")?></td></tr>
<tr><td>機種</td><td nowrap><?=db_result($hrs, "engine_name")?></td></tr>
<tr><td>機種</td><td nowrap><?=db_result($hrs, "engine_name2")?></td></tr>
<tr><td nowrap>保管場所</td><td nowrap><?=db_result($hrs, "dock")?></td></tr>
</table>

</td></tr>
</table>

<?php
}
function echo_detail_header() {
	global $col, $width, $ctitle;

	$w = $width-35-120-35-80-2;
	$w -= ($col[1] ? 80 : 0);
	$w -= ($col[2] ? 80 : 0);
	$w -= ($col[3] ? 80 : 0);
?>
<div class="detail">
<table width=<?=$width?>>
<thead>
<tr>
<th width=35 nowrap>区分</th>
<th width=120 nowrap>商品コード</th>
<th width=<?=$w?> nowrap colspan=2>品名</th>
<th width=35 nowrap>数量</th>
<th width=80 nowrap>単価</th>
<?=($col[1] ? "<th width=80 nowrap>".($ctitle[1]=="" ? "請求金額" : $ctitle[1])."</th>" : "")?>
<?=($col[2] ? "<th width=80 nowrap>".($ctitle[2]=="" ? "振替金額" : $ctitle[2])."</th>" : "")?>
<?=($col[3] ? "<th width=80 nowrap>".($ctitle[3]=="" ? "売上原価" : $ctitle[3])."</th>" : "")?>
</tr>
</thead>
<tbody>
<?php
}
function echo_group_header() {
	global $gname, $col;
?>
<tr bgcolor=silver><th colspan=6 align=left>　　<?=$gname?></th>
<?php
	if ($col[1]) echo "<td id=$gname_1><br></td>";
	if ($col[2]) echo "<td id=$gname_2><br></td>";
	if ($col[3]) echo "<td id=$gname_3><br></td>";
	echo "<tr>";
	return 1;
}

function echo_group_footer() {
	global $gsum1, $gsum2, $gsum3, $col;
?>
<tr bgcolor=silver><th colspan=6>小計</th>
<?php
	if ($col[1]) echo "<td align=right>".number_format($gsum1)."</td>";
	if ($col[2]) echo "<td align=right>".number_format($gsum2)."</td>";
	if ($col[3]) echo "<td align=right>".number_format($gsum3)."</td>";
	echo "<tr>";
	return 1;
}

function echo_detail() {
	global $i,$f,$kubun, $col;
	global $html_charset;

	$n = 18+12*3;
	$n -= ($col[1] ? 12 : 0);
	$n -= ($col[2] ? 12 : 0);
	$n -= ($col[3] ? 12 : 0);
	$f2 = mb_strimwidth($f[2], 0, $n, "", $html_charset);
	$f1 = mb_strimwidth($f[1], 0, 16, "", $html_charset);
?>
<tr>
<td align=center><?=($kubun==4 ? "*" : "")?><?=$i?></td>
<td nowrap><?=$f1 ?><br></td>
<td colspan=2 nowrap><?=$f2 ?><br></td>
<td align=center><?=print_format($f[3], $kubun==2 ? "float" : "int") ?></td>
<td align=right><?=print_format($f[4]) ?></td>
<?php
	if ($col[1]) echo "<td align=right>".print_format($f[5])."</td>";
	if ($col[2]) echo "<td align=right>".print_format($f[6])."</td>";
	if ($col[3]) echo "<td align=right>".print_format($f[7])."</td>";
	echo "</tr>";
	return 1;
}
function echo_null_line($n=1) {
	global $col;

	for ($i=0; $i<$n; $i++) {
?>
<tr class="null">
<td><br></td>
<td><br></td>
<td colspan=2><br></td>
<td><br></td>
<td><br></td>
<?php
		if ($col[1]) echo "<td><br></td>";
		if ($col[2]) echo "<td><br></td>";
		if ($col[3]) echo "<td><br></td>";
		echo "</tr>";
	}
	return $n;
}

function echo_detail_footer() {
?>
</tbody>
</table>
</div>
<?php
}
function echo_report_footer() {
	global $sum_type, $t, $col, $hrs, $stitle;
?>
</tbody>
<tfoot>

<tr><td colspan=3 width=200><br></td>
<td colspan=3>①工賃小計</td>
<?php echo_sum_col(2)?>
</tr>

<tr><td colspan=3 rowspan=6 valign=top>
<p>備考</p>
<?=html_format(db_result($hrs, "mainte5"), "str")?>
</td>
<td colspan=3>②部品代小計</td>
<?php echo_sum_col(1)?>
</tr>

<tr>
<td colspan=3>③諸経費小計</td>
<?php echo_sum_col(3)?>
</tr>

<tr>
<td colspan=3>④合計　①＋②＋③</td>
<?php echo_sum_col(4)?>
</tr>

<tr>
<td colspan=3>⑤消費税　④×５％</td>
<?php echo_sum_col(5)?>
</tr>

<tr>
<td colspan=3>⑥非課税請求額</td>
<?php echo_sum_col(7)?>
</tr>

<tr>
<td nowrap colspan=3>⑦<?=($stitle=="" ? "ご請求総金額" : $stitle)?>　④＋⑤＋⑥</td>
<?php echo_sum_col(8)?>
</tr>

</tfoot>
</table>
</div>

<?php
//合計額としてヘッダーに表示する値
	if ($col[1]) $r[0] = $t[1][8];
	if ($col[2]) $r[0] = $t[2][8];
	if ($sum_type==3) {
		$r[0]=$t[2][4];
		$r[1]=$t[3][4];
		$r[2]=$t[2][7];
	}
	return $r;
}
function echo_sum_col($n) {
	global $col, $t;
	if ($col[1]) echo "<td align=right>".number_format($t[1][$n])."</td>";
	if ($col[2]) echo "<td align=right>".number_format($t[2][$n])."</td>";
	if ($col[3]) {
		if ($n>=5) {
			echo "<td><br></td>";
		} else {
			echo "<td align=right>".number_format($t[3][$n])."</td>";
		}
	}
}

function echo_page_footer($end="") {
	global $page_footer, $footer_html;

	if ($page_footer) {
?>
<div style="width:650px;height:75px">
<?=$footer_html?>
</div>
<?php
	}
?>
</div></center>
<?php if ($end=="") { ?>
<div style="page-break-after: always;">&nbsp;</div>
<div class="no_print"><hr></div>
<?php
	}
}
?>
