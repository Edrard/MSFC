<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.0.2 $
    *
    */
?>
<?php

    function get_clan_province($config,$id)
    {
        //echo $search;
        $url = "http://".$config['gm_url']."/uc/clans/".$id."/provinces/?type=table" ;
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
        $err = curl_errno($ch);
        $errmsg = curl_error($ch) ;
        curl_close($ch);
        if($err == 0){
            return (json_decode(trim($data), true));
        }else{
            return array();
        }
    }
    function get_clan_attack($config,$id)
    {
        //echo $search;
        $url = "http://".$config['gm_url']."/uc/clans/".$id."/battles/?type=table" ;
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
        $err = curl_errno($ch);
        $errmsg = curl_error($ch) ;
        curl_close($ch);
        if($err == 0){
            return (json_decode(trim($data), true));
        }else{
            return array();
        }
    }
    function get_api_roster($clan_id,$config)
    {
        $url = 'api.'.$config['gm_url']."/community/clans/".$clan_id."/api/1.1/?source_token=Intellect_Soft-WoT_Mobile-unofficial_stats" ;
        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch) ;
        $header = curl_getinfo($ch);
        curl_close($ch);
        if($err == 0){
            return json_decode(trim($data), true);
        }else{
            return array();
        }

    }
    function link_creater($vals,$config){


        if(count($vals) > 0){
            foreach($vals as $val){
                $link[$val['account_name']] = $config['td'].'/uc/accounts/'.$val['account_id'].'/api/1.8/?source_token=Intellect_Soft-WoT_Mobile-unofficial_stats';
            }
        }


        //print_r($new);
        return $link;
    }

    function multiget($inurls, &$result,$tcurl = 'curl')
    {
        $urlss = array_chunk($inurls,10,TRUE);
        $result = array();
        if($tcurl == 'curl'){    
            foreach($urlss as $urls){
                $curl = new CURL();
                $curl->retry = 2;
                $opts = array( CURLOPT_RETURNTRANSFER => true );  
                foreach($urls as $key => $link){
                    $curl->addSession( $link, $key, $opts );
                }  
                $res = $curl->exec();  
                $curl->clear();
                $result = array_special_merge($result,$res);
            }
        }else{
            foreach($urlss as $urls){
                $curl = new MCurl; 
                $curl->threads = 100;  
                $curl->timeout = 15;    
                $curl->sec_multiget($urls, $result);
            }  
        }
    }
?>
