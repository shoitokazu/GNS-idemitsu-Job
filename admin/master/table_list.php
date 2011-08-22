<?php include 'header.php'; ?>

<?php
$table = $_REQUEST['table'];
$sql = "select * from $table";
$where = $_REQUEST['where'];
if ($where<>'') $sql .= " where $where";
$order = "ID desc";
if ($order<>'') $sql .= " order by $order";
?>
<p>テーブル名：<?=$table?></p>
<p><a href="table_form.php?table=<?=$table?>">新規追加</a></p>
<?php
$rs = db_exec($conn, $sql);
echo "<table border=1>";
$fields = db_fields_name($rs);
$types = db_fields_type($rs);
echo '<tr><td></td>';
foreach ($fields as $f) {
	echo "<td>$f</td>";
}
echo '</tr>';
$p = $_REQUEST['page'];
if ($p=="") $p=0;
$PAGE_SIZE = 100;
for ($i=0; $i<$p*$PAGE_SIZE; $i++) {
	if (!db_fetch_row($rs)) break;
}
while (db_fetch_row($rs)) {
	$i++;
	if ($i>($p+1)*$PAGE_SIZE) break;
	$id = db_result($rs, 'ID');
	echo '<tr>';
	echo "<td><a href='table_form.php?table=$table&id=$id&move=on'>複製</a>";
	echo "/<a href='table_form.php?table=$table&id=$id'>編集</a></td>";
	foreach ($fields as $f) {
		echo "<td>".html_format(db_result($rs, $f), $types[$f])."</td>";
	}
	echo '</tr>';
}
echo "</table>";
db_free($rs);
echo '<p>';
if ($p>0) echo ' <a href="?table='.$table.'&page='.($p-1).'">前</a>';
if ($i>($p+1)*$PAGE_SIZE) echo ' <a href="?table='.$table.'&page='.($p+1).'">次</a>';
echo '</p>';
?>

<?php include 'footer.php'; ?>
