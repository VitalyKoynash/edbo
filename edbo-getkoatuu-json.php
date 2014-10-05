<?php
set_time_limit(300);
include './edbo-initsoap.php';
include './utils/utils.php';

$url = $_SERVER["SCRIPT_NAME"];
$break = Explode('/', $url);
$file = $break[count($break) - 1];
$cachefile = 'cached-'.substr_replace($file ,"",-4).'.json';
// Открыть текстовый файл
$f = fopen($cachefile, "r");

if (!is_null($f)) {
// Записать текст
   // fread($f, $data); 
    $contents = fread($f, filesize($cachefile));
// Закрыть текстовый файл
    fclose($f);
    
    echo $contents;
    return;
}


$sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);

$res = $eg->{'LanguagesGet'}($sessionId);
$eg->printLastError($sessionId);
$dLanguages = $res['dLanguages'];
        
$Id_Language = $dLanguages['Id_Language'];

$dateNow = getDateNow();

$res = $eg->KOATUUGetL1($sessionId, $dateNow, $Id_Language);
 
$dKOATUU1 = $res['dKOATUU'];

$data = "";

$data.= "[";

for ($i1 = 0; $i1 < count($dKOATUU1); $i1++)
{
    $item1 = $dKOATUU1[$i1];
    $KOATUUFullName1 = $item1['KOATUUFullName'];
    $KOATUUCodeL1 = $item1['KOATUUCodeL1'];
    
    if ($KOATUUCodeL1== "")            continue;
    
     $Type = $item1['Type'];
    if (!is_null($Type) && $Type != "") {
        $Type.= '. ';
    } else {
        $Type = '';
    }
    
    $data.= "\r\n".'{ "id" : "'.$KOATUUCodeL1.'" , "parent" : "#", "text" : "'.$Type.$KOATUUFullName1.'" },';
    
    $res = $eg->KOATUUGetL2($sessionId, $dateNow, $Id_Language, $KOATUUCodeL1);
    
    if (!isset($res['dKOATUU']))
            continue;
    
    $dKOATUU2 = $res['dKOATUU'];
    for ($i2 = 0; $i2 < count($dKOATUU2); $i2++)
    {
        $item2 = $dKOATUU2[$i2];
        $KOATUUFullName2 = $item2['KOATUUName'];
        $KOATUUCodeL2 = $item2['KOATUUCodeL2'];
        
        if ($KOATUUCodeL2== "")            continue;
        
        $Type = $item2['Type'];
        if (!is_null($Type) && $Type != "") {
            $Type.= '. ';
        } else {
            $Type = '';
        }
            
        $data.= "\r\n".'{ "id" : "'.$KOATUUCodeL2.'" , "parent" : "'.$KOATUUCodeL1.'", "text" : "'.$Type.$KOATUUFullName2.'" },';
        
        $res = $eg->KOATUUGetL3($sessionId, $dateNow, $Id_Language, $KOATUUCodeL2,"");
        
        //if (!isset($res['dKOATUU']))
        if (count($res) == 0)
            continue;
        
        $dKOATUU3 = $res['dKOATUU'];
        print "<br>".count($dKOATUU3)."<br>";
        
        for ($i3 = 0; $i3 < count($dKOATUU3); $i3++)
        {
            $item3 = $dKOATUU3[$i3];
            $KOATUUFullName3 = $item3['KOATUUName'];
            
            //$Type = $item3['Type'];
            //if (strlen($Type) > 0) $Type.= '. ';
            $Type = $item3['Type'];
            if (!is_null($Type) && $Type != "") {
                $Type.= '. ';
            } else {
                $Type = '';
            }
            
            $KOATUUCodeL3 = $item3['KOATUUCodeL3'];
            if ($KOATUUCodeL3== "")            continue;
            
            $data.= "\r\n".'{ "id" : "'.$KOATUUCodeL3.'" , "parent" : "'.$KOATUUCodeL2.'", "text" : "'.$Type.$KOATUUFullName3.'" },';
            
            //break;
        }
        //break;
    }
    //break;
    
}
//echo '['.$data.']';
$data.= "\r\n]";

$url = $_SERVER["SCRIPT_NAME"];
$break = Explode('/', $url);
$file = $break[count($break) - 1];
$cachefile = 'cached-'.substr_replace($file ,"",-4).'.json';
// Открыть текстовый файл
$f = fopen($cachefile, "w");
// Записать текст
fwrite($f, $data); 
// Закрыть текстовый файл
fclose($f);

echo $data;
/*
echo ' [
        { "id" : "ajson1", "parent" : "#", "text" : "Simple root node" },
        { "id" : "ajson2", "parent" : "#", "text" : "Root node 2", "state" : { "opened" : true } },
        { "id" : "ajson3", "parent" : "ajson2", "text" : "Child 1" },
        { "id" : "ajson4", "parent" : "ajson2", "text" : "Child 2" },
     ]

';
 */
