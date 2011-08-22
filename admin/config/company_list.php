<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="company_update.php" method="POST" name="form1">

<?php echo_sub_header("", "更新"); ?>

<div class="frame_box">

<?php
$table = "company";
$sql = "select * from $table order by LPAD(sort,10,'0'),ID,name";
$rs = db_exec($conn, $sql);

echo_list_frame();
?>
<tr>
<th>ID</th>
<th>表示順</th>
<th>コード</th>
<th>名称</th>
<th>区分</th>
</tr>
<?php
init_list_format();
while (db_fetch_row($rs)) {
	init_list_line($rs);
	echo "<tr>";
	echo_html_td($rs, "ID", "int");
	echo_list_td($rs, "sort", "sort");
	echo_list_td($rs, "code", "code");
	echo_list_td($rs, "name", "name");
	echo_list_td($rs, "group1", "str");
	echo "</tr>";
}
db_free($rs);
echo_list_frame_end();
?>

<p><input type="submit" name="addnew" value="新規追加" onClick="return confirm('新規追加します。よろしいですか？')"></p>

</div>

</form>

<?php include 'footer.php'; ?>
