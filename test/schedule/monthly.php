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
	location.href = 'monthly.php?sid=<?php echo $sid ?>&date=<?php echo $date ?>&area=' + area;
}
</script>

<h1>
<?=DLookUp("name", "choices", "field='schedule_type' and value=$type")?>
（月間）
</h1>

<form name="areaForm">
<?php echo_button_frame();?>

<td><input name="mb1" type="button" value="戻る" onClick="history.back()"></td>
<td><input name="mb2" type="button" value="進む" onClick="history.forward()"></td>
<td><input name="mb3" type="button" value="本日" onClick="location.href='daily.php?<?=$link?>'"></td>
<td><input name="mb4" type="button" value="新着" onClick="location.href='new.php'"></td>
<td><input name="mb5" type="button" value="新規登録" onClick="window.open('addnew.php?date=<?php echo date('Y/m/d') ?>');"></td>
<td> / </td>
<td><input type=button value="先月" onClick="location.href='monthly.php?<?=$link?>&date=<?php echo date('Y/m/d', mktime(0,0,0,$m-1,$d,$y)) ?>'"></td>
<td><input type=button value="来月" onClick="location.href='monthly.php?<?=$link?>&date=<?php echo date('Y/m/d', mktime(0,0,0,$m+1,$d,$y)) ?>'"></td>
<td><select name="area" size="1" onchange="changeArea();">
<option value=""<?php if ($area=='') echo ' selected' ?>>自分</option>
<?php
$list = DListUp("ID,name", "company", "ID<>0");
echo_option($list, $area);
?>
</select></td>
</td>
<td><?php echo $y.'年'.$m.'月' ?></td>

<?php echo_button_frame_end();?>
</form>

<div id="schedule" class="windows">
    <table border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th class="sunday">日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th class="saturday">土</th>
            </tr>
        </thead>
        <tbody>
<?php
$time = mktime(0,0,0,$m,1,$y); 
$w = date('w', $time); 
/*
$stime = mktime(0,0,0,$m,1-$w,$y);
$sdate = date('Y/m/d', $stime);
$etime = mktime(0,0,0,$m,5*7+6-$w+1,$y);
$edate = date('Y/m/d', $etime);
*/

for ($i=0;$i<6;$i++) {
	$time = mktime(0,0,0,$m,$i*7-$w+1,$y);
	$absm = (date('m', $time) - $m) % 12;
	if ($absm == -11 or $absm == 1) break; 
?>
            <tr>
                <th><a href="weekly.php?<?=$link?>&date=<?=date('Y/m/d', mktime(0,0,0,$m,1+$i*7,$y)) ?>">
		第<?=($i+1) ?>週</a>
		</th>
<?php
	for ($j=0;$j<7;$j++) {
		$time = mktime(0,0,0,$m,$i*7+$j-$w+1,$y);
		$date = date('Y/m/d', $time);
		switch (date('w', $time)) {
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
		$today = date('Y/m/d', $time) == date('Y/m/d') ?  '<span class="add"><font color=RED>Today</font></span><br>': '';
?>
                <td<?=$class?>><a href="daily.php?<?=$link?>&date=<?=date('Y/m/d', $time) ?>">
			<?=(date('m', $time) <> $m) ? date('n', $time).'/' : '' ?>
			<?=date('j', $time) ?>
		</a>
<?php if (($area=="" and $sid==$uid) or $area == $company) { ?>
		<a class="icon" href="#" onClick="window.open('addnew.php?date=<?=$date ?>');return False;"><img src="../common/images/pencil.gif" width="15" height="15" border=0></a>
<?php } ?>
		<?=$today?>
<?php
		$list = get_schedule_id($sid, $area, $date, $type);
		if (is_array($list)) {
		$sql = "select * from schedule where ID in (".implode(",",$list).") order by start_time";
		$rs = db_exec($conn, $sql);
		while (db_fetch_row($rs)) {
?>
			<br><a href="form.php?id=<?=db_result($rs, "ID")?>">
<?php
			$st = db_result($rs, "start_time");
			$title = db_result($rs, "title");
			if ($st=='') {
				if ($title=='') {
					echo '○';
				} else {
					echo '●'.$title;
				}
			} else {
				echo '【'.html_format($st, "time").'】'.$title;
			}
		}
		}
?>
		</td>
<?php
	}
?>
	    </tr>
<?php
}
?>
        </tbody>
    </table>
</div>

<?php include 'footer.php' ?>
