<?php
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
?>