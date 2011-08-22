<?php include 'header.php'; ?>

<?php
$id = $_REQUEST['id'];
?>

<form action="pass_update.php" method="POST">
<input type="hidden" name="id" value="<?=$id?>">

<?php
echo_sub_header("", "更新");
?>

<div class="frame_box">
<?php echo_form_frame();?>

<tr><td>新しいパスワード</td><td><input type="password" name="pass1"></td></tr>
<tr><td>確認用パスワード</td><td><input type="password" name="pass2"></td></tr>
<tr><td>暗号化</td><td><input type="checkbox" name="encode" value="1"></td></tr>

<?php echo_form_frame_end();?>
</div>

</form>

<?php include 'footer.php'; ?>
