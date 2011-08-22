<?php include 'header.php' ?>

<?php
$mid = $_REQUEST['mid'];
?>

<script language="javascript">
function checkAll(objAll) {
    // 全てのチェックボックス選択
    for ( var i = 0; i < document.form1.length; i++ ) {
        document.form1[i].checked = objAll.checked;
    }
}
</script>

<br><br>
<table border="0" cellspacing="1" cellpadding="" bgcolor="#ffffff" width="320">
<tr><td align="left"><b><font size="4">サービスショップリスト</font></b></td></tr>
</table>

<table border="0" cellspacing="1" cellpadding="" bgcolor="#ffffff" width="320">
<form action="mshop_select.php?mid=<?=$mid?>" name="form1" method="POST">
<tr><td align="right"><input type="submit" value="決定"></td></tr>
</table>
<table border="0" cellspacing="1" cellpadding="2" bgcolor="#999999" width="320">
<tr>
<!--<td bgcolor="#eeeeee"><b><font size="2">サービスショップID</font></b></td>-->
<td bgcolor="#eeeeee"><b><font size="2">サービスショップ名</font></b></td>
<td bgcolor="#eeeeee"><input type="checkbox" onClick="checkAll(this);"></td>
</tr>
<?php
	$where = "auth=1";
	$query = "SELECT ssid AS ready_id FROM mainte_shop WHERE mid=$mid";
	$table = "sshop";
	$table = "($table) left join ($query) as A on sshop.ID=A.ready_id";
	$field = "sshop.ID, sshop.name,ready_id";
	$order = "sshop.ID";
	$sql = "SELECT $field FROM $table";
	if ($where<>"") $sql .= " WHERE $where";
	if ($order<>"") $sql .= " ORDER BY $order";
	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
		$id = db_result($rs, 'ID');
		$checked = db_result($rs, 'ready_id');
		$name = db_result($rs, 'name');
		if ($class == "odd") {
			$class = "even";
		} else {
			$class = "odd";
		}
?>
<tr>
<!--<td bgcolor="#ffffff"><?=$id?></td>-->
<td bgcolor="#ffffff"><font size="2"><?=$name?></font></td>
<td bgcolor="#ffffff" width="15"><input name="check1[]" type="checkbox" value="<?=$id ?>"<?= $checked==$id ? ' checked' : '' ?>></td>
</tr>
<?php
	}

?>

</table>
<table border="0" cellspacing="1" cellpadding="" bgcolor="#ffffff" width="320">
<tr><td align="right"><input type="submit" value="決定"></td></tr>
</form>
</table>
<br>
<table border="0" cellspacing="1" cellpadding="" bgcolor="#ffffff" width="320">
<tr><td align="center"><a href="javascript:window.close();">[ 閉じる ]</a></td></tr>
</table>

<?php include 'footer.php' ?>
