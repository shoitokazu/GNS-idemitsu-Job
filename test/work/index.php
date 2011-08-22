<?php require 'header.php';?>

<div class="frame_index">

<p><a href="list.php?clear=1&work_chance=1&sort=wcode">案件見込</a></p>
<p><a href="list.php?clear=1&work_state=2&sort=wcode">案件成立後</a></p>
<p><a href="search.php">検索</a></p>
<p><a href="addnew.php" onClick="return confirm('新規作成します。よろしいですか？')">新規作成</a></p>

<p>---</p>
<p><a href="total_staff.php">担当者別案件数集計表</a></p>
<p><a href="total_state.php">見込度別案件数集計表</a></p>

</div>

<?php require 'footer.php';?>
