<?php
$ids = $_REQUEST['id'];
$checks = $_REQUEST['check'];
$stamp = $_REQUEST['stamp'];
if (is_array($checks)) {
	$body_onload_func = "auto_print()";
	$msg = "一括印刷を実行しました。";
} else {
//	$body_onload_func = "history.back()";
	$ids = array();
	$msg = "印刷する伝票が選択されていません。";
}
require '../bill/header.php';
?>
<br>
<p><?=$msg?></p>

<script language="javaScript">
function auto_print() {
<?php
	foreach ($ids as $i => $id) {
		if ($checks[$i]=="1") {
			echo "window.open('../print/mainte_print.php?id=$id&type=1&auto=1', '_blank');";
		}
	}
?>
}
</script>
<?php
require "../bill/footer.php";
?>
