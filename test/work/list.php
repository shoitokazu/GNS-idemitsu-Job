<?php
require 'header.php';

set_page_tag("work");
init_where();

$base = "work_h";
//$where = limitCompany("", $base, true);
add_where('work_h.company', 'int');
add_where('wcode', 'str');
add_where('making_date', 'date');
$state = add_where('work_state', 'int');
if ($state=="") {
	if ($where<>"") $where .= " and ";
	$where .= "work_state<>9";
}
add_where('uid', 'int');
if ($_REQUEST['work_chance']=='1') {
	if ($where<>"") $where .= " and ";
	$where .= "work_state in (1,5,6,7)";
}
add_where('wstaff', 'str');
add_where('ccode', 'str');
?>

<?php
$table = $base;
//$table = "$base left join account on $base.uid=account.ID";
$field = "$base.*";
//$field .= ",account.name as staff";
$sql = "select $field from $table";
if ($where<>'') $sql .= " where $where";
$sql .= " group by $base.ID";
$sort = get_sort();
if ($sort<>'') $sql .= " order by $sort";
$rs = paging_list_init($conn, $sql);
echo_paging_list_header();
?>
<table>
<thead>
<tr>
<?php
echo_sort_td("案件番号", "wcode");
echo_sort_td("作成日", "making_date");
echo_sort_td("状態", "work_state");
echo_sort_td("顧客名", "cname");
echo_sort_td("案件名", "wname");
echo_sort_td("購入手段", "way");
echo_sort_td("担当者", "wstaff");
echo_sort_td("事業所", "company");
if ($select_mode) echo '<th></th>';
?>
</tr>
</thead>

<tbody>
<?php
while (db_fetch_row($rs)) {
	if (paging_list_loop()) break;
	$id = db_result($rs, 'ID');
	echo '<tr>';
//	echo_html_td($rs, "ID", "int");
	$code = db_result($rs, "wcode");
?>
<td><a href="form1.php?id=<?=$id?>&<?=$select_arg?>"><?=$code?></a></td>
<?php
	echo_html_td($rs, "making_date", "date");
	echo_html_td($rs, "work_state", "select2");
	echo_html_td($rs, "cname", "str");
	echo_html_td($rs, "wname", "str");
	echo_html_td($rs, "way", "str");
	echo_html_td($rs, "wstaff", "str");
	echo_html_td($rs, "company", "table");
	if ($select_mode) {
?>
<td><input type="button" onClick="setSelectValue('<?=$code?>')" value="決定"></td>
<?php
 	}
	echo "</tr>";
}
db_free($rs);
?>
</tbody>
</table>

<?php echo_paging_list_footer();?>

<?php require 'footer.php' ?>
