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
* @version     $Rev: 3.0.2 $
*
*/



function checkfield($a) {
    if (!empty($a)) {
        return "&fields=".implode(",", $a);
    }   else {
        return '';
    }
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

function get_player_v2($plid, $whattoload, $config, $fields_array = array()) {
    //whattoload accept 'info', 'ratings', 'tanks'
    $fields = checkfield($fields_array);
    $url = $config['td']."/wot/account/".$whattoload."/?application_id=".$config['application_id']."&account_id=".$plid.$fields;
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
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
        $ret = json_decode(trim($data), true);
        return ($ret);
    }
}

function get_tank_v2($config, $fields_array = array()) {
    $fields = checkfield($fields_array);
    $url = $config['td']."/wot/encyclopedia/tanks/?application_id=".$config['application_id'].$fields;
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
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

function get_tankinfo_v2($tankid, $config, $fields_array = array()) {
    $fields = checkfield($fields_array);
    $url = $config['td']."/wot/encyclopedia/tanks/?application_id=".$config['application_id']."&tank_id=".$tankid.$fields;
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
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

function multiget_v2($clanids, $whattoload, $config, $fields_array = array(), $result = array()) {
    //whattoload accept 'clan/info', 'clan/provinces', 'clan/battles', 'account/info', 'account/ratings', 'account/tanks', 'encyclopedia/tankinfo'
    $fields = checkfield($fields_array);
    $timeout = 100;
    $tcurl = $config['pars'];
    $num = $config['multiget'];

    $clids = array_chunk($clanids, $num, TRUE);
    $second = explode('/',$whattoload);
    if (($second[0] == 'clan') || ($second[0] == 'account')) {
        $second[0] .= '_id';
    }    elseif ($second[0] == 'encyclopedia') {
        $second[0] = 'tank_id';
    }    elseif ($second[0] == 'ratings') {
        $second[0] = 'type=all&account_id';
    }
    $urls = $res = array();
    foreach($clids as $arrid => $ids){
        $toload = implode(',',$ids).',';
        $toload = substr($toload, 0, strlen($toload)-1);
        $urls[$arrid] = $config['td']."/wot/".$whattoload."/?application_id=".$config['application_id']."&".$second[0]."=".$toload.$fields;
    }
    unset ($fields_array, $clids, $toload);
    if ($tcurl == 'curl'){
        $curl = new CURL();
        $curl->retry = 2;
        $opts = array(CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $timeout
        );
        usleep(5); 
        foreach($urls as $key => $url) $curl->addSession($url, $key, $opts);
        $result = array_special_merge($curl->exec(),$result);
        $curl->clear();

    }   elseif($tcurl == 'mcurl') {
        $curl = new MCurl;
        $curl->threads = 100;
        $curl->timeout = 15;
        $curl->sec_multiget($urls, $result);
    }   else {
        foreach($urls as $key => $url) {
            $ch[$key] = curl_init();
            curl_setopt($ch[$key], CURLOPT_URL, $url);
            curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch[$key], CURLOPT_FAILONERROR, true);
            curl_setopt($ch[$key], CURLOPT_CONNECTTIMEOUT, $timeout);
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
    unset($urls);   
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
                foreach ($clanids as $id) {
                    if (isset($json['error']['message'])) {
                        $message = 'Get current error from WG: ('.$json['error']['message'].')';
                    }   else {
                        $message = 'Param config[multiget] too big!';
                    }
                    $res[$id]['status'] = 'error';
                    $res[$id]['error']['message'] = $message;
                }
            }
            unset ($result[$key], $json, $val);
        }
    }   else {
        foreach ($clanids as $id) {
            $res[$id]['status'] = 'error';
            $res[$id]['error']['message'] = '(M)Incoming array to load is empty!';
        }
    }
    return $res;
}
?>