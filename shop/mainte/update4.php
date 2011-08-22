<?php
$body_onload_func = "next()";
include 'header.php';

$id = read_form_request();

if (isset($_REQUEST['p1'])) $type = 1;
if (isset($_REQUEST['p2'])) $type = 2;
if (isset($_REQUEST['p3'])) $type = 3;
if (isset($_REQUEST['p4'])) $type = 4;
if (isset($_REQUEST['p5'])) $type = 5;
if (isset($_REQUEST['p6'])) $type = 6;
?>
<script language="javaScript">
function next() {
	location.replace("../print/mainte_print.php?id=<?=$id?>&type=<?=$type?>");
}
</script>

<?php include 'footer.php'; ?>
