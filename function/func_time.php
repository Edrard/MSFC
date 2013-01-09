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
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<?php
    function went_players($roster,$start = 0,$end = -1){

        global $db;

        $losed = array();

        if($end == -1){
            $end = now();  
        }else{
            $roster = array_resort(get_last_roster($end),'name');
        }
        $sql = "SELECT name,account_id,role,member_since,up FROM `col_players` WHERE up <= '".$end."' AND up >= '".$start."' ORDER BY up DESC;";
        ///echo $sql;
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $result = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        foreach($result as $val){
            if(!isset($roster[$val['name']])){
                $losed[$val['name']] = $val;   
            }
        }
        return $losed;
    }
    function new_players($roster,$start = 0,$end = -1){

        global $db;

        $come = array();

        if($end == -1){
            $end = now();
        }
        $sql = "SELECT name,account_id,role,member_since FROM `col_players` WHERE member_since <= '".$end."' AND member_since >= '".$start."' ORDER BY member_since DESC;";

        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $result = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        foreach($result as $val){
            $new_res[$val['name']] = $val;
        }
        if(isset($new_res)){
            foreach($new_res as $name => $val){
                if(isset($roster[$name])){
                    $come[$name] = $val;
                }
            }
        }
        return $come;
    }                                                    
    function player_progress_main($start = 0,$end = -1){

        global $db;
        $diff = array();
        if($end == -1){
            $end = now();
        }

        $sql = "SELECT DISTINCT up FROM `col_players` WHERE up < '".$end."' AND up >= '".$start."' ORDER BY up DESC;";

        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $result = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        if(count($result) > 1){
            $first = array_pop($result);
            $first = $first['up'];
            $last = $result[0]['up'];

            $sql = "SELECT * FROM `col_players` WHERE up = '".$first."';";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $dfirst = $q->fetchAll();
            } else {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            $sql = "SELECT * FROM `col_players` WHERE up = '".$last."';";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $dlast = $q->fetchAll();
            } else {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            foreach($dfirst as $val){
                $dfirst_new[$val['name']] = $val;
            }
            foreach($dlast as $vals){
                if(isset($dfirst_new[$vals['name']])){
                    if($vals['total'] != 0){
                        $f['win'] = $vals['win']/$vals['total']*100;
                        $f['lose'] = $vals['lose']/$vals['total']*100;
                        $f['alive'] = $vals['alive']/$vals['total']*100; 
                    }else{
                        $f['win'] = 0;
                        $f['lose'] = 0;
                        $f['alive'] = 0;
                    }
                    if($dfirst_new[$vals['name']]['total'] != 0){
                        $s['win'] = $dfirst_new[$vals['name']]['win']/$dfirst_new[$vals['name']]['total']*100;
                        $s['lose'] = $dfirst_new[$vals['name']]['lose']/$dfirst_new[$vals['name']]['total']*100;
                        $s['alive'] = $dfirst_new[$vals['name']]['alive']/$dfirst_new[$vals['name']]['total']*100; 
                    }else{
                        $s['win'] = 0;
                        $s['lose'] = 0;
                        $s['alive'] = 0;
                    }
                    $diff[$vals['name']]['winp'] = round($f['win'] - $s['win'],4);
                    $diff[$vals['name']]['losep'] = round($f['lose'] - $s['lose'],4);
                    $diff[$vals['name']]['alivep'] = round($f['alive'] - $s['alive'],4);
                    foreach($vals as $key => $val){
                        if(!is_numeric($key) && $key != 'account_id' && $key != 'name' && $key != 'role' && $key != 'server' && $key != 'reg' && $key != 'local' && $key != 'member_since' && $key != 'up'){
                            $diff[$vals['name']][$key] = $val - $dfirst_new[$vals['name']][$key];    
                        }
                    }
                }
            }
        }
        return $diff;
    }
    function best_player_progress_main($data){
        $max = array();
        foreach($data as $name => $vals){
            foreach($vals as $key => $val){
                if(!isset($max[$key]['value'])){
                    $max[$key]['value'] = $val;
                    $max[$key]['name'] = $name;
                }else{
                    if($max[$key]['value'] < $val){
                        $max[$key]['value'] = $val;
                        $max[$key]['name'] = $name;   
                    }
                }    
            }
        }
        return $max;

    }
    function medal_progress($start = 0,$end = -1){

        global $db;
        $diff['unsort'] = array();
        $diff['sort'] = array();
        if($end == -1){
            $end = now();
        }   

        $sql = "SELECT DISTINCT up FROM `col_medals` WHERE up < '".$end."' AND up >= '".$start."' ORDER BY up DESC;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $result = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        if(count($result) > 1){
            $first = array_pop($result);
            $first = $first['up'];
            $last = $result[0]['up'];

            $sql = "SELECT * FROM `col_medals` WHERE up = '".$first."';";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $dfirst = $q->fetchAll();
            } else {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            $sql = "SELECT * FROM `col_medals` WHERE up = '".$last."';";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $dlast = $q->fetchAll();
            } else {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            foreach($dfirst as $val){
                $dfirst_new[$val['account_id']] = $val;
            }
            foreach($dlast as $vals){
                if(isset($dfirst_new[$vals['account_id']])){
                    foreach($vals as $key => $val){
                        if(!is_numeric($key) && $key != 'account_id' && $key != 'up' && $key != 'maxDiehardSeries' && $key != 'maxInvincibleSeries' && $key != 'maxKillingSeries' && $key != 'maxPiercingSeries' && $key != 'maxSniperSeries' && $key != 'medalWittmann' ){
                            $diff['unsort'][$vals['account_id']][$key] = $val - $dfirst_new[$vals['account_id']][$key];    
                        }
                    }
                }
            }
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

            $tename = 'tankExperts';
            $mename = 'mechanicEngineers';

            $medn['sinai']['type'] = 'special';

            $medn['evileye']['type'] = 'hero';
            $medn['medalDeLanglade']['type'] = 'epic2';
            $medn['medalTamadaYoshio']['type'] = 'epic2';
            $medn['medalNikolas']['type'] = 'epic2';
            $medn['medalLehvaslaiho']['type'] = 'epic2';
            $medn['medalDumitru']['type'] = 'epic2';
            $medn['medalPascucci']['type'] = 'epic2';
            $medn['medalLafayettePool']['type'] = 'epic2';
            $medn['medalRadleyWalters']['type'] = 'epic2';
            $medn['medalTarczay']['type'] = 'epic2';
            $medn['medalBrunoPietro']['type'] = 'epic2';
            $medn['medalCrucialContribution']['type'] = 'epic';
            $medn['medalBrothersInArms']['type'] = 'epic';
            $medn['heroesOfRassenay']['type'] = 'epic2';
            $medn['bombardier']['type'] = 'special';

            $medn['mechanicEngineer']['type'] = '';
            
            $medn[$tename.'_usa']['type'] = 'expert';
            $medn[$tename.'_france']['type'] = 'expert';
            $medn[$tename.'_ussr']['type'] = 'expert';
            $medn[$tename.'_china']['type'] = 'expert';
            $medn[$tename.'_uk']['type'] = 'expert';
            $medn[$tename.'_germany']['type'] = 'expert';

            $medn[$mename.'_usa']['type'] = 'expert';
            $medn[$mename.'_france']['type'] = 'expert';
            $medn[$mename.'_ussr']['type'] = 'expert';
            $medn[$mename.'_china']['type'] = 'expert';
            $medn[$mename.'_uk']['type'] = 'expert';
            $medn[$mename.'_germany']['type'] = 'expert';

            foreach($diff['unsort'] as $id => $vals){
                foreach($vals as $key => $val){
                    $diff['sorted'][$medn[$key]['type']][$id][$key] = $val;
                }
            }
        }
        return $diff; 
    }
    function best_medal_progress($data){
        $max = array();
        foreach($data as $id => $vals){
            foreach($vals as $key => $val){
                if(!isset($max[$key]['value'])){
                    $max[$key]['value'] = $val;
                    $max[$key]['account_id'] = $id;
                }else{
                    if($max[$key]['value'] < $val){
                        $max[$key]['value'] = $val;
                        $max[$key]['account_id'] = $id;   
                    }
                }    
            }
        }
        return $max;    

    }
    function player_progress($account_id,$tables,$start = 0,$end = -1){

        global $db;

        if($end == -1){
            $end = now();
        } 
        $sql = "SELECT * FROM `col_players` WHERE account_id = '".$account_id."' AND up < '".$end."' AND up >= '".$start."' ORDER BY up DESC;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $players = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        foreach($players as $vals){
            foreach($vals as $key => $val){
                if(!is_numeric($key)){
                    $pnew[$vals['up']][$key] = $val;
                }     
            }
        }
        $sql = "SELECT * FROM `col_medals` WHERE account_id = '".$account_id."' AND up < '".$end."' AND up >= '".$start."' ORDER BY up DESC;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $medals = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        foreach($medals as $vals){
            foreach($vals as $key => $val){
                if(!is_numeric($key) && $key != 'account_id' && $key != 'up'){
                    $pnew[$vals['up']]['medals'][$key] = $val;
                }     
            }
        }

        foreach($tables as $val){
            $tmp = explode('_',$val);
            $tables_sec = $tmp[0].'_rating_'.$tmp[1].'_'.$tmp[2];
            $sql = "SELECT * FROM `".$val."` WHERE account_id = '".$account_id."' AND up < '".$end."' AND up >= '".$start."' ORDER BY up DESC;";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $ftank[$tmp[2]] = $q->fetchAll();
            } else {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            $sql = "SELECT * FROM `".$tables_sec."` WHERE account_id = '".$account_id."' AND up < '".$end."' AND up >= '".$start."' ORDER BY up DESC;";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $stank[$tmp[2]] = $q->fetchAll();
            } else {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
        }     

        $newt = array();
        foreach($ftank as $nat){
            foreach($nat as $vals){
                foreach($vals as $key => $val){
                    if(!is_numeric($key)){
                        $newt[$vals['up']][$key] = $val;
                    }
                }
            }
        }
        foreach($stank as $nat){
            foreach($nat as $vals){
                foreach($vals as $key => $val){
                    if(!is_numeric($key)){
                        $newt[$vals['up']][$key] = $val;
                    }
                }
            }
        }

        $sql = "SELECT * FROM `tanks` ORDER BY id ASC;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $tank_name = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        foreach($newt as $time => $dd){
            foreach($tank_name as $val){
                if(isset($dd[$val['id'].'_t'])){
                    if($dd[$val['id'].'_t'] > 0){
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['type'] = $val['tank'];
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['total'] = $dd[$val['id'].'_t']; 
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['link'] = $val['link'];
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['win'] = $dd[$val['id'].'_w'];
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['nation'] = $val['nation'];
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['lvl'] = $val['lvl'];
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['class'] = $val['type'];     
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['spotted'] = $dd[$val['id'].'_sp'];
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['damageDealt'] = $dd[$val['id'].'_dd'];
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['survivedBattles'] = $dd[$val['id'].'_sb'];
                        $pnew[$time]['tank'][$val['lvl']][$val['type']][$val['tank']]['frags'] = $dd[$val['id'].'_fr']; 
                    }
                }
            }
        }  
        return $pnew;
    }
    function new_tanks($roster,$tables,$start = 0,$end = -1){

        global $db;
        $new = array();
        if($end == -1){
            $end = now();
        } 

        $sql = "SELECT * FROM `tanks` ORDER BY id ASC;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $tank_name_tmp = $q->fetchAll(PDO::FETCH_ASSOC);
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        foreach($tank_name_tmp as $tmp) {
            $tank_name[$tmp['id']] = $tmp;
        }
        unset($tank_name_tmp);

        foreach($roster as $pers){
            foreach($tables as $val){
                $tmp = explode('_',$val);
                $sql = "SELECT * FROM `".$val."` WHERE account_id = '".$pers['account_id']."' AND up < '".$end."' AND up >= '".$start."' ORDER BY up DESC;";

                $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $tank[$tmp[2]] = $q->fetchAll();
                } else {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                foreach($tank as $vals){
                    $first = count($vals) - 1;
                    if(isset($vals[0])){
                        foreach($vals[0] as $key => $val){
                            if(!is_numeric($key) && $key != 'account_id' && $key != 'up'){
                                $tmp = explode('_',$key);
                                if($tmp[1] == 't'){
                                    if(!isset($vals[$first][$key])){
                                        $new[$tank_name[($tmp[0])]['tank']] = $vals[0]['account_id'];  
                                    }   
                                    if($vals[$first][$key] == 0 && $val > 0){
                                        $new[$tank_name[($tmp[0])]['tank']] = $vals[0]['account_id'];
                                    }
                                }
                            }
                        }
                    }    
                }
            }
        }
        return $new;
    }
    function time_summer($array,$name){
        $sum = 0;
        foreach($array as $val){
            $sum += $val[$name];    
        }
        return $sum;
    }
?>
