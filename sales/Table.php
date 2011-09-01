<?php
/*
 * error_reporting(-1);
*/
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
       $shuteiHokukan = array(
       	    0 => "舟艇保管",
            1=>  "申込金",
            2 => "保管料（舟艇）",
            3 => "保管料（PW）",
            4 => "計",
            5 => "粗利益",
            6 => "保管増減",
            7 => "保管巣計"
       );
       $shuteiHokukanCount = count($shuteiHokukan);
       
       $marineClub = array(
       	    0 => "年会費（法人）",
            1=>  "年会費（個人）",
            2 => "計　　　粗利益",
            3 => "レンタルボート　　売上",
            4 => "＆　営業収入　　仕入",
            5 => "粗利益",
            6 => "運行管理　　売上",
            7 => "運行管理　　仕入",
            8 => "粗利益",
            9 => "売上計",
           10 => "マリンクラブ　計　仕入計",
           11 => "粗利益",
           12 => "法人口数",
           13 => "個人口数"
       );
       $marineClubCount = count($marineClub);
       
       $count = 0;
       $table = "<table $this->tableWidth $this->tableId $this->tableClass bgcolor='#000' cellspacing='1' cellpadding='2' border='0'>";


       /*$dataCount = max($headersub);*/
       /*$people = array("Peter", "Joe", "Glenn", "Cleveland");*/
	   $dataCount = count($headersub);

       //$table .= "<thead $this->headerId $this->headerClass><tr>";
       $table.= "<tr bgcolor='#fff'>";
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
       
       $table .= "</tr><tr bgcolor='#fff'>";
      $table.= "<td'></td>";
       for ($i = 0; $i < 5; $i++)
       {
           if ($i == 0)
           {
               $table.="<td nowrap='nowrap' width='100'><b>部門</b></td><td width='100'></td>";
               $j++;
           }
           
           for ($i = 0; $i < 4; $i++)
           {
               for ($j = 0; $j < 4; $j++)
              {
               //$style = $this->columnsWidth[$count];
               $table .= "<td align='center' nowrap='nowrap' width='70'><b>".$headersub[$i][$j]."</b></td>";
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


       /*print_r($data);*/
       //BODY
        $j = 0;
        $count = 0;
        $table.= "<td rowspan='$shuteiHokukanCount' nowrap='nowrap' valign='top' bgcolor='#fff'><b>$shuteiHokukan[0]</b></td>";
        for ($i = 0; $i < 3; $i++)
        {   
            
            $table.= "<tr bgcolor='#fff'>";
            for ($i = 0; $i < 3; $i++)
            {
                
                if ($i == 0)
            {
                $j=1;
                $table.="<td nowrap='nowrap'><b>$shuteiHokukan[$j]</b></td>";
                $j++;
                
            }
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jitsu_shushi']['mishikomikin'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikaku_shushi']['mishikomikin'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jisseki_shushi']['mishikomikin'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikakuZ_shushi']['mishikomikin'] . "</td>";
                
                if ($i == 2)
                {
                $total =  $data[0]['jitsu_shushi']['mishikomikin'] + $data[1]['jitsu_shushi']['mishikomikin'] + $data[2]['jitsu_shushi']['mishikomikin'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikaku_shushi']['mishikomikin'] + $data[1]['keikaku_shushi']['mishikomikin'] + $data[2]['keikaku_shushi']['mishikomikin'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['jisseki_shushi']['mishikomikin'] + $data[1]['jisseki_shushi']['mishikomikin'] + $data[2]['jisseki_shushi']['mishikomikin'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikakuZ_shushi']['mishikomikin'] + $data[1]['keikakuZ_shushi']['mishikomikin'] + $data[2]['keikakuZ_shushi']['mishikomikin'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                }
            }
            
            $table .= "</tr>";
            $table.= "<tr bgcolor='#fff'>";
            for ($i = 0; $i < 3; $i++)
            {
                     if ($i == 0)
            {
                $table.="<td nowrap='nowrap'><b>$shuteiHokukan[$j]</b></td>";
                $j++;
                
            }
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jitsu_shushi']['hokanryou-shutei'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikaku_shushi']['hokanryou-shutei'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jisseki_shushi']['hokanryou-shutei'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikakuZ_shushi']['hokanryou-shutei'] . "</td>";
                if ($i == 2)
                {
                $total =  $data[0]['jitsu_shushi']['hokanryou-shutei'] + $data[1]['jitsu_shushi']['hokanryou-shutei'] + $data[2]['jitsu_shushi']['hokanryou-shutei'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikaku_shushi']['hokanryou-shutei'] + $data[1]['keikaku_shushi']['hokanryou-shutei'] + $data[2]['keikaku_shushi']['hokanryou-shutei'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['jisseki_shushi']['hokanryou-shutei'] + $data[1]['jisseki_shushi']['hokanryou-shutei'] + $data[2]['jisseki_shushi']['hokanryou-shutei'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikakuZ_shushi']['hokanryou-shutei'] + $data[1]['keikakuZ_shushi']['hokanryou-shutei'] + $data[2]['keikakuZ_shushi']['hokanryou-shutei'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                }
            }
            $table .= "</tr>";
            $table.= "<tr bgcolor='#fff'>";
            for ($i = 0; $i < 3; $i++)
            {
                     if ($i == 0)
            {
                $table.="<td nowrap='nowrap'><b>$shuteiHokukan[$j]</b></td>";
                $j++;
                
            }
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jitsu_shushi']['hokanryou-pw'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikaku_shushi']['hokanryou-pw'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jisseki_shushi']['hokanryou-pw'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikakuZ_shushi']['hokanryou-pw'] . "</td>";
                
                if ($i == 2)
                {
                $total =  $data[0]['jitsu_shushi']['hokanryou-pw'] + $data[1]['jitsu_shushi']['hokanryou-pw'] + $data[2]['jitsu_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikaku_shushi']['hokanryou-pw'] + $data[1]['keikaku_shushi']['hokanryou-pw'] + $data[2]['keikaku_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['jisseki_shushi']['hokanryou-pw'] + $data[1]['jisseki_shushi']['hokanryou-pw'] + $data[2]['jisseki_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikakuZ_shushi']['hokanryou-pw'] + $data[1]['keikakuZ_shushi']['hokanryou-pw'] + $data[2]['keikakuZ_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                }
                
            }
            $table .= "</tr>";
            $table.= "<tr bgcolor='#fff'>";
            for ($i = 0; $i < 3; $i++)
            {
                         if ($i == 0)
            {
                $table.="<td nowrap='nowrap'><b>$shuteiHokukan[$j]</b></td>";
                $j++;
                
            }
                $total = $data[$i]['jitsu_shushi']['hokanryou-shutei'] + $data[$i]['jitsu_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                $total = $data[$i]['keikaku_shushi']['hokanryou-shutei'] + $data[$i]['keikaku_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                $total = $data[$i]['jisseki_shushi']['hokanryou-shutei'] + $data[$i]['jisseki_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                $total = $data[$i]['keikakuZ_shushi']['hokanryou-shutei'] + $data[$i]['keikakuZ_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                if ($i == 2)
                {
                $total =  $data[0]['jitsu_shushi']['hokanryou-shutei'] + $data[1]['jitsu_shushi']['hokanryou-shutei'] + $data[02]['jitsu_shushi']['hokanryou-shutei'] +  $data[0]['jitsu_shushi']['hokanryou-pw'] + $data[1]['jitsu_shushi']['hokanryou-pw'] + $data[2]['jitsu_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikaku_shushi']['hokanryou-shutei'] + $data[1]['keikaku_shushi']['hokanryou-shutei'] + $data[02]['keikaku_shushi']['hokanryou-shutei'] +  $data[0]['keikaku_shushi']['hokanryou-pw'] + $data[1]['keikaku_shushi']['hokanryou-pw'] + $data[2]['keikaku_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['jisseki_shushi']['hokanryou-shutei'] + $data[1]['jisseki_shushi']['hokanryou-shutei'] + $data[02]['jisseki_shushi']['hokanryou-shutei'] +  $data[0]['jisseki_shushi']['hokanryou-pw'] + $data[1]['jisseki_shushi']['hokanryou-pw'] + $data[2]['jisseki_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikakuZ_shushi']['hokanryou-shutei'] + $data[1]['keikakuZ_shushi']['hokanryou-shutei'] + $data[02]['keikakuZ_shushi']['hokanryou-shutei'] +  $data[0]['keikakuZ_shushi']['hokanryou-pw'] + $data[1]['keikakuZ_shushi']['hokanryou-pw'] + $data[2]['keikakuZ_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                }
               
            }
            $table .= "</tr>";
             $table.= "<tr bgcolor='#fff'>";
            for ($i = 0; $i < 3; $i++)
            {
                         if ($i == 0)
            {
                $table.="<td nowrap='nowrap'><b>$shuteiHokukan[$j]</b></td>";
                $j++;
                
            }
                $total = $data[$i]['jitsu_shushi']['mishikomikin'] + $data[$i]['jitsu_shushi']['hokanryou-shutei'] + $data[$i]['jitsu_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                $total = $data[$i]['keikaku_shushi']['mishikomikin'] + $data[$i]['keikaku_shushi']['hokanryou-shutei'] + $data[$i]['keikaku_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                $total = $data[$i]['jisseki_shushi']['mishikomikin'] + $data[$i]['jisseki_shushi']['hokanryou-shutei'] + $data[$i]['jisseki_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                $total = $data[$i]['keikakuZ_shushi']['mishikomikin'] + $data[$i]['keikakuZ_shushi']['hokanryou-shutei'] + $data[$i]['keikakuZ_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                if ($i == 2)
                {
                $total =  $data[0]['jitsu_shushi']['mishikomikin'] + $data[1]['jitsu_shushi']['mishikomikin'] + $data[2]['jitsu_shushi']['mishikomikin'] + $data[0]['jitsu_shushi']['hokanryou-shutei'] + $data[1]['jitsu_shushi']['hokanryou-shutei'] + $data[2]['jitsu_shushi']['hokanryou-shutei'] +  $data[0]['jitsu_shushi']['hokanryou-pw'] + $data[1]['jitsu_shushi']['hokanryou-pw'] + $data[2]['jitsu_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikaku_shushi']['mishikomikin'] + $data[1]['keikaku_shushi']['mishikomikin'] + $data[2]['keikaku_shushi']['mishikomikin'] + $data[0]['keikaku_shushi']['hokanryou-shutei'] + $data[1]['keikaku_shushi']['hokanryou-shutei'] + $data[2]['keikaku_shushi']['hokanryou-shutei'] +  $data[0]['keikaku_shushi']['hokanryou-pw'] + $data[1]['keikaku_shushi']['hokanryou-pw'] + $data[2]['keikaku_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['jisseki_shushi']['mishikomikin'] + $data[1]['jisseki_shushi']['mishikomikin'] + $data[2]['jisseki_shushi']['mishikomikin'] + $data[0]['jisseki_shushi']['hokanryou-shutei'] + $data[1]['jisseki_shushi']['hokanryou-shutei'] + $data[2]['jisseki_shushi']['hokanryou-shutei'] +  $data[0]['jisseki_shushi']['hokanryou-pw'] + $data[1]['jisseki_shushi']['hokanryou-pw'] + $data[2]['jisseki_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikakuZ_shushi']['mishikomikin'] + $data[1]['keikakuZ_shushi']['mishikomikin'] + $data[2]['keikakuZ_shushi']['mishikomikin'] + $data[0]['keikakuZ_shushi']['hokanryou-shutei'] + $data[1]['keikakuZ_shushi']['hokanryou-shutei'] + $data[2]['keikakuZ_shushi']['hokanryou-shutei'] +  $data[0]['keikakuZ_shushi']['hokanryou-pw'] + $data[1]['keikakuZ_shushi']['hokanryou-pw'] + $data[2]['keikakuZ_shushi']['hokanryou-pw'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                }
               
            }
            $table .= "</tr>";
            $table.= "<tr bgcolor='#fff'>";
            for ($i = 0; $i < 3; $i++)
            {
                         if ($i == 0)
            {
                $table.="<td nowrap='nowrap'><b>$shuteiHokukan[$j]</b></td>";
                $j++;
                
            }
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jitsu_shushi']['hokanzougen'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikaku_shushi']['hokanzougen'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jisseki_shushi']['hokanzougen'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikakuZ_shushi']['hokanzougen'] . "</td>";
                if ($i == 2)
                {
                $total =  $data[0]['jitsu_shushi']['hokanzougen'] + $data[1]['jitsu_shushi']['hokanzougen'] + $data[2]['jitsu_shushi']['hokanzougen'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikaku_shushi']['hokanzougen'] + $data[1]['keikaku_shushi']['hokanzougen'] + $data[2]['keikaku_shushi']['hokanzougen'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['jisseki_shushi']['hokanzougen'] + $data[1]['jisseki_shushi']['hokanzougen'] + $data[2]['jisseki_shushi']['hokanzougen'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikakuZ_shushi']['hokanzougen'] + $data[1]['keikakuZ_shushi']['hokanzougen'] + $data[2]['keikakuZ_shushi']['hokanzougen'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                }
            }
            $table .= "</tr>";
            $table.= "<tr bgcolor='#fff'>";
            for ($i = 0; $i < 3; $i++)
            {
                         if ($i == 0)
            {
                $table.="<td nowrap='nowrap'><b>$shuteiHokukan[$j]</b></td>";
                $j++;
                
            }
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jitsu_shushi']['hokansukei'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikaku_shushi']['hokansukei'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['jisseki_shushi']['hokansukei'] . "</td>";
                $table .= "<td nowrap='nowrap'>" . $data[$i]['keikakuZ_shushi']['hokansukei'] . "</td>";
                if ($i == 2)
                {
                $total =  $data[0]['jitsu_shushi']['hokansukei'] + $data[1]['jitsu_shushi']['hokansukei'] + $data[2]['jitsu_shushi']['hokansukei'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikaku_shushi']['hokansukei'] + $data[1]['keikaku_shushi']['hokansukei'] + $data[2]['keikaku_shushi']['hokansukei'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['jisseki_shushi']['hokansukei'] + $data[1]['jisseki_shushi']['hokansukei'] + $data[2]['jisseki_shushi']['hokansukei'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                
                $total =  $data[0]['keikakuZ_shushi']['hokansukei'] + $data[1]['keikakuZ_shushi']['hokansukei'] + $data[2]['keikakuZ_shushi']['hokansukei'];
                $table .= "<td nowrap='nowrap'>" . $total . "</td>";
                }
            }
            $table .= "</tr>";





            $j++;
        }

        $table .= "</table>";

       echo $table;
   }
}

?>
