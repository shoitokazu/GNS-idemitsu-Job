<?php require 'header.php'; ?>

<script src="../common/calendar.js" language="JavaScript"></script>

<?php
//$body_onload_func = "tmp()";

// 顧客のIDと、編集される免許情報の行番号を取得
$id  = $_REQUEST['id'];
$lid = $_REQUEST['lid'];
?>

<form action="date_update.php" method="POST" name="subform">
<h1>免許情報更新画面</h1>

<?php
set_page_tag("customer");
?>
<input type="hidden" name="cid" value="<?php echo $id ?>">
<?php
init_form_format($rs);

echo_form_frame("免許情報");
$sql = "select * from license_limit where cid=$id and lid=$lid limit 1";
$rs = db_exec($conn, $sql);
while (db_fetch_row($rs)) {
	$lid = db_result($rs, "lid");
	$able_date = db_result($rs, "able_date");
	$next_date = db_result($rs, "next_date");
	$lclass    = db_result($rs, "lclass");
	$lmemo     = db_result($rs, "lmemo");
	$lflg      = db_result($rs, "lflg");
	$del_flg   = db_result($rs, "del_flg");
	echo "<tr><th>NO</th><td nowrap><input type='text' name='lid' value='$lid' size=12 readonly='readonly' style='background-color: #e0e0e0;'></td></tr>";
	echo "<tr><th>免許有効日</th><td nowrap><input type='text' id='able_date' name='able_date' value='$able_date' size=12><input type='button' value='選択' onclick='wrtCalendar(this.form.able_date)'></td></tr>";
	echo "<tr><th>次回更新日</th><td nowrap><input type='text' id='next_date' name='next_date' value='$next_date' size=12><input type='button' value='選択' onclick='wrtCalendar(this.form.next_date)'></td></tr>";
	echo "<tr><th>免許種別</th><td nowrap><input type='text' id='lclass' name='lclass' value='$lclass' size=24></td></tr>";
	echo "<tr><th>免許メモ</th><td nowrap><input type='text' id='lmemo' name='lmemo' value='$lmemo' size=20></td></tr>";
	echo "<tr><th>有効／無効</th><td nowrap><input type='checkbox' id='lflg' name='lflg' value='$lflg' ".(($lflg<1)? "checked>　有効</td></tr>":">　無効</td></tr>");
	echo "<tr><th>行削除</th><td nowrap><input type='checkbox' id='del_flg' name='del_flg' value='$del_flg' ".(($del_flg>0)? "checked>　削除</td></tr>":"></td></tr>");

	echo_form_frame_end();
}
echo_list_frame_end();
?>
<input type="submit" value="更新"><br>
<p class="close"><a href="javascript:btn();">[ 閉じる ]</a></p>

</form>

<script language="javascript">
function btn() {
	window.close();
}
</script>

<?php db_free($rs);?>

<?php require 'footer.php'; ?>
