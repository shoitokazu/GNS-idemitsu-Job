<?php
/**
 * @author Michele Andreoli <michi.andreoli@gmail.com>
 * @name index.php
 * @version 0.1
 * @license�http://opensource.org/licenses/gpl-license.php�GNU�Public�License
 * @package TableGenerator
 */
require 'header.php';
            require_once 'Table.php';

            //Set table's headers
            $headers = array("平成21年4月","平成21年5月","平成21年6月","平成21年4〜6月計");
            /*
            $headersub = array("２０／４実","計画","実績","計画増減");
            */
            $headersub = array( 
             
            				array("20/4実","計画","実績","計画増減"),
               			 	array("20/5実","計画","実績","計画増減"),
               			 	array("20/6実","計画","実績","計画増減"),
               			 	array("前Q実績","前Q計画","前Q実績","計画増減")  
             ); 
            //Set table's matrix data
function db_connect()
{
   //$result = new mysqli('localhost', 'blackj6_ray', 'ayabua0607111', 'blackj6_dunami');
   $result = new mysqli('localhost', 'root', '', 'ilocsdb');
   $result->set_charset("utf8");
  
   
if (!$result) {
throw new Exception('Could not connect to database server');
} else {
return $result;
}

}

$data = array();
$data2 = array();
$data3 = array();
$data4 = array();

$db = db_connect();
$SQL ="SELECT * FROM jitsu_shushi";
$result = $db->query($SQL) or die(print_r($db->error));
$i = 1;
while ($obj = $result->fetch_array(MYSQL_ASSOC))
{
 $data[$i]["mishikomikin"] = $obj["mishikomikin"];
 $data[$i]["hokanryou-shutei"] = $obj["hokanryou-shutei"];
 $data[$i]["hokanryou-pw"] = $obj["hokanryou-pw"];
 $data[$i]["hokanzougen"] = $obj["hokanzougen"];
 $data[$i]["hokansukei"] = $obj["hokansukei"];
 $i++;
}
$db = db_connect();
$SQL ="SELECT * FROM keikaku_shushi";
$result = $db->query($SQL) or die(print_r($db->error));
$i = 1;
while ($obj = $result->fetch_array(MYSQL_ASSOC))
{
 $data2[$i]["mishikomikin"] = $obj["mishikomikin"];
 $data2[$i]["hokanryou-shutei"] = $obj["hokanryou-shutei"];
 $data2[$i]["hokanryou-pw"] = $obj["hokanryou-pw"];
 $data2[$i]["hokanzougen"] = $obj["hokanzougen"];
 $data2[$i]["hokansukei"] = $obj["hokansukei"];
 $i++;
}

$db = db_connect();
$SQL ="SELECT * FROM jisseki_shushi";
$result = $db->query($SQL) or die(print_r($db->error));
$i = 1;
while ($obj = $result->fetch_array(MYSQL_ASSOC))
{
 $data3[$i]["mishikomikin"] = $obj["mishikomikin"];
 $data3[$i]["hokanryou-shutei"] = $obj["hokanryou-shutei"];
 $data3[$i]["hokanryou-pw"] = $obj["hokanryou-pw"];
 $data3[$i]["hokanzougen"] = $obj["hokanzougen"];
 $data3[$i]["hokansukei"] = $obj["hokansukei"];
 $i++;
}
  
$db = db_connect();
$SQL ="SELECT * FROM keikakuz_shushi";
$result = $db->query($SQL) or die(print_r($db->error));
$i = 1;
while ($obj = $result->fetch_array(MYSQL_ASSOC))
{
 $data4[$i]["mishikomikin"] = $obj["mishikomikin"];
 $data4[$i]["hokanryou-shutei"] = $obj["hokanryou-shutei"];
 $data4[$i]["hokanryou-pw"] = $obj["hokanryou-pw"];
 $data4[$i]["hokanzougen"] = $obj["hokanzougen"];
 $data4[$i]["hokansukei"] = $obj["hokansukei"];
 $i++;
}

            echo "<h1 style=\"text-align: center\">Table exemple</h1>";

            /**
             * Create an instance of Table class
             * @param <Boolean> true: zebra rows on, false: zebra rows off
             * [@param] <String> table id
             * [@param] <String> table class
             */
            $t = new Table(true, "tableID");
            
            /**
             * Set a class or id for thead, tbody and tfoot of table
             * @param <String> class name
             * [@param] <String> id name
             */
            $t->setHeaderClass("headClass");
           
            $t->setBodyClass("bodyClass");
            $t->setFooterClass("footClass");

            /**
             * Specify a width for every columns
             */
            $t->setColumnsWidth(array("20px", "150px", "150px", "80px"));

            /**
             * Show the table
             * @param <Array> headers
             * @param <Array> data
             */
            $t->showTable($headers, $data,$data2,$data3,$data4,$headersub);
        ?>
    </body>
</html>
