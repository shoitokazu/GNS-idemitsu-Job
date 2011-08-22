<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="account_update.php" method="POST" name="form1">

<?php echo_sub_header("", "更新"); ?>

<div class="frame_box">

<?php
$table = "account";
$sql = "select * from $table order by LPAD(sort,10,'0'),ID";
$rs = db_exec($conn, $sql);

echo_list_frame();
?>
<tr>
<th>表示順</th>
<th>ログインID</th>
<th>パスワード</th>
<th>社員名</th>
<th>権限</th>
<th>備考</th>
<th>所属オフィス</th>
</tr>
<?php
init_list_format();
while (db_fetch_row($rs)) {
	init_list_line($rs);
	$id = db_result($rs, "ID");
	echo "<tr>";
	echo_list_td($rs, "sort", "sort");
	echo_list_td($rs, "user", "code");
//	echo_list_td($rs, "pass", "pass");
?>
<td><input type="button" value="変更" onClick="location.href='pass_form.php?id=<?=$id?>'"></td>
<?php
	echo_list_td($rs, "name", "name");
	echo_list_td($rs, "auth", "select2");
	echo_list_td($rs, "note", "varchar");
	echo_list_td($rs, "company", "table");
//	echo_list_td($rs, "area", "str");
	echo "</tr>";
}
db_free($rs);
echo_list_frame_end();
?>

<p><input type="submit" name="addnew" value="新規追加" onClick="return confirm('新規追加します。よろしいですか？')"></p>

</div>

</form>

<?php include 'footer.php'; ?>
