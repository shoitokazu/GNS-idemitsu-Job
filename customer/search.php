<?php require 'header.php';?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="list.php" method="get">
<input type="hidden" name="select" value="<?=$select?>">

<?php
set_page_tag("customer");
echo_search_frame();
?>

<tr><td colspan=2>
表示順
<?php
echo_sort_frame();
echo_sort_option("顧客名", "customer.cname");
echo_sort_option("顧客コード", "customer.ccode");
echo_sort_frame_end();
?>
<input type="submit" value="検索">
<input type="reset" value="元に戻す">
<input type="button" value="クリア" onClick="search_clear()">
</td></tr>
<tr><td><br></td></tr>

<?php
echo_search_tr("顧客コード", "ccode", "str");
/*
echo_search_tr("住所", "addr1", "str");
*/
echo_search_tr("名称など", "free2", "str");
echo '<tr><td></td><td>個人住所の顧客名・会社住所の顧客名・フリガナなどに入力している情報から検索します</td></tr>';
echo_search_tr("住所など", "free", "str");
echo '<tr><td></td><td>住所・電話・メールなどに入力している情報から検索します</td></tr>';
echo_search_tr("顧客区分", "customer_type", "select2");
echo_search_tr("状態", "customer_kind", "select1");
//echo_search_tr("誕生日", "birthday", "date");
//echo_search_tr("役職名", "post", "str");
//echo_search_tr("備考", "remarks", "str");

if ($page_mode=="shop") {
	echo_search_tr("営業担当者", "wstaff", "str");
	echo_search_tr("サービス担当者", "scstaff", "str");
} else {
	echo_search_tr("営業担当者", "wstaff", "staff");
	echo_search_tr("サービス担当者", "scstaff", "staff");
}

/*
//未選択も検索できるタイプ
echo_search_tr("ランク", "f1", "bool");
echo_search_tr("営業", "f2", "bool");
echo_search_tr("カウンター", "f3", "bool");
echo_search_tr("サービス", "f4", "bool");
*/
//未選択は選択できないタイプ
//echo_search_tr("ランク", "f1", "checkbox");
//echo_search_tr("営業", "f2", "checkbox");
//echo_search_tr("カウンター", "f3", "checkbox");
//echo_search_tr("サービス", "f4", "checkbox");


////// 次回免許更新（冨山_追加：2011/06/21） ////// ここから
echo_search_tr("免許更新日", "next_date", "date");
echo_search_tr("免許種別", "lclass", "str");
////// ここまで 

$list = DListUp("value,name", "choices", "field='customer_flag'", "sort", true);
if (is_array($list)) {
	echo '<tr><th>【担当エリア】</th><td>※○：選択　×：選択以外　－：全て';
	echo '</td></tr>';
	foreach ($list as $v) {
		if ($v[1]<>"") echo_search_tr($v[1], "group".$v[0], "bool");
	}
}

if ($page_mode<>"shop") {

/*
$list = DListUp("value,name", "choices", "field='customer_group'", "sort", true);
if (is_array($list)) {
	echo '<tr><th>【顧客グループ】</th><td>';
	echo '</td></tr>';
	foreach ($list as $v) {
		if ($v[1]<>"") echo_search_tr($v[1], "g".$v[0], "bool");
	}
}
*/

//echo '<tr><th>【'.$uname.'】</th><td>※○：担当　×：担当以外　－：全て';
echo '<tr><th>【選択者抽出】</th><td>※○：選択　×：選択以外　－：全て';
echo_search_tr("チェック", "selected", "bool");

}

echo_search_frame_end();
?>

<p>表示順
<?php
echo_sort_frame();
echo_sort_option("顧客名", "customer.cname");
echo_sort_option("顧客コード", "customer.ccode");
echo_sort_frame_end();
?>

<input type="submit" value="検索">
<input type="reset" value="元に戻す">

<?php echo_button_search_clear(); ?>

</p>

</form>

<?php require 'footer.php' ?>
