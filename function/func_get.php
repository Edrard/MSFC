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
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php
    function get_clan_province($config,$id)
    {
        $url = "http://".$config['gm_url']."/uc/clans/".$id."/provinces/list/" ;
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
        curl_close($ch);
        if ($data === false) {
            $header = curl_getinfo($ch);
            $err = curl_errno($ch);
            $errmsg = curl_error($ch);
            return array();
        }   else{
            return (json_decode(trim($data), true));
        }
    }
    function get_clan_attack($config,$id)
    {
        $url = "http://".$config['gm_url']."/uc/clans/".$id."/battles/list/" ;
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
        curl_close($ch);
        if ($data === false) {
            $header = curl_getinfo($ch);
            $err = curl_errno($ch);
            $errmsg = curl_error($ch);
            return array();
        }   else{
            return (json_decode(trim($data), true));
        }
    }
    function get_api_roster($clan_id,$config)
    {
        $url = 'http://api.'.$config['gm_url']."/community/clans/".$clan_id."/api/1.1/?source_token=Intellect_Soft-WoT_Mobile-unofficial_stats" ;
        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data === false) {
            $header = curl_getinfo($ch);
            $err = curl_errno($ch);
            $errmsg = curl_error($ch);
            return array();
        }   else{
            return (json_decode(trim($data), true));
        }
    }

    function multiget($inurls, &$res,$config,$transit,$roster,$lang,$multi = 0)
    {
        global $db,$cache;
        $timeout = 10;
        $tcurl = $config['pars'];
        $num = $config['multiget'];
        $urlss = array_chunk($inurls,$num,TRUE);
        foreach($urlss as $id => $urls){
            if ($tcurl == 'curl'){
                $curl = new CURL();
                $curl->retry = 2;
                $opts = array( CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => $timeout );
                foreach($urls as $key => $link){
                    $curl->addSession( $link, $key, $opts );
                }  
                $result = $curl->exec();  
                $curl->clear();
            }   elseif($tcurl == 'mcurl') {
                $curl = new MCurl; 
                $curl->threads = 100;  
                $curl->timeout = 15;    
                $curl->sec_multiget($urls, $result);
            }   else {
                foreach($urls as $id => $link){
                   $ch[$id] = curl_init();
                   curl_setopt($ch[$id], CURLOPT_URL, $link);
                   curl_setopt($ch[$id], CURLOPT_RETURNTRANSFER, 1);
                   curl_setopt($ch[$id], CURLOPT_FAILONERROR, true);
                   curl_setopt($ch[$id], CURLOPT_CONNECTTIMEOUT, $timeout);
                   curl_setopt($ch[$id], CURLOPT_HTTPHEADER, array(
                     "X-Requested-With: XMLHttpRequest",
                     "Accept: text/html, */*",
                     "User-Agent: Mozilla/3.0 (compatible; easyhttp)",
                     "Connection: Keep-Alive",
                   ));
                }
                $mh = curl_multi_init();
                foreach($ch as $id => $h) curl_multi_add_handle($mh,$h);
                $running = null;
                do{     curl_multi_exec($mh, $running);
                }while($running > 0);
                foreach($ch as $id => $h){
                  $result[$id] = curl_multi_getcontent( $h );
                }
                foreach($ch as $id => $h){
                  curl_multi_remove_handle($mh, $h);
                }
                curl_multi_close($mh);
                unset($ch);
            }
            if($multi != 0){
                foreach($result as $name => $val){
                    $res[$name] = $val;
                }   
            }else{
                foreach($result as $name => $val){ 
                    $json = json_decode($val,TRUE);
                    if($json['status'] == 'ok' && $json['status_code'] == 'NO_ERROR'){
                        $transit = insert_stat($json,$roster[$name],$config,$transit);
                        $res[$name] = pars_data2($json,$name,$config,$lang,$roster[$name]);
                        $cache->set($name, $res[$name],ROOT_DIR.'/cache/players/');  
                    }
                }
            }
            unset($result,$json);

        }
    }

    function get_api_tanks($config)
    {
        $url = 'http://api.'.$config['gm_url']."/encyclopedia/vehicles/api/1.0/?source_token=Intellect_Soft-WoT_Mobile-unofficial_stats" ;
        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        if ($data === false) {
            $header = curl_getinfo($ch);
            $err = curl_errno($ch);
            $errmsg = curl_error($ch);
            return array();
        }   else{
            return (json_decode(trim($data), true));
        }
    }
?>