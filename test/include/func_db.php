<?php
$conn = db_conn();
$db_result_row = array();

function db_conn() {
	global $db_host;
	global $db_name;
	global $db_user;
	global $db_pass;
	global $client_charset;

	$link = mysql_connect($db_host, $db_user, $db_pass)
	   or die('Could not connect: ' . mysql_error());
	mysql_select_db($db_name) or die('Could not select database');
	mysql_query("SET character_set_client=$client_charset");
	mysql_query("SET character_set_connection=$client_charset");
	mysql_query("SET character_set_results=$client_charset");
	return $link;
}
function db_exec($conn, $query) {
	global $debug, $rn;
	if ($debug) echo "$query<br>$rn";
	$result = mysql_query($query, $conn) or die('Query failed: ' . mysql_error());
	return $result;
}
function db_fetch_row($result) {
	global $db_result_row;
	if ($result==null) return false;
	$row = mysql_fetch_assoc($result);
	if ($row==false) return false;
	$db_result_row[$result] = $row;
	return true;
}
function db_result($result, $field) {
	global $db_result_row;
	$value = $db_result_row[$result][$field];
	return $value;
}
function db_free($result) {
	global $db_result_row;

	if ($result==null) return false;
	unset($db_result_row[$result]);
	return mysql_free_result($result);
}
function db_is_fetch($result) {
	global $db_result_row;

	return isset($db_result_row[$result]);
}
function db_row($conn, $query) {
	global $debug;
	if ($debug) echo "$query<br>\n";
	$result = mysql_query($query, $conn) or die('Query failed: ' . mysql_error());
	$row = mysql_fetch_row($result);
	mysql_free_result($result);
	if ($row==false) return false;
	return $row;
}
function db_fields_name($result) {
	$n = mysql_num_fields($result);
	for ($i=0; $i<$n; $i++) {
		$f[] = mysql_field_name($result, $i);
	}
	return $f;
}
function db_fields_type($result) {
	$n = mysql_num_fields($result);
	for ($i=0; $i<$n; $i++) {
		$name = mysql_field_name($result, $i);
		$f[$name] = mysql_field_type($result, $i);
	}
	return $f;
}
function db_type($result, $field) {
	$n = mysql_num_fields($result);
	for ($i=0; $i<$n; $i++) {
		if ($field==mysql_field_name($result, $i))
			return mysql_field_type($result, $i);
	}
	return false;
}

function db_insert($conn, $table, $fields, $types, $values, $admin_mode=false) {
	if (!$admin_mode) {
		$set_fields = "company";
		$set_values = db_value($_SESSION['company'], "int");
	}
	for ($i=0; $i<count($types); $i++) {
		if ($set_fields<>'') $set_fields .= ',';
		$set_fields .= '`'.$fields[$i].'`';
		if ($set_values<>'') $set_values .= ',';
		$set_values .= db_value($values[$i], $types[$i]);
	}
	$sql = "insert into `$table` ($set_fields) values ($set_values)";
	db_exec($conn, $sql);
	return mysql_insert_id();
}
function db_update($conn, $table, $fields, $types, $values, $where, $admin_mode=false) {
	for ($i=0; $i<count($types); $i++) {
		if ($sets<>'') $sets .= ',';
		$sets .= '`'.$fields[$i].'`='.db_value($values[$i], $types[$i]);
	}
	if (!$admin_mode) {
		if ($where<>'') $where = "($where) and ";
		$where .= "company=".db_value($_SESSION['company'], "int");
	}
	$sql = "update `$table` set $sets where $where";
	db_exec($conn, $sql);
}
function db_delete($conn, $table, $where, $admin_mode=false) {
	if (!$admin_mode) {
		$where .= " and company=".db_value($_SESSION['company'], "int");
	}
	$sql = "delete from `$table` where $where";
	db_exec($conn, $sql);
}
function db_copy($conn, $table, $id) {
	$where = "ID=$id";
//	$where .= " and company=".db_value($_SESSION['company'], "int");
	$sql = "select * from $table where $where";
	$result = db_exec($conn, $sql);
	$n = mysql_num_fields($result);
	for ($i=0; $i<$n; $i++) {
		$f = mysql_field_name($result, $i);
		if ($f<>'ID') $fields[] = $f;
	}
	db_free($result);
	$field_list = implode(",", $fields);
	$sql = "insert into $table select 0 as ID,$field_list from $table where $where";
	db_exec($conn, $sql);
	return mysql_insert_id();
}
function db_copy_data($conn, $table, $where, $fields="ID", $values="0") {
//	$where .= " and company=".db_value($_SESSION['company'], "int");
	$sql = "select * from $table where $where";
	$result = db_exec($conn, $sql);
	$n = mysql_num_fields($result);
	$except_fields = explode(',', $fields);
	$set_values = explode(',', $values);
	$c = count($except_fields);
	for ($i=0; $i<$n; $i++) {
		$f = mysql_field_name($result, $i);
		$except = false;
		for ($j=0; $j<$c; $j++) {
			if ($f==trim($except_fields[$j])) {
				$except = true;
				$field_array[] = $set_values[$j]." as `".$f."`";
			}
		}
		if (!$except) $field_array[] = "`".$f."`";
	}
	db_free($result);
	$field_list = implode(",", $field_array);
	$sql = "insert into $table select $field_list from $table where $where";
	db_exec($conn, $sql);
	return mysql_insert_id();
}
function strip_mqg($value) {
	if (get_magic_quotes_gpc()) return stripslashes($value);
	return $value;
}
function make_value_string($value, $type) {
	return db_value($value, $type);
}
function db_value($value, $type="str") {
	global $debug;

//	echo $type.'<br>';
	switch ($type) {
	case "str":
	case "string":
	case "pass":
	case "text":
	case "blob":
	case "hidden":
	case "hidstr":
		if ($value=='') {
//			return "null";
			return "''";
		} else {
/*
			$v = strip_mqg($value);
			$v = str_replace("'", "''", $v);
			$v = str_replace("\\", "\\\\", $v);
*/
			$v = $value;
			if (!get_magic_quotes_gpc()) $v = addslashes($v);
			return "'".$v."'";
		}
		break;
	case "bool":
	case "int":
	case "cur":
	case "float":
	case "real":
	case "hidint":
		$value = mb_convert_kana($value, "as");
		if ($value==0) {
			return "0";
		} elseif ($value=='' or $value=='null') {
			return "null";
		} else {
			$r = str_replace(",", "", $value);
			$r = trim($r);
			if (is_numeric($r)) return $r;
			return "null";
		}
		break;
	case "sort":
		$value = mb_convert_kana($value, "as");
		if ($value===0) {
			return "0";
		} elseif ($value==='' or $value==='null') {
			return "null";
		} else {
			$r = str_replace(",", "", $value);
			if (is_numeric($r)) return $r;
			return "null";
		}
		break;
	case "date":
	case "time":
	case "datetime":
	case "hidate":
	case "timestamp":
		if ($value=='') return "null";
		$tmp = strtotime($value);
		if ($debug) echo "strtotime:$tmp<br>";
		if ($tmp=='') {
			return "null";
		} else {
			switch ($type) {
			case "date":
			case "timestamp":
				return "'".date('Y/m/d', $tmp)."'";
			case "time":
				return "'".date('H:i:s', $tmp)."'";
			case "datetime":
				return "'".date('Y/m/d H:i:s', $tmp)."'";
			}
		}
		break;
	case "now":
		return 'NOW()';
	case "func":
	default:
		return $value;
	}
}
/*
function DLookUp($field, $table, $where="", $order="") {
	global $conn;

	$where = limitCompany($where, $table);
	$sql = "select distinct $field from $table";
	if ($where<>'') $sql .= " where $where";
	if ($order<>'') $sql .= " order by $order";
	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
		$list[] = db_result($rs, $field);
	}
	return $list;
}
*/
function DLookUp($field, $table, $where="", $limit=false) {
	global $conn;

	if ($limit) $where = limitCompany($where, $table);
	$sql = "select $field from $table";
	if ($where<>'') $sql .= " where $where";
	$row = db_row($conn, $sql);
	return $row[0];
}
function DLookUp2($field, $table, $where="", $order="") {
	global $conn;

	$sql = "select $field from $table";
	if ($where<>'') $sql .= " where $where";
	if ($order<>'') $sql .= " order by $order";
	$row = db_row($conn, $sql);
	return $row[0];
}
function DListUp($field_list, $table, $where="", $order="", $limit=false) {
	global $conn;

	if ($limit) $where = limitCompany($where, $table);
	$sql = "select distinct $field_list from $table";
	if ($where<>'') $sql .= " where $where";
	if ($order<>'') $sql .= " order by $order";
	$rs = db_exec($conn, $sql);
	while ($v = mysql_fetch_row($rs)) {
		$list[] = $v;
	}
	return $list;
}
function echo_table($rs) {
	echo "<table border=1>";
	$fields = db_fields_name($rs);
	echo '<tr>';
	foreach ($fields as $f) {
		echo "<td>$f</td>";
	}
	echo '</tr>';
	while (db_fetch_row($rs)) {
		echo '<tr>';
		foreach ($fields as $f) {
			echo "<td>".db_result($rs, $f)."</td>";
		}
		echo '</tr>';
	}
	echo "</table>";
}
function db_skip_row($result, $number) {
	//if ($number==0) return;
	if ($number > mysql_num_rows($result) - 1) {
		$number = mysql_num_rows($result) - 1;
		mysql_data_seek($result, $number);
		db_fetch_row($result);
		return;
	}
	mysql_data_seek($result, $number);
}
?>