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
        $error = 0;
        $data = array();
        $request = "GET /uc/clans/".$id."/provinces/?type=table HTTP/1.0\r\n";
        //$request = "GET /uc/clans/?type=table&search=\"The Red\"".$off." HTTP/1.0\r\n"; 
        //echo $request;
        $request.= "Accept: text/html, */*\r\n";
        $request.= "User-Agent: Mozilla/3.0 (compatible; easyhttp)\r\n";
        $request.= "X-Requested-With: XMLHttpRequest\r\n";
        $request.= "Host: ".$config['gm_url']."\r\n";
        $request.= "Connection: Keep-Alive\r\n";
        $request.= "\r\n";
        $n = 0;
        while(!isset($fp)){  
            $fp = fsockopen($config['gm_url'], 80, $errno, $errstr, 15);
            if($n == 3){
                break;
            }
            $n++;
        }
        if (!$fp) {
            echo "$errstr ($errno)<br>\n";
        } else {

            stream_set_timeout($fp,20);
            $info = stream_get_meta_data($fp);

            fwrite($fp, $request);
            $page = '';

            while (!feof($fp) && (!$info['timed_out'])) { 
                $page .= fgets($fp, 4096);
                $info = stream_get_meta_data($fp);
            }
            fclose($fp);
            if ($info['timed_out']) {
                $error = 1; //Connection Timed Out
            }
        }
        if($error == 0){
            preg_match_all("/{\"request(.*?)success\"}/", $page, $matches);
            $data = (json_decode($matches[0][0], true));
        }
        $new = &$data;
        return $new;
    }
    function get_clan_attack($config,$id)
    {
        //echo $search;
        $error = 0;
        $data = array();
        $request = "GET /uc/clans/".$id."/battles/?type=table HTTP/1.0\r\n";
        //$request = "GET /uc/clans/?type=table&search=\"The Red\"".$off." HTTP/1.0\r\n"; 
        //echo $request;
        $request.= "Accept: text/html, */*\r\n";
        $request.= "User-Agent: Mozilla/3.0 (compatible; easyhttp)\r\n";
        $request.= "X-Requested-With: XMLHttpRequest\r\n";
        $request.= "Host: ".$config['gm_url']."\r\n";
        $request.= "Connection: Keep-Alive\r\n";
        $request.= "\r\n";
        $n = 0;
        while(!isset($fp)){  
            $fp = fsockopen($config['gm_url'], 80, $errno, $errstr, 15);
            if($n == 3){
                break;
            }
            $n++;
        }
        if (!$fp) {
            echo "$errstr ($errno)<br>\n";
        } else {

            stream_set_timeout($fp,20);
            $info = stream_get_meta_data($fp);

            fwrite($fp, $request);
            $page = '';

            while (!feof($fp) && (!$info['timed_out'])) { 
                $page .= fgets($fp, 4096);
                $info = stream_get_meta_data($fp);
            }
            fclose($fp);
            if ($info['timed_out']) {
                $error = 1; //Connection Timed Out
            }
        }
        if($error == 0){
            preg_match_all("/{\"request(.*?)success\"}/", $page, $matches);
            $data = (json_decode($matches[0][0], true));
        }
        $new = &$data;
        return $new;
    }
    function get_player($clan_id,$config)
    {
        $error = 0;
        $data = array();
        $request = "GET /uc/clans/".$clan_id."/members/?type=table HTTP/1.0\r\n";
        $request.= "Accept: text/html, */*\r\n";
        $request.= "User-Agent: Mozilla/3.0 (compatible; easyhttp)\r\n";
        $request.= "X-Requested-With: XMLHttpRequest\r\n";
        $request.= "Host: ".$config['gm_url']."\r\n";
        $request.= "Connection: Keep-Alive\r\n";
        $request.= "\r\n";

        $wot_host=$config['gm_url'];
        $n = 1;
        while(!isset($fp)){
            $fp = fsockopen($config['gm_url'], 80, $errno, $errstr, 20);
            if($n == 3){
                break;
            }
            $n++;
        }
        if (!$fp) {
            echo "$errstr ($errno)<br>\n";
        } else {

            stream_set_timeout($fp,20);
            $info = stream_get_meta_data($fp);

            fwrite($fp, $request);
            $page = '';

            while (!feof($fp) && (!$info['timed_out'])) { 
                $page .= fgets($fp, 4096);
                $info = stream_get_meta_data($fp);
                //echo $page;
            }
            fclose($fp);
            if ($info['timed_out']) {
                $error = 1; //Connection Timed Out
            }

        }
        if($error == 0){
            preg_match_all("/{\"request(.*?)success\"}/", $page, $matches);
            $data = (json_decode($matches[0][0], true));
        }
        $new['error'] = &$error;
        $new['data'] = &$data;
        return $new;
    }

    function get_data($name,$trans,$stat_config,$tables)
    {
        global $db;      
        //$result = $db->query("select * from players WHERE name = '".$name."';")->fetch();

        $sql = "SELECT * FROM players WHERE name = '".$name."';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $result = $q->fetch();
        } else {
            print_r($q->errorInfo());
            die();
        }

        //Даты
        $new['date']['reg'] = $stat_config['reg'].' '.date('d.m.Y',$result['reg']);
        $new['date']['local'] = $stat_config['dateof'].' '.date('d.m.Y, H:i',$result['local']);
        $new['date']['reg_num'] = $result['reg'];
        $new['date']['local_num'] = $result['local'];

        //Общие результаты
        $new['overall'][$trans['games_p']] = $result['total'];
        $new['overall'][$trans['victories']] = $result['win'];
        $new['overall'][$trans['defeats']] = $result['lose'];
        $new['overall'][$trans['battles_s']] = $result['alive'];

        //Боевая эффективность
        $new['perform'][$trans['destroyed']] = $result['des'];
        $new['perform'][$trans['spotted']] = $result['spot'];
        $new['perform'][$trans['hit_ratio']] = $result['accure'];
        $new['perform'][$trans['damage']] = $result['dmg'];
        $new['perform'][$trans['capture']] = $result['cap'];
        $new['perform'][$trans['defend']] = $result['def'];

        //Боевой опыт
        $new['exp'][$trans['total_exp']] = $result['exp'];
        $new['exp'][$trans['exp_battle']] = $result['averag_exp'];
        $new['exp'][$trans['exp_max']] = $result['max_exp'];

        //Рейтинг

        $new['rating'][$trans['gr']]['type'] = 'GR';;
        $new['rating'][$trans['gr']]['link'] = $stat_config['rating_link'].'gr.png';;
        $new['rating'][$trans['gr']]['name'] = $trans['gr'];
        $new['rating'][$trans['gr']]['value'] = $result['gr_v'];
        $new['rating'][$trans['gr']]['place'] = $result['gr_p'];

        $new['rating'][$trans['wb']]['type'] = 'W/B';;
        $new['rating'][$trans['wb']]['link'] = $stat_config['rating_link'].'wb.png';;
        $new['rating'][$trans['wb']]['name'] = $trans['wb'];
        $new['rating'][$trans['wb']]['value'] = $result['wb_v'];
        $new['rating'][$trans['wb']]['place'] = $result['wb_p'];

        $new['rating'][$trans['eb']]['type'] = 'E/B';;
        $new['rating'][$trans['eb']]['link'] = $stat_config['rating_link'].'eb.png';;
        $new['rating'][$trans['eb']]['name'] = $trans['eb'];
        $new['rating'][$trans['eb']]['value'] = $result['eb_v'];
        $new['rating'][$trans['eb']]['place'] = $result['eb_p'];

        $new['rating'][$trans['win']]['type'] = 'WIN';;
        $new['rating'][$trans['win']]['link'] = $stat_config['rating_link'].'win.png';;
        $new['rating'][$trans['win']]['name'] = $trans['win'];
        $new['rating'][$trans['win']]['value'] = $result['win_v'];
        $new['rating'][$trans['win']]['place'] = $result['win_p'];

        $new['rating'][$trans['gpl']]['type'] = 'GPL';;
        $new['rating'][$trans['gpl']]['link'] = $stat_config['rating_link'].'gpl.png';;
        $new['rating'][$trans['gpl']]['name'] = $trans['gpl'];
        $new['rating'][$trans['gpl']]['value'] = $result['gpl_v'];
        $new['rating'][$trans['gpl']]['place'] = $result['gpl_p'];

        $new['rating'][$trans['cpt']]['type'] = 'CPT';;
        $new['rating'][$trans['cpt']]['link'] = $stat_config['rating_link'].'cpt.png';;
        $new['rating'][$trans['cpt']]['name'] = $trans['cpt'];
        $new['rating'][$trans['cpt']]['value'] = $result['cpt_v'];
        $new['rating'][$trans['cpt']]['place'] = $result['cpt_p'];

        $new['rating'][$trans['dmg']]['type'] = 'DMG';;
        $new['rating'][$trans['dmg']]['link'] = $stat_config['rating_link'].'dmg.png';;
        $new['rating'][$trans['dmg']]['name'] = $trans['dmg'];
        $new['rating'][$trans['dmg']]['value'] = $result['dmg_v'];
        $new['rating'][$trans['dmg']]['place'] = $result['dmg_p'];

        $new['rating'][$trans['dpt']]['type'] = 'DPT';;
        $new['rating'][$trans['dpt']]['link'] = $stat_config['rating_link'].'dpt.png';;
        $new['rating'][$trans['dpt']]['name'] = $trans['dpt'];
        $new['rating'][$trans['dpt']]['value'] = $result['dpt_v'];
        $new['rating'][$trans['dpt']]['place'] = $result['dpt_p'];

        $new['rating'][$trans['frg']]['type'] = 'FRG';;
        $new['rating'][$trans['frg']]['link'] = $stat_config['rating_link'].'frg.png';;
        $new['rating'][$trans['frg']]['name'] = $trans['frg'];
        $new['rating'][$trans['frg']]['value'] = $result['frg_v'];
        $new['rating'][$trans['frg']]['place'] = $result['frg_p'];

        $new['rating'][$trans['spt']]['type'] = 'SPT';;
        $new['rating'][$trans['spt']]['link'] = $stat_config['rating_link'].'spt.png';;
        $new['rating'][$trans['spt']]['name'] = $trans['spt'];
        $new['rating'][$trans['spt']]['value'] = $result['spt_v'];
        $new['rating'][$trans['spt']]['place'] = $result['spt_p'];

        $new['rating'][$trans['exp']]['type'] = 'EXP';;
        $new['rating'][$trans['exp']]['link'] = $stat_config['rating_link'].'exp.png';;
        $new['rating'][$trans['exp']]['name'] = $trans['exp'];
        $new['rating'][$trans['exp']]['value'] = $result['exp_v'];
        $new['rating'][$trans['exp']]['place'] = $result['exp_p'];

        $tank = array();
        $tank_rating = array();
        //print_r($tables);
        foreach($tables as $tab){
            //$result_tank = $db->query("SELECT * FROM ".$tab." WHERE id = '".$result['id']."';")->fetch();

            $sql = "SELECT * FROM ".$tab." WHERE id = '".$result['id']."';";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $result_tank = $q->fetch();
            } else {
                print_r($q->errorInfo());
                die();
            }

            if(is_array($result_tank)){
                foreach(array_keys($result_tank) as $val){
                    if(is_numeric($val)){
                        unset($result_tank[$val]);
                    }
                }
                $tank = array_merge($tank,$result_tank);
            }

            $sql = "SELECT * FROM rating_".$tab." WHERE id = '".$result['id']."';";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $rating_result_tank = $q->fetch();
            } else {
                print_r($q->errorInfo());
                die();
            }

            if(is_array($rating_result_tank)){
                foreach(array_keys($rating_result_tank) as $val){
                    if(is_numeric($val)){
                        unset($rating_result_tank[$val]);
                    }
                }
                $tank_rating = array_merge($tank_rating,$rating_result_tank);
            }

        }
        //print_r($tank);
        unset($result_tank,$rating_result_tank);
        $total = array();
        foreach($tank as $key => $game){
            if($key != 'id' && $game >= 0){
                if(strpos($key,'t')){
                    $total[$key] = $game;
                }else{
                    $win[$key] = $game;
                }
            }
        }
        //print_r($tank);
        unset($tank);
        if(is_array($total)){
            arsort($total,SORT_NUMERIC);
        }else{
            $total = array();
        }
        //$tank_name = $db->query("SELECT * FROM tanks;")->fetchAll();

        $sql = "SELECT * FROM tanks;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $tank_name = $q->fetchAll();
        } else {
            print_r($q->errorInfo());
            die();
        }

        $i = 0;
        foreach($total as $key => $game){
            //$tmp = explode('_',$key);
            $tkey = preg_replace("'(\D+)'si","",$key);;

            //$tkey = $tmp[0];
            if(isset($tank_name[$tkey-1]['lvl'])){
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['type'] = $tank_name[$tkey-1]['tank'];
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['total'] = $game; 
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['link'] = $tank_name[$tkey-1]['link'];
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['win'] = $win[$tkey.'_w'];
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['nation'] = $tank_name[$tkey-1]['nation'];
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['lvl'] = $tank_name[$tkey-1]['lvl'];
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['class'] = $tank_name[$tkey-1]['type'];     
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['spotted'] = $tank_rating[$tkey.'_sp'];
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['damageDealt'] = $tank_rating[$tkey.'_dd'];
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['survivedBattles'] = $tank_rating[$tkey.'_sb'];
                $new['tank'][($tank_name[$tkey-1]['lvl'])][$tank_name[$tkey-1]['type']][$tank_name[$tkey-1]['tank']]['frags'] = $tank_rating[$tkey.'_fr'];
            }
        }
        //$result_med = $db->query("select * FROM medals WHERE id = '".$result['id']."';")->fetch();

        $sql = "SELECT * FROM medals WHERE id = '".$result['id']."';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $result_med = $q->fetch();
        } else {
            print_r($q->errorInfo());
            die();
        }
        $medn = array();
        unset($result_med['id'],$result_med[0]);
        if(isset($result_med)){
            foreach($result_med as $name => $medal){
                if(!is_numeric($name)){
                    if($name == 'maxDiehardSeries'){
                        $medn['diehard']['max'] = $medal;
                        $medn['diehard']['max_name'] = 'maxDiehardSeries';    
                    }elseif($name == 'maxInvincibleSeries'){
                        $medn['invincible']['max'] = $medal;
                        $medn['invincible']['max_name'] = 'maxInvincibleSeries';    
                    }elseif($name == 'maxPiercingSeries'){
                        $medn['armorPiercer']['max'] = $medal;
                        $medn['armorPiercer']['max_name'] = 'maxPiercingSeries';    
                    }elseif($name == 'maxKillingSeries'){
                        $medn['handOfDeath']['max'] = $medal; 
                        $medn['handOfDeath']['max_name'] = 'maxKillingSeries';   
                    }elseif($name == 'maxSniperSeries'){
                        $medn['titleSniper']['max'] = $medal; 
                        $medn['titleSniper']['max_name'] = 'maxSniperSeries';   
                    }else{
                        $medn[$name]['value'] = $medal;
                    }
                }
            }


            foreach(array_keys($medn) as $name){
                $medn[$name]['title'] = $trans['medal_'.$name];     
            }
        }
        $medn['medalCarius']['img'] = 'images/medals/MedalCarius'.$medn['medalCarius']['value'].'.png';
        $medn['medalHalonen']['img'] = 'images/medals/MedalHalonen.png';
        $medn['invader']['img'] = 'images/medals/Invader.png';
        $medn['medalFadin']['img'] = 'images/medals/MedalFadin.png';
        $medn['armorPiercer']['img'] = 'images/medals/ArmorPiercer.png';
        $medn['medalEkins']['img'] = 'images/medals/MedalEkins'.$medn['medalEkins']['value'].'.png';
        $medn['mousebane']['img'] = 'images/medals/Mousebane.png';
        $medn['medalKay']['img'] = 'images/medals/MedalKay'.$medn['medalKay']['value'].'.png';
        $medn['defender']['img'] = 'images/medals/Defender.png';
        $medn['medalLeClerc']['img'] = 'images/medals/MedalLeClerc'.$medn['medalLeClerc']['value'].'.png';
        $medn['supporter']['img'] = 'images/medals/Supporter.png';
        $medn['steelwall']['img'] = 'images/medals/Steelwall.png';
        $medn['medalAbrams']['img'] = 'images/medals/MedalAbrams'.$medn['medalAbrams']['value'].'.png';
        $medn['medalPoppel']['img'] = 'images/medals/MedalPoppel'.$medn['medalPoppel']['value'].'.png';
        $medn['medalOrlik']['img'] = 'images/medals/MedalOrlik.png';
        $medn['handOfDeath']['img'] = 'images/medals/HandOfDeath.png';
        $medn['sniper']['img'] = 'images/medals/Sniper.png';
        $medn['warrior']['img'] = 'images/medals/Warrior.png';
        $medn['titleSniper']['img'] = 'images/medals/TitleSniper.png';
        $medn['medalWittmann']['img'] = 'images/medals/MedalBoelter.png';
        $medn['medalBurda']['img'] = 'images/medals/MedalBurda.png';
        $medn['scout']['img'] = 'images/medals/Scout.png';
        $medn['beasthunter']['img'] = 'images/medals/Beasthunter.png';
        $medn['kamikaze']['img'] = 'images/medals/Kamikaze.png';
        $medn['raider']['img'] = 'images/medals/Raider.png';
        $medn['medalOskin']['img'] = 'images/medals/MedalOskin.png';
        $medn['medalBillotte']['img'] = 'images/medals/MedalBillotte.png';
        $medn['medalLavrinenko']['img'] = 'images/medals/MedalLavrinenko'.$medn['medalLavrinenko']['value'].'.png';
        $medn['medalKolobanov']['img'] = 'images/medals/MedalKolobanov.png';
        $medn['invincible']['img'] = 'images/medals/Invincible.png';
        $medn['lumberjack']['img'] = 'images/medals/Invincible.png';
        $medn['tankExpert']['img'] = 'images/medals/TankExpert.png';
        $medn['diehard']['img'] = 'images/medals/Diehard.png';
        $medn['medalKnispel']['img'] = 'images/medals/MedalKnispel'.$medn['medalKnispel']['value'].'.png'; 
        $medn['medalBoelter']['img'] = 'images/medals/MedalBoelter.png';

        $medn['medalCarius']['type'] = 'major';
        $medn['medalHalonen']['type'] = 'epic';
        $medn['invader']['type'] = 'hero';
        $medn['medalFadin']['type'] = 'epic';
        $medn['armorPiercer']['type'] = 'special';
        $medn['medalEkins']['type'] = 'major';
        $medn['mousebane']['type'] = 'special';
        $medn['medalKay']['type'] = 'major';
        $medn['defender']['type'] = 'hero';
        $medn['medalLeClerc']['type'] = 'major';
        $medn['supporter']['type'] = 'hero';
        $medn['steelwall']['type'] = 'hero';
        $medn['medalAbrams']['type'] = 'major';
        $medn['medalPoppel']['type'] = 'major';
        $medn['medalOrlik']['type'] = 'epic';
        $medn['handOfDeath']['type'] = 'special';
        $medn['sniper']['type'] = 'hero';
        $medn['warrior']['type'] = 'hero';
        $medn['titleSniper']['type'] = 'special';
        $medn['medalWittmann']['type'] = 'epic';
        $medn['medalBurda']['type'] = 'epic';
        $medn['scout']['type'] = 'hero';
        $medn['beasthunter']['type'] = 'special';
        $medn['kamikaze']['type'] = 'special';
        $medn['raider']['type'] = 'special';
        $medn['medalOskin']['type'] = 'epic';
        $medn['medalBillotte']['type'] = 'epic';
        $medn['medalLavrinenko']['type'] =  'major';
        $medn['medalKolobanov']['type'] = 'epic';
        $medn['invincible']['type'] = 'special';
        $medn['lumberjack']['type'] = 'special';
        $medn['tankExpert']['type'] = 'special';
        $medn['diehard']['type'] = 'special';
        $medn['medalKnispel']['type'] =  'major';
        $medn['medalBoelter']['type'] = 'epic';

        foreach($medn as $name => $val){
            $nmedn[$val['type']][$name] = $val;
        }
        unset($nmedn['special']['lumberjack']);
        unset($nmedn['epic']['medalWittmann']);
        $new['medals'] = $nmedn;


        //print_r($new);
        //print_r($tank_name);
        return $new;   

    }

    function checker($val,$lang,$config,$tables,$error){
        global $db;
        //print_r($error);
        if($error == 0){
            $sql = "SELECT COUNT(id) FROM players WHERE name = '".$val['name']."' AND up < '".(now() - $config['cache']*3600)."';";
            //echo $sql;
            $q = $db->prepare($sql);
            $q->execute();
            $status = $q->fetchColumn();
        }else{
            $status = 0;
        }
        $sql = "SELECT COUNT(id) FROM players WHERE name = '".$val['name']."';";
        $q = $db->prepare($sql);
        $q->execute();
        $player_stat = $q->fetchColumn();

        if($status > 0 || $player_stat == 0){
            $link = $config['td'].'/uc/accounts/'.$val['account_id'].'/api/1.5/?source_token=Intellect_Soft-WoT_Mobile-unofficial_stats';
        }else{
            $data = get_data($val['name'],$lang,$config,$tables);
        }
        $new['link'] = &$link;
        $new['data'] = &$data;
        //print_r($new);
        return $new;
    }
    function link_creater($vals,$config){


        if(count($vals) > 0){
            foreach($vals as $val){
                $link[$val['name']] = $config['td'].'/uc/accounts/'.$val['account_id'].'/api/1.5/?source_token=Intellect_Soft-WoT_Mobile-unofficial_stats';
            }
        }


        //print_r($new);
        return $link;
    }

    function multiget($urls, &$result,$tcurl = 'curl')
    {
        if($tcurl == 'curl'){
            $curl = new CURL();
            $opts = array( CURLOPT_RETURNTRANSFER => true );  
            foreach($urls as $key => $link){
                $curl->addSession( $link, $key, $opts );
            }  
            $result = $curl->exec();  
            $curl->clear();
        }else{
            $curl = new MCurl; 
            $curl->threads = 100;  
            $curl->timeout = 15;   
            unset($results); 
            $curl->sec_multiget($urls, $result);  
        }
    }
?>
