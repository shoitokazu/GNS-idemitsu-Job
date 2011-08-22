<?php require 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<?php
$id = $_REQUEST['id'];
$ssid = $_REQUEST['ssid'];

$table = "mainte_h";
$where = "ID=".$id;
//$field = "ID,scenter,transfer,trans_sc";
$field = "*";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);

echo_menu_tab($form_tab, 4, $id);

$state_lock = false;
//if (!is_my_company($rs)) $state_lock = true;
$state = db_result($rs, "mainte_state");
if ($state==3) $state_lock = true;
lock_form($state_lock);
if ($state_lock) $attr = " disabled";
?>

<form action="update4.php" method="POST" name="form1">
<input type="hidden" name="type" value="0">

<?php echo_button_frame(); ?>
<tr><td>
<?php echo_form_submit() ?>
</td><td>
<input type="submit" value="顧客用請求書" onClick="document.form1.type.value=1">
</td><td>
<input type="submit" value="経理控" onClick="document.form1.type.value=2">
</td><td>
<input type="submit" value="振替先用" onClick="document.form1.type.value=3">
</td><td>
<input type="submit" value="お客様用明細" onClick="document.form1.type.value=4">
</td><td>
<input type="submit" value="納品書" onClick="document.form1.type.value=5">
</td><td>
<input type="submit" value="見積書" onClick="document.form1.type.value=6">
</td><td>
<input type="submit" value="納品書（振替先用）" onClick="document.form1.type.value=7">
</td></tr>
<?php echo_button_frame_end(); ?>


<?php
init_form_format($rs);

echo_form_frame();
?>
<tr><th></th><td>
</td></tr>
<?php
echo_form_tr($rs, "SC", "trans_sc", "select1");
/*
echo '<tr><th>SC</th><td>';
$v = db_result($rs, "trans_sc");
$no = echo_form_ini("trans_sc", "str");
echo "<select name='values[$no]' id='trans_sc'$attr>";
echo '<option value=""></option>';
$list = DListUp("group1", "transfer", "", "sort");
echo_option($list, $v);
echo '</select></td></tr>';
*/

//echo_form_tr($rs, "振替先", "transfer", "table");
echo '<tr><th>振替先</th><td>';
$v = db_result($rs, "transfer");
$no = echo_form_ini("transfer", "str");
echo "<select name='values[$no]' id='transfer'$attr>";
echo '<option value=""></option>';
$list = DListUp("name", "transfer", "", "sort");
echo_option($list, $v);
echo '<input type="button" value="選択" onClick="button_transfer_select()"'.$attr.'>';
echo '</select></td></tr>';
echo '</td></tr>';

//echo_form_tr($rs, "発行元", "scenter", "select1");
echo '<tr><th>印刷スタンプ（印刷用発行元）</th><td>';
$v = db_result($rs, "scenter");
echo "<select name='stamp' id='stamp'>";
echo '<option value=""></option>';
$list = DListUp("name", "stamp", "", "sort");
echo_option($list, $v);
echo '</select></td></tr>';

echo_html_tr($rs, "印刷日<BR>(顧客用請求書)", "mainte14", "date");
echo_form_frame_end();
db_free($rs);
?>
</form>
<script>
function button_transfer_select() {
/*
	var v1 = document.form1.scenter.value;
	var v2 = document.form1.trans_sc.value;
	openSelectWindow("../select/transfer_list.php?select=transfer&stamp=" + escape(v1) + "&sc=" + escape(v2));
*/
	var i1 = document.form1.stamp.selectedIndex;
	var i2 = document.form1.trans_sc.selectedIndex;
	openSelectWindow("../select/transfer_list.php?select=transfer&stamp_index=" + i1 + "&sc_index=" + i2);
}
</script>

<?php require 'footer.php'; ?>
