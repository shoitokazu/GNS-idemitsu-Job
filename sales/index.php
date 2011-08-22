<?php require 'header.php';?>

<div class="frame_index">

<p><a href="list.php?clear=1&sales_state=0">見通し</a></p>
<p><a href="list.php?clear=1&sales_state=2">受注</a></p>
<p><a href="search.php">検索</a></p>
<p><a href="addnew.php" onClick="return confirm('新規作成します。よろしいですか？')">新規作成</a></p>

<p>---</p>
<p><a href="sales_category1.php">販売管理表（月別）</a></p>
<p><a href="sales_category2.php">販売管理表（累計）</a></p>
<p><a href="sales_detail.php">販売実績（商品グループ別）</a></p>
<p><a href="sales_staff.php">販売実績（担当者別）</a></p>
<p><a href="sales_customer.php">販売実績（顧客別）</a></p>

<?php if ($auth1==2) { ?>

<p>---</p>
<p><a href="plan_form.php">販売計画のインポート</a></p>

<?php } ?>

</div>

<?php require 'footer.php';?>
