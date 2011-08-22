<?php
//$body_onload_func = "next()";
require 'header.php'
?>
<?php
$sql = $_REQUEST['sql'];
if ($sql=='') exit();
$sql = str_replace("\\", "", $sql);

$type=$_REQUEST['type'];

switch ($type) {
case 1:
	include 'customer_format1.php';
	$page_line = 12;
	$top = 21.2;		//上余白(mm)
	$center = true;	//中央寄せ
//	$left = 18.6;		//左余白(mm)
	$width = 86.4;		//１カラムの横幅(mm)
	$height = 42.3;	//１カラムの縦幅(mm)
	$x = 2;		//横個数
	$y = 6;		//縦個数
	break;
case 2:
	include 'customer_format1.php';
	$page_line = 18;
	$top = 9;		//上余白(mm)
	$center = true;	//中央寄せ
//	$left = 7.2;		//左余白(mm)
	$width = 66.05;		//１カラムの横幅(mm)
	$height = 46.5;	//１カラムの縦幅(mm)
	$x = 3;		//横個数
	$y = 6;		//縦個数
	break;
case 3:
	$table = "print_config";
	$id = read_form_request();
	if ($id=='') {
		$id = db_insert($conn, $table, $fields, $types, $values, true);
	} else {
		$where = "ID=$id and uid=$uid";
		db_update($conn, $table, $fields, $types, $values, $where, true);
	}

	include 'customer_format1.php';
	if ($values[$fno['center']]=="1") {
		$center = true;
	} else {
		$center = false;
		$left = $values[$fno['left']];
	}
	$top = $values[$fno['top']];
	$bottom = $values[$fno['bottom']];
	$width = $values[$fno['width']];
	$height = $values[$fno['height']];
	$margin1 = $values[$fno['margin1']];
	$margin2 = $values[$fno['margin2']];
	$margin3 = $values[$fno['margin3']];
	$margin4 = $values[$fno['margin4']];
	$x = $values[$fno['x']];
	$y = $values[$fno['y']];
	$page_line = $x * $y;
	break;
default:
	include 'customer_format1.php';
	if ($_REQUEST['center']=="1") {
		$center = true;
	} else {
		$center = false;
		$left = $_REQUEST['left'];
	}
	$top = $_REQUEST['top'];
	$bottom = $_REQUEST['bottom'];
	$width = $_REQUEST['width'];
	$height = $_REQUEST['height'];
	$margin1 = $_REQUEST['margin1'];
	$margin2 = $_REQUEST['margin2'];
	$margin3 = $_REQUEST['margin3'];
	$margin4 = $_REQUEST['margin4'];
	$x = $_REQUEST['x'];
	$y = $_REQUEST['y'];
	$page_line = $x * $y;
	break;
}
if ($page_line<=0) return_error("設定エラー");

$rs = db_exec($conn, $sql);

$pre=null;
$i=1;
$line=0;
$page=1;
echo_page_header("start");
echo_report_header();
echo_detail_header();
$line += $header_line;

function page_check() {
	global $line, $page_line, $page;
	if ($line >= $page_line) {
		echo_detail_footer();
		echo_page_footer();
		$page++;
		echo_page_header();
		echo_detail_header();
		$line=0;
	}
}
while (db_fetch_row($rs)) {
	page_check();
	$pn = db_result($rs, "print_address");
	if ($pn==1 or $pn==0) $pn="";
	$f[1] = db_result($rs, "cname$pn");
	$f[2] = db_result($rs, "csub$pn");
	$f[3] = db_result($rs, "zip$pn");
	$f[4] = db_result($rs, "address$pn");
	$f[5] = db_result($rs, "building$pn");
	$f[6] = db_result($rs, "honorific");
	$line += echo_detail();
	$i++;
}
//page_check();
echo_detail_footer();
echo_report_footer();
echo_page_footer("end");
?>

<?php require 'footer.php' ?>
