<?php
/**
 * @author Michele Andreoli <michi.andreoli@gmail.com>
 * @name Table.class.php
 * @version 0.1
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package TableGenerator
 */

class Table {
    private $zebra;
    private $tableId;
    private $tableClass;
    private $headerId;
    private $headerClass;
    private $bodyId;
    private $bodyClass;
    private $footerId;
    private $footerClass;
    private $zebraClass;
    private $tableWidth;
    private $columnsWidth;

    /**
     * Constructor for table class
     * @param <Boolean> $zebra set on/off the zebra mode
     * @param <String> $id id name for this table
     * @param <String> $class class name for this table
     */
    public function __construct($zebra=null, $id=null, $class=null) {
        if ($id != null)
            $this->tableId = "id=\"$id\"";
        if ($class != null)
            $this->tableClass = "class=\"$class\"";
        if ($zebra != null)
            $this->zebra = true;
        else
            $this->zebra = false;
    }

    /**
     * Setter for zebra mode
     * @param <type> $zebra set on/off the zebra mode
     */
    public function setZebra($zebra) {
        $this->zebra = $zebra;
    }

    /**
     * Setter for table width
     * @param <String> $string width of table for example '600px' or '100%'
     */
    public function setTableWidth($string) {
        $this->tableWidth = "style=\"width:$string\"";
    }

    /**
     * Setter for columns width
     * @param <Array> $array array with the width for every column
     */
    public function setColumnsWidth($array) {        
        foreach ($array as $elem) {
            if ($elem != "")
                $this->columnsWidth[] = "style=\"width:$elem\"";
            else
                $this->columnsWidth[] = "";
        }
    }

    /**
     * Setter for table class name
     * @param <String> $class class name
     */
    public function setTableClass($class) {
        $this->tableClass = "class=\"$class\"";
    }

    /**
     * Setter for table id name
     * @param <String> $id id name
     */
    public function setTableId($id) {
        $this->tableId = "id=\"$id\"";
    }

    /**
     * Setter for thead class name
     * @param <String> $class class name
     */
    public function setHeaderClass($class) {
        $this->headerClass = "class=\"$class\"";
    }

    /**
     * Setter for thead id name
     * @param <String> $id id name
     */
    public function setHeaderId($id) {
        $this->headerId = "id=\"$id\"";
    }

    /**
     * Setter for tbody class name
     * @param <String> $class class name
     */
    public function setBodyClass($class) {
        $this->bodyClass = "class=\"$class\"";
    }

    /**
     * Setter for tbody id name
     * @param <String> $id id name
     */
    public function setBodyId($id) {
        $this->bodyId = "id=\"$id\"";
    }

    /**
     * Setter for tfoot class name
     * @param <String> $class class name
     */
    public function setFooterClass($class) {
        $this->footerClass = "class=\"$class\"";
    }

    /**
     * Setter for tfoot id name
     * @param <String> $id id name
     */
    public function setFooterId($id) {
        $this->footerId = "id=\"$id\"";
    }

    /**
     * Print the table
     * @param <Array> $headers header for every column
     * @param <Array> $data data matrix
     */
    public function showTable($headers, $data, $headersub)
   {
       $leftCol = array(
       		0 => "舟艇保管",
            1=> "申込金",
            2 => "保管料（舟艇）",
            3 => "保管料（PW）",
            4 => "計",
            5 => "粗利益",
            6 => "保管増減",
            7 => "保管巣計"
       );
       $leftColCount = count($leftCol);
       $count = 0;
       $table = "<table $this->tableWidth $this->tableId $this->tableClass background='#000' cellspacing='1' border='0'>";


       /*$dataCount = max($headersub);*/
       /*$people = array("Peter", "Joe", "Glenn", "Cleveland");*/
	   $dataCount = count($headersub);

       //$table .= "<thead $this->headerId $this->headerClass><tr>";
       $table.= "<tr>";
       $table.= "<td'></td>";
      
           if ($i == 0)
           {
               $table.="<td colspan='2'></td>";
               $j++;
           }
           foreach ($headers as $h)
           {
               //$style = $this->columnsWidth[$count];
               $table .= "<td colspan='$dataCount' align='center' nowrap='nowrap'><b>$h</b></td>";
               $count++;
           }
       
       $table .= "</tr><tr>";
      $table.= "<td'></td>";
       for ($i = 0; $i < 5; $i++)
       {
           if ($i == 0)
           {
               $table.="<td nowrap='nowrap'><b>部門</b></td><td></td>";
               $j++;
           }
           
           for ($i = 0; $i < 4; $i++)
           {
               for ($j = 0; $j < 4; $j++)
              {
               //$style = $this->columnsWidth[$count];
               $table .= "<td align='center' nowrap='nowrap'><b>".$headersub[$i][$j]."</b></td>";
               $count++;
              }
           }
           
           /*
            * foreach ($headersub as $g)
            
           {
               $table .= "<td nowrap='nowrap'>$g</td>";
           }
            * 
            */
       }
       $table .= "</tr>";



       //BODY
       $j = 0;
       $count = 0;
        $table.= "<td rowspan='$leftColCount' nowrap='nowrap'><b>$leftCol[0]</b></td>";
       foreach ($data as $row)
       {


           $table .= "<tr $this->zebraClass>";
           for ($i = 0; $i < 4; $i++)
           {
               if ($i == 0)
               {
                   $table.="<td nowrap='nowrap'><b>$leftCol[$j]</b></td>";
                   $j++;
               }
               foreach ($row as $col)
               {
                   $table .= "<td nowrap='nowrap'>$col</td>";
               }
           }
       }
       $table .= "</tr>";
       $table .= "</table>";

       echo $table;
   }
}

?>
