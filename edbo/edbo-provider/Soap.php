<?php
namespace EDBO
{
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirName(__FILE__).'/../configure/config.php';


/**
 * Soap for DEBO
 *
 * @author VItaly Koynash
 */
class Soap {
    //put your code here
    private $soapClient ; // EDBOGuides
    
    public $debug = \FALSE;
    
    public function isOkSoap() {
        if (is_null($this->soapClient)) {
            return FALSE;
        }
        return TRUE;
    }
      
    private function buildObject($data) {
        //return $data;
        //var_dump($data);
        if ($this->debug == \TRUE) {
            print '<p class="soap_debug">';
            print_r($data);
            print '</p>';
        }
        //print '<p class="soap_debug">&nbsp;</p>';
        $res = NULL;
        if (is_array($data) || is_object($data)) {
            $res = array();
            foreach ($data as $key => $value) {
                
                if (is_string($key)) {
                    //print '<p class="soap_debug">type: ['.$key.'] val : '.gettype($value).':</p>';
                    
                    switch (gettype($value)){
                        case "string":
                            if ($this->debug == TRUE) print '<p class="soap_debug"> ['.$key.']  = '.$value.'</p>';
                            $res[$key] = $value;
                            break;
                        case "integer":
                            if ($this->debug == TRUE) print '<p class="soap_debug"> ['.$key.']  = '.$value.'</p>';
                            $res[$key] = $value;
                            break;
                        case "int":
                            if ($this->debug == TRUE) print '<p class="soap_debug"> ['.$key.']  = '.$value.'</p>';
                            $res[$key] = $value;
                            break;
                        case "any":
                            // xml
                            if ($this->debug == TRUE) print '<p class="soap_debug"> ['.$key.']  = '.  htmlspecialchars($value).'</p>';
                            $res[$key] = simplexml_load_string("<?xml version=\"1.0\"?><document>".$value."</document>");
                            break;
                        default:
                            if ($this->debug == TRUE) print '<p class="soap_debug">==== level down ====</p>';
                            if ($this->debug == TRUE) print '<p class="soap_debug">['.$key.'] : '.gettype($value).'</p>';
                            $res[$key] = $this->buildObject($value);
                            break;
                    }
                } elseif (is_integer($key)) {
                    if (gettype($value) != "object") {
                        if ($this->debug == TRUE) print '<p class="soap_debug"> ['.$key.']  = '.  htmlspecialchars($value).'</p>';
                    } else {
                        if ($this->debug == TRUE) print '<p class="soap_debug"> ['.$key.']  = {object} </p>';
                    }
                    $res[$key] = $this->buildObject($value);
                }
            }
        } else {
            //print "$data";
            $res = $data;
        }
        /*
        if (is_array($res) && count($res) == 1) {
            $res = $res[0];
        }
         * 
         */
        return $res;
    }
    
    
/*
    public static function myErrorHandler($errno, $errstr, $errfile, $errline) {
        if ( E_RECOVERABLE_ERROR===$errno ) {
            echo "FATAL ERROR service EDBO";
            throw new \Exception($errstr, $errno, 0, $errfile, $errline);
            
        }
        return false;
    }
    
    public static function exc_handler($exception) {
        $log = $exception->getMessage() . "\n" . $exception->getTraceAsString() . LINEBREAK;
        if ( ini_get('log_errors') )
            error_log($log, 0);
        //print("Unhandled Exception" . (DEBUG ? " - $log" : ''));
        echo 'EDBO FATAL ERROR';
    }
       */
    public function invoke ($method, $params) {
        //\set_error_handler( 'Soap::myErrorHandler' );
        //\set_exception_handler( 'Soap::exc_handler' );
        
        try {
            $save_errlogin = ini_get('display_errors');
            
            if ($method == 'Login1') {
                ini_set('display_errors','Off');
                error_reporting('E_ALL');
            }
             
            ob_start();
            try {
                
                
            
                $invs = $this->soapClient->__soapCall ($method, array($params));
                $sresult = $invs->{$method."Result"};
                
                
            
            } catch (Exception $ex) {
                $err = ob_get_contents();
                header('Location: ./edbo-login.php?sessionId='.$err); 
            }
            
            ob_clean();
            
            if ($method == 'Login') {
                ini_set('display_errors',$save_errlogin);
                error_reporting('E_ALL');
            }
            
            $res = $this->buildObject($sresult);
            if ($this->debug == TRUE) print '<p class="soap_debug">____________ result _____________</p>';
            if ($this->debug == TRUE) print '<p class="soap_debug">';
            if ($this->debug == TRUE) var_dump($res);
            if ($this->debug == TRUE) print '</p>';
            return $res;
        } catch (SoapFault $ex) {
            print "Exception in try call method $method<br>\n";
            print "Parameters:<br>\n";
            print_r($params);
            print "<br>\n";
            throw $ex;
        }
    }
    
    
    public function __construct($address) {
        try {
            
            $this->soapClient = new \SoapClient ($address, array ('encoding'=>'utf-8'));
        } catch (SoapFault $ex) {
            print "Exception by create Soap by address $address <br>\n";
            print "<br>\n";
            throw $ex;
        }    
    }
}

}