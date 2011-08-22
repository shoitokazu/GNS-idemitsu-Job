<?php include 'header.php'; ?>

<div class="frame_index">

<p>販売計画のインポート</p>

<p>
<form enctype="multipart/form-data" action="plan_import.php" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
	<input name="csvfile" type="file" size="50" />
	<input type="submit" value="計画インポート実行" />
</form>
</p>

<p><a href="plan_sample.csv">計画表サンプル</a></p>
</div>

<?php include 'footer.php'; ?>
