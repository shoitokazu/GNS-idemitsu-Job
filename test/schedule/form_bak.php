<?php include 'header.php' ?>

<script src="calendar.js" language="JavaScript"></script>
<script src="selectWindow.js" language="JavaScript"></script>
<script language="JavaScript">
function openDateWindow(id) {
	var d = document.form1.<?php echo $schedule[2][0] ?>.value;
	openSelectWindow('date_list.php?select=none&mode=5&arg1=' + id + '&arg2=' + d);
}
function openDeleteWindow(URL,msg) {
	if (confirm(msg + 'します。よろしいですか？')) window.open(URL, 'delete', 'alwaysLowered=yes');
}
</script>

<?php
$date = $_REQUEST['date'];
$id = $_REQUEST['id'];
$wid = $_REQUEST['wid'];
if ($id <> '') {
//	$conn = odbc_connect("ybs", "", "");
	$table = "商談";
	$table = "($table) left join 社員マスター on 商談.社員ID=社員マスター.社員ID";
	$table = "($table) left join 案件 on 商談.案件ID=案件.案件ID";
	$sql = "SELECT * FROM $table WHERE 商談ID=".$id;
	$rs = odbc_exec($conn, $sql);
	if (odbc_fetch_row($rs)) {
		foreach ($schedule as $key => $a) {
			$schedule[$key][3] = loadRsValue($rs, $a);
		}
		$sname = odbc_result($rs, '社員名');
	}
} else {
	echo 'error!';
	exit();
}
?>
<div id="main_area">
<!-- ******** ここからメイン ******** -->

<h2 id="pagetitle">商談情報</h2>

<form name="form1" method="POST" action="schedule_update.php">
<input type="hidden" name="id" value="<?php echo $id ?>">

<table class="buttons" border="2" cellpadding="0" cellspacing="1">
    <tr>
      <td><input name="Submit3" type="button" onClick="history.back()" value="戻る"></td>
      <td><input name="update" type="submit" value="登録更新"></td>
      <td><input name="delete" type="submit" onClick="return confirm('削除します。よろしいですか？');" value="削除"></td>
    </tr>
</table>

<div id="master" class="windows">

    <h3>スケジュール追加</h3>

    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <th>日程:</th>
            <td>
                日付:
		<input type="text" name="<?=$schedule[2][0] ?>" value="<?=$schedule[2][3] ?>" size=15> 
		<input type="button" onclick="wrtCalendar(this.form.<?=$schedule[2][0] ?>)" value="カレンダー">
		<input type="button" value="追加" onClick="openDateWindow(<?php echo $id ?>);">
		<input type="button" value="クリア" onClick="openDeleteWindow('date_delete.php?id=<?=$id ?>', '追加日付をクリア');">
                時間:
                <select name="<?=$schedule[3][0] ?>1">
			<option selected><?=substr($schedule[3][3],0,2) ?></option>
			<option>08</option>
			<option>09</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
			<option>13</option>
			<option>14</option>
			<option>15</option>
			<option>16</option>
			<option>17</option>
			<option>18</option>
			<option>19</option>
			<option>20</option>
			<option>21</option>
			<option>22</option>
			<option>23</option>
			<option>00</option>
			<option>01</option>
			<option>02</option>
			<option>03</option>
			<option>04</option>
			<option>05</option>
			<option>06</option>
			<option>07</option>
		</select>
                :
		<select name="<?=$schedule[3][0] ?>2">
			<option selected><?=substr($schedule[3][3],3,2) ?></option>
			<option>00</option>
			<option>10</option>
			<option>20</option>
			<option>30</option>
			<option>40</option>
			<option>50</option>
		</select>
                ～
		<select name="<?=$schedule[4][0] ?>1">
			<option selected><?=substr($schedule[4][3],0,2) ?></option>
			<option>08</option>
			<option>09</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
			<option>13</option>
			<option>14</option>
			<option>15</option>
			<option>16</option>
			<option>17</option>
			<option>18</option>
			<option>19</option>
			<option>20</option>
			<option>21</option>
			<option>22</option>
			<option>23</option>
			<option>00</option>
			<option>01</option>
			<option>02</option>
			<option>03</option>
			<option>04</option>
			<option>05</option>
			<option>06</option>
			<option>07</option>
		</select>
                :
		<select name="<?=$schedule[4][0] ?>2">
			<option selected><?=substr($schedule[4][3],3,2) ?></option>
			<option>00</option>
			<option>10</option>
			<option>20</option>
			<option>30</option>
			<option>40</option>
			<option>50</option>
		</select>
<br>
<?php
	$table = "商談日";
	$sql = "select 日付 from $table where 商談ID=$id group by 日付 order by 日付";
	$rs1 = odbc_exec($conn, $sql);
	while (odbc_fetch_row($rs1)) {
		$dstr = odbc_result($rs1, '日付');
		$date = str_replace('-', '/', substr($dstr, 0, 10));
		$week = '('.strToWeek($date).')';
		$y1 = substr($date, 0, 4);
		$y2 = substr($schedule[2][3], 0, 4);
		if ($y1==$y2) $date = substr($date, 5, 7);
		$date .= $week;
?>
<a href="date_delete.php?id=<?php echo $id ?>&date=<?php echo $dstr ?>" onClick="return confirm('削除しますか？');"><?php echo $date ?></a>
<?php
	}
?>
            </td>
        </tr>
        <tr>
            <th>項目区分:</th>
            <td>
		<select name="<?=$schedule[10][0] ?>" size="1">
			<option selected><?=$schedule[10][3] ?></option>
			<option>商談</option>
			<option>会議</option>
			<option>顧客の声</option>
			<option>スケジュール</option>
			<option>クレーム</option>
		</select>
            </td>
        </tr>
        <tr>
            <th>PPランク:</th>
            <td><input type="text" name="<?=$schedule[11][0] ?>" value="<?=$schedule[11][3] ?>" size="20"></td>
        </tr>
        <tr>
            <th>タイトル:</th>
            <td><input type="text" name="<?=$schedule[5][0] ?>" value="<?=$schedule[5][3] ?>" size="50"></td>
        </tr>
        <tr>
            <th>面談者:</th>
            <td><input type="text" name="<?=$schedule[8][0] ?>" value="<?=$schedule[8][3] ?>" size="50"></td>
        </tr>
        <tr>
            <th>場所:</th>
            <td><input type="text" name="<?=$schedule[9][0] ?>" value="<?=$schedule[9][3] ?>" size="50"></td>
        </td>
        </tr>
        <tr>
            <th>内容:</th>
            <td><textarea rows="5" cols="75" name="<?=$schedule[7][0] ?>" wrap="virtual"><?=$schedule[7][3] ?></textarea></td>
        </tr>
        <tr>
            <th>備考:</th>
            <td><textarea name="<?=$schedule[6][0] ?>" cols="75" rows="5"><?=$schedule[6][3] ?></textarea></td>
        </tr>
        <tr>
            <th>参加者:</th>
            <td>
		<input type="button" value="選択" onClick="openSelectWindow('participant_list.php?select=none&mode=3&arg1=<?php echo $id ?>');">
<?php
	$table = "商談参加者";
	$table = "($table) left join 社員マスター on 商談参加者.社員ID=社員マスター.社員ID";
	$sql = "select * from $table where 商談ID=$id";
	$rs1 = odbc_exec($conn, $sql);
	while (odbc_fetch_row($rs1)) {
		$pid = odbc_result($rs1, '参加ID');
		$pname = odbc_result($rs1, '社員名');
?>
		<?php echo $pname ?>
		<input type="button" value="削除" onClick="openDeleteWindow('participant_delete.php?id=<?=$pid ?>', '参加者を削除');">
<?php	} ?>
            </td>
        </tr>
        <tr>
            <th>担当者:</th>
            <td>
		<input type="hidden" name="<?=$schedule[1][0] ?>" value="<?=$schedule[1][3] ?>">
		<input type="button" value="選択" onClick="openSelectWindow('staff_list.php?select=<?php echo $schedule[1][0] ?>');">
		<?=$sname ?>
	    </td>
        </tr>
        <tr class="hr_none">
            <th>案件:</th>
            <td>
		<input type="hidden" name="<?=$schedule[0][0] ?>" value="<?=$schedule[0][3] ?>">
		<input type="button" value="選択" onClick="openSelectWindow('work_list.htm?select=<?=$schedule[0][0] ?>');">
<?php
$wid = $schedule[0][3];
$wname = odbc_result($rs, '案件名');
if ($wid<>null) {
	if ($wname=='') $wname='○';
?>
		<a href="work_form.htm?id=<?=$wid ?>"><?=$wname ?></a>
		<input type="button" value="リンク解除" onClick="openDeleteWindow('select.php?select=on,<?=$schedule[0][0] ?>,form1&id=null', '案件リンクを解除');">
<?php
}
?>
            </td>
        </tr>
    </table>

    <p class="buttons">
        <input type="submit" name="update" value="登録更新">
        <input type="submit" name="delete" value="削除" onclick="return confirm('削除します。よろしいですか？');">
    </p>

</div>

</form>

</div>

</div>

<?php include 'footer.php' ?>
