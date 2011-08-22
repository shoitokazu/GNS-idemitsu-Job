<?php require 'header.php';?>

<?php
$comp = $_REQUEST['company'];
$table = "choices";
$field = $_REQUEST['field'];
$name = $_REQUEST['name'];
?>
</form>

<form action="config_update.php" method="POST" name="form1">
<input type="hidden" name="field" value="<?=$field?>">
<input type="hidden" name="select" value="<?=$select?>">

<?php
echo_sub_header("", "更新");

switch ($field) {
case "office":
	$name = "支店";
	break;
}
?>
<p><?=$name?></p>

<form action="?" method="post">
<div class="frame_box">
<?php
echo_form_frame("抽出条件");
echo '<tr><th>オフィス</th><td>';
echo_company_select($comp);
?>
<input type="button" value="表示" onClick="btn_search();">
<?php
echo '</td></tr>';
echo_form_frame_end();
?>
</div>

<br clear=all>
<div class="frame_box">
<?php
echo_list_frame();
?>
<thead>
<tr>
<th>削除</th>
<th>ID</th>
<th>表示順</th>
<th>コード</th>
<th>選択肢</th>
<th>グループ</th>
<th>オフィス</th>
</tr>
</thead>

<tbody>
<?php
$where = "field='$field'";
if ($comp<>"") $where .= " and company=$comp";
$sql = "select * from $table where $where order by LPAD(sort,10,'0'),ID";
$rs = db_exec($conn, $sql);

init_list_format();
while (db_fetch_row($rs)) {
	init_list_line($rs);
	echo "<tr>";
	echo '<td>';
	echo_list_delete();
	echo '</td>';
	echo_html_td($rs, "ID", "int");
	echo_list_td($rs, "sort", "sort");
	echo_list_td($rs, "value", "code");
	echo_list_td($rs, "name", "varchar");
	echo_list_td($rs, "category", "varchar");
	echo_list_td($rs, "company", "table");
	echo "</tr>";
}
echo '</tbody>';
echo_list_frame_end();
db_free($rs);
?>
<p><input type="submit" name="addnew" value="新規追加"></p>

</div>

</form>

<form action="?" method="GET" name="form2">
<input type="hidden" name="company">
<input type="hidden" name="field" value="<?=$field?>">
<input type="hidden" name="name" value="<?=$name?>">
</form>

<script language="javaScript">
function btn_search() {
	document.form2.company.value = document.form1.company.value;
	document.form2.submit();
}
</script>


<?php require 'footer.php' ?>
