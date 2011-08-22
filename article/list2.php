<?php require 'header.php';?>

<form action="update_list.php" method="POST" name="form1">

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

<?php
set_page_tag("article2");
$_SESSION['article_tag'] = "article2";
init_where();

add_where('atask_date', 'date');
add_where('atask_name', 'like');
add_where('atask1', 'date');
add_where('atask2', 'str');
add_where('atask3', 'date');
add_where('atask4', 'date');
add_where('atask5', 'like');

$table = "article_task";
$table = "($table) left join article on $table.hid=article.ID";
$field = "article_task.ID";
$field .= ",article.*";
$field .= ",article_task.atask_date";
$field .= ",article_task.atask_name";
$field .= ",article_task.atask1";
$field .= ",article_task.atask2";
$field .= ",article_task.atask3";
$field .= ",article_task.atask4";
$field .= ",article_task.atask5";

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
echo_sort_td("船名", "aname");
//echo_sort_td("顧客コード", "ccode");
echo_sort_td("顧客名", "cname");
echo_sort_td("点検日付", "atask_date");
echo_sort_td("検査内容", "atask_name");
echo_sort_td("ＤＭ発送日付", "atask1");
echo_sort_td("返事確認", "atask2");
echo_sort_td("実施日", "atask4");
?>
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
	echo_html_td($rs, "aname", "str");
//	echo_html_td($rs, "ccode", "str");
	echo_html_td($rs, "cname", "str");
	echo_html_td($rs, "atask_date", "date");
	echo_html_td($rs, "atask_name", "str");
	echo_html_td($rs, "atask1", "date");
	echo_html_td($rs, "atask2", "str");
	echo_html_td($rs, "atask4", "date");
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
$table = "customer";
$table = "($table) inner join article on customer.ccode=article.ccode";
$table = "($table) inner join article_task on article.ID=article_task.hid";
$sql = "select customer.* from $table";
if ($where<>'') $sql .= " where $where";
?>
	if (confirm('ＤＭ発送日付を記録し、宛名を印刷します。')==true) {
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
	window.open("write_print_date.php?where=<?=urlencode($where)?>", "_BLANK");
}
</script>

<?php require 'footer.php' ?>
