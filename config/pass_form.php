<?php include 'header.php'; ?>

<form action="pass_update.php" method="POST">

<?php
echo_sub_header("", "更新");
?>

<div class="frame_box">
<?php echo_form_frame();?>

<tr><td>新しいパスワード</td><td><input type="password" name="pass1"></td></tr>
<tr><td>確認のため同じもの</td><td><input type="password" name="pass2"></td></tr>

<?php echo_form_frame_end();?>
</div>

</form>

<?php include 'footer.php'; ?>
