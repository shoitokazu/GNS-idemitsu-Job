<?php
/**
 * @author Michele Andreoli <michi.andreoli@gmail.com>
 * @name index.php
 * @version 0.1
 * @license�http://opensource.org/licenses/gpl-license.php�GNU�Public�License
 * @package TableGenerator
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="index.css" type="text/css" />
        <title>Table generator</title>
    </head>
    <body>
        <?php
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
            $data[0] = array("0", "0", "200", "200");
            $data[1] = array("2", "John", "GNS", "<a href=\"#\">index</a>");
            $data[2] = array("3", "Paul", "GNS", "<a href=\"#\">index</a>");
            $data[3] = array("4", "Michael", "GNS", "<a href=\"#\">index</a>");
            $data[4] = array("5", "George", "GNS", "<a href=\"#\">index</a>");

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
