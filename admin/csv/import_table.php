<?php
set_time_limit(300);
$body_onload_func = "history.go(-1);";
require "../include/header.php";

$table = $_REQUEST['table'];
switch ($table) {
case 'customer':
	break;
default:
	return_error('このテーブルにはインポートできません。');
}
$keyField = $_REQUEST['keyField'];
$keyType = $_REQUEST['keyType'];
if ($keyField=="") return_error('キーフィールドが不明');

$office = $_REQUEST['office'];

$err = $_FILES['userfile']['error'];
switch ($err) {
case UPLOAD_ERR_INI_SIZE:
	return_error('値: 1; アップロードされたファイルは、php.ini の upload_max_filesize ディレクティブの値を超えています。');
case UPLOAD_ERR_FORM_SIZE:
	return_error('値: 2; アップロードされたファイルは、HTMLフォームで指定された MAX_FILE_SIZE を超えています。');
case UPLOAD_ERR_PARTIAL:
	return_error('値: 3; アップロードされたファイルは一部のみしかアップロードされていません。');
case UPLOAD_ERR_NO_FILE:
	return_error('値: 4; ファイルはアップロードされませんでした。');
case UPLOAD_ERR_NO_TMP_DIR:
	return_error('値: 6; テンポラリフォルダがありません。PHP 4.3.10 と PHP 5.0.3 で導入されました。');
case UPLOAD_ERR_OK:
	break;
default:
	return_error();
}

$temp_file = $_FILES['userfile']['tmp_name'];

$handle = fopen($temp_file, "r");
$header = fgetcsv($handle, 1000, ",");

$sql = "select * from $table limit 1";
$rs = db_exec($conn, $sql);
$fields_type = db_fields_type($rs);
foreach ($fields_type as $fn) echo $fn;
db_free($rs);

$key_no = null;
$i=0;
foreach ($header as $f) {
	if ($f=='ID') continue;
	if ($f=='company') continue;
	if ($f=='office') continue;
	if ($fields_type[$f]=='') continue;
	if ($f==$keyField) $key_no = $i;
	$fields[$i] = $f;
	$types[$i] = $fields_type[$f];
	$i++;
}
if (!is_array($fields)) return_error("取り込むフィールドが存在しません。");
if ($office!="") {
	$fields[$i] = "office";
	$types [$i] = "int";
	$values[$i] = $office;
}
while (($data = fgetcsv_reg($handle, 1000, ",")) !== FALSE) {
	$i=0;
	foreach ($data as $k => $v) {
		$f = $header[$k];
		if ($f=='ID') continue;
		if ($f=='company') continue;
		if ($f=='office') continue;
		if ($fields_type[$f]=='') continue;
		$values[$i] = mb_convert_encoding($v, $html_charset, $csv_charset);
//		$values[$i] = $v;
		if ($f=='making_date') {
			if ($values[$i]=="") $values[$i]=Date('Y/m/d');
		}
		$i++;
	}
	if ($key_no===null) {
	} else {
		$where = $keyField."=".db_value($values[$key_no], $keyType);
		if ($office!='') $where .= " and office=".db_value($office, "int");
		db_delete($conn, $table, $where, true);
	}
	$id = db_insert($conn, $table, $fields, $types, $values);
	setCode($table, $id, $keyField);
}
fclose($handle);

unlink($temp_file)
?> 

<?php require "../include/footer.php"; ?>
