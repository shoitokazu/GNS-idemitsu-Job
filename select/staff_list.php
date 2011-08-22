<?php include 'header.php' ?>

<?php
$target = $_REQUEST['target'];
?>

<h1>スタッフリスト</h1>

<form method="POST" action="" name="form_area">

<?php
set_page_tag("staff_select");
echo_search_tr("事業所", "company", "select2");
?>
<input type="submit" value="表示">
</form>

<table id="staff" border="0" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th class="name" colspan="2">名前</th>
        </tr>
    </thead>
    <tbody>
<?php
$where = "auth=1";
add_where('company', 'int');

$sql = "select ID,name from account where $where order by sort";
$rs = db_exec($conn, $sql);
while (db_fetch_row($rs)) {
	if ($class == "odd") {
		$class = "even";
	} else {
		$class = "odd";
	}
	$id = db_result($rs, "ID");
	$v = db_result($rs, "name");
?>
        <tr class="<?=$class?>">
            <th><?=$id?></th>
            <td><?=$v?></td>
            <td class="button"><input name="button" type="button" onclick="btn('<?=$v?>')" value="選択"></td>
        </tr>
<?php
}
?>
    </tbody>
</table>


<p class="close"><a href="javascript:window.close();">[ 閉じる ]</a></p>

<script language="javascript">
function btn(v) {
	window.opener.document.getElementById('<?=$target?>').value = v;
//	window.opener.document.form1.submit();
	window.close()
}
</script>

<?php include 'footer.php'; ?>
