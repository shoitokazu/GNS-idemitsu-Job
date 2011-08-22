<?php
require 'header.php';
?>
<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="list_shop.php" method="get">

<?php
set_page_tag("mainte_shop");
echo_search_frame();
?>

<tr><td colspan=2>
表示順
<?php
echo_sort_frame();
echo_sort_option("作成日", "making_date desc");
echo_sort_frame_end();
?>
<input type="submit" value="検索">
<input type="reset" value="元に戻す">
<input type="button" value="クリア" onClick="search_clear()">
</td></tr>
<tr><td><br></td></tr>

<?php
echo_search_tr("伝票区分", "mainte1", "select1");
echo_search_tr("状態", "mainte_state", "select2");
echo_search_tr("伝票番号", "mcode", "str");
echo_search_tr("作成日", "making_date", "date");

echo_search_tr("顧客名", "cname", "str");
echo_search_tr("船名", "aname", "str");
echo_search_tr("商品名（艇種名）", "model_name", "str");
echo_search_tr("エンジン名", "engine_name", "str");

echo_search_tr("確認日", "shop1", "date");
echo_search_tr("完了日", "shop2", "date");
echo_search_tr("売上日", "shop3", "date");

echo_search_frame_end();
?>

<p>表示順
<?php
echo_sort_frame();
//echo_sort_option("伝票番号", "mcode");
echo_sort_option("作成日", "making_date desc");
echo_sort_frame_end();
?>

<input type="submit" value="検索">
<input type="reset" value="元に戻す">

<?php echo_button_search_clear(); ?>

</p>

</form>

<?php require 'footer.php' ?>
