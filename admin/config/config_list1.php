<?php require 'header.php';?>

<?php
$table = "choices";
$field = $_REQUEST['field'];
$name = $_REQUEST['name'];
?>
</form>

<form action="config_update.php" method="POST" name="form1">
<input type="hidden" name="field" value="<?=$field?>">

<?php
echo_sub_header("", "更新");
?>

<?php
echo_list_frame($name);
?>

<thead>
<tr>
<th>削除</th>
<th>ID</th>
<th>表示順</th>
<th>選択肢</th>
<!--
<th>グループ</th>
-->
</tr>
</thead>

<tbody>
<?php
$where = "field='$field'";
$sql = "select * from $table where $where order by sort,ID";
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
	echo_list_td($rs, "value", "name");
//	echo_list_td($rs, "category", "varchar");
	echo "</tr>";
}
echo '</tbody>';
echo_list_frame_end();
db_free($rs);
?>
<p><input type="submit" name="addnew" value="新規追加"></p>

</form>

<?php require 'footer.php' ?>
