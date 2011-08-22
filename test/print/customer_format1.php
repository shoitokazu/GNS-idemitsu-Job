<?php
$page_line = 12;	//１ページあたりの行数
$footer_line = 0;	//レポートフッターの行数
$header_line = 0;	//レポートヘッダーの行数

$center = true;		//センタリング
$top = 21.2;		//上余白(mm)
$left = 18.6;		//左余白(mm)
$bottom = 20;		//下余白(mm)
$width = 86.4;		//１カラムの横幅(mm)
$height = 42.3;	//１カラムの縦幅(mm)
$x = 2;		//横個数
$y = 6;		//縦個数
//カラム内余白(mm)
$margin1 = 5;	//上
$margin2 = 5;	//右
$margin3 = 5;	//下
$margin4 = 5;	//左

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
	global $top, $left, $center, $bottom;
	global $width, $height, $x, $y;

	if ($center) {
		$w = $width * $x;
//		$h = $top + $height * $y - $bottom;
		$h = $height * $y - $bottom;
?>
<center>
<div style="width:<?=$w?>mm;height:<?=$h?>mm;position:relative;top:<?=$top?>mm;">
<?php
	} else {
		$w = $left + $width * $x;
//		$h = $top + $height * $y - $bottom;
		$h = $height * $y - $bottom;
?>
<div style="width:<?=$w?>mm;height:<?=$h?>mm;position:relative;top:<?=$top?>mm;left:<?=$left?>mm">
<?php
	}

	if ($start=="") {
	}
}
function echo_report_header() {
}
function echo_detail_header() {
}
function echo_group_header() {
	return 0;
}

function echo_group_footer() {
	return 0;
}

function echo_detail() {
	global $f, $line;
	global $width, $height, $x, $y;
	global $margin1, $margin2, $margin3, $margin4;

	$left = ($line % $x) * $width;
	$top = (($line % ($y * $x)) - ($line % $x)) / $x * $height;
	$margin = $margin1."mm ".$margin2."mm ".$margin3."mm ".$margin4."mm";
?>
<div style="position:absolute;top:<?=$top?>mm;left:<?=$left?>mm;width:<?=$width?>mm;height:<?=$height?>mm;padding:<?=$margin?>;text-align:left">
〒<?=$f[3] ?><br>
<?=$f[4] ?><br>
<?=$f[5] ?><br>
<table>
<tr><td><?=$f[1] ?><br><?=$f[2] ?></td><td>&nbsp;&nbsp;&nbsp;<?=$f[6]?></td></tr>
</table>
</div>
<?php
	return 1;
}
function echo_null_line($n=1) {
	return $n;
}

function echo_detail_footer() {
	return 0;
}
function echo_report_footer() {
	return 0;
}

function echo_page_footer($end="") {
	global $center;
?>
</div>
<?php
	if ($center) echo "</center>";
	if ($end=="") {
?>
<div style="page-break-after: always;">&nbsp;</div>
<?php
	}
}
?>
