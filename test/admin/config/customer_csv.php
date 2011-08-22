<?php require 'header.php';?>

<p>顧客マスターのＣＳＶ操作</p>

<form action="../csv/import_table.php" method="post" enctype="multipart/form-data" name="form1">
<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
<input type="hidden" name="table" value="customer">
<input type="hidden" name="keyField" value="ccode">
<input type="hidden" name="keyType" value="str">

<?php
echo_form_frame("CSV出力");
?>
<tr><th>CSV出力</th><td><input type="button" value="実行" onClick="location.href='../csv/export_table.php?table=customer'"></td></tr>
<?php
echo_form_frame_end();

echo_form_frame("CSV入力");
?>
<tr><th>ファイル名</th><td><input name="userfile" type="file" size="50" /></td></tr>
<tr><th>CSV入力</th><td><input type="submit" name="input" value="実行" onClick="return confirm('顧客コードが同一のものは上書きされます。よろしいですか？');"></td></tr>
<tr><th>キー</th><td>ccode : 同一のものは上書きされます。未指定の場合は、自動設定。</td></tr>
<?php
echo_form_frame_end();
?>

</form>

<?php require 'footer.php' ?>
