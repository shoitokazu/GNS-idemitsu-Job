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
            /*
             * $headersub = array( 
             
            				array("20/4実","計画","実績","計画増減"),
               			 	array("20/5実","計画","実績","計画増減"),
               			 	array("20/6実","計画","実績","計画増減"),
               			 	array("前Q実績","前Q計画","前Q実績","計画増減")  
             ); 
             */
            //Set table's matrix data
            
        $db = db_conni();
        $SQL="SELECT * FROM jitsu_shushi";
$result=$db->query($SQL)or die(print_r($db->error));
$i=0;
while($obj=$result->fetch_array(MYSQLI_ASSOC));
{
    echo $obj[$i];
    $i++;
}


/*$SQL->bind_param('sis', $comment2,$id,$_SESSION['username']);*/
     
             
             /*
            $data[0] = array("0", "0", "200", "200"); 
            $data[1] = array("2", "John", "GNS", "<a href=\"#\">index</a>");
            $data[2] = array("3", "Paul", "GNS", "<a href=\"#\">index</a>");
            $data[3] = array("4", "Michael", "GNS", "<a href=\"#\">index</a>");
            $data[4] = array("5", "George", "GNS", "<a href=\"#\">index</a>");
            */
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
            $t->showTable($headers, $data,$headersub);
        ?>
    </body>
</html>
