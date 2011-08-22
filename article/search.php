<?php require 'header.php';?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="list.php" method="get">
<input type="hidden" name="select" value="<?=$select?>">

<?php
$type = $_REQUEST['type'];

set_page_tag("article");
echo_search_frame();
?>
<tr><td colspan=2>
表示順
<?php
echo_sort_frame();
echo_sort_option("商品コード", "model_code");
echo_sort_frame_end();
?>
<input type="submit" value="検索">
<input type="reset" value="元に戻す">
<input type="button" value="クリア" onClick="search_clear()">
</td></tr>
<tr><td><br></td></tr>

<tr><th>物件種別</th><td>
<?php
if ($type===null) {
	echo 'すべて';
} else {
	echo '<a href="search.php?'.$select_arg.'">すべて</a>';
}
if ($type==="0") {
	echo ' / 船';
	echo '<input type="hidden" name="atype" value="0">';
} else {
	echo ' / <a href="search.php?type=0&'.$select_arg.'">船</a>';
}
if ($type==1) {
	echo ' / エンジン';
	echo '<input type="hidden" name="atype" value="1">';
} else {
	echo ' / <a href="search.php?type=1&'.$select_arg.'">エンジン</a>';
}
?>
</td></tr>
<?php
echo '<tr><td><br></td></tr>';
//echo '<tr><td>所有者情報</td></tr>';
echo_search_tr("顧客コード", "ccode", "customer");
echo_search_tr("顧客名", "cname", "str");

echo '<tr><td><br></td></tr>';
//echo '<tr><td>船体情報</td></tr>';
echo_search_tr("物件コード", "acode", "str");
echo_search_tr("物件名(船名)", "aname", "str");
echo_search_tr("製造番号", "article16", "str");

echo_search_tr("商品名(艇種名)", "model_name", "str");
echo_search_tr("売上区分(商品区分)", "sales_category", "select2");

if ($type<>1) {
	$y = date('Y');
	$m = date('n');
	$d = date('j');
	echo_search_tr("船舶登録日", "article3", "date");
	echo_search_tr("初回船検日", "article19", "date");
	echo_search_tr("次回船検月", "article15", "month", date('Y/n/j', mktime(0,0,0,$m+3,$d,$y)));
	echo_search_tr("船舶検査番号", "article4", "str");
	echo_search_tr("メーカー区分", "article8", "str");
	echo_search_tr("保管場所", "dock", "str");
	echo_search_tr("航行区域", "article7", "str");
	echo_search_tr("在庫区分", "article17", "select1");
	echo_search_tr("在庫エリア", "article18", "select1");
}

echo '<tr><td><br></td></tr>';
//echo '<tr><td>エンジン情報</td></tr>';
echo_search_tr("エンジン名", "ename", "str");
echo_search_tr("エンジン番号", "ecode", "str");

echo_search_frame_end();
?>

<p>表示順
<?php
echo_sort_frame();
echo_sort_option("商品コード", "model_code");
echo_sort_frame_end();
?>

<input type="submit" value="検索">
<input type="reset" value="元に戻す">

<?php echo_button_search_clear(); ?>

</p>

</form>

<?php require 'footer.php' ?>
