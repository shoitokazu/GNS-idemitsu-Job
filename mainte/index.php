<?php require 'header.php';?>

<div class="frame_index">

<p><a href="list.php?clear=1&mainte_state=0&mine=1">新規受付</a></p>
<p><a href="list.php?clear=1&mainte_state=1&mine=1">作業中</a></p>
<p><a href="list.php?clear=1&mainte_state=2&mine=1">作業完了</a></p>
<p><a href="list.php?clear=1&mainte_state=9&mine=1">見積記録</a></p>
<p><a href="search.php">検索</a></p>
<p><a href="addnew.php?type=1" onClick="return confirm('見積書を作成します。よろしいですか？')">新規見積書作成</a></p>
<p><a href="addnew.php?type=0" onClick="return confirm('整備伝票を作成します。よろしいですか？')">新規整備明細作成</a></p>

<p>---</p>
<p><a href="shop_report.php">サービスショップ別工程管理</a></p>
<p><a href="sales_search.php">売上集計表（経理業務）</a></p>

</div>

<?php require 'footer.php';?>
