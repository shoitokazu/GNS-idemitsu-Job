<?php require 'header.php';?>

<?php if ($page_mode<>"shop") { ?>
<table bgcolor="#0000aa" width=100% height=30><tr>
<td height=25 width=30></td>
<td><input type="button" value="集計用CSV出力" onClick="go_csv()"></td>
</tr></table>
<?php } ?>

<?php
set_page_tag("mainte");
init_where();

$base = "mainte_h";
//$where = limitCompany("", $base, true);

add_where('mainte_h.company', 'int');
add_where('mcode', 'str');
add_where('making_date', 'date');
$state = add_where('mainte_state', 'int');
if ($state=="") {
	if ($where<>"") $where .= " and ";
	$where .= "mainte_state<>9";
}
add_where('uid', 'int');
add_where('ccode', 'str');
$cname = add_where('cname', 'none');
if ($cname<>"") {
	if ($where<>"") $where .= " and ";
	$where .= "(cname like '%$cname%'";
	$list = DListUp("ccode", "customer", "cname like '%$cname%' or cname2 like '%$cname%'");
	if (is_array($list)) {
		$where .= "or ccode in (";
		$code_list = array();
		foreach ($list as $v) {
			$code_list[] = db_value($v[0], "str");
		}
		$where .= implode(",", $code_list);
		$where .= ")";
	}
	$where .= ")";
}
add_where('mainte_category', 'int');
add_where('acode', 'str');
add_where('aname', 'like');
add_where('wstaff', 'like');
add_where('scstaff', 'like');
add_where('mstaff', 'like');
add_where('mainte7', 'date');
add_where('mainte8', 'date');
add_where('mainte9', 'date');
add_where('mainte10', 'date');
add_where('scenter', 'str');
add_where('mainte1', 'str');
add_where('model_name', 'like');
$ename = add_where('ename', 'none');
if ($ename<>'') {
	if ($where<>"") $where .= " and ";
	$where .= "(engine_name like '%$ename%'";
	$where .= "or engine_name2 like '%$ename%')";
}
add_where('mname', 'like');

$slip_type = add_where('mtype', 'int');

$mine = $_REQUEST['mine'];
if ($mine<>"") {
	$where = "(".$where.") and (";
	$where .= "wstaff=".db_value($uname, "str");
	$where .= " or scstaff=".db_value($uname, "str");
	$where .= ")";
}

$table = $base;
//$table = join_data($table, $base, "mainte_d");
$field = "$base.*";
//$field .= ", sum(num*price) as asum";
$sql = "select $field from $table";
if ($where<>'') $sql .= " where $where";
//$sql .= " group by $base.ID";
$sort = get_sort();
if ($sort=='') $sort = "making_date desc";
if ($sort<>'') $sql .= " order by $sort";
$rs = paging_list_init($conn, $sql);
echo_paging_list_header();
?>
<table>
<thead>
<tr>
<?php
echo_sort_td("整備番号", "mcode");
echo_sort_td("作成日", "making_date");
echo_sort_td("状態", "mainte_state");

echo_sort_td("売上区分", "mainte_category");
echo_sort_td("伝票区分", "mainte1");
echo_sort_td("案件名", "mname");
echo_sort_td("製品名", "model_name");
echo_sort_td("船名", "aname");
//echo_sort_td("合計金額", "asum");
echo '<th>合計金額</th>';
echo_sort_td("担当者", "mstaff");

echo_sort_td("事業所", "company");
?>
</tr>
</thead>

<tbody>
<?php
$sum_field = "sum(num*price*rate/100) as asum";
$sum_sql = "select $sum_field from mainte_d where hid=";

while (db_fetch_row($rs)) {
	if (paging_list_loop()) break;
	$id = db_result($rs, 'ID');
	echo '<tr>';
//	echo_html_td($rs, "ID", "int");
?>
<td><a href="form1.php?id=<?=$id?>"><?=db_result($rs, "mcode")?></a></td>
<?php
	echo_html_td($rs, "making_date", "date");
	echo_html_td($rs, "mainte_state", "select2");

	echo_html_td($rs, "mainte_category", "select2");
	echo_html_td($rs, "mainte1", "str");
	echo_html_td($rs, "mname", "str");
	echo_html_td($rs, "model_name", "str");
	echo_html_td($rs, "aname", "str");
//	echo_html_td($rs, "asum", "int");
	$row = db_row($conn, $sum_sql.$id);
	echo '<td align=right>'.number_format($row[0]).'</td>';

	echo_html_td($rs, "mstaff", "str");

	echo_html_td($rs, "company", "table");
	echo "</tr>";
}
db_free($rs);
?>
</tbody>
</table>

<?php echo_paging_list_footer();?>

<script>
function go_csv() {
	location.href="../csv/export_mainte_sum.php?sql=<?=urlencode($sql)?>";
}
</script>

<?php require 'footer.php' ?>
