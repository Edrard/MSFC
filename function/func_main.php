<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-11-20 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, SHW  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.0.2 $
*
*/


function marks() {
    $a = array(1=>'/static/3.15.0.4.2/common/img/classes/class-3.png', 2=> '/static/3.15.0.4.2/common/img/classes/class-2.png', 3=> '/static/3.15.0.4.2/common/img/classes/class-1.png', 4=>'/static/3.15.0.4.2/common/img/classes/class-ace.png');
    return $a;
}

function medn($nations) {
    //$nations = array ('usa', 'france', 'ussr', 'china', 'uk', 'germany');
    $tename = 'tank_expert';
    $mename = 'mechanic_engineer';
    foreach ($nations as $val2) {
        $val = $val2['nation'];
        if ($val != 'japan'){
            $medn[$tename.'_'.$val]['img'] = 'images/medals/TankExperts_'.$val.'.png';
            $medn[$mename.'_'.$val]['img'] = 'images/medals/MechanicEngineers_'.$val.'.png';
            $medn[$tename.'_'.$val]['type'] = 'expert';
            $medn[$mename.'_'.$val]['type'] = 'mechanic';
        }
    }
    $medn['warrior']['img'] = 'images/medals/Warrior.png';
    $medn['sniper']['img'] = 'images/medals/Sniper.png';
    $medn['supporter']['img'] = 'images/medals/Supporter.png';
    $medn['steelwall']['img'] = 'images/medals/Steelwall.png';
    $medn['invader']['img'] = 'images/medals/Invader.png';
    $medn['defender']['img'] = 'images/medals/Defender.png';
    $medn['scout']['img'] = 'images/medals/Scout.png';
    $medn['evileye']['img'] = 'images/medals/Evileye.png';

    $medn['medal_ekins']['img'] = 'images/medals/MedalEkins.png';
    $medn['medal_carius']['img'] = 'images/medals/MedalCarius.png';
    $medn['medal_kay']['img'] = 'images/medals/MedalKay.png';
    $medn['medal_le_clerc']['img'] = 'images/medals/MedalLeClerc.png';
    $medn['medal_abrams']['img'] = 'images/medals/MedalAbrams.png';
    $medn['medal_poppel']['img'] = 'images/medals/MedalPoppel.png';
    $medn['medal_lavrinenko']['img'] = 'images/medals/MedalLavrinenko.png';
    $medn['medal_knispel']['img'] = 'images/medals/MedalKnispel.png';
    for ($i =0; $i<=4; $i++) {
        $medn['medal_ekins'][$i]['img'] = 'images/medals/MedalEkins'.$i.'.png';
        $medn['medal_carius'][$i]['img'] = 'images/medals/MedalCarius'.$i.'.png';
        $medn['medal_kay'][$i]['img'] = 'images/medals/MedalKay'.$i.'.png';
        $medn['medal_le_clerc'][$i]['img'] = 'images/medals/MedalLeClerc'.$i.'.png';
        $medn['medal_abrams'][$i]['img'] = 'images/medals/MedalAbrams'.$i.'.png';
        $medn['medal_poppel'][$i]['img'] = 'images/medals/MedalPoppel'.$i.'.png';
        $medn['medal_lavrinenko'][$i]['img'] = 'images/medals/MedalLavrinenko'.$i.'.png';
        $medn['medal_knispel'][$i]['img'] = 'images/medals/MedalKnispel'.$i.'.png';
    }

    $medn['medal_fadin']['img'] = 'images/medals/MedalFadin.png';
    $medn['armor_piercer']['img'] = 'images/medals/ArmorPiercer.png';
    $medn['beasthunter']['img'] = 'images/medals/Beasthunter.png';
    $medn['bombardier']['img'] = 'images/medals/Bombardier.png';
    $medn['huntsman']['img'] = 'images/medals/Huntsman.png';
    $medn['medal_halonen']['img'] = 'images/medals/MedalHalonen.png';
    $medn['mousebane']['img'] = 'images/medals/Mousebane.png';
    $medn['medal_orlik']['img'] = 'images/medals/MedalOrlik.png';
    $medn['hand_of_death']['img'] = 'images/medals/HandOfDeath.png';
    $medn['title_sniper']['img'] = 'images/medals/TitleSniper.png';
    $medn['medal_boelter']['img'] = 'images/medals/MedalBoelter.png';
    $medn['medal_burda']['img'] = 'images/medals/MedalBurda.png';
    $medn['kamikaze']['img'] = 'images/medals/Kamikaze.png';
    $medn['raider']['img'] = 'images/medals/Raider.png';
    $medn['medal_oskin']['img'] = 'images/medals/MedalOskin.png';
    $medn['medal_billotte']['img'] = 'images/medals/MedalBillotte.png';
    $medn['medal_kolobanov']['img'] = 'images/medals/MedalKolobanov.png';
    $medn['invincible']['img'] = 'images/medals/Invincible.png';
    //$medn['lumberjack']['img'] = 'images/medals/Invincible.png';
    $medn['tank_expert']['img'] = 'images/medals/TankExpert.png';
    $medn['diehard']['img'] = 'images/medals/Diehard.png';
    $medn['sinai']['img'] = 'images/medals/Sinai.png';

    $medn['medal_delanglade']['img'] = 'images/medals/MedalDeLanglade.png';
    $medn['medal_tamada_yoshio']['img'] = 'images/medals/MedalTamadaYoshio.png';
    $medn['medal_nikolas']['img'] = 'images/medals/MedalNikolas.png';
    $medn['medal_lehvaslaiho']['img'] = 'images/medals/MedalLehvaslaiho.png';
    $medn['medal_dumitru']['img'] = 'images/medals/MedalDumitru.png';
    $medn['medal_pascucci']['img'] = 'images/medals/MedalPascucci.png';
    $medn['medal_lafayette_pool']['img'] = 'images/medals/MedalLafayettePool.png';
    $medn['medal_radley_walters']['img'] = 'images/medals/MedalRadleyWalters.png';
    $medn['medal_tarczay']['img'] = 'images/medals/MedalTarczay.png';
    $medn['medal_bruno_pietro']['img'] = 'images/medals/MedalBrunoPietro.png';
    $medn['medal_crucial_contribution']['img'] = 'images/medals/MedalCrucialContribution.png';
    $medn['medal_brothers_in_arms']['img'] = 'images/medals/MedalBrothersInArms.png';
    $medn['medal_heroes_of_rassenay']['img'] = 'images/medals/HeroesOfRassenay.png';
    $medn['lucky_devil']['img'] = 'images/medals/LuckyDevil.png';
    $medn['iron_man']['img'] = 'images/medals/IronMan.png';
    $medn['sturdy']['img'] = 'images/medals/Sturdy.png';
    $medn['patton_valley']['img'] = 'images/medals/PattonValley.png';
    $medn['mechanic_engineer']['img'] = 'images/medals/MechanicEngineer.png';
    $medn['tank_expert']['type'] = 'expert';
    $medn['mechanic_engineer']['type'] = 'mechanic';

    $medn['medal_halonen']['type'] = 'epic';
    $medn['invader']['type'] = 'hero';
    $medn['medal_fadin']['type'] = 'epic';
    $medn['armor_piercer']['type'] = 'special';
    $medn['mousebane']['type'] = 'special2';
    $medn['defender']['type'] = 'hero';
    $medn['supporter']['type'] = 'hero';
    $medn['steelwall']['type'] = 'hero';
    $medn['medal_orlik']['type'] = 'epic';
    $medn['hand_of_death']['type'] = 'special';
    $medn['sniper']['type'] = 'hero';
    $medn['warrior']['type'] = 'hero';
    $medn['title_sniper']['type'] = 'special';
    $medn['medal_boelter']['type'] = 'epic';
    $medn['medal_burda']['type'] = 'epic';
    $medn['scout']['type'] = 'hero';
    $medn['beasthunter']['type'] = 'special2';
    $medn['kamikaze']['type'] = 'special';
    $medn['raider']['type'] = 'special';
    $medn['medal_oskin']['type'] = 'epic';
    $medn['medal_billotte']['type'] = 'epic';
    $medn['medal_kolobanov']['type'] = 'epic';
    $medn['invincible']['type'] = 'special';
    //   $medn['lumberjack']['type'] = 'special';
    $medn['diehard']['type'] = 'special';
    $medn['medal_carius']['type'] = 'major';
    $medn['medal_ekins']['type'] = 'major';
    $medn['medal_kay']['type'] = 'major';
    $medn['medal_le_clerc']['type'] = 'major';
    $medn['medal_abrams']['type'] = 'major';
    $medn['medal_poppel']['type'] = 'major';
    $medn['medal_lavrinenko']['type'] =  'major';
    $medn['medal_knispel']['type'] =  'major';
    $medn['sinai']['type'] = 'special2';
    $medn['evileye']['type'] = 'hero';
    $medn['medal_delanglade']['type'] = 'epic2';
    $medn['medal_tamada_yoshio']['type'] = 'epic2';
    $medn['medal_nikolas']['type'] = 'epic2';
    $medn['medal_lehvaslaiho']['type'] = 'epic2';
    $medn['medal_dumitru']['type'] = 'epic2';
    $medn['medal_pascucci']['type'] = 'epic2';
    $medn['medal_lafayette_pool']['type'] = 'epic2';
    $medn['medal_radley_walters']['type'] = 'epic2';
    $medn['medal_tarczay']['type'] = 'epic2';
    $medn['medal_bruno_pietro']['type'] = 'epic2';
    $medn['medal_crucial_contribution']['type'] = 'epic';
    $medn['medal_brothers_in_arms']['type'] = 'epic';
    $medn['medal_heroes_of_rassenay']['type'] = 'epic2';
    $medn['bombardier']['type'] = 'special';
    $medn['huntsman']['type'] = 'special2';
    $medn['lucky_devil']['type'] = 'special2';
    $medn['iron_man']['type'] = 'special2';
    $medn['sturdy']['type'] = 'special2';
    $medn['patton_valley']['type'] = 'special2';
    return $medn;
}

function tanks() {
    global $db;
    $sql = " SELECT * FROM `tanks` ORDER BY tank_id ASC;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $tmp = $q->fetchAll();
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
    $ret = array();
    foreach ($tmp as $key =>$val) {
        foreach ($val as $key2 => $val2) {
            if (is_numeric($key2)) unset ($val[$key2]);
        }
        $ret[$val['tank_id']] = $val;
    }
    return $ret;
}

function check_tables($medals, $nations, $tanks) {
    global $db;
    $sql = "show tables like 'col_medals';";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $tmp = $q->fetchAll();
    }   else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
    if (count($tmp)==0) {
        $tsql = '';
        $sql = "CREATE TABLE IF NOT EXISTS `col_medals` (
        `account_id` INT(12),
        `updated_at` INT( 12 ) NOT NULL,
        KEY `updated_at` (`updated_at`) ) ENGINE=MYISAM ROW_FORMAT=DYNAMIC;";
        $q = $db->prepare($sql);
        if ($q->execute() !== TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        foreach ($medals as $key => $val) {
            $tmp2 = substr($key, 0, 6);

            if ($tmp2 == 'mechan' || $tmp2 == 'tank_e') {
                $size = 'tinyint(1)';
            }   else {
                $size = 'smallint(12)';
            }
            $tsql .= "ALTER TABLE `col_medals` ADD `".$key."` ".$size." UNSIGNED NOT NULL DEFAULT 0;";
        }
        if ($tsql != '') {
            $q = $db->prepare($tsql);
            if ($q->execute() !== TRUE) { die(show_message($q->errorInfo(),__line__,__file__,$tsql)); }
        }
    }
    foreach ($tanks as $tank_id => $val3) {
        $ntanks[$val3['nation']][$tank_id] = $tank_id;
    }
    unset ($tanks);
    foreach ($nations as $val2) {
        $val = $val2['nation'];
        $sql = "show tables like 'col_tank_".$val."';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $tmp = $q->fetchAll();
        }   else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        $sqlarr = array();
        if (count($tmp)==0) {
            $sql = "CREATE TABLE IF NOT EXISTS `col_tank_".$val."` (
            `account_id` INT(12),
            `updated_at` INT( 12 ) NOT NULL,
            KEY `updated_at` (`updated_at`) ) ENGINE=MYISAM ROW_FORMAT=DYNAMIC;";
            $q = $db->prepare($sql);
            if ($q->execute() !== TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }

            foreach ($ntanks[$val] as $tank_id => $val4 ) {
                $sqlarr[] = "ALTER TABLE `col_tank_".$val."`
                ADD `".$tank_id."_battles` smallint(12) UNSIGNED NOT NULL DEFAULT 0,
                ADD `".$tank_id."_wins` smallint( 12 ) NOT NULL DEFAULT 0,
                ADD `".$tank_id."_mark_of_mastery` tinyint(1) UNSIGNED NOT NULL DEFAULT 0;";
            }
            if (!empty($sqlarr)) {
                foreach ($sqlarr as $tsql) {
                    $q = $db->prepare($tsql);
                    if ($q->execute() !== TRUE) { die(show_message($q->errorInfo(),__line__,__file__,$tsql)); }
                }
            }
        }   else {
            $sql = "select * from `col_tank_".$val."` LIMIT 0 , 1;";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $sel = $q->fetchAll();
            }   else {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            if (count($sel) <> 0) {
                foreach ($ntanks[$val] as $tank_id => $val4 ) {
                    if (!isset($sel[0][$tank_id.'_battles'])) {
                        $sqlarr[] = "ALTER TABLE `col_tank_".$val."`
                        ADD `".$tank_id."_battles` smallint(12) UNSIGNED NOT NULL DEFAULT 0,
                        ADD `".$tank_id."_wins` smallint( 12 ) NOT NULL DEFAULT 0,
                        ADD `".$tank_id."_mark_of_mastery` tinyint(1) UNSIGNED NOT NULL DEFAULT 0;";
                    }
                }
            }
            if (!empty($sqlarr)) {
                foreach ($sqlarr as $tsql) {
                    $q = $db->prepare($tsql);
                    if ($q->execute() !== TRUE) { die(show_message($q->errorInfo(),__line__,__file__,$tsql)); }
                }
            }
        }
    }
}
function get_last_roster($time)
{
    global $db;
    global $config;
    $error = 1;

    $sql = "
    SELECT p.name, p.account_id, p.role, p.member_since
    FROM `col_players` p,
    (SELECT max(up) as maxup
    FROM `col_players`
    WHERE up <= ".$time."
    LIMIT 1) maxresults
    WHERE p.up = maxresults.maxup
    ORDER BY p.up DESC;";

    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return $q->fetchAll();
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}

function roster_sort($array)
{
    $new = array();
    foreach($array as $val){
        $new[$val['account_name']] = $val;
    }
    return $new;  //new
}
function roster_resort_id($roster)
{
    foreach($roster as $val){
        $new[$val['account_id']] = $val;
    }
    return $new;
}

function tanks_group($array){
    $name = array();
    foreach($array as $val){
        if(isset($val['tank'])){
            if(is_array($val['tank'])) {
                foreach($val['tank'] as $lvl => $types){
                    foreach($types as $type => $tanks){
                        foreach($tanks as $tank){
                            $name[$type][$lvl][($tank['type'])] = true;
                        }
                    }
                }
            }
        }
    }

    return $name;
}

function restr($array)
{
    foreach(array_keys($array) as $val){
        if(is_array($array[$val])){
            foreach(array_keys($array[$val]) as $v){
                if(is_numeric($v)){
                    unset($array[$val][$v]);
                }
            }
        }else{
            if(is_numeric($val)){
                unset($array[$val]);
            }
        }
    }
    return $array;
}
function tanks_nations() {
    global $db;
    $sql='SELECT DISTINCT nation FROM `tanks`;';
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return $q->fetchAll();
    }else{
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}
function tanks_types() {
    global $db;
    $sql='SELECT DISTINCT type FROM `tanks`;';
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return $q->fetchAll();
    }else{
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}
function tanks_lvl() {
    global $db;
    $sql='SELECT DISTINCT level FROM `tanks` order by level ASC;';
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return $q->fetchAll();
    }else{
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}
/***** Exinaus *****/
function get_available_tanks() {
    global $db;
    $top_tanks=array();

    $sql='SELECT t.level, t.type, t.name_i18n, t.tank_id, tt.show, tt.order, tt.shortname as title, tt.index
    FROM `top_tanks` tt, `tanks` t
    WHERE t.tank_id = tt.tank_id AND tt.show = "1"
    ORDER BY tt.index ASC, tt.order ASC, t.name_i18n ASC;';
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $top_tanks_unsorted = $q->fetchAll();
    }   else{
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

    foreach($top_tanks_unsorted as $val) {
        $top_tanks[$val['name_i18n']]['tank_id'] = $val['tank_id'];
        $top_tanks[$val['name_i18n']]['lvl'] = $val['level'];
        $top_tanks[$val['name_i18n']]['title'] = $val['title'];
        $top_tanks[$val['name_i18n']]['type'] = $val['type'];
        $top_tanks[$val['name_i18n']]['show'] = ($val['show'] == 1) ? 'checked="checked"' : '';
        $top_tanks[$val['name_i18n']]['order'] = $val['order'];
        $top_tanks[$val['name_i18n']]['shortname'] = isset($val['shortname']) ? $val['shortname'] : '';
        $top_tanks[$val['name_i18n']]['index'] = $val['index'];
    }
    return $top_tanks;
}
function get_available_tanks_index() {
    global $db;
    $top_tanks = array();
    $sql='SELECT DISTINCT tt.index
    FROM `top_tanks` tt
    WHERE tt.show = "1"
    ORDER BY tt.index ASC;';
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $top_tanks_unsorted = $q->fetchAll();
    }   else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

    $count = 0;
    foreach($top_tanks_unsorted as $val) {
        $top_tanks['index'][$val['index']] = $val['index'];
        $count++;
    }
    $top_tanks['count'] = $count;
    return $top_tanks;
}
/**** end ****/
function roster_num($var)
{
    $data['recruit'] = '8';
    $data['private'] = '7';
    $data['recruiter'] = '6';    
    $data['treasurer'] = '5';
    $data['diplomat'] = '4';
    $data['commander'] = '3'; 
    $data['vice_leader'] = '2';  
    $data['leader'] = '1';

    return $data[$var];
}
function read_multiclan($dbprefix = FALSE)
{
    global $db;
    if($dbprefix == FALSE){
        $sql = "SELECT * FROM multiclan ORDER BY sort ASC;";
    }else{
        $sql = "SELECT * FROM multiclan WHERE prefix = '".$dbprefix."' ORDER BY sort ASC;";
    }
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }else{ 
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }     
}
function autoclean($time,$multi,$config,$directory)
{
    global $cache,$db;
    //$global = array(); 
    if(($config['autoclean'] + $time) <= now()){
        $map = directory_map($directory);
        foreach($multi as $val){
            $new = $cache->get('get_last_roster_'.$val['id'],0);
            if($new === FALSE) 
            { 
                $new = get_clan_v2($config['clan'], 'info', $config);
            }else{
                $cache->clear('get_last_roster_'.$val['id']);
                $cache->set('get_last_roster_'.$val['id'], $new);
            }
            //print_r($new); die;
            foreach($new['data']['members'] as $player){
                foreach($map as $key => $file){
                    if(sha1($player['account_name']) == $file){
                        unset($map[$key]);
                    }
                }
            } 
            $sql = "UPDATE ".$val['prefix']."config SET value = '".now()."' WHERE name = 'autoclean';";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
        }              
        foreach($map as $file){ 
            unlink($directory.$file);   
        }      
    }
}
function multi_main($multi){
    foreach($multi as $key => $val){
        if($val['main'] == '1'){
            return $multi[$key];
        }
    }
}
function get_updated_at(){
    global $db;
    $sql = "SELECT DISTINCT updated_at FROM `col_players` ;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return count($q->fetchAll());
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}
function get_tables_like_col_tank($dbname){
    global $db;  
    $sql = "SHOW TABLES FROM `".$dbname."` LIKE 'col_tank_%';";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return reform($q->fetchAll());
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}
function update_tanks_db() {
    global $db,$config,$cache;
    if(isset($_POST['update_tanks_db'])){
        $sql = "DELETE from `tanks` WHERE 1;";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        } 
        $cache->clear_all(array(), ROOT_DIR.'/cache/tanks/',7200);   
    }
    $tmp = get_tank_v2($config); 
    $tmp_tanks = tanks();
    if(!empty($tmp_tanks)){
        $current = array_resort($tmp_tanks,'tank_id');
    }
    unset($tmp_tanks);
    if ($tmp['status'] == 'ok') {
        $updatearr = $toload = array ();
        foreach ($tmp['data'] as $tank_id => $val) {
            if(!isset($current[$val['tank_id']])){
                $cache_tanks = $cache->get($val['tank_id'], 0, ROOT_DIR.'/cache/tanks/');
                $updatearr [$tank_id] = $cache_tanks['data'];
                $updatearr [$tank_id]['tank_id']     = $val['tank_id'];
                $updatearr [$tank_id]['type']        = $val['type'];
                $updatearr [$tank_id]['nation_i18n'] = $val['nation_i18n'];
                $updatearr [$tank_id]['level']       = $val['level'];
                $updatearr [$tank_id]['nation']      = $val['nation'];
                if($tank_id == 52225){
                    $updatearr [$tank_id]['name_i18n']     = 'БТ-СВ';
                    $updatearr [$tank_id]['image']         = 'http://worldoftanks.ru/static/3.16.0.3.1/encyclopedia/tankopedia/vehicle/ussr-bt-sv.png';
                    $updatearr [$tank_id]['contour_image'] = 'http://worldoftanks.ru/static/3.16.0.3.1/encyclopedia/tankopedia/vehicle/small/ussr-bt-sv.png';
                    $updatearr [$tank_id]['image_small']   = 'http://worldoftanks.ru/static/3.16.0.3.1/encyclopedia/tankopedia/vehicle/contour/ussr-bt-sv.png';    
                } 
                if ($val['is_premium']== true) {
                    $updatearr [$val['tank_id']]['is_premium']      = 1;
                }   else {
                    $updatearr [$val['tank_id']]['is_premium']      = 0;
                }
                if( ($cache_tanks === FALSE) || (empty($cache_tanks)) || ((isset($cache_tanks['status'])) && ($cache_tanks['status']<>'ok')) ) {
                    $toload[] = $val['tank_id'];
                }
            }
        }
        unset($tmp);
        $toload = array_chunk($toload,$config['multiget']*2);
        $tmp = array();
        foreach($toload as $urls){
            $tmp = array_special_merge($tmp,multiget_v2($urls, 'encyclopedia/tankinfo', $config, array ('contour_image', 'image', 'image_small', 'name_i18n')));
            foreach($tmp as $tank_id => $val){
                if($val['status'] == 'ok'){
                    $cache->set($tank_id, $val, ROOT_DIR.'/cache/tanks/');
                }
            }
        }
        foreach ($tmp as $tank_id => $val) {
            $updatearr [$tank_id]['name_i18n']     = $val['data']['name_i18n'];
            $updatearr [$tank_id]['image']         = $val['data']['image'];
            $updatearr [$tank_id]['contour_image'] = $val['data']['contour_image'];
            $updatearr [$tank_id]['image_small']   = $val['data']['image_small'];
            if($tank_id == 52225){
                $updatearr [$tank_id]['name_i18n']     = 'БТ-СВ';
                $updatearr [$tank_id]['image']         = 'http://worldoftanks.ru/static/3.16.0.3.1/encyclopedia/tankopedia/vehicle/ussr-bt-sv.png';
                $updatearr [$tank_id]['contour_image'] = 'http://worldoftanks.ru/static/3.16.0.3.1/encyclopedia/tankopedia/vehicle/small/ussr-bt-sv.png';
                $updatearr [$tank_id]['image_small']   = 'http://worldoftanks.ru/static/3.16.0.3.1/encyclopedia/tankopedia/vehicle/contour/ussr-bt-sv.png';    
            } 
        }

        unset($tmp);
        if(!empty($updatearr)){
            $sql = "INSERT INTO `tanks` (`tank_id`, `nation_i18n`, `level`, `nation`, `is_premium`, `name_i18n`, `type`, `image`, `contour_image`, `image_small`) VALUES ";
            foreach ($updatearr as $tank_id => $val) {
                $sql .= "('{$val['tank_id']}', '{$val['nation_i18n']}', '{$val['level']}', '{$val['nation']}', '{$val['is_premium']}', '{$val['name_i18n']}', '{$val['type']}', '{$val['image']}', '{$val['contour_image']}', '{$val['image_small']}'), ";
            }
            $sql = substr($sql, 0, strlen($sql)-2);
            $sql .= ';';
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
        }
    }else {
        if (isset($tmp['error']['message'])) {
            $message = ' ( '.$tmp['error']['message'].' )';
        }   else {
            $message = '';
        }
        die ('Some error with getting data from WG'.$message);  
    }
}
?>