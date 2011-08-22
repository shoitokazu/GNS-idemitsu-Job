<?php include 'header.php'; ?>

<h1>請求書用ＤＢの構築</h1>

<?php
$confirm = $_REQUEST['confirm'];
if ($confirm<>'実行') {
?>
<form action="#" method="post">
<input type="submit" name="confirm" value="実行">
<p><a href="javascript:history.back()">戻る</a></p>
</form>
<?php
	exit();
}
?>

<?php
$sql = "ALTER TABLE `profit`";
$sql .= " ADD `bank` TINYINT NOT NULL ,";
$sql .= " ADD `request_date` DATE NULL ,";
$sql .= " ADD `request_staff` INT NOT NULL ,";
$sql .= " ADD `printing_date` DATE NULL ,";
$sql .= " ADD `printing_staff` INT NOT NULL ;";
db_exec($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS `profit_detail` (";
$sql .= "  `ID` int(10) unsigned NOT NULL auto_increment,";
$sql .= "  `hid` int(11) NOT NULL,";
$sql .= "  `contents` varchar(100) NOT NULL,";
$sql .= "  `amount` int(11) NOT NULL,";
$sql .= "  `tax_type` int(11) NOT NULL,";
$sql .= "  `sales` tinyint(1) NOT NULL,";
$sql .= "  PRIMARY KEY  (`ID`)";
$sql .= ")";
db_exec($conn, $sql);
?>

<p><?=$sql?></p>
<p>実行しました</p>

<?php include 'footer.php'; ?>
