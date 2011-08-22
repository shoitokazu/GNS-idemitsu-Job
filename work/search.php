<?php
require 'header.php';
?>
<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="list.php" method="get">
<input type="hidden" name="select" value="<?=$select?>">

<?php
set_page_tag("work");
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
echo_search_tr("案件番号", "wcode", "str");
echo_search_tr("作成日", "making_date", "date");
echo_search_tr("状態", "work_state", "select2");
//echo_search_tr("事業所", "work_h.company", "table", $company);
echo_search_tr("事業所", "work_h.company", "select2", $company);
//echo_search_tr("担当者", "uid", "account");
echo_search_tr("担当者", "wstaff", "staff");
echo_search_tr("顧客名", "cname", "str");
echo_search_tr("顧客コード", "ccode", "customer");
echo_search_frame_end();
?>

<p>表示順
<?php
echo_sort_frame();
//echo_sort_option("伝票番号", "wcode");
echo_sort_option("作成日", "making_date desc");
echo_sort_frame_end();
?>

<input type="submit" value="検索">
<input type="reset" value="元に戻す">

<?php echo_button_search_clear(); ?>

</p>

</form>

<?php require 'footer.php' ?>
