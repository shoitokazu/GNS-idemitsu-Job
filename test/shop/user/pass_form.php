<?php
$page_title = "会社情報　パスワード設定";
require 'header.php';
?>

<form action="pass_update.php" method="POST">

<div class="frame_box">
<?php echo_form_frame();?>

<tr><td>新しいパスワード</td><td><input type="password" name="pass1"></td></tr>
<tr><td>確認のため同じもの</td><td><input type="password" name="pass2"></td></tr>

<?php echo_form_frame_end();?>
</div>

<?php
echo_button_frame();
echo_form_submit();
echo_button_frame_end();
?>

</form>

<?php include 'footer.php'; ?>
