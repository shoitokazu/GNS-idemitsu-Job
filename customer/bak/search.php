<?php require 'header.php';?>

<script src="../common/selectWindow.js" language="JavaScript"></script>

<form action="list.php" method="get">
<input type="hidden" name="select" value="<?=$select?>">

<?php
set_page_tag("customer");
echo_search_frame();
echo_search_tr("フリーワード", "free", "str");
echo '<tr><td></td><td>コード・名前・電話・住所などに入力している情報から検索します</td></tr>';
/*
echo_search_tr("顧客コード", "ccode", "str");
echo_search_tr("ふりがな", "kana", "str");
echo_search_tr("顧客名", "cname", "str");
echo_search_tr("住所", "addr1", "str");
*/
echo_search_tr("顧客区分", "customer_type", "select2");
echo_search_tr("状態", "customer_kind", "select1");
echo_search_tr("誕生日", "birthday", "date");
echo_search_tr("役職名", "post", "str");
echo_search_tr("備考", "remarks", "str");
/*
//未選択も検索できるタイプ
echo_search_tr("ランク", "f1", "bool");
echo_search_tr("営業", "f2", "bool");
echo_search_tr("カウンター", "f3", "bool");
echo_search_tr("サービス", "f4", "bool");
*/
//未選択は選択できないタイプ
echo_search_tr("ランク", "f1", "checkbox");
echo_search_tr("営業", "f2", "checkbox");
echo_search_tr("カウンター", "f3", "checkbox");
echo_search_tr("サービス", "f4", "checkbox");

$list = DListUp("value,name", "choices", "field='customer_flag'", "sort");
if (is_array($list)) {
	echo '<tr><th>【担当エリア】</th><td>※○：選択　×：選択以外　－：全て';
	echo '</td></tr>';
	foreach ($list as $v) {
		if ($v[1]<>"") echo_search_tr($v[1], "group".$v[0], "bool");
	}
}

$list = DListUp("value,name", "choices", "field='customer_group'", "sort");
if (is_array($list)) {
	echo '<tr><th>【顧客グループ】</th><td>';
	echo '</td></tr>';
	foreach ($list as $v) {
		if ($v[1]<>"") echo_search_tr($v[1], "g".$v[0], "bool");
	}
}

echo '<tr><th>【担当者チェック】</th><td>';
//echo_search_tr("個人選択", "selected", "checkbox", "1");
echo_search_tr("チェック", "selected", "bool");
echo_search_frame_end();
?>

<p>表示順
<?php
echo_sort_frame();
echo_sort_option("顧客コード", "ccode");
echo_sort_option("顧客名", "cname");
echo_sort_frame_end();
?>

<input type="submit" value="検索">
<input type="reset" value="元に戻す">

<?php echo_button_search_clear(); ?>

</p>

</form>

<?php require 'footer.php' ?>
