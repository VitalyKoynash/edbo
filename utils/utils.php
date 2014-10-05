<?php


function check_guid ($sessionId) {
    if (is_null($sessionId)) {
        return false;
    }
    //return preg_match("/([a-f\d]{8})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{12})/i", $sessionId);
    return preg_match("/([a-f\d]{8})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{12})/i", $sessionId)==0?false:true;
}

function getDateNow () {
    return date ("d.m.Y h:i:s");
}

if (!function_exists("mb_trim")) 
{
    function mb_trim( $string ) 
    { // '/\\A\s*(.*[^\s])?\s*\\z/u'
        //$string = mb_ereg_replace( '\\A\s*(.*[^\s])?\s*\\z', "", $string ); 
    
        return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$string); 
    }
}

function strtotimef($stime,$format=''){
    if( trim($format)=='' )return strtotime($stime);
    $artimer = array(
        'd'=>'([0-9]{2})',
        'j'=>'([0-9]{1,2})',
        'F'=>'([a-z]{3,10})',
        'm'=>'([0-9]{2})',
        'M'=>'([a-z]{3})',
        'n'=>'([0-9]{1,2})',
        'Y'=>'([0-9]{4})',
        'y'=>'([0-9]{2})',
        'i'=>'([0-9]{2})',
        's'=>'([0-9]{2})',
        'h'=>'([0-9]{2})',
        'H'=>'([0-9]{2})',
        '#'=>'\\#',
        ' '=>'\\s',
    );
    $arttoval = array(
        'j'=>'d',
        'f'=>'m',
        'n'=>'m',
    );
    $reg_format = '#'.strtr($format,$artimer).'#Uis';
    if( preg_match_all('#[djFmMnYyishH]#',$format,$list) and preg_match($reg_format,$stime,$list1) ){
        $item = array('h'=>'00','i'=>'00','s'=>'00','m'=>1,'d'=>1,'y'=>1970);
        foreach($list[0] as $key=>$valkey){
            if( !isset($arttoval[strtolower($valkey)]) )
                $item[strtolower($valkey)] = $list1[$key+1];
            else
                $item[$arttoval[strtolower($valkey)]] = $list1[$key+1];
        }
        return strtotime($item['h'].':'.$item['i'].':'.$item['s'].' '.$item['d'].' '.$item['m'].' '.$item['y']);
    }else return false;
}




/*
 * 
 */

$valid_keys = array(
        'FIO'  => 'ПІБ',
'SpecClasifierCode' => 'Код напряму',
'SpecIndastryName' => 1,
'SpecDirectionName' => 'Напрям',
'SpecSpecialityName' => 'Спеціальність',
'PersonRequestStatusTypeName' => 'Статус заявки',
'PersonEnteranceTypeName' => 1,
'PersonRequestExaminationCauseName' => 1,
'PersonEducationFormName' => 'Форма навчання',
'KonkursValue' => 'Конк. бал',
'QualificationName' => 'ОКР',
'PersonDocumentTypeName' => 'Док. для вступу',
'EntrantDocumentSeries' => 'Серія док. для вступу',
'EntrantDocumentNumbers' => 'Номер док. для вступу',
'EntrantDocumentDateGet' => 'Дата видачі док для вступу',
'EntrantDocumentIssued' => 'Ким видан док. для вступу',
'EntrantDocumentValue' => 'Середній бал док для вступу',
'ForeignTypeName' => 'Іноземці',
'ContactPhone' => 'Телефон',
'ContactMobile' => 'Мобільний тел.',
'EntranceCodes' => 'Коди умов вступу',
'Birthday' => 'Дата народження',
'ИНН' => 'ІПН',
'DocumentSeries' => 1,
'DocumentNumbers' => 1,
'DocumentDateGet'  => 1,
'DocumentIssued' => 1,
'PersonDocumentTypeName'  => 'Док',
    );
function valid_keys($key, $valid_keys){
    if (isset($valid_keys[$keys]))
        return true;
    
    return false;
}

function buildTableRows($arrOfData, $createHeader) {
    
    //print_r($arrOfData);
    //return;
    ob_start();
    
    
    $arr = array();
    $idx = 0;
    foreach ($arrOfData as $key1 => $data) {
        //print "$key1 => <br>";
        
        //print_r($data);
        
        //print "<br>";
        
        foreach ($data as $key2 => $columns) {
           // if (!valid_keys($key2,$valid_keys))
            //    continue;
            
            if (!isset($arr[strval($key2).strval($idx)])) {
                $arr[strval($key2).strval($idx)] = array ();
            }
            $arr[strval($key2).strval($idx)][] = $columns;
            $idx++;
        }
        
    }
    //print_r($arr);
    //return;
    // column name
    if ($createHeader != false) {
        echo '<tr class="request_list" >';
        foreach ($arr as $key1 => $value) {
             echo '<td align="center">';
             echo $key1;
             echo '</td>';
        }
        echo '</tr>';
    }
    // data 
    $rows = 0;
    
    foreach ($arr as $key => $column) {
        $rows = count($column);
        break;
    }
    
    for ($i = 0; $i < $rows; $i++)
    {
        echo '<tr class="request_list" >';
         foreach ($arr as $key => $column) {
            echo '<td>';
            if (isset($column[$i]))
                echo $column[$i];
            
            echo '</td>';
         }
        echo '</tr>';
    }
    
    $out = ob_get_contents();
    ob_clean();
    
    return $out;
    
}

function GetInTranslit($string) {
	$replace=array(
		"'"=>"",
		"`"=>"",
		"а"=>"a","А"=>"a",
		"б"=>"b","Б"=>"b",
		"в"=>"v","В"=>"v",
		"г"=>"g","Г"=>"g",
		"д"=>"d","Д"=>"d",
		"е"=>"e","Е"=>"e",
		"ж"=>"zh","Ж"=>"zh",
		"з"=>"z","З"=>"z",
		"и"=>"i","И"=>"i",
		"й"=>"y","Й"=>"y",
		"к"=>"k","К"=>"k",
		"л"=>"l","Л"=>"l",
		"м"=>"m","М"=>"m",
		"н"=>"n","Н"=>"n",
		"о"=>"o","О"=>"o",
		"п"=>"p","П"=>"p",
		"р"=>"r","Р"=>"r",
		"с"=>"s","С"=>"s",
		"т"=>"t","Т"=>"t",
		"у"=>"u","У"=>"u",
		"ф"=>"f","Ф"=>"f",
		"х"=>"h","Х"=>"h",
		"ц"=>"c","Ц"=>"c",
		"ч"=>"ch","Ч"=>"ch",
		"ш"=>"sh","Ш"=>"sh",
		"щ"=>"sch","Щ"=>"sch",
		"ъ"=>"","Ъ"=>"",
		"ы"=>"y","Ы"=>"y",
		"ь"=>"","Ь"=>"",
		"э"=>"e","Э"=>"e",
		"ю"=>"yu","Ю"=>"yu",
		"я"=>"ya","Я"=>"ya",
		"і"=>"i","І"=>"i",
		"ї"=>"yi","Ї"=>"yi",
		"є"=>"e","Є"=>"e"
	);
	return $str=iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
}


function read_csv($filename) {
  //$file = $_POST['file'];
  $csv_lines  = file($filename);
  $values = array();
  if(is_array($csv_lines))
  {
    //разбор csv
    $cnt = count($csv_lines);
    for($i = 0; $i < $cnt; $i++)
    {
      $line = $csv_lines[$i];
      $line = trim($line);
      //указатель на то, что через цикл проходит первый символ столбца
      $first_char = true;
      //номер столбца
      $col_num = 0;
      $length = strlen($line);
      for($b = 0; $b < $length; $b++)
      {
        //переменная $skip_char определяет обрабатывать ли данный символ
        if($skip_char != true)
        {
          //определяет обрабатывать/не обрабатывать строку
          ///print $line[$b];
          $process = true;
          //определяем маркер окончания столбца по первому символу
          if($first_char == true)
          {
            if($line[$b] == '"')
            {
              $terminator = '";';
              $process = false;
            }
            else
              $terminator = ';';
            $first_char = false;
          }

          //просматриваем парные кавычки, опредляем их природу
          if($line[$b] == '"')
          {
            $next_char = $line[$b + 1];
            //удвоенные кавычки
            if($next_char == '"')
              $skip_char = true;
            //маркер конца столбца
            elseif($next_char == ';')
            {
              if($terminator == '";')
              {
                $first_char = true;
                $process = false;
                $skip_char = true;
              }
            }
          }

          //определяем природу точки с запятой
          if($process == true)
          {
            if($line[$b] == ';')
            {
               if($terminator == ';')
               {

                  $first_char = true;
                  $process = false;
               }
            }
          }

          if($process == true)
            $column .= $line[$b];

          if($b == ($length - 1))
          {
            $first_char = true;
          }

          if($first_char == true)
          {

            $values[$i][$col_num] = $column;
            $column = '';
            $col_num++;
          }
        }
        else
          $skip_char = false;
      }
    }
  }
  
  return $values;
};
  // var_dump($values);

function save_to_cache ($data, $cacheKey,  $params) {
    $lifetime = 172800;
    $cacheDir = @$params['cacheDir'] or './';
    // Кэшируем данные на 2 дня
    $frontCache = new Phalcon\Cache\Frontend\Data(array(
        "lifetime" => $lifetime
    ));
    
    // Создаем компонент, который будем кэшировать из "Выходных данных" в "Файл"
    // Устанавливаем папку для кэшируемых файлов - важно сохранить символ "/" в конце пути
    $cache = new Phalcon\Cache\Backend\File($frontCache, array(
        //"cacheDir" => "../cache/"
        "cacheDir" => $cacheDir
    ));
    
    // Пробуем получить закэшированные записи
    //$cacheKey = 'robots_order_id.cache';
    /*
    $robots    = $cache->get($cacheKey);
    if ($robots === null) {

        // $robots может иметь значение NULL из-за того, что истекла годность хранения или данных просто не существует
        // Получим данные из БД
        $robots = Robots::find(array("order" => "id"));

        // Сохраняем их в кэше
        $cache->save($cacheKey, $robots);
    }
     * */
    $cache->delete($cacheKey);
    // Сохраняем их в кэше
    
    try {
        $res = $cache->save($cacheKey, $data);
    }  catch (Phalcon\Cache\Exception $ex) {
        return $ex->getMessage();
    }
    return true;
}

function get_from_cache ($cacheKey,  $params) {
    $lifetime = 172800;
    $cacheDir = @$params['cacheDir'] or './';
    // Кэшируем данные на 2 дня
    $frontCache = new Phalcon\Cache\Frontend\Data(array(
        "lifetime" => $lifetime
    ));
    
    // Создаем компонент, который будем кэшировать из "Выходных данных" в "Файл"
    // Устанавливаем папку для кэшируемых файлов - важно сохранить символ "/" в конце пути
    $cache = new Phalcon\Cache\Backend\File($frontCache, array(
        //"cacheDir" => "../cache/"
        "cacheDir" => $cacheDir
    ));
    
    if ($cache->exists($cacheKey)) {
        return $cache->get($cacheKey);
    }
    return null;
}


/*
PHP Redirect with HTTP Status Code

Create a sample function called movePage() in sitefunctions.php (note I'm not the author of the following I found it somewhere else on the Internet):
*/
function movePage($num,$url){
   static $http = array (
       100 => "HTTP/1.1 100 Continue",
       101 => "HTTP/1.1 101 Switching Protocols",
       200 => "HTTP/1.1 200 OK",
       201 => "HTTP/1.1 201 Created",
       202 => "HTTP/1.1 202 Accepted",
       203 => "HTTP/1.1 203 Non-Authoritative Information",
       204 => "HTTP/1.1 204 No Content",
       205 => "HTTP/1.1 205 Reset Content",
       206 => "HTTP/1.1 206 Partial Content",
       300 => "HTTP/1.1 300 Multiple Choices",
       301 => "HTTP/1.1 301 Moved Permanently",
       302 => "HTTP/1.1 302 Found",
       303 => "HTTP/1.1 303 See Other",
       304 => "HTTP/1.1 304 Not Modified",
       305 => "HTTP/1.1 305 Use Proxy",
       307 => "HTTP/1.1 307 Temporary Redirect",
       400 => "HTTP/1.1 400 Bad Request",
       401 => "HTTP/1.1 401 Unauthorized",
       402 => "HTTP/1.1 402 Payment Required",
       403 => "HTTP/1.1 403 Forbidden",
       404 => "HTTP/1.1 404 Not Found",
       405 => "HTTP/1.1 405 Method Not Allowed",
       406 => "HTTP/1.1 406 Not Acceptable",
       407 => "HTTP/1.1 407 Proxy Authentication Required",
       408 => "HTTP/1.1 408 Request Time-out",
       409 => "HTTP/1.1 409 Conflict",
       410 => "HTTP/1.1 410 Gone",
       411 => "HTTP/1.1 411 Length Required",
       412 => "HTTP/1.1 412 Precondition Failed",
       413 => "HTTP/1.1 413 Request Entity Too Large",
       414 => "HTTP/1.1 414 Request-URI Too Large",
       415 => "HTTP/1.1 415 Unsupported Media Type",
       416 => "HTTP/1.1 416 Requested range not satisfiable",
       417 => "HTTP/1.1 417 Expectation Failed",
       500 => "HTTP/1.1 500 Internal Server Error",
       501 => "HTTP/1.1 501 Not Implemented",
       502 => "HTTP/1.1 502 Bad Gateway",
       503 => "HTTP/1.1 503 Service Unavailable",
       504 => "HTTP/1.1 504 Gateway Time-out"
   );
   header($http[$num]);
   header ("Location: $url");
}


//First include sitefunctions.php and than call movePage() as follows:
/*
<?php
@include("/path/to/sitefunctions.php");
 
// Move page with 301 http status code
movePage(301,"http://www.cyberciti.biz/");
?>
 
 */