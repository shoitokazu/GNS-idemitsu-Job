<?php require 'header.php';?>

<div class="frame_index">

<p><a href="company_list.php">オフィス設定</a></p>
<p><a href="account_list.php">アカウント設定</a></p>

<p><a href="table_list.php?table=account">アカウント</a></p>
<p><a href="table_list.php?table=sshop">ショップ</a></p>
<p>---</p>
<p><a href="config_list.php?field=auth">ユーザー権限</a></p>
<p><a href="config_list.php?field=company">事業所選択肢</a></p>
<p>--- 顧客 ---</p>
<p><a href="config_list.php?field=customer_type">顧客区分</a></p>
<p><a href="config_list1.php?field=customer_kind">顧客状態</a></p>
<p><a href="config_list.php?field=print_address">印刷住所選択</a></p>
<p><a href="config_list.php?field=customer_flag">顧客エリア</a></p>
<p><a href="config_list.php?field=customer_group">顧客グループ</a></p>
<p>--- 物件 ---</p>
<p><a href="config_list.php?field=atype">物件区分</a></p>
<p><a href="table_list.php?table=model">商品マスター</a></p>
<p><a href="config_list1.php?field=article17">在庫区分</a></p>
<p><a href="config_list1.php?field=article18">在庫エリア</a></p>
<p><a href="config_list1.php?field=atask_name">検査内容</a></p>
<p><a href="config_list1.php?field=atask2">返事確認</a></p>
<p>--- 案件 ---</p>
<p><a href="config_list.php?field=work_state">案件状態</a></p>
<p><a href="config_list1.php?field=work5">購入手段</a></p>
<p><a href="config_list1.php?field=work6">キャッチ手段</a></p>
<p>--- 売上 ---</p>
<p><a href="config_list.php?field=sales_state">売上状態</a></p>
<p><a href="config_list.php?field=sales_category">売上区分</a></p>
<p><a href="config_list1.php?field=area">エリア選択肢</a></p>
<p>--- 整備 ---</p>
<p><a href="config_list.php?field=mainte_state">整備状態</a></p>
<p><a href="config_list.php?field=mainte_category">整備売上区分</a></p>
<p><a href="config_list1.php?field=mainte1">整備伝票区分</a></p>
<p><a href="config_list1.php?field=mainte3">整備依頼区分</a></p>
<p><a href="config_list1.php?field=scenter">発行元SC選択肢</a></p>
<p><a href="config_list1.php?field=trans_sc">振替先SC選択肢</a></p>
<p><a href="table_list.php?table=stamp">印刷スタンプ</a></p>
<p><a href="table_list.php?table=transfer">振替先</a></p>
<p>--- 部品 ---</p>
<p><a href="config_list.php?field=itype">部品区分</a></p>
<p>--- スケジュール ---</p>
<p><a href="config_list.php?field=schedule_type">スケジュール区分</a></p>
<p><a href="config_list1.php?field=schedule3_0">項目区分（スケジュール）</a></p>
<p><a href="config_list1.php?field=schedule3_1">項目区分（来店履歴）</a></p>


<p>---</p>
<p><a href="customer_csv.php">顧客マスターＣＳＶ</a></p>


</div>

<?php require 'footer.php';?>
