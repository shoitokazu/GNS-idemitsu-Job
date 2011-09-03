<?php

class dbCallActions
{
    //put your code here
         
    public function tableMaker($tableName)
    {
        $db = db_connect();
	$SQL ="SELECT * FROM $tableName WHERE timePeriod > 213";
	$result = $db->query($SQL) or die(print_r($db->error));
	$i = 0;
	$data = array();
	while ($obj = $result->fetch_array(MYSQL_ASSOC))
	{
            
		$data[$i][$tableName]["mishikomikin"] = $obj["mishikomikin"];
		$data[$i][$tableName]["hokanryou-shutei"] = $obj["hokanryou-shutei"];
		$data[$i][$tableName]["hokanryou-pw"] = $obj["hokanryou-pw"];
               $data[$i][$tableName]["hokanzougen"] = $obj["hokanzougen"];
                $data[$i][$tableName]["hokansukei"] = $obj["hokansukei"];
		
		$i++;
	}
	return $data;
}

function merge_array ($array1, $array2) {  
  
    if (is_array($array2) && count($array2)) {  
      foreach ($array2 as $m => $n) {  
        if (is_array($n) && count($n)) {  
          $array1[$m] = $this->merge_array($array1[$m], $n);  
        } else {  
          $array1[$m] = $n;  
        }  
      }  
    } else {  
      $array1 = $array2;  
    }  
  
    return $array1;  
} 


    
}

?>
