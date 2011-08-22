<?php include 'header.php' ?>

<?php
$type = $_REQUEST['type'];
if ($type=="") {
	$type = $_SESSION['schedule_type'];
}
$_SESSION['schedule_type'] = $type;

//$uid = $_SESSION['uid'];
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

$area = $_REQUEST['area'];
if ($area == '') {
	$link = "sid=$sid";
	$areawhere = "WHERE ID=$sid";
} else {
	$link = "area=$area";
	$areawhere = "WHERE company=$area and auth<>0";
}
?>

<script language="JavaScript">
function changeArea() {
	var i = document.areaForm.area.selectedIndex;
	var area = document.areaForm.area.options[i].value;
	location.href = 'weekly.php?sid=<?php echo $sid ?>&date=<?php echo $date ?>&area=' + area;
}
</script>

<h1><?=DLookUp("name", "choices", "field='schedule_type' and value=$type")?>
（週間）
</h1>

<form name="areaForm">
<?php echo_button_frame();?>

<td><input name="mb1" type="button" value="戻る" onClick="history.back()"></td>
<td><input name="mb2" type="button" value="進む" onClick="history.forward()"></td>
<td><input name="mb3" type="button" value="本日" onClick="location.href='daily.php?<?=$link?>'"></td>
<td><input name="mb4" type="button" value="新着" onClick="location.href='new.php'"></td>
<td><input name="mb5" type="button" value="新規登録" onClick="window.open('addnew.php?date=<?=date('Y/m/d')?>');"></td>
<td> / </td>
<?php
$w = date('w', mktime(0,0,0,$m,$d,$y));
$dd = number_format(($d - $w + 7) / 7);
?>
<td><input type="button" name="prev" value="先週" onClick="location.href='weekly.php?sid=<?php echo $sid ?>&date=<?php echo date('Y/m/d', mktime(0,0,0,$m,$d-7,$y)) ?>&area=<?php echo $area ?>'"></td>
<td><input type="button" name="next" value="次週" onClick="location.href='weekly.php?sid=<?php echo $sid ?>&date=<?php echo date('Y/m/d', mktime(0,0,0,$m,$d+7,$y)) ?>&area=<?php echo $area ?>'"></td>
<td><select name="area" size="1" onchange="changeArea();">
<option value=""<?php if ($area=='') echo ' selected' ?>>自分</option>
<?php
$list = DListUp("ID,name", "company", "ID<>0", "sort");
echo_option($list, $area);
?>
</select></td>
<td><input name="monthly" type="button" onClick="location.href='monthly.php?<?=$link?>&date=<?php echo $date ?>'" value="月間"></td>
<td><?php echo $y.'年'.$m.'月 第'.$dd.'週' ?></td>

<?php echo_button_frame_end();?>
</form>

<?php
$w = date('w', mktime(0,0,0,$m,$d,$y));
$d = $d - $w;

$stime = mktime(0,0,0,$m,$d,$y);
$sdate = date('Y/m/d', $stime);
$etime = mktime(0,0,0,$m,$d+6,$y);
$edate = date('Y/m/d', $etime);
?>

<div id="schedule" class="windows">
    <table border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
		<th width="90">社員名</th>
<?php
for ($i=0; $i<7; $i++) {
	$time = mktime(0,0,0,$m,$d+$i,$y);
	switch ($i) {
	case 6:
		$class = ' class="saturday"';
		break;
	case 0:
		$class = ' class="sunday"';
		break;
	default:
		$class = '';
		break;
	}
	if (date('Y/m/d', $time) == date('Y/m/d')) $class = ' class="today"';
?>
		<th<?=$class?>>
			<a href="daily.php?<?=$link?>&date=<?=date('Y/m/d', $time) ?>">
			<?=date('m/d', $time).'('.html_format($i, "week").')' ?>
			</a>
		</th>
<?php
}
?>
            </tr>
        </thead>
        <tbody>

<?php //表示する社員分の繰り返し

$sql = "SELECT * FROM account $areawhere ORDER BY ID";
$rs1 = db_exec($conn, $sql);
while (db_fetch_row($rs1)) {
	$sid = db_result($rs1, 'ID');
?>
   <TR>
    <th>
      <a href="monthly.php?sid=<?=db_result($rs1, 'ID')?>&date=<?=$sdate?>">
        <?=db_result($rs1, 'name') ?>
      </a>
    </th>

<?php //この社員の、一週間分のスケジュールの表示
for ($i=0;$i<7;$i++) {
	$time = mktime(0,0,0,$m,$d+$i,$y);
	$date = date('Y/m/d', $time);
	switch ($i) {
	case 6:
		$class = ' class="saturday"';
		break;
	case 0:
		$class = ' class="sunday"';
		break;
	default:
		$class = '';
		break;
	}
?>
	<TD<?=$class?>>
<?php	if ($sid==$uid) { ?>
		<a class="icon" href="#" onclick="window.open('addnew.php?date=<?=$date ?>');"><img src="../common/images/pencil.gif" width="15" height="15" border=0></a>
<?php	} ?>

<?php //この社員の、この日のスケジュールの表示
	$list = get_schedule_id($sid, "", $date, $type);
	if (is_array($list)) {
	$sql = "select * from schedule where ID in (".implode(",",$list).") order by start_time";
	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
?>
		<br><a href="form.php?id=<?=db_result($rs, 'ID') ?>">
<?php
$st = db_result($rs, 'start_time');
$title = db_result($rs, 'title');
//$customerName = db_result($rs, 'cname');
//$officeName = db_result($rs, 'oname');
if ($st=='') {
	if ($title=='') {
//		echo '○'."(".$customerName.")<br>〔".$officeName."〕";
		echo '○';
	} else {
//		echo '●'.$title."(".$customerName.")<br>〔".$officeName."〕";
		echo '●'.$title;
	}
} else {
	echo '【'.html_format($st, "time").'】'.$title;
}
?>
		</a>
<?php //この社員の、この日のスケジュールの表示
	}
	}
?>
	</td>
<?php //この社員の、一週間分のスケジュールの表示
}
?>
  </tr>

<?php //表示する社員分の繰り返し
}
?>

        </tbody>
    </table>
</div>

<?php include 'footer.php' ?>
