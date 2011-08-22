<?php
require 'header.php';
?>
<?php
set_page_tag("sales");
init_where();

$base = "sales_h";
//$where = limitCompany("", $base, true);
add_where('sales_h.company', 'int');
add_where('scode', 'str');
add_where('making_date', 'date');
$state = add_where('sales_state', 'int');
if ($state=="") {
	if ($where<>"") $where .= " and ";
	$where .= "sales_state<>9";
}
add_where('uid', 'int');
add_where('acceptance_date', 'date');
add_where('due_date', 'date');
add_where('sales_date', 'date');

add_where('area', 'str');
add_where('acode', 'str');
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
?>

<?php
$table = $base;
//$table = join_data($table, $base, "sales_d");
$field = "$base.*";
//$field .= ", sum(num*price) as asum";
//$field .= ", sum(num*(price-cost)) as profit";
$sql = "select $field from $table";
if ($where<>'') $sql .= " where $where";
//$sql .= " group by $base.ID";
$sort = get_sort();
if ($sort<>'') $sql .= " order by $sort";
$rs = paging_list_init($conn, $sql);
echo_paging_list_header();
?>
<table>
<thead>
<tr>
<?php
echo_sort_td("売上番号", "scode");
echo_sort_td("作成日", "making_date");
echo_sort_td("状態", "sales_state");

echo_sort_td("顧客名", "cname");
echo_sort_td("船名", "aname");
echo_sort_td("エリア", "area");
//echo_sort_td("合計金額", "asum");
//echo_sort_td("荒利", "profit");
echo '<th>合計金額</th>';
echo '<th>荒利</th>';

echo_sort_td("担当者", "wstaff");
echo_sort_td("事業所", "company");
?>
</tr>
</thead>

<tbody>
<?php
$sum_field = "sum(num*price) as asum";
$sum_field .= ", sum(num*(price-cost)) as profit";
$sum_sql = "select $sum_field from sales_d where hid=";

while (db_fetch_row($rs)) {
	if (paging_list_loop()) break;
	$id = db_result($rs, 'ID');
	echo '<tr>';
//	echo_html_td($rs, "ID", "int");
?>
<td><a href="form1.php?id=<?=$id?>"><?=db_result($rs, "scode")?></a></td>
<?php
	echo_html_td($rs, "making_date", "date");
	echo_html_td($rs, "sales_state", "select2");

	echo_html_td($rs, "cname", "str");
	echo_html_td($rs, "aname", "str");
	echo_html_td($rs, "area", "str");
//	echo_html_td($rs, "asum", "cur");
//	echo_html_td($rs, "profit", "cur");
	$row = db_row($conn, $sum_sql.$id);
	echo '<td align=right>'.number_format($row[0]).'</td>';
	echo '<td align=right>'.number_format($row[1]).'</td>';

	echo_html_td($rs, "wstaff", "str");
	echo_html_td($rs, "company", "table");
	echo "</tr>";
}
db_free($rs);
?>
</tbody>
</table>

<?php echo_paging_list_footer();?>

<?php require 'footer.php' ?>
