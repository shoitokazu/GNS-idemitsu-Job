<?php require 'header.php'; ?>

<?php
$sql = $_REQUEST['sql'];
?>
<h2>宛名印刷</h2>

<form action="../print/customer_print.php" method="POST" name="form1">

<?php
$table = "print_config";

$where = "uid=$uid";
$config_sql = "select * from $table where $where";
$rs = db_exec($conn, $config_sql);
db_fetch_row($rs);

//echo_sub_header($buttons_html, "更新", "", $id_list);
init_form_format($rs);
?>

<input type="hidden" name="sql" value="<?=$sql?>">
<input type="hidden" name="type" value="3">

<!--
<div class="frame_box">
<?php //echo_form_frame("自動設定&nbsp;&nbsp;"); ?>
<tr><td colspan=2><input type="button" value="１２面" onClick="set12()"></td></tr>
<tr><td colspan=2><input type="button" value="１８面" onClick="set18()"></td></tr>
<tr><td colspan=2><input type="reset" value="元に戻す"></td></tr>
<?php //echo_form_frame_end(); ?>
</div>
-->

<div class="frame_box">
<?php 
echo_form_frame("印刷設定");
echo_form_db($rs, "uid", "hidint", $uid);
echo '<tr><th colspan=2>ラベルの数</th></tr>';
echo_form_tr($rs, "横数", "x", "int", 2);
echo_form_tr($rs, "縦数", "y", "int", 6);
echo '<tr><th colspan=2>用紙の位置</th></tr>';
echo_form_tr($rs, "上位置", "top", "mm", "0");
echo_form_tr($rs, "下余白", "bottom", "mm", 20);
echo_form_tr($rs, "中央寄せ", "center", "bool", 1);
echo_form_tr($rs, "左位置", "left", "mm", "0");
echo '<tr><th colspan=2>ラベルの大きさ</th></tr>';
echo_form_tr($rs, "横幅", "width", "mm", "86.4");
echo_form_tr($rs, "縦幅", "height", "mm", "42.3");
echo '<tr><th colspan=2>ラベル内の余白</th></tr>';
echo_form_tr($rs, "上余白", "margin1", "mm", 5);
echo_form_tr($rs, "右余白", "margin2", "mm", 5);
echo_form_tr($rs, "下余白", "margin3", "mm", 5);
echo_form_tr($rs, "左余白", "margin4", "mm", 5);
?>
<tr><td colspan=2><input type="button" value="印刷" onClick="document.form1.type.value=3;document.form1.submit()"></td></tr>
<?php
echo_form_frame_end();
?>
</div>

<!--
<div class="frame_box">
<?php //echo_form_frame("印刷設定");?>
<tr><th>横数</th><td><input class="num" type="text" name="x" value="2" size=5></td></tr>
<tr><th>縦数</th><td><input class="num" type="text" name="y" value="6" size=5></td></tr>
<tr><th>上位置</th><td><input class="num" type="text" name="top" value="0" size=5>mm</td></tr>
<tr><th>下余白</th><td><input class="num" type="text" name="bottom" value="20" size=5>mm</td></tr>
<tr><th>中央寄せ</th><td><input type="checkbox" name="center" value="1" checked></td></tr>
<tr><th>左位置</th><td><input class="num" type="text" name="left" value="0" size=5>mm</td></tr>
<tr><th>横幅</th><td><input class="num" type="text" name="width" value="86.4" size=5>mm</td></tr>
<tr><th>縦幅</th><td><input class="num" type="text" name="height" value="42.3" size=5>mm</td></tr>
<tr><th>上余白</th><td><input class="num" type="text" name="margin1" value="5" size=5>mm</td></tr>
<tr><th>右余白</th><td><input class="num" type="text" name="margin2" value="5" size=5>mm</td></tr>
<tr><th>下余白</th><td><input class="num" type="text" name="margin3" value="5" size=5>mm</td></tr>
<tr><th>左余白</th><td><input class="num" type="text" name="margin4" value="5" size=5>mm</td></tr>
<tr><td colspan=2><input type="button" value="印刷" onClick="document.form1.type.value=3;document.form1.submit()"></td></tr>
<?php //echo_form_frame_end(); ?>
</div>
-->

<?php db_free($rs);?>
</form>

<script language="javaScript">
function set12() {
	document.form1.x.value = 2;
	document.form1.y.value = 6;
	document.form1.top.value = 21.2;
	document.form1.bottom.value = 0;
	document.form1.center.checked = true;
	document.form1.left.value = 18.6;
	document.form1.width.value = 86.4;
	document.form1.height.value = 42.3;
	document.form1.margin1.value = 5;
	document.form1.margin2.value = 5;
	document.form1.margin3.value = 5;
	document.form1.margin4.value = 5;
}
function set18() {
	document.form1.x.value = 3;
	document.form1.y.value = 6;
	document.form1.top.value = 9;
	document.form1.bottom.value = 0;
	document.form1.center.checked = true;
	document.form1.left.value = 7.2;
	document.form1.width.value = 66.05;
	document.form1.height.value = 46.5;
	document.form1.margin1.value = 5;
	document.form1.margin2.value = 5;
	document.form1.margin3.value = 5;
	document.form1.margin4.value = 5;
}
</script>

<?php require 'footer.php'; ?>
