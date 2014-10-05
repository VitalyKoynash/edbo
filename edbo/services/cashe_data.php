<?php

require_once '../../utils/utils.php';
require_once "../../utils/FGetCSV.php";

$action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
$cacheKey = filter_input(INPUT_POST, "cacheKey", FILTER_SANITIZE_STRING);

ob_start();

$result = array();

$result['input']['action'] = $action;
$result['input']['cacheKey'] = $cacheKey;
$result['error']['code'] = 0;
$result['error']['message'] = '';


if ($action == 'set_cashe_csv') {
    $result['_FILES'] = $_FILES;
    if (is_null($cacheKey)) {
        $result['error']['code'] = 1;
        $result['error']['message'] = 'need key for save cashe!';
        $result['echo'] = ob_get_clean();
        echo json_encode($result);
        return;
    }
    
    
    
    
    if (isset($_FILES["csv_file"]) && $_FILES["csv_file"]["error"] == UPLOAD_ERR_OK) {
        $fn = $_FILES["csv_file"]['tmp_name'];
        $f = @fopen($fn, "r") or die("Could not open \"$fn\" - " . $php_errormsg);
        $Data = array();
        while ($list = File_FGetCSV::fgetcsv($f, 65536, ";")) {
            $Data[] = $list;
        }
        
        foreach ($Data as $idx1 => $value1) {
            foreach ($value1 as $idx2 => $value2) {
                $Data[$idx1][$idx2] = mb_trim($value2);
            }
            //break;
        }
    
        fclose($f);
        $params = array();
        $params['cacheDir'] = "../../cache/";
        
        $res = save_to_cache ($Data, $cacheKey,  $params);
        if (!$res || strlen(ob_get_contents()) > 0) {
            $result['error']['code'] = 1;
            $result['error']['message'] = $res.PHP_EOL.  ob_get_contents();
        }
        
    
    }
    
    $result['echo'] = ob_get_clean();
    echo json_encode($result);
    return;

} elseif ($action == 'get_table_data') {
    $result['data'] = array();
    if (is_null($cacheKey)) {
        $result['error']['code'] = 1;
        $result['error']['message'] = 'need key for save cashe!';
        $result['echo'] = ob_get_clean();
        echo json_encode($result);
        return;
    }
    
    $Data = array();
    $params = array();
    $params['cacheDir'] = "../../cache/";

    $res = get_from_cache ($cacheKey,  $params);
    if ($res === null || strlen(ob_get_contents()) > 0) {
        $result['error']['code'] = 1;
        $result['error']['message'] = $res.PHP_EOL.  ob_get_contents();
    }
    //$Data = $res;
    $columns = array();
    foreach ($res as $idx1 => $value1) {
        foreach ($value1 as $idx2 => $value2) {
            $columns[] = $value2;
        }
        //unset ($res[0]);
        break;
    }
    //$table = $res;
    
    
    $table = array();
    $idx_i = -1;
    foreach ($res as $idx1 => $value1) {
        
        $idx_i++;
        if ($idx_i == 0)            continue;
        
        
        //$idx_j = -1;
        foreach ($value1 as $idx2 => $value2) {
            //$idx_j++;
            $table[$idx1-1][$idx2] = $value2;
        }
        //break;
    }
    
    
    $result['data'] = $table;
    
    
    $title = array();
    foreach ($columns as $key => $value) {
        $title[] = array("title" => $value);
    }
    $result['data_title'] = $title;
    //$result['data_title2'] = json_encode($title);
    
    $result['echo'] = ob_get_clean();
    echo json_encode($result);
    return;

} elseif ($action == 'get_cashe') {
    $result['data'] = array();
    if (is_null($cacheKey)) {
        $result['error']['code'] = 1;
        $result['error']['message'] = 'need key for save cashe!';
        $result['echo'] = ob_get_clean();
        echo json_encode($result);
        return;
    }
    
    $Data = array();
    $params = array();
    $params['cacheDir'] = "../../cache/";

    $res = get_from_cache ($cacheKey,  $params);
    if ($res === null || strlen(ob_get_contents()) > 0) {
        $result['error']['code'] = 1;
        $result['error']['message'] = $res.PHP_EOL.  ob_get_contents();
    }
    
    $result['data'] = $res;//
    
    
    $result['echo'] = ob_get_clean();
    echo json_encode($result);
    return;

}

$result['error']['code'] = 1;
$result['error']['message'] = 'no set action!';
$result['echo'] = ob_get_clean();
echo json_encode($result);

