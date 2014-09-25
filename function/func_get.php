<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-11-19 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, Shw  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.1.2 $
*
*/



function checkfield($a) {
    if (!empty($a)) {
        return "&fields=".implode(",", $a);
    }   else {
        return '';
    }
}

function checkparam($p) {
  $return = '';
  if(!empty($p)) {
    foreach($p as $k => $v) {
      if(is_array($v)) {
        $return .= '&'.$k.'='.implode(",", $v);
      } else {
        $return .= '&'.$k.'='.$v;
      }
    }
  }
  return $return;
}

function checklang($p) {
  global $api_langs;
  $return = '';
  $lang_api = array_keys($api_langs);
  if(in_array($p,$lang_api)) {
    $return = '&language='.$p;
  } else {
    $return = '&language=en';
  }
  return $return;
}

function get_clan_v2($clanid, $whattoload, $config, $fields_array = array()) {
    //whattoload accept 'info', 'provinces', 'battles'
    $fields = checkfield($fields_array);
    $url = $config['td']."/wot/clan/".$whattoload."/?application_id=".$config['application_id']."&clan_id=".$clanid.$fields;
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "X-Requested-With: XMLHttpRequest",
        "Accept: text/html, */*",
        "User-Agent: Mozilla/3.0 (compatible; easyhttp)",
        "Connection: Keep-Alive",
    ));
    $data = curl_exec($ch);
    if ($data === false or curl_errno($ch)) {
        $return = array('status' => 'error', 'error' => array('message' => curl_error($ch)) );
        curl_close($ch);
        return $return;
    }   else {
        curl_close($ch);
        return (json_decode(trim($data), true));
    }
}

function get_api($method, $param_array = array(), $fields_array = array()) {
    global $config;

    $api_lang = checklang($config['api_lang']);
    $param = checkparam($param_array);
    $fields = checkfield($fields_array);
    $url = $config['td'].'/wot/'.$method.'/?application_id='.$config['application_id'].$api_lang.$param.$fields;
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "X-Requested-With: XMLHttpRequest",
        "Accept: text/html, */*",
        "User-Agent: Mozilla/3.0 (compatible; easyhttp)",
        "Connection: Keep-Alive",
    ));
    $data = curl_exec($ch);
    if ($data === false or curl_errno($ch)) {
        $return = array('status' => 'error', 'error' => array('message' => curl_error($ch)) );
        curl_close($ch);
        return $return;
    }   else {
        curl_close($ch);
        return (json_decode(trim($data), true));
    }
}

//------------------------------------------------------------------------------

function multiget_v2($paramtoload, $clanids, $whattoload, $fields_array = array(), $param_array = array(), $result = array()) {

    global $config;

    $fields = checkfield($fields_array);
    $param = checkparam($param_array);
    $api_lang = checklang($config['api_lang']);
    $timeout = 100;
    $urls = $res = array();

    $clids = array_chunk($clanids, $config['multiget'], TRUE);
    foreach($clids as $arrid => $ids){
        $toload = checkparam(array($paramtoload => $ids));
        $urls[$arrid] = $config['td']."/wot/".$whattoload."/?application_id=".$config['application_id'].$param.$api_lang.$toload.$fields;
    }
    unset ($fields_array,$toload,$param_array);
    if ($config['pars'] == 'curl'){
        $curl = new CURL();
        $curl->retry = 2;
        $opts = array(CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_CONNECTTIMEOUT => $timeout,
                      CURLOPT_FOLLOWLOCATION => false
        );
        usleep(5);
        $url_chunk = array_chunk($urls, 10, TRUE);
        foreach($url_chunk as $chunk) {
          foreach($chunk as $key => $url) $curl->addSession($url, $key, $opts);
          $result = array_special_merge($curl->exec(),$result);
          $curl->clear();
        }
    }   elseif($config['pars'] == 'mcurl') {
        $curl = new MCurl;
        $curl->threads = 10;
        $curl->timeout = 15;
        $curl->sec_multiget($urls, $result);
    }   else {
        $url_chunk = array_chunk($urls, 10, TRUE);
        foreach($url_chunk as $chunk) {
          foreach($chunk as $key => $url) {
              $ch[$key] = curl_init();
              curl_setopt($ch[$key], CURLOPT_URL, $url);
              curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch[$key], CURLOPT_FAILONERROR, true);
              curl_setopt($ch[$key], CURLOPT_CONNECTTIMEOUT, $timeout);
              curl_setopt($ch[$key], CURLOPT_FOLLOWLOCATION, false);
              curl_setopt($ch[$key], CURLOPT_HTTPHEADER, array(
                  "X-Requested-With: XMLHttpRequest",
                  "Accept: text/html, */*",
                  "User-Agent: Mozilla/3.0 (compatible; easyhttp)",
                  "Connection: Keep-Alive",
              ));
          }
          $mh = curl_multi_init();
          foreach($ch as $key => $h) curl_multi_add_handle($mh,$h);
          $running = null;
          do {curl_multi_exec($mh, $running);} while($running > 0);
          foreach($ch as $key => $h){
              $result[$key] = curl_multi_getcontent( $h );
          }
          foreach($ch as $clanid => $h){
              curl_multi_remove_handle($mh, $h);
          }
          curl_multi_close($mh);
          unset($ch, $mh);
        }
    }
    unset($urls,$url_chunk,$chunk);
    if (isset($result)) {
        foreach ($result as $key => $val) {
            $json = json_decode($val,TRUE);
            if ((isset($json['status'])) && ($json['status'] == 'ok')) {
                foreach ($json['data'] as $clanid => $data) {
                    $res[$clanid]['status'] = $json['status'];
                    $res[$clanid]['count'] = '1';
                    $res[$clanid]['data'] = $data;
                }
            }   else {
                foreach ($clids[$key] as $id) {
                    if (isset($json['error']['message'])) {
                        $message = 'Get current error from WG: ('.$json['error']['message'].')';
                    }   else {
                        $message = 'No connection to WG API';
                    }
                    $res[$id]['status'] = 'error';
                    $res[$id]['error']['message'] = $message;
                }
            }
            unset ($result[$key], $json, $val, $data);
        }
    }   else {
        foreach ($clanids as $id) {
            $res[$id]['status'] = 'error';
            $res[$id]['error']['message'] = '(M)Incoming array to load is empty!';
        }
    }
    return $res;
}

function get_wn8() {
    $url = 'http://www.wnefficiency.net/exp/expected_tank_values_latest.json';
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "X-Requested-With: XMLHttpRequest",
        "Accept: text/html, */*",
        "User-Agent: Mozilla/3.0 (compatible; easyhttp)",
        "Connection: Keep-Alive",
    ));
    $data = curl_exec($ch);
    if ($data === false or curl_errno($ch)) {
        $return = array('status' => 'error', 'error' => array('message' => curl_error($ch)) );
        curl_close($ch);
        return $return;
    }   else {
        curl_close($ch);
        return (json_decode(trim($data), true));
    }
}
?>