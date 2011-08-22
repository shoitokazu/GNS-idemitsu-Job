<?php
$page_line = 45;
$footer_line = 3;
$header_line = 2;
$width = 650;

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
	global $stamp_html, $addressee_html, $dtitle, $date;
	global $width;
?>
<center>
<div style="width:720px;height:480px;position:relative;">

<table width=<?=$width?>>
<tr>
<td width=100></td>
<td align=center><span style="font-size:20px"><b><?=$title?></b></span></td>
<td width=100 align=right><?=$page?>　/　<span id="page_max_<?=$page?>">0</span></td>
</tr>
</table>

<table width=<?=$width?>>
<tr><td align=right colspan=2>
<?=$dtitle[1]?>:<?=$date[1]?>
</td></tr>

<tr>
<td>
<div style="width:310px;height:150px;overflow:hidden;">
<?=$addressee_html?>
</div>
</td>
<td valign=bottom align=right>
<div align=left style="width:280px;height:110px;overflow:hidden;">
<?=$stamp_html?>
</div>
</td>
</tr>

</table>

<?php
	if ($start=="") {
//先頭ページ以外のときに印刷するものを設定
	} else {
//先頭ページにのみ印刷するものを設定
	}
}
function echo_report_header() {
	global $work, $customer, $machine, $mainte;
	global $stamp, $transfer, $dtitle, $date, $stitle, $work_staff;
	global $staff_type, $customer_type, $sum_type;
	global $width;
	global $sc_html;
?>
<table width=<?=$width?>><tr><td>

<table cellspacing=0 width=300>
<tr><td class="sum"><?=($stitle=="" ? "ご請求総金額" : $stitle)?></td>
<td align=right class="sum"><span id="total_print_0">0</span>円</td></tr>
</table>

</td></tr></table>
<br>

<?php
}
function echo_detail_header() {
	global $col, $width, $ctitle;

?>
<div class="detail">
<table width=<?=$width?>>
<thead>
<tr>
<th width=35 nowrap>請求明細</th>
<th width=80 nowrap>整備伝票No</th>
<th width=150 nowrap>顧客名</th>
<th width=150 nowrap>案件名</th>
<th width=100 nowrap><?=$ctitle[1]?></th>
<th width=100 nowrap><?=$ctitle[2]?></th>
</tr>
</thead>
<tbody>
<?php
}

function echo_group_header() {
	return 0;
}

function echo_group_footer() {
	return 0;
}

function echo_detail() {
	global $i,$f,$kubun, $col;

	$f2 = mb_strimwidth($f[2], 0, 25, "", "SJIS");
	$f3 = mb_strimwidth($f[3], 0, 25, "", "SJIS");
?>
<tr>
<td align=center><?=$i?></td>
<td nowrap><?=$f[1]?><br></td>
<td nowrap><?=$f2?><br></td>
<td nowrap><?=$f3?><br></td>
<td align=right><?=print_format($f[4]) ?></td>
<td align=right><?=print_format($f[5]) ?></td>
</tr>
<?php
	return 1;
}
function echo_null_line($n=1) {
	global $col;

	for ($i=0; $i<$n; $i++) {
?>
<tr class="null">
<td><br></td>
<td><br></td>
<td><br></td>
<td><br></td>
<td><br></td>
</tr>
<?php
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
	global $sum_type, $t, $col, $work, $stitle;
?>
</tbody>
<tfoot>

<tr><td colspan=3 rowspan=3 width=200><br></td>
<td>合計</td>
<td align=right><?=number_format($t[1])?></td>
<td align=right><?=number_format($t[2])?></td>
</tr>

<td>消費税</td>
<td align=right><?=number_format($t[3])?></td>
<td align=center>課税対象外</td>
</tr>

<td>総請求金額</td>
<td align=center colspan=2><?=number_format($t[4])?></td>
</tr>

</tfoot>
</table>
</div>

<?php
//合計額としてヘッダーに表示する値
	$r[0] = $t[4];
	return $r;
}

function echo_page_footer($end="") {
	global $page_footer, $footer_html;

	if ($page_footer) {
?>
<br>
<div style="width:650px;height:75px">
<?=$footer_html?>
</div>
<?php
	} else {
		echo "<br>";
	}
?>
</div></center>
<?php if ($end=="") { ?>
<div style="page-break-after: always;">&nbsp;</div>
<?php
	}
}
?>
