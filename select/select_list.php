<?php require 'header.php'; ?>

<?php
$table = $_REQUEST['table'];
if ($table=='') $table = "choices";
$target = $_REQUEST['target'];
if ($target=='') $target = "form1.text1";
$field = $_REQUEST['field'];
if ($field=='') $field = "value";
$where = $_REQUEST['where'];
if ($where=='') $where = "field='$target'";

$sql = "select $field from $table where $where order by sort";
$rs = db_exec($conn, $sql);
?>

<h1>値の選択</h1>

<table class="select">

<thead>
<tr>
<th>値</th>
<th>選択</th>
</tr>
</thead>

<tbody>
<?php
while (db_fetch_row($rs)) {
	$v = db_result($rs, $field);
?>

<tr>
<td><?=$v?></td>
<td><input name="button" type="button" onclick="btn('<?=$v?>')" value="選択"></td>
</tr>

<?php
}
?>
</tbody>

</table>

<p><a href="javascript:window.close();">[ 閉じる ]</a></p>

<script language="javascript">
function btn(v) {
	window.opener.document.getElementById('<?=$target?>').value = v;
//	window.opener.document.form1.submit();
	window.close()
}
</script>

<?php require 'footer.php'; ?>
