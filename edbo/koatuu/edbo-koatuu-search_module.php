<?php
include_once dirName(__FILE__).'/../security/security.php';
//include '../edbo-provider/edbo-initsoap.php';
//include '../../utils/utils.php';

set_time_limit(60*60);
ob_start();

function convert_to_ASCII($str) {
    if (mb_detect_encoding ($str, "auto") == 'UTF-8')
        return mb_convert_encoding($str, "ASCII");
    return $str;
}

$sessionId = get_input_str("sessionId");
$search = get_input_str("search");
$action = get_input_str("action");
$cacheKey = get_input_str("cacheKey");

$result['input']['sessionId'] = $sessionId;
$result['input']['cacheKey'] = $cacheKey;
$result['input']['action'] = $action;
$result['input']['search'] = $search;


$result['error']['code'] = 1;
$result['error']['message'] = 'Invalid action';


if ($action == 'search_koatuu') {
    $res = $eg->{'LanguagesGet'}($sessionId);
    
    if (is_null($res) || count($res) == 0) {
        $result['error']['code'] = 1;
        $result['error']['message'] = 'call LanguagesGet() FAILED! '.$eg->printLastError($sessionId);
        $result['echo'] = ob_get_clean();
        echo json_encode($result);
        return;
    }
    
    $dLanguages = $res['dLanguages'];
    $Id_Language = $dLanguages['Id_Language'];

    $res = $eg->KOATUUGet($sessionId, $eg->getDateNow(), $Id_Language, "", 1, $search, "", 0);
    
    $result['call']['function'] = 'KOATUUGet';
    $result['call']['params'] = array($sessionId, $eg->getDateNow(), $Id_Language, "", 1, $search, "", 0);
    $result['res'] = $res;

    if (is_null($res)|| count($res) == 0 ) {
      
        $result['error']['code'] = 1;
        $result['error']['message'] = 'call KOATUUGet() FAILED! '.$eg->printLastError($sessionId);
        $result['echo'] = ob_get_clean();
        echo json_encode($result);
        return;
    }
    $data = array();
    $dKOATUU = $res['dKOATUU'];
    
    //$result['data_title'] = array("title" = >'Код КОАТУУ','Название');$title[] = array("title" => 'ФИО');
    $title[] = array("title" => 'Код КОАТУУ');
    $title[] = array("title" => 'ФИО');
    $result['data_title'] = $title;
     
    
        
    if (isset($dKOATUU[0])) { // много вариантов
        for ($i=0; $i < count($dKOATUU); $i++) {
            $item = $dKOATUU[$i];
            $KOATUUCodeL3 = $item['KOATUUCodeL3'];
            $KOATUUFullName = $item['KOATUUFullName'];
            $data_item = array();
            $data_item[] = $KOATUUCodeL3;
            $data_item[] = $KOATUUFullName;

            $data[] = $data_item;
        }
    } else {

            $item = $dKOATUU;
            $KOATUUCodeL3 = $dKOATUU['KOATUUCodeL3'];
            $KOATUUFullName = $dKOATUU['KOATUUFullName'];
            
            $data_item[] = $KOATUUCodeL3;
            $data_item[] = $KOATUUFullName;

            $data[] = $data_item;
    }

    $result['error']['code'] = 0;
    $result['data'] = $data;
    $result['echo'] = ob_get_clean();
    echo json_encode($result);
    return;
}

$result['echo'] = ob_get_clean();
echo json_encode($result);

return;

$res = $eg->{'LanguagesGet'}($sessionId);
$eg->printLastError($sessionId);
$dLanguages = $res['dLanguages'];
$Id_Language = $dLanguages['Id_Language'];

$res = $eg->KOATUUGet($sessionId, getDateNow(), $Id_Language, "", 1, $search, "", 0);
//$eg->printLastError($sessionId);


if (isset($res['dKOATUU']))
{
	$dKOATUU = $res['dKOATUU'];
	if (isset($dKOATUU[0])) { // много вариантов

		echo '<table>';
		for ($i=0; $i < count($dKOATUU); $i++) {
			
			$item = $dKOATUU[$i];
			$KOATUUCodeL3 = $item['KOATUUCodeL3'];
			$KOATUUFullName = $item['KOATUUFullName'];
			echo '<tr>';
				echo '<td>',$KOATUUCodeL3,'</td>';
				echo '<td>',$KOATUUFullName,'</td>';
			echo '</tr>';
		}
		echo '</table>';
		return;

	} else {
		
		$item = $dKOATUU;
		$KOATUUCodeL3 = $dKOATUU['KOATUUCodeL3'];
		$KOATUUFullName = $dKOATUU['KOATUUFullName'];
		echo '<table>';
		echo '<tr>';
			echo '<td>',$KOATUUCodeL3,'</td>';
			echo '<td>',$KOATUUFullName,'</td>';
		echo '</tr>';
		echo '</table>';
		return;
	}

}

echo 'Ничего не нашлось';
