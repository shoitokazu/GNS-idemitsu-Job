<?php include 'header.php' ?>

<?php
$type = $_REQUEST['type'];
if ($type=="") {
	$type = $_SESSION['schedule_type'];
}
$_SESSION['schedule_type'] = $type;
?>

<h1>新着
<?=DLookUp("name", "choices", "field='schedule_type' and value=$type")?>
</h1>

<?php echo_button_frame();?>
<td><input name="mb1" type="button" value="戻る" onClick="history.back()"></td>
<td><input name="mb2" type="button" value="進む" onClick="history.forward()"></td>
<td><input name="mb3" type="button" value="本日" onClick="location.href='daily.php'"></td>
<td><input name="mb4" type="button" value="新着" onClick="location.href='new.php'"></td>
<td><input name="mb5" type="button" value="新規登録" onClick="window.open('addnew.php?date=<?=date('Y/m/d') ?>');"></td>
<?php echo_button_frame_end();?>

<?php
echo_list_frame("新着スケジュール");
?>
        <thead>
            <tr>
                <th>商談ID</th>
                <th>最終更新日時</th>
                <th>日付</th>
                <th>開始時刻</th>
                <th>件名</th>
                <th>内容</th>
                <th>担当</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
<?php
	$where = "schedule5 is not null";
	$where .= " and schedule_type=$type";
	$sql = "select * from schedule where $where order by schedule5 desc limit 25";
	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
		$id = db_result($rs, 'ID');
		echo '<tr>';
		echo_html_td($rs, "ID", "int");
		echo_html_td($rs, "schedule5", "datetime");
		echo_html_td($rs, "date", "date");
		echo_html_td($rs, "start_time", "time");
		echo_html_td($rs, "title", "str");
		echo_html_td($rs, "contents", "str");
		echo_html_td($rs, "uid", "account");
?>
    <TD class="button"><INPUT TYPE=button onClick="location.href='form.php?id=<?=$id ?>'" VALUE="詳細"></TD>
<?php
		echo '</tr>';
	}
echo '</tbody>';
echo_list_frame_end();
?>

<?php include 'footer.php' ?>
