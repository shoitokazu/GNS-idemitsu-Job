<?php $body_onload_func = "history.go(-2)"; ?>
<?php include 'header.php'; ?>

<?php
$table = $_REQUEST['table'];
$id = $_REQUEST['id'];
$fields = $_REQUEST['fields'];
$types = $_REQUEST['types'];
$values = $_REQUEST['values'];
if ($id=='') {
	$i=0;
	$auto_code = false;
	foreach ($fields as $f) {
		if ($f=='code') {
			if ($values[$i]=='') $auto_code = true;
		}
		$i++;
	}
	$id = db_insert($conn, $table, $fields, $types, $values);
	if ($auto_code) {
		$sql = "update $table set code='a$id' where ID=$id";
		db_exec($conn, $sql);
	}
	echo "新規追加しました。<br>";
} else {
	db_update($conn, $table, $fields, $types, $values, "ID=$id");
	echo "更新しました。<br>";
}
?>

<?php include 'footer.php'; ?>
