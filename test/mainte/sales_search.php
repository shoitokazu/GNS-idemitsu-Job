<?php require 'header.php'; ?>

<script src="../common/calendar.js" language="JavaScript"></script>

<form action="sales_report.php" method="get">

<?php
set_page_tag("accounting");
echo_search_frame();
//echo_search_tr("伝票タイプ", "mtype", "estimate", 0);
echo_search_tr("サービスセンター", "trans_sc", "select1");
echo_search_tr("振替先", "transfer", "table1");
echo_search_tr("売上確定日", "mainte10", "date");
echo_search_frame_end();
?>

<input type="submit" value="検索">
<input type="reset" value="元に戻す">

<?php echo_button_search_clear(); ?>

</p>

</form>

<?php require 'footer.php'; ?>
