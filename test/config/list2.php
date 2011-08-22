<?php require 'header.php';?>

<?php
$table = "choices";
$field = $_REQUEST['field'];
$name = $_REQUEST['name'];
?>

<form action="update_list.php" method="POST" name="form1">
<input type="hidden" name="field" value="<?=$field?>">
<input type="hidden" name="select" value="<?=$select?>">

<?php
echo_sub_header("", "更新");

$where = "field='$field'";
$where = limitCompany($where, $table);

$sql = "select * from $table where $where order by sort,ID";
$rs = db_exec($conn, $sql);

switch ($field) {
case "customer_group":
	$name = "顧客グループ";
	break;
default:
	return_error();
	break;
}

echo_list_frame($name);
?>
<thead>
<tr>
<th>コード</th>
<th>表示順</th>
<th>名称</th>
</tr>
</thead>

<tbody>
<?php
init_list_format();
while (db_fetch_row($rs)) {
	init_list_line($rs);
	$c = db_result($rs, "company");
	echo "<tr>";
	if ($c==$_SESSION['company']) {
		echo_list_td($rs, "value", "str", "", "echo");
		echo_list_td($rs, "sort", "str");
		echo_list_td($rs, "name", "name");
	} else {
		echo_list_td($rs, "value", "str", "", "echo");
		echo_list_td($rs, "sort", "str", "", "echo");
		echo_list_td($rs, "name", "str", "", "echo");
	}
	echo "</tr>";
}
echo '</tbody>';
echo_list_frame_end();
db_free($rs);
?>

</form>

<?php require 'footer.php' ?>
