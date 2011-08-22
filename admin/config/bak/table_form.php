<?php include 'header.php'; ?>

<?php
$id = $_REQUEST['id'];
$table = $_REQUEST['table'];
$copy = $_REQUEST['move'];
if ($copy=='on') $copy=true;
if ($id=='') {
	$where = "ID=0";
} else {
	$where = "ID=$id";
}
$order = "ID";
$sql = "select * from $table where $where order by $order";
$rs = db_exec($conn, $sql);
?>
<form action="table_update.php?table=<?=$table?>" method="POST">
<table>
<?php
$fields = db_fields_name($rs);
$types = db_fields_type($rs);
db_fetch_row($rs);
//if (db_fetch_row($rs)) {
	foreach ($fields as $f) {
		echo "<tr><td>$f</td><td>$rn";
		echo_form($f, $types[$f], db_result($rs, $f));
		echo "</td></tr>$rn";
	}
//}
db_free($rs);

function echo_form($field, $type, $value) {
	global $rn;
	global $id;
	global $copy;
	if ($field=='ID') {
		if ($id=='') {
			echo "新規$rn";
		} elseif ($copy) {
			echo "<input type='hidden' name='move' value='on'>$rn";
			echo "copy($value)$rn";
		} else {
			echo "<input type='hidden' name='id' value='$value'>$rn";
			echo "$value$rn";
		}
		return;
	}
	switch ($type) {
	default:
		echo "<input type='hidden' name='fields[]' value='$field'>$rn";
		echo "<input type='hidden' name='types[]' value='$type'>$rn";
		echo "<input type='text' name='values[]' value='$value' size=100>$rn";
		break;
	case 'int':
		echo "<input type='hidden' name='fields[]' value='$field'>$rn";
		echo "<input type='hidden' name='types[]' value='$type'>$rn";
		echo "<input type='text' name='values[]' value='$value' size=10>$rn";
		break;
	case 'string':
		echo "<input type='hidden' name='fields[]' value='$field'>$rn";
		echo "<input type='hidden' name='types[]' value='$type'>$rn";
		echo "<input type='text' name='values[]' value='$value' size=50>$rn";
		break;
	case 'timestamp':
		echo "$value$rn";
		break;
	case 'date':
		echo "<input type='hidden' name='fields[]' value='$field'>$rn";
		echo "<input type='hidden' name='types[]' value='$type'>$rn";
		echo "<input type='text' name='values[]' value='$value' size=15>$rn";
		break;
	case 'blob':
		echo "<input type='hidden' name='fields[]' value='$field'>$rn";
		echo "<input type='hidden' name='types[]' value='$type'>$rn";
		echo "<textarea name='values[]'>$rn";
		echo $value;
		echo "</textarea>$rn";
		break;
	}
}
?>
</table>
<input type="submit" value="<?=($id=='' ? '新規追加' : '更新')?>">
</form>
<?php if ($id<>'' and !$copy) { ?>
<p><a href="table_delete.php?table=<?=$table?>&id=<?=$id?>" onClick="return confirm('削除しますか？');">削除</a></p>
<?php } ?>

<?php include 'footer.php'; ?>
