<?php 

$body_onload_func = "tmp()";
include '../include/script_header.php';

set_page_tag("customer");

$cid       = $_REQUEST['cid'];
$lid       = $_REQUEST['lid'];
$able_date = $_REQUEST['able_date'];
$next_date = $_REQUEST['next_date'];
$lclass    = $_REQUEST['lclass'];
$lmemo     = $_REQUEST['lmemo'];
$lflg      = $_REQUEST['lflg'];
$del_flg   = $_REQUEST['del_flg'];

$table  = "license_limit";
$fields = array();
$types  = array();
$values = array();

$fields[0] = "able_date";	// 免許有効日
$types [0] = "date";
$values[0] = $able_date;
$fields[1] = "next_date";	// 次回更新日
$types [1] = "date";
$values[1] = $next_date;
$fields[2] = "lclass";		// 免許種別
$types [2] = "str";
$values[2] = $lclass;
$fields[3] = "lflg";		// 有効無効FLG
$types [3] = "int";
(($lflg<>'')? $values[3] = 0 :$values[3] = 1);
$fields[4] = "del_flg";		// 削除FLG
$types [4] = "int";
(($del_flg<>'')? $values[4] = 1 :$values[4] = 0);
$fields[5] = "lmemo";		// 免許メモ
$types [5] = "str";
$values[5] = $lmemo;

$where     = " lid=$lid and cid=$cid ";

db_update($conn, $table, $fields, $types, $values, $where);


include '../include/script_footer.php';
?>
<script language="javascript">
function tmp() {

    if (!window.opener.document.getElementById('<?=able_date.$lid ?>')) {
    // 親ウィンドウが存在しない
		location.replace('date.php?id=<?=$cid?>&lid=<?=$lid?>');
	} else {
        // form＞免許情報の当該行の日付を更新
		window.opener.document.getElementById('<?=able_date.$lid ?>').value = <?php echo '"'.$able_date.'"'; ?>;
		window.opener.document.getElementById('<?=next_date.$lid ?>').value = <?php echo '"'.$next_date.'"'; ?>;
		window.opener.document.getElementById('<?=lclass.$lid ?>').value    = <?php echo '"'.$lclass.'"'; ?>;
		window.opener.document.getElementById('<?=lmemo.$lid ?>').value     = <?php echo '"'.$lmemo.'"'; ?>;
		window.opener.document.getElementById('<?=lflg.$lid ?>').value      = <?php echo '"'.(($lflg<>'')? "有効":"無効").'"'; ?>;
		window.opener.document.getElementById('<?=del_flg.$lid ?>').value   = <?php echo '"'.(($del_flg<>'')? "削除":"").'"'; ?>;
		location.replace('date.php?id=<?=$cid?>&lid=<?=$lid?>');
    }
}
</script>