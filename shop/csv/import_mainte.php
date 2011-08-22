<?php
include 'header.php';
include '../../include/config_fields.php';

$id = $_REQUEST['id'];
$ssid = $uid;
$mode = $_REQUEST['mode'];
$add = $_REQUEST['add'];
$fname = $_FILES['csvfile']['name'];
$err = $_FILES['csvfile']['error'];
$tmp = $_FILES['csvfile']['tmp_name'];

$table = "mainte_d";

if ( $mode == 'ins' ) {
	switch ($err) {
	case UPLOAD_ERR_OK:
		if ($id=='') {
			echo "<p>IDが不正です。</p>";
		} else {
		//既存データを削除
			$where = "hid=$id";
			$where .= " and ssid=$ssid";
			if ($add=='') {
				$sql = "DELETE FROM $table WHERE $where";
				$rs = db_exec($conn, $sql);
			}
			$no = DLookUp("max(sort)", $table, $where);
			if ($no==false) $no=0;
			// CSVファイルを開く
//			$fp = fopen($tmp, "r") or die();
//文字コードの変換
			$buf = mb_convert_encoding(file_get_contents($tmp), $client_charset, $csv_charset);
			if ($debug) echo $buf;
			$fp = tmpfile();
			fwrite($fp, $buf);
			rewind($fp);
			// 一行目を読んでヘッダにする
			$header = fgetcsv_reg($fp, 1024);

			$sql = "select * from $table limit 1";
			$rs = db_exec($conn, $sql);
			$fields_type = db_fields_type($rs);
			db_free($rs);

			$fields[0] = "hid";
			$types[0] = "int";
			$fields[1] = "sort";
			$types[1] = "int";
			$fields[2] = "ssid";
			$types[2] = "int";
			$i=3;
			foreach ($header as $f_jp) {
				$f = jp2db($f_jp);
				if ($f=='ID') continue;
				if ($f=='hid') continue;
				if ($f=='sort') continue;
				if ($f=='ssid') continue;
				if ($f=='company') continue;
				if ($fields_type[$f]=='') continue;
				$fields[$i] = $f;
				$types[$i] = $fields_type[$f];
				$i++;
			}
			$values[0] = $id;
			$values[2] = $ssid;
			while(($data = fgetcsv_reg($fp, 1024))) {
				$no++;
				$values[1] = $no;
				$i=3;
				foreach ($data as $k => $v) {
					$f_jp = $header[$k];
					$f = jp2db($f_jp);
					if ($f=='ID') continue;
					if ($f=='hid') continue;
					if ($f=='sort') continue;
					if ($f=='ssid') continue;
					if ($f=='company') continue;
					if ($fields_type[$f]=='') continue;
					$values[$i] = $v;
					$i++;
				}
				db_insert($conn, $table, $fields, $types, $values);
			}
			// ファイルを閉じる
			fclose($fp);
		}
		$errorMessage = '整備明細を上書き登録しました。';
		if ($add<>'') $errorMessage = '整備明細を追加登録しました。';
		break;
	case UPLOAD_ERR_INI_SIZE:
		$errorMessage = '値: 1; アップロードされたファイルは、php.ini の upload_max_filesize ディレクティブの値を超えています。';
		break;
	case UPLOAD_ERR_FORM_SIZE:
		$errorMessage =  '値: 2; アップロードされたファイルは、HTMLフォームで指定された MAX_FILE_SIZE を超えています。';
		break;
	case UPLOAD_ERR_PARTIAL:
		$errorMessage =  '値: 3; アップロードされたファイルは一部のみしかアップロードされていません。';
		break;
	case UPLOAD_ERR_NO_FILE:
		$errorMessage =  '値: 4; ファイルはアップロードされませんでした。';
		break;
	case UPLOAD_ERR_NO_TMP_DIR:
		$errorMessage =  '値: 6; テンポラリフォルダがありません。PHP 4.3.10 と PHP 5.0.3 で導入されました。';
		break;
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title>整備明細CSV登録フォーム</title>
<script type="text/javascript">

	function reloadWindows() {
		window.opener.location.reload();
	}

</script>
</head>

<?php
if ( $mode == 'ins' ) {
	echo "<body onLoad='reloadWindows()'>";
} else {
	echo "<body>";
}
?>

<center>

<table border="0" cellspacing="0" cellpadding="1" width="400">
<tr><td><b>整備明細CSV登録</b></td></tr>
<tr><td bgcolor="aaaaaa"></td></tr>
</table>
<br>

<table border="0" cellspacing="1" cellpadding="2" bgcolor="#999999" width="360">
<form enctype="multipart/form-data" action="?" method="POST">
<tr><td colspan="2" bgcolor="#aaaaff">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr><td><b><font size="2">CSVファイルの選択</font></b></td><td align="right"><img src="" width="1" height="22"></td></tr>
</table>
</td></tr>

<tr><td bgcolor="#eeeeff"><input name="csvfile" type="file" size="56" /></td></tr>
<tr><td align="right" bgcolor="#ffffff">
<input type="submit" value="上書き登録">
<input type="submit" name="add" value="追加登録"></td></tr>
<input type="hidden" name="mode" value="ins" />
<input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>">
<input type="hidden" name="ssid" value="<?php echo $_REQUEST['ssid'] ?>">
<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
</form>
</table>
　<b><font size="2" color="#cc0000"><?= $errorMessage ?></font></b>　<br><br>
<a href="javascript:window.close()"><b><font size="2">閉じる</font></b></a>
<br><br><br>
<table border="0" cellspacing="0" cellpadding="2" bgcolor="#aaaaaa" width="400">
<tr><td bgcolor="aaaaaa"></td></tr>
<tr><td align="center" bgcolor="#ffffff"><font size="2">Copyright &reg; YAMAHA BOATING SYSTEM</font></td></tr>
</table>

</center>
</body>
</html>
<?php include 'footer.php';?>