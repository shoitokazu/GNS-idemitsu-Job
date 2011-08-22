<?php require 'header.php'; ?>

<script src="../common/calendar.js" language="JavaScript"></script>

<form action="sales_report.php" method="get" name="form1">

<?php
set_page_tag("accounting");
echo_search_frame();
//echo_search_tr("伝票タイプ", "mtype", "estimate", 0);
?>

<tr><th>サービスセンター</th><td>
<input type="hidden" name="trans_sc" id="trans_sc" value="">
<select name='select_sc' onChange='SetCell("transfer", this.value)'>
<option value=""></option>
<?php
$list = DListUp("group1", "transfer", "", "sort");
for ($i=0; $i<count($list); $i++) {
	echo '<option value="'.$i.'">'.$list[$i][0].'</option>';
}
//echo_option($list, $v);
?>
</select></td></tr>

<tr><th>振替先</th><td>
<select name='transfer' id='transfer'>
<option value=""></option>
<?php
$list = DListUp("name", "transfer", "", "sort");
echo_option($list, $v);
?>
</select></td></tr>

<?php
//echo_search_tr("サービスセンター", "trans_sc", "select1");
//echo_search_tr("振替先", "transfer", "table1");
echo_search_tr("売上確定日", "mainte10", "date");
echo_search_frame_end();
?>

<input type="submit" value="検索">
<input type="reset" value="元に戻す">

<?php echo_button_search_clear(); ?>

</p>

</form>
<script>
<?php
$debug = false;
$list_sc = DListUp("group1", "transfer", "", "sort");
$num_sc = count($list_sc);
?>
var sc = new Array(<?=$num_sc?>);
var v = new Array(<?=$num_sc?>);
<?php
for ($i=0; $i<$num_sc; $i++) {
	$list = DListUp("name", "transfer", "group1='".$list_sc[$i][0]."'", "sort");
	$num = count($list);
?>
sc[<?=$i?>] = "<?=$list_sc[$i][0]?>"
v[<?=$i?>] = new Array(<?=$num?>);
<?php
	for ($j=0; $j<$num; $j++) {
?>
v[<?=$i?>][<?=$j?>] = "<?=$list[$j][0]?>";
<?php
	}
}
?>
function DeleteCell(id, len) {
	obj = document.getElementById(id);
	if (len == 0) len = obj.length;
	for (i = len; i != 0; i--) {
		obj.options[i] = null;
	}
}
function SetCell(id, no) {
	obj = document.getElementById(id);
	if (obj.length != 1) DeleteCell(id, 0);
	len = v[no].length;
	for (i = 0; i < len; i++) {
		obj.options[i+1] = new Option(v[no][i], v[no][i]);
	}
	document.form1.trans_sc.value = sc[no];
}
</script>

<?php require 'footer.php'; ?>
