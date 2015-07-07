<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2011-10-24 11:54:02 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, Shw  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.1.2 $
*
*/
if (preg_match ("/func.php/", $_SERVER['PHP_SELF']))
{
    header ("Location: ./index.php");
    exit;
}
function echop ($param = '') {
    echo '<div align="left"><pre>';
    print_r($param);
    echo '</pre></div>';
}
function array_resort($array,$param){
    $new = array();
    foreach($array as $val){
        $new[$val[$param]] = $val;
    }
    return $new;
}
function redirect($url)
{
    preg_match('%^(https?://)([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i',$url,$matches);
    if(count($matches) == 0){
        $url = 'http://'.$url;
    }
    echo "<script>window.location = '".$url."'</script>";
}
function reform($array){
    $new = array();
    foreach($array as $val){
        $new[] = end($val);
    }
    return $new;
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function now(){
    return strtotime(date("Y-m-d H:i:s"));
}
function today(){
    return strtotime(date("Y-m-d"));
}
function fnow(){
    return strtotime(date("Y-m-d H:i"));
}

function error_rep($error,$lang){
    if($error == 1){
        return $lang['error_2'];    
    }elseif($error == 21){
        return $lang['error_3'];
    }

}
function key_compare_func($array1, $array2)
{
    $new = array();
    $array_keys1 = array_keys($array1);
    $array_keys2 = array_keys($array2);
    foreach($array2 as $key2 => $val2){
        if(!in_array($key2,$array_keys1)){
            $new['new'][$key2] = $val2; 
        }
    }
    foreach($array1 as $key1 => $val1){
        if(!in_array($key1,$array_keys2)){
            $new['unset'][$key1] = true; 
        }
    }
    return $new;
}
function unset_diff(&$res,$unset){
    $res_keys = array_keys($res);
    foreach(array_keys($unset) as $name){
        if(in_array($name,$res_keys)){
            unset($res[$name]);
        }
    }    
}
function array_special_merge($array1,$array2)
{
    foreach($array2 as $key2 => $val2){
        if(!array_key_exists($key2,$array1)){
            $array1[$key2] = $val2;
        }else{
            $array1[] = $val2;
        }

    }
    return $array1;

}
function array_special_merge_res(&$array1,$array2)
{
    foreach($array2 as $key2 => $val2){
        if(!array_key_exists($key2,$array1)){
            $array1[$key2] = $val2;
        }else{
            $array1[] = $val2;
        }

    }
    return $array1;

}

function number_transform($num)
{
    $data['I'] = 1;
    $data['II'] = 2;
    $data['III'] = 3;
    $data['IV'] = 4;
    $data['V'] = 5;
    $data['VI'] = 6;
    $data['VII'] = 7;
    $data['VIII'] = 8;
    $data['IX'] = 9;
    $data['X'] = 10;
    $return = $data[trim($num)];

    return $return;

}

function lockin_mysql()
{
    global $db,$exec_time;
    //$check_if = $db->query("SELECT value FROM config WHERE name = 'lockin';")->fetch();

    $check_if = $db->select('SELECT value FROM `config` WHERE name = "lockin";',__line__,__file__,'fetch');
    if((now() - $check_if['value']) > ($exec_time + 5)){
        $db->insert('UPDATE `config` SET value = "'.now().'" WHERE name = "lockin";',__line__,__file__);
        return true;
    }elseif($check_if['value'] != 0){
        return false;
    }else{
        $db->insert('UPDATE `config` SET value = "'.now().'" WHERE name = "lockin";',__line__,__file__);
        return true;
    }
}
function lockout_mysql()
{
    global $db;
    $db->insert('UPDATE `config` SET value = "0" WHERE name = "lockin";',__line__,__file__);
}
function lock_check()
{
    global $db,$exec_time;
    //$check_if = $db->query("SELECT value FROM config WHERE name = 'lockin';")->fetch();

    $check_if = $db->select('SELECT value FROM `config` WHERE name = "lockin";',__line__,__file__,'fetch');
    if((now() - $check_if['value']) > ($exec_time + 5)){
        $db->insert('UPDATE `config` SET value = "0" WHERE name = "lockin";',__line__,__file__);
        return true;
    }elseif($check_if['value'] != 0){
        return false;
    }else{
        return true;
    }

}
if (!function_exists('ctype_alnum')) {
    function ctype_alnum($text) {
        return !preg_match('/^\w*$/', $text);
    }
}


function get_headers_curl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,            $url);
    curl_setopt($ch, CURLOPT_HEADER,         true);
    curl_setopt($ch, CURLOPT_NOBODY,         true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT,        10);

    $r = @curl_exec($ch);
    $r = @explode("\n", $r);
    return $r;
}

function get_config()
{
    global $db;
    $err = array('lang' => 'en', 'server' => 'ru', 'error' => 2);

    $test = $db->select('SHOW TABLES LIKE "config";',__line__,__file__,'column');

    if(empty($test)) {
        return $err;
    }

    $tmp = $db->select('SELECT * FROM `config`;',__line__,__file__);
    if(isset($tmp) and !empty($tmp)) {
        foreach($tmp as $val){
            $new[$val['name']] = $val['value'];
        }
    } else {
        return $err;
    }       
    return $new;
}      
function read_tabs($where = '')
{
    global $db;
    return array_resort($db->select("SELECT * FROM `tabs` $where ORDER BY id ASC;",__line__,__file__),'id');
}
function sort_id($a, $b)
{
    return strnatcmp($a["id"], $b["id"]);
}
function update_array($array,$update)
{
    foreach($update as $key => $val){
        if(isset($array[$key])){
            $array[$key] = $val;
        }
    }
    return $array;
}

function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
{
    if ($fp = @opendir($source_dir))
    {
        $filedata    = array();
        $new_depth    = $directory_depth - 1;
        $source_dir    = rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        while (FALSE !== ($file = readdir($fp)))
        {
            if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
            {
                continue;
            }

            if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
            {
                $filedata[$file] = directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
            }
            else
            {
                $filedata[] = $file;
            }
        }

        closedir($fp);
        return $filedata;
    }

    return FALSE;
}
function indent($json) {

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

            // If this character is the end of an element, 
            // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element, 
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}
function get_post($name,$type = 'both'){ // $type can be both, get, post
    switch ($type) {
        case 'both':
            if(isset($_POST[$name])){
                return xss_clean($_POST[$name]);
            }else if(isset($_GET[$name])){
                return xss_clean($_GET[$name]);
            }
            return FALSE;
            break;
        case 'get':
            if(isset($_GET[$name])){
                return xss_clean($_GET[$name]);
            }
            break;
            return FALSE;
        case 'post':
            if(isset($_POST[$name])){
                return xss_clean($_POST[$name]);
            }
            break;
            return FALSE;
        default:
            return FALSE;
    }
}
//Clean XSS
function xss_clean($data)
{
    // Fix &entity\n;
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do
    {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    // we are done...
    return $data;
}
?>