<?php require 'header.php';?>

<div class="frame_index">



<?php if ($auth1==2) { ?>

<h2>管理者権限でログインしています</h2>

<p><a href="../admin/config/index.php">管理システム</a></p>

<?php } ?>

<?php if ($auth1==3) { ?>

<h2>特権ユーザー（CSV出力ができる）</h2>

<?php } ?>

</div>

<?php require 'footer.php';?>
