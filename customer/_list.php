<?php require 'header.php';?>

<form action="update_list.php" method="POST" name="form1">

<?php if ($page_mode<>"shop") { ?>
<table bgcolor="#0000aa" width=100% height=30><tr>
<td height=25 width=30></td>
<td width=50><input type="submit" value="更新"></td>
<td><input type="button" value="宛名印刷" onClick="go_print()"></td>
</tr></table>
<?php } ?>

<?php
set_page_tag("customer");

$table = "customer";
$flag = DListUp("value,name", "choices", "field='customer_flag'", "sort", ture);
if ($flag==false) $flag = array();
$group = DListUp("value,name", "choices", "field='customer_group'", "sort", ture);
if ($group==false) $group = array();

init_where();
//$where = limitCompany("", $table);
add_where('ccode', 'like');
add_where('kana', 'like');
add_where('cname', 'like');
add_where('csub', 'like');
add_where('address', 'like');

add_where('customer_type', 'int');
add_where('customer_kind', 'str');
add_where('birthday', 'date');
add_where('post', 'like');
add_where('remarks', 'like');
add_where('f1', 'bool');
add_where('f2', 'bool');
add_where('f3', 'bool');
add_where('f4', 'bool');

add_where('wstaff', 'like');
add_where('scstaff', 'like');

add_where('free', 'none');
$free = $_REQUEST['free'];
//$flist[] = "customer.ccode";
//$flist[] = "customer.cname";
//$flist[] = "csub";
//$flist[] = "kana";
$flist[] = "zip";
$flist[] = "address";
$flist[] = "building";
$flist[] = "tel";
$flist[] = "fax";
$flist[] = "email";
//$flist[] = "cname2";
//$flist[] = "csub2";
//$flist[] = "kana2";
$flist[] = "zip2";
$flist[] = "address2";
$flist[] = "building2";
$flist[] = "tel2";
$flist[] = "fax2";
$flist[] = "email2";
if ($free<>"") {
	if ($where<>"") $where .= " and ";
	$where .= "(";
	$first = true;
	foreach ($flist as $f) {
		if (!$first) {
			$where .= " or ";
		}
		$where .= "$f like '%$free%'";
		$first = false;
	}
	$where .= ")";
}
$free2 = add_where('free2', 'none');
$flist = array();
$flist[] = "customer.cname";
$flist[] = "csub";
$flist[] = "kana";
$flist[] = "cname2";
$flist[] = "csub2";
$flist[] = "kana2";
if ($free2<>"") {
	if ($where<>"") $where .= " and ";
	$where .= "(";
	$first = true;
	foreach ($flist as $f) {
		if (!$first) {
			$where .= " or ";
		}
		$where .= "$f like '%$free2%'";
		$first = false;
	}
	$where .= ")";
}

for ($i=1; $i<=5; $i++) {
	add_where('group'.$i, 'checkbox');
}
for ($i=1; $i<=5; $i++) {
	add_where('g'.$i, 'checkbox');
}
add_where('selected', 'bool');

$field = "$table.*";

/*
$kensa = add_where('kensa', 'none');
if ($kensa<>"") {
	$table = "($table) left join article on customer.ccode=article.ccode";
	$where .= "article.acode='abc'";
}
*/

foreach ($group as $v) {
	$gid = $v[0];
	$query = "select cid,selected as g$gid from customer_group where company=$company and gid=$gid";
	$table = "($table) left join ($query) as q$gid on customer.ID=q$gid.cid";
	$field .= ",g$gid";
}
$query = "select rid,selected from list_select where `table`='customer' and uid=$uid";
$table = "($table) left join ($query) as tmp on customer.ID=tmp.rid";
$field .= ",selected";

$sql = "select * from $table";
if ($where<>'') $sql .= " where $where";
$sort = get_sort();
if ($sort<>'') {
	if (substr($sort, -5)==" desc") {
		$sql .= " order by $sort";
	} else {
		$sql .= " order by IF($sort='','龍龍',$sort)";
	}
}
$rs = paging_list_init($conn, $sql);
echo_paging_list_header();
init_list_format();
?>
<table>
<thead>
<tr>
<?php
echo_sort_td("DM拒否", "customer1");
echo_sort_td("顧客コード", "ccode");
echo_sort_td("顧客名", "cname");
//echo_sort_td("ふりがな", "kana");
echo_sort_td("住所", "address");
echo_sort_td("会社名", "cname2");
//echo_sort_td("会社ふりがな", "kana2");
echo_sort_td("会社住所", "address2");
echo_sort_td("営業担当", "wstaff");
echo_sort_td("SC担当", "scstaff");
/*
foreach ($flag as $v) {
	if ($v[1]<>"") echo '<th>'.$v[1].'</th>';
}
*/
if ($page_mode<>"shop") {
/*
	foreach ($group as $v) {
		if ($v[1]<>"") echo '<th>'.$v[1].'</th>';
	}
*/
	if ($select_mode) {
		echo '<th>選択</th>';
	} else {
?>
<th>選択</th>
<?php
	}
}
?>
</tr>
</thead>

<tbody>
<?php
while (db_fetch_row($rs)) {
	if (paging_list_loop()) break;
	init_list_line($rs);
	$id = db_result($rs, 'ID');
	$code = db_result($rs, 'ccode');
	$dm = db_result($rs, 'customer1');
	if ($dm==1) {
		echo '<tr class="strong">';
		echo '<td>○</td>';
	} else {
		echo '<tr>';
		echo '<td></td>';
	}
?>
<td><a href="form.php?id=<?=$id?>&<?=$select_arg?>"><?=$code?></a></td>
<?php
	echo_html_td($rs, "cname", "str10");
//	echo_html_td($rs, "kana", "str");
	echo_html_td($rs, "address", "str15");
	echo_html_td($rs, "cname2", "str10");
//	echo_html_td($rs, "kana2", "str");
	echo_html_td($rs, "address2", "str15");
	echo_html_td($rs, "wstaff", "str");
	echo_html_td($rs, "scstaff", "str");
/*
	foreach ($flag as $v) {
		if ($v[1]<>"") echo_html_td($rs, "group".$v[0], "bool");
	}
*/
if ($page_mode<>"shop") {
/*
	foreach ($group as $v) {
		if ($v[1]<>"") echo_html_td($rs, "g".$v[0], "bool");
	}
*/
	if ($select_mode) {
?>
<td><input type="button" onClick="setSelectValue('<?=$code?>')" value="決定"></td>
<?php
 	} else {
		echo_list_td($rs, "selected", "bool");
//		$sel = DLookUp("selected", "list_select", "`table`='customer' and rid=$id and selected=1");
//		echo '<td>'.($sel[0]==1 ? "○" : "×").'</td>';
	}
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
	location.href="form_print.php?sql=<?=urlencode($sql)?>";
}
function all_check(fn, v) {
	for (i=0; i<<?=$PAGE_SIZE?>; i++) {
		document.getElementById(fn + "_" + i).checked = v;
	}
}
</script>
<?php echo $sql ?>
<?php require 'footer.php' ?>
