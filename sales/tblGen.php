<?php

require 'db.php';
require_once 'Table.php';
require_once 'dbCallActions.php';

            //Set table's headers
            $headers = array("平成21年4月","平成21年5月","平成21年6月","平成21年4〜6月計");
            $headersKeihi;
            
            $headersub = array( 
            				array("20/4実","計画","実績","計画増減"),
               			 	array("20/5実","計画","実績","計画増減"),
               			 	array("20/6実","計画","実績","計画増減"),
               			 	array("前Q実績","前Q計画","前Q実績","計画増減")  
             ); 
            $headersubKeihi;
            
            //Set table's matrix data
      $data = array();

        $myTblData = new dbCallActions();
        
        $data = $myTblData->tableMaker("jitsu_shushi");
        $data2 = $myTblData->tableMaker("keikaku_shushi");
         $data3 = $myTblData->merge_array($data,$data2);
        $data4 = $myTblData->tableMaker("jisseki_shushi");
        $data5 = $myTblData->tableMaker("keikakuZ_shushi");
       $data6 = $myTblData->merge_array($data4,$data5);
       $result = $myTblData->merge_array($data3,$data6);
       
        
           echo "<h1 style=\"text-align: center\">Table exemple</h1>";

            $t = new Table(true, "tableID");
            
           
           $t->setBodyClass("bodyClass");

            $t->showTable($headers, $headersub);
            $t->showTableBody($result);
            $t->showTableBody($result);
            $t->closeTable();
            
        ?>
    </body>
</html>
