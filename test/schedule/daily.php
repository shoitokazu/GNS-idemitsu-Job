<?php include 'header.php' ?>

<?php
$type = $_REQUEST['type'];
if ($type=="") {
	$type = $_SESSION['schedule_type'];
}
$_SESSION['schedule_type'] = $type;

$sid = $_REQUEST['sid'];
if ($sid == '' ) {
	$sid = $uid;
	$sname = $uname;
} else {
	$sname = DLookUp("name", "account", "ID=$sid");
}

$date = $_REQUEST['date'];
if ($date == '') {
	$date = date('Y/m/d');
}
$y = substr($date, 0, 4);
$m = substr($date, 5, 2);
$d = substr($date, 8, 2);

$time = mktime(0,0,0,$m,$d,$y);

$area = $_REQUEST['area'];
if ($area == '') {
	$link = "sid=$sid";
} else {
	$link = "area=$area";
}
?>
<script language="JavaScript">
function changeArea() {
	var i = document.areaForm.area.selectedIndex;
	var area = document.areaForm.area.options[i].value;
	location.href = 'daily.php?sid=<?php echo $sid ?>&date=<?php echo $date ?>&area=' + area;
}
</script>

<h1>
<?=DLookUp("name", "choices", "field='schedule_type' and value=$type")?>
（日程）
</h1>

<form name="areaForm">
<?php echo_button_frame();?>

<td><input name="mb1" type="button" value="戻る" onClick="history.back()"></td>
<td><input name="mb2" type="button" value="進む" onClick="history.forward()"></td>
<td><input name="mb3" type="button" value="本日" onClick="location.href='daily.php'"></td>
<td><input name="mb4" type="button" value="新着" onClick="location.href='new.php'"></td>
<td><input name="mb5" type="button" value="新規登録" onClick="window.open('addnew.php?date=<?=date('Y/m/d') ?>');"></td>
<td> / </td>
<td><input type=button value="前日" onClick="location.href='daily.php?<?=$link?>&date=<?=date('Y/m/d', mktime(0,0,0,$m,$d-1,$y)) ?>'"></td>
<td><input type=button value="次日" onClick="location.href='daily.php?<?=$link?>&date=<?=date('Y/m/d', mktime(0,0,0,$m,$d+1,$y)) ?>'"></td>
<td><select name="area" size="1" onchange="changeArea();">
<option value=""<?php if ($area=='') echo ' selected' ?>>自分</option>
<?php
$list = DListUp("ID,name", "company", "ID<>0");
echo_option($list, $area);
?>
</select></td>
<td><input type="button" value="週間" onClick="location.href='weekly.php?<?=$link?>&date=<?=$date ?>'"></td>
<td><input type="button" value="月間" onClick="location.href='monthly.php?<?=$link?>&date=<?=$date ?>'"></td>
<td><input type="button" value="追加" onClick="window.open('addnew.php?date=<?=$date ?>');"></td>
<td><?=$date.'('.html_format(date('w', $time), "week").')' ?></td>

<?php echo_button_frame_end();?>
</form>

<?php
echo_list_frame("デイリースケジュール");
?>
        <thead>
            <tr>
                <th>開始時間</th>
                <th>終了時間</th>
                <th>件名</th>
                <th>担当</th>
                <th>備考</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
<?php
$list = get_schedule_id($sid, $area, $date, $type);
if (is_array($list)) {
	$base = "schedule";
	$table = "$base left join account on schedule.uid=account.ID";
	$sql = "select $base.*,account.name from $table where $base.ID in (".implode(",",$list).")";
	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
		$id = db_result($rs, 'ID');
		echo '<tr>';
		echo_html_td($rs, "start_time", "time");
		echo_html_td($rs, "end_time", "time");
		echo_html_td($rs, "title", "str");
		echo_html_td($rs, "name", "str");
		echo_html_td($rs, "remarks", "str");
?>
                <td class="button"><input type="button" value="詳細" onClick="location.href='form.php?id=<?=$id ?>'"></td>
<?php
		echo '</tr>';
	}
}
echo '</tbody>';
echo_list_frame_end();
?>

<?php include 'footer.php' ?>
