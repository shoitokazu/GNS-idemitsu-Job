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
                $data[$i][$tableName]["nenKaihi-houjin"] = $obj["nenKaihi-houjin"];  
                $data[$i][$tableName]["nenKaihi-kojin"] = $obj["nenKaihi-kojin"];
                $data[$i][$tableName]["rentalBoat"] = $obj["rentalBoat"];
                $data[$i][$tableName]["eigyouShuIre"] = $obj["eigyouShuIre"];
                $data[$i][$tableName]["tsukouKanri-uriage"] = $obj["tsukouKanri-uriage"];
                $data[$i][$tableName]["tsukoKanri-shiIre"] = $obj["tsukoKanri-shiIre"];
                $data[$i][$tableName]["houjinKousu"] = $obj["houjinKousu"];
                $data[$i][$tableName]["kojinKousu"] = $obj["kojinKousu"];
                $data[$i][$tableName]["shuuteiHanbai-uriage"] = $obj["shuuteiHanbai-uriage"]; 
                $data[$i][$tableName]["shuuteiHanbai-shiire"] = $obj["shuuteiHanbai-shiire"];
                $data[$i][$tableName]["shuuteiHanbai-gesshoTanaOroshi"] = $obj["shuuteiHanbai-gesshoTanaOroshi"];
                $data[$i][$tableName]["shuuteiHanbai-getsumatsuTanaOroshi"] = $obj["shuuteiHanbai-getsumatsuTanaOroshi"];
                $data[$i][$tableName]["shuuteiHanbai-hanbaiSekiShuu-boatYaught"] = $obj["shuuteiHanbai-hanbaiSekiShuu-boatYaught"];
                $data[$i][$tableName]["shuuteiHanbai-shopTsurigu-uriage"] = $obj["shuuteiHanbai-shopTsurigu-uriage"];
                $data[$i][$tableName]["shuuteiHanbai-shopTsurigu-shiire"] = $obj["shuuteiHanbai-shopTsurigu-shiire"];
                $data[$i][$tableName]["shuuteiHanbai-shopGesshoTanaOroshi"] = $obj["shuuteiHanbai-shopGesshoTanaOroshi"];
                $data[$i][$tableName]["shuuteiHanbai-shopGetsumatsuTanaOroshi"] = $obj["shuuteiHanbai-shopGetsumatsuTanaOroshi"];
                $data[$i][$tableName]["menkuyou-uriage"] = $obj["menkuyou-uriage"];
                $data[$i][$tableName]["menkyou-shiire"] = $obj["menkyou-shiire"];
                $data[$i][$tableName]["menkyou-jyukenshasu"] = $obj["menkyou-jyukenshasu"];
                $data[$i][$tableName]["sc-uriage"] = $obj["sc-uriage"];
                $data[$i][$tableName]["sc-shiire"] = $obj["sc-shiire"];
                $data[$i][$tableName]["sc-gesshoTanaOroshi"] = $obj["sc-gesshoTanaOroshi"];
                $data[$i][$tableName]["sc-getsumatsuTanaOroshi"] = $obj["sc-getsumatsuTanaOroshi"];
                $data[$i][$tableName]["sc-menteKeiyaku-uriage"] = $obj["sc-menteKeiyaku-uriage"];
                $data[$i][$tableName]["sc-menteKeiyaku-shiire"] = $obj["sc-menteKeiyaku-shiire"];
                $data[$i][$tableName]["sc-menteKeiyaku-SenKazu"] = $obj["sc-menteKeiyaku-SenKazu"];
                $data[$i][$tableName]["sc-gasolineSuRyo-kl"] = $obj["sc-gasolineSuRyo-kl"];
                $data[$i][$tableName]["sc-keiYuSuRyo-kl"] = $obj["sc-keiYuSuRyo-kl"];
                $data[$i][$tableName]["sc-nenryou-hanbai-uriage"] = $obj["sc-nenryou-hanbai-uriage"];
                $data[$i][$tableName]["sc-nenryou-hanbai-shiire"] = $obj["sc-nenryou-hanbai-shiire"];
                $data[$i][$tableName]["sc-nenryou-geshoTanaOroshi"] = $obj["sc-nenryou-geshoTanaOroshi"];
                $data[$i][$tableName]["sc-nenryou-getsumatsuTanaOroshi"] = $obj["sc-nenryou-getsumatsuTanaOroshi"];
                $data[$i][$tableName]["sc-nenkanYoukoSagyou-uriage"] = $obj["sc-nenkanYoukoSagyou-uriage"];
                $data[$i][$tableName]["sc-nenkanYoukoSagyou-shiire"] = $obj["sc-nenkanYoukoSagyou-shiire"];
                $data[$i][$tableName]["sc-nankanYoukoKeiyakuSenKazu"] = $obj["sc-nankanYoukoKeiyakuSenKazu"];
                $data[$i][$tableName]["shisetsu-lodge"] = $obj["shisetsu-lodge"];
                $data[$i][$tableName]["shisetsu-shukuhakusha"] = $obj["shisetsu-shukuhakusha"];
                $data[$i][$tableName]["shisetsu-chushajou"] = $obj["shisetsu-chushajou"];
                $data[$i][$tableName]["shisetsu-nyujoudaisu"] = $obj["shisetsu-nyujoudaisu"];
                $data[$i][$tableName]["shisetsu-bridal-uriage"] = $obj["shisetsu-bridal-uriage"];
                $data[$i][$tableName]["shisetsu-bridal-shiire"] = $obj["shisetsu-bridal-shiire"];
                $data[$i][$tableName]["shisetsu-kumiKazu"] = $obj["shisetsu-kumiKazu"];
                $data[$i][$tableName]["shisetsu-sailLocker"] = $obj["shisetsu-sailLocker"];
                $data[$i][$tableName]["shisetsu-sailLocker-keiyakuKosu"] = $obj["shisetsu-sailLocker-keiyakuKosu"];
                $data[$i][$tableName]["shisetsu-tenentShunyu-captainHook"] = $obj["shisetsu-tenentShunyu-captainHook"];
                $data[$i][$tableName]["shisetsu-blueMomento"] = $obj["shisetsu-blueMomento"];
                $data[$i][$tableName]["shisetsu-funatoOtomobiru"] = $obj["shisetsu-funatoOtomobiru"];
                $data[$i][$tableName]["shisetsu-progress"] = $obj["shisetsu-progress"];
                $data[$i][$tableName]["shisetsu-restauran-uriage"] = $obj["shisetsu-restauran-uriage"];
                $data[$i][$tableName]["shisetsu-restauran-shiire"] = $obj["shisetsu-restauran-shiire"];
                $data[$i][$tableName]["shisetsu-shunyu-uriage"] = $obj["shisetsu-shunyu-uriage"];
                $data[$i][$tableName]["shisetsu-shunyu-shiire"] = $obj["shisetsu-shunyu-shiire"];
                $data[$i][$tableName]["shisetsu-event-uriage"] = $obj["shisetsu-event-uriage"];
                $data[$i][$tableName]["shisetsu-event-shiire"] = $obj["shisetsu-event-shiire"];
                $data[$i][$tableName]["shisetsu-senpakuHoken"] = $obj["shisetsu-senpakuHoken"];
                $data[$i][$tableName]["shisetsu-gairaisen"] = $obj["shisetsu-gairaisen"];
                $data[$i][$tableName]["shisetsu-jihanki-shunyu"] = $obj["shisetsu-jihanki-shunyu"];
                $data[$i][$tableName]["shisetsu-sonota-uriage"] = $obj["shisetsu-sonota-uriage"];
                $data[$i][$tableName]["shisetsu-sonota-shiire"] = $obj["shisetsu-sonota-shiire"];
		$i++;
	}
	return $data;
    }

function merge_array ($array1, $array2) 
    {  
        if (is_array($array2) && count($array2)) 
            {  
                foreach ($array2 as $m => $n) 
                    {  
                        if (is_array($n) && count($n)) 
                               {  
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
