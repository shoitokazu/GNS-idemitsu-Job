<?php
$page_title = "会社情報　印刷設定";
require 'header.php';
?>

<form action="update.php" method="POST" name="form1">

<div class="frame_box">
<table>
<tr><th>名称</th><td><input id="f1" type="text" size=40></td></tr>
<tr><th>住所</th><td><input id="f2" type="text" size=40></td></tr>
<tr><th>電話番号</th><td><input id="f3" type="text" size=40></td></tr>
<tr><td colspan=2><input type="button" onClick="make_stamp()" value="作成"></td>
</table>
</div>
<br clear=all>

<script language="javaScript">
function make_stamp() {
	var f1=document.getElementById("f1").value
	var f2=document.getElementById("f2").value
	var f3=document.getElementById("f3").value
	var txt;
	txt = "<font size='+1'>" + f1 + "</font><br><br>\n";
	txt += f2 + "<br>\n";
	txt += f3 + "<br>\n";
	document.getElementById("html_from").value = txt;
}
</script>

<?php
$where = "ID=".$uid;
$table = "sshop";
$field = "*";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>null) $sql .= " order by ".implode(",", $sort);
$rs = db_exec($conn, $sql);
init_form_format($rs);
echo_form_frame();
echo_form_tr($rs, "スタンプ", "html_from", "txt");
echo_form_tr($rs, "フッター", "html_footer", "str");
echo_form_frame_end();
db_free($rs);

echo_button_frame();
echo_form_submit();
echo_button_frame_end();
?>

<?php require 'footer.php'; ?>
