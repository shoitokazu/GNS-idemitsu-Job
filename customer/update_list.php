<?php
$body_onload_func = "history.go(-1);";
include 'header.php';
?>

<?php
$table = "list_select";
$f[0] = "table";
$t[0] = "str";
$v[0] = "customer";
$f[1] = "rid";
$t[1] = "int";
//$v[1] = $id;
$f[2] = "uid";
$t[2] = "int";
$v[2] = $uid;
$f[3] = "selected";
$t[3] = "bool";
//$v[3] = true;
read_list_request();
if (is_array($keys)) {
	foreach ($keys as $key => $id) {
		$values = $list[$key];
		if ($id!='') {
			$where = "`table`='customer' and rid=$id and uid=$uid";
			$sql = "delete from $table where $where";
			db_exec($conn, $sql);
			$v[1] = $id;
			$v[3] = $values[$fno["selected"]];
			db_insert($conn, $table, $f, $t, $v);
		}
	}
}
?>

<?php include 'footer.php'; ?>
