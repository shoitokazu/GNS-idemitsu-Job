<?php require 'header.php';?>

<form action="update_list.php" method="POST" name="form1">

<?php if ($page_mode<>"shop") { ?>
<table bgcolor="#0000aa" width=100% height=30><tr><td>
<table><tr>
<td height=25 width=30></td>
<!--
<td width=50><input type="submit" value="更新"></td>
-->
<td><input type="button" value="宛名印刷" onClick="go_print()"></td>
<!--
<td><input type="text" value="" id="history_text"></td>
<td><input type="button" value="履歴記録" onClick="write_history()"></td>
-->
</tr></table>
</td></tr></table>
<?php } ?>

<?php
if ($select_mode) {
//	$target = explode(',', $select);
?>
<script language="javaScript">
function setSelectValue2(v) {
<?php if ($target[1]=="sales") { ?>
	window.opener.document.getElementById('<?=$target[0]?>').value = v;
	window.opener.document.form1.addnew1.value="ON";
	window.opener.document.form1.submit();
	window.close()
<?php } else { ?>
	setSelectValue(v);
<?php } ?>
}
</script>
<?php
}
set_page_tag("article");
init_where();

$table = "article";

//$where = limitCompany("", $table);
$type = add_where('atype', 'str');
add_where('acode', 'str');
add_where('aname', 'like');
add_where('selected', 'checkbox');
add_where('ccode', 'str');
add_where('cname', 'like');

/*
$kensa = add_where('kensa', 'none');
if ($kensa<>"") {
	if ($where<>"") $where .= " and ";
	$where .= "article3<>''";
}
*/

add_where('sales_category', 'int');
add_where('model_name', 'like');

$ecode = add_where('ecode', 'none');
if ($ecode<>'') {
	if ($where<>"") $where .= " and ";
	$where .= "(engine_code='$ecode'";
	$where .= "or engine_code2='$ecode')";
}
$ename = add_where('ename', 'none');
if ($ename<>'') {
	if ($where<>"") $where .= " and ";
	$where .= "(engine_name like '%$ename%'";
	$where .= "or engine_name2 like '%$ename%')";
}
//add_where('engine_code', 'str');
//add_where('engine_name', 'like');

add_where('article1', 'str');
add_where('article2', 'date');
add_where('article3', 'date');
add_where('article4', 'str');
add_where('article5', 'str');
add_where('article6', 'str');
add_where('article7', 'str');
add_where('article8', 'str');
add_where('article9', 'str');
add_where('article10', 'str');
add_where('article11', 'str');
add_where('article12', 'str');
add_where('article13', 'str');
add_where('article14', 'str');
add_where('article15', 'month');
add_where('article16', 'str');
add_where('article17', 'str');
add_where('article18', 'str');
add_where('dock', 'like');

$field = "$table.*";

$query = "select rid,selected from list_select where `table`='$table' and uid=$uid";
$table = "($table) left join ($query) as tmp on $table.ID=tmp.rid";
$field .= ",selected";

$sql = "select * from $table";
if ($where<>'') $sql .= " where $where";
$sort = get_sort();
if ($sort<>'') $sql .= " order by $sort";
$rs = paging_list_init($conn, $sql);
echo_paging_list_header();
init_list_format();
?>
<table>
<thead>
<tr>
<?php
echo_sort_td("物件コード", "acode");
echo_sort_td("物件種別", "atype");
echo_sort_td("船名", "aname");
echo_sort_td("商品コード", "model_code");
echo_sort_td("商品名", "model_name");
echo_sort_td("エンジン名", "engine_name");
echo_sort_td("顧客名", "cname");
?>
<?php if ($type==="0") { ?>
<th>船検登録日</th>
<th>次回船検日</th>
<?php } ?>
<?php if ($select_mode) { ?>
<th>選択</th>
<?php } else { ?>
<th>最終宛名印刷日</th>
<?php } ?>
</tr>
</thead>

<tbody>
<?php
while (db_fetch_row($rs)) {
	if (paging_list_loop()) break;
	init_list_line($rs);
	$id = db_result($rs, 'ID');
	$code = db_result($rs, 'acode');
	echo "<tr>";
?>
<td><a href="form.php?id=<?=$id?>&<?=$select_arg?>"><?=$code?></a></td>
<?php
	echo_html_td($rs, "atype", "select2");
	echo_html_td($rs, "aname", "str");
	echo_html_td($rs, "model_code", "str");
	echo_html_td($rs, "model_name", "str");
	echo_html_td($rs, "engine_name", "str");
	echo_html_td($rs, "cname", "str");
	if ($type==="0") {
		echo_html_td($rs, "article3", "date");
		echo_html_td($rs, "article15", "date");
	}
	if ($select_mode) {
?>
<td><input type="button" onClick="setSelectValue2('<?=$code?>')" value="決定"></td>
<?php
 	} else {
//		echo_list_td($rs, "selected", "bool");
		$print_date = DLookUp2("date", "history", "`table`='article' and rid=$id", "date desc");
		echo '<td>'.html_format($print_date, "date").'</td>';
	}
	echo "</tr>";
}
db_free($rs);
?>
</tbody>
</table>

<?php echo_paging_list_footer();?>

</form>

<script>
function go_print() {
<?php
$table = "article inner join customer on article.ccode=customer.ccode";
$sql = "select customer.* from $table";
if ($where<>'') $sql .= " where $where";
?>
	if (confirm('印刷履歴を記録し、宛名を印刷します。')==true) {
		write_history();
		location.href="../customer/form_print.php?sql=<?=urlencode($sql)?>";
	}
}
function all_check(fn, v) {
	for (i=0; i<<?=$PAGE_SIZE?>; i++) {
		document.getElementById(fn + "_" + i).checked = v;
	}
}
function write_history() {
//	location.href="write_history.php?where=<?=urlencode($where)?>";
	window.open("write_history.php?where=<?=urlencode($where)?>&remarks=<?=urlencode("宛名印刷")?>", "_BLANK");
}
</script>

<?php require 'footer.php' ?>
