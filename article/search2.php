<?php require 'header.php';?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="list2.php" method="get">

<?php
set_page_tag("article2");
echo_search_frame();
?>
<tr><td colspan=2>
表示順
<?php
echo_sort_frame();
echo_sort_option("点検日付", "atask_date");
echo_sort_frame_end();
?>
<input type="submit" value="検索">
<input type="reset" value="元に戻す">
<input type="button" value="クリア" onClick="search_clear()">
</td></tr>
<tr><td><br></td></tr>

<?php
$y = date('Y');
$m = date('n');
$d = date('j');
//echo_search_tr("船検月", "atask_date", "month", date('Y/n/j', mktime(0,0,0,$m+3,$d,$y)));
echo_search_tr("点検日付", "atask_date", "date");
echo_search_tr("検査内容", "atask_name", "select1");
echo_search_tr("ＤＭ発送日付", "atask1", "date");
echo_search_tr("返事確認", "atask2", "select1");
echo_search_tr("返事確認日", "atask3", "date");
echo_search_tr("実施日", "atask4", "date");
echo_search_tr("備考", "atask5", "str");

echo_search_frame_end();
?>

<p>表示順
<?php
echo_sort_frame();
echo_sort_option("点検日付", "atask_date");
echo_sort_frame_end();
?>

<input type="submit" value="検索">
<input type="reset" value="元に戻す">

<?php echo_button_search_clear(); ?>

</p>

</form>

<?php require 'footer.php' ?>
