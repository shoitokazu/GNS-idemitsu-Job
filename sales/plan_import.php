<?php
include 'header.php';
if ($auth1<>2) return_error("アクセス権がありません。");
?>

<p><a href="#" onclick="history.go(-2)">戻る</a></p>

<?php
$fn = $_FILES['csvfile']['name'];
$err = $_FILES['csvfile']['error'];
$tmp = $_FILES['csvfile']['tmp_name'];

switch ($err) {
   case UPLOAD_ERR_OK:

// CSVファイルを開く
	$buf = mb_convert_encoding(file_get_contents($tmp), $client_charset, $csv_charset);
	$fp = tmpfile();
	fwrite($fp, $buf);
	rewind($fp); 
//	$fp = fopen($tmp, "r") or die();
// 一行目を読んで計画案件を作る
	$header = fgetcsv_reg($fp, 1024);
	if ($header[0]<>'エリア') {
		echo 'test ver<br>';
		echo $header[0]." <- これがエリアでないとエラー<br>";
		return_error('<p>売上計画表ではありません。</p>');
	}
	$area = $header[1];
	for ($i=2; $i<count($header); $i++) {
		if ($header[$i]<>"") {
			$date = $header[$i];
//同じ計画があれば削除
			$where = " where sales_state=5 and due_date='$date' and area='$area'";
			$sql = "delete from sales_d where hid in (select ID from sales_h $where )";
			$rs = db_exec($conn, $sql);
			$sql = "delete from sales_h $where";
			$rs = db_exec($conn, $sql);
//計画案件を作成
			$now = date('Y/m/d');
			$fields[0] = "sales_state";
			$types [0] = "int";
			$values[0] = 5;
			$fields[1] = "due_date";
			$types [1] = "date";
			$values[1] = $date;
			$fields[2] = "area";
			$types [2] = "str";
			$values[2] = $area;
			$fields[3] = "making_date";
			$types [3] = "date";
			$values[3] = date('Y/m/d');
			$table = "sales_h";
			$id = db_insert($conn, $table, $fields, $types, $values);
			setCode($table, $id, "scode");
			$wid[$i] = $id;
		}
	}
//一行読み飛ばし
	$line = fgetcsv_reg($fp, 1024);
// 以降の行を読んでSQL分を作成
	$sql_head = "insert into sales_d (hid,sales_category,num,price,cost) values ";
	while(($line = fgetcsv($fp, 1024))) {
		$code = $line[0];
		if ($line[0]=="") continue;
		for ($i=2;$i<count($line);$i++) {
			if ($wid[$i]<>'') {
				$n = str_replace(',', '', $line[$i]);
				switch ($n) {
				case '':
				case 0:
//					$num = 'null';
					$num = 0;
					break;
				case 1:
				default:
					$num = 1;
					break;
				}
				$gaku = str_replace(',', '', $line[$i+1]);
				if ($gaku=='') $gaku=0;
				$gen = $gaku - str_replace(',', '', $line[$i+2]);
				if ($gen=='') $gen=0;
//追加案2009.1.22
				if (($gaku!=0 or $gen!=0) and $num==0) $num=1; 
				$sql = $sql_head."($wid[$i],$code,$num,$gaku,$gen)";
				$rs = db_exec($conn, $sql);
				if ($n >= 2) {
					$num = $n - 1;
//					$sql = $sql_head."($wid[$i],$code,$num,null,null)";
					$sql = $sql_head."($wid[$i],$code,$num,0,0)";
					$rs = db_exec($conn, $sql);
				}
			}
		}
	}
// ファイルを閉じる
	fclose($fp);
	echo '<p>計画表を取り込みました。</p>';
include 'footer.php';
exit();

	break;

   case UPLOAD_ERR_INI_SIZE:
	echo '<p>値: 1; アップロードされたファイルは、php.ini の upload_max_filesize ディレクティブの値を超えています。</p>';
	break;

   case UPLOAD_ERR_FORM_SIZE:
	echo '<p>値: 2; アップロードされたファイルは、HTMLフォームで指定された MAX_FILE_SIZE を超えています。</p>';
	break;

   case UPLOAD_ERR_PARTIAL:
	echo '<p>値: 3; アップロードされたファイルは一部のみしかアップロードされていません。</p>';
	break;

   case UPLOAD_ERR_NO_FILE:
	echo '<p>値: 4; ファイルはアップロードされませんでした。</p>';
	break;

   case UPLOAD_ERR_NO_TMP_DIR:
	echo '<p>値: 6; テンポラリフォルダがありません。PHP 4.3.10 と PHP 5.0.3 で導入されました。</p>';
	break;
}


echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
   echo "File is valid, and was successfully uploaded.\n";
} else {
   echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";

?>

<?php include 'footer.php'; ?>
