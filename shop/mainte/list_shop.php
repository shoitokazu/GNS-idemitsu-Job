<?php
require 'header.php';
?>
<?php
set_page_tag("mainte_shop");
init_where();

$base = "mainte_h";

$where = "mainte_shop.ssid=$uid";
$slip_type = add_where('mtype', 'int');
add_where('mcode', 'str');
add_where('making_date', 'date');
add_where('mainte_state', 'int');

add_where('cname', 'like');
add_where('model_name', 'like');
add_where('aname', 'like');
$en = add_where('engine_name', 'none');
if ($en<>"") {
	if ($where<>'') $where .= " and ";
	$where .= "(engine_name like '$en'";
	$where .= " or engine_name2 like '$en')";
}

add_where('shop1', 'date');
add_where('shop2', 'date');
add_where('shop3', 'date');
add_where('mainte1', 'str');

$table = $base;
$table = "($table) left join mainte_shop on mainte_h.ID=mainte_shop.mid"; 
$table = "($table) left join mainte_d on mainte_shop.mid=mainte_d.hid and mainte_shop.ssid=mainte_d.ssid";
$field = "$base.*";
$field .= ", sum(num*price) as asum";
//$field .= ", num, price";
$sql = "select $field from $table";
if ($where<>'') $sql .= " where $where";
$sql .= " group by $base.ID";
$sort = get_sort();
if ($sort=="") $sort = "$base.making_date desc";
if ($sort<>'') $sql .= " order by $sort";
$rs = paging_list_init($conn, $sql);
echo_paging_list_header();
?>
<table>
<thead>
<tr>
<?php
echo_sort_td("管理番号", "mcode");
//echo_sort_td("状態", "mainte_state");
echo_sort_td("伝票区分", "mainte1");
echo_sort_td("案件名", "mname");
echo_sort_td("製品名", "model_name");
echo_sort_td("船名", "aname");
echo_sort_td("合計金額", "asum");
echo_sort_td("担当者", "mstaff");
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
?>
<td><a href="form1.php?id=<?=$id?>"><?=db_result($rs, "mcode")?></a></td>
<?php
//	echo_html_td($rs, "mainte_state", "select2");
	echo_html_td($rs, "mainte1", "str");
	echo_html_td($rs, "mname", "str");
	echo_html_td($rs, "model_name", "str");
	echo_html_td($rs, "aname", "str");
	echo_html_td($rs, "asum", "int");
	echo_html_td($rs, "mstaff", "str");
	echo "</tr>";
}
db_free($rs);
?>
</tbody>
</table>

<?php echo_paging_list_footer();?>

<?php require 'footer.php' ?>
