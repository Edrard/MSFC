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
* @version     $Rev: 3.2.3 $
*
*/


function marks() {
    $a = array(1=>'/static/3.22.0.2/common/img/classes/class-3.png', 2=> '/static/3.22.0.2/common/img/classes/class-2.png', 3=> '/static/3.22.0.2/common/img/classes/class-1.png', 4=>'/static/3.22.0.2/common/img/classes/class-ace.png');
    return $a;
}

function tanks() {
    global $db;

    $tmp = $db->select('SELECT * FROM `tanks` ORDER BY tank_id ASC;',__line__,__file__);
    $ret = array();
    foreach ($tmp as $val) {
        $ret[$val['tank_id']] = $val;
    }
    return $ret;
}

function check_tables($medals, $nations, $tanks) {
    global $db;
    $tmp = $db->select('show tables like "col_medals";',__line__,__file__);

    if (count($tmp)==0) {
        $sql = 'CREATE TABLE IF NOT EXISTS `col_medals` (
        `account_id` INT(12),
        `updated_at` INT( 12 ) NOT NULL,
        KEY `updated_at` (`updated_at`) ) ENGINE=MYISAM ROW_FORMAT=DYNAMIC;';
        $db->insert($sql,__line__,__file__);
    }

    //Получаем структуру таблицы
    $medals_structure = array_flip($db->select('SHOW COLUMNS FROM `col_medals`;',__line__,__file__,'rows'));
    $tsql = '';

    foreach ($medals as $key => $val) {
        if(!isset($medals_structure[$key])) {
            $tmp2 = substr($key, 0, 6);

            if ($tmp2 == 'mechan' || $tmp2 == 'tank_e') {
                $size = 'tinyint(1)';
            }   else {
                $size = 'smallint(12)';
            }
            $tsql .= 'ALTER TABLE `col_medals` ADD `'.$key.'` '.$size.' UNSIGNED NOT NULL DEFAULT 0;';
        }
    }
    if ($tsql != '') {
        $db->insert($tsql,__line__,__file__);
    }

    foreach ($tanks as $tank_id => $val3) {
        $ntanks[$val3['nation']][$tank_id] = $tank_id;
    }
    unset ($tanks);
    foreach ($nations as $val2) {
        $val = $val2['nation'];
        $tmp = $db->select('show tables like "col_tank_'.$val.'";',__line__,__file__);
        if (count($tmp)==0) {
            $sql = 'CREATE TABLE IF NOT EXISTS `col_tank_'.$val.'` (
            `account_id` INT(12),
            `updated_at` INT( 12 ) NOT NULL,
            KEY `updated_at` (`updated_at`) ) ENGINE=MYISAM ROW_FORMAT=DYNAMIC;';
            $db->insert($sql,__line__,__file__);

            $sqlarr = array();
            foreach ($ntanks[$val] as $tank_id => $val4 ) {
                $sqlarr[] = 'ALTER TABLE `col_tank_'.$val.'`
                ADD `'.$tank_id.'_battles` smallint(12) UNSIGNED NOT NULL DEFAULT 0,
                ADD `'.$tank_id.'_wins` smallint( 12 ) NOT NULL DEFAULT 0,
                ADD `'.$tank_id.'_mark_of_mastery` tinyint(1) UNSIGNED NOT NULL DEFAULT 0;';
            }
            if (!empty($sqlarr)) {
                foreach ($sqlarr as $tsql) {
                    $db->insert($tsql,__line__,__file__);
                }
            }
        }

        $sqlarr = $tanks_structure = array();
        //Получаем структуру таблицы
        $tanks_structure = array_flip($db->select('SHOW COLUMNS FROM `col_tank_'.$val.'`;',__line__,__file__,'rows'));

        foreach ($ntanks[$val] as $tank_id => $val4 ) {
            if (!isset($tanks_structure[$tank_id.'_battles'])) {
                $sqlarr[] = 'ALTER TABLE `col_tank_'.$val.'`
                ADD `'.$tank_id.'_battles` smallint(12) UNSIGNED NOT NULL DEFAULT 0,
                ADD `'.$tank_id.'_wins` smallint( 12 ) NOT NULL DEFAULT 0,
                ADD `'.$tank_id.'_mark_of_mastery` tinyint(1) UNSIGNED NOT NULL DEFAULT 0;';
            }
        }
        if (!empty($sqlarr)) {
            foreach ($sqlarr as $tsql) {
                $db->insert($tsql,__line__,__file__);
            }
        }
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
    $new = array();
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

function tanks_nations() {
    global $db;
    return $db->select('SELECT DISTINCT nation FROM `tanks`;',__line__,__file__);
}

function tanks_types() {
    global $db;
    return $db->select('SELECT DISTINCT type FROM `tanks`;',__line__,__file__);
}

function tanks_lvl() {
    global $db;
    return $db->select('SELECT DISTINCT level FROM `tanks` order by level ASC;',__line__,__file__);
}

/***** Exinaus *****/
function get_available_tanks() {
    global $db;
    $top_tanks = array();
    $sql = 'SELECT t.level, t.type, t.name_i18n, t.tank_id, tt.show, tt.order, tt.shortname as title, tt.index
    FROM `top_tanks` tt, `tanks` t
    WHERE t.tank_id = tt.tank_id AND tt.show = "1"
    ORDER BY tt.index ASC, tt.order ASC, t.name_i18n ASC;';
    $top_tanks_unsorted = $db->select($sql,__line__,__file__);

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
    $top_tanks_unsorted = $db->select('SELECT DISTINCT tt.index FROM `top_tanks` tt WHERE tt.show = "1" ORDER BY tt.index ASC;',__line__,__file__);
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
    $data = array();
    $data['reservist'] = '90';
    $data['junior_officer'] = '65';
    $data['personnel_officer'] = '25';
    $data['recruit'] = '80';
    $data['private'] = '70';
    $data['recruiter'] = '60';
    $data['treasurer'] = '50';
    $data['diplomat'] = '40';
    $data['commander'] = '30';
    $data['vice_leader'] = '20';
    $data['leader'] = '10';
    return isset($data[$var])?$data[$var]:100;
}

function read_multiclan($dbprefix = FALSE)
{
    global $db;
    if($dbprefix == FALSE){
        $sql = "SELECT * FROM `multiclan` ORDER BY sort ASC;";
    }else{
        $sql = "SELECT * FROM `multiclan` WHERE prefix = '".$dbprefix."' ORDER BY sort ASC;";
    }
    return $db->select($sql,__line__,__file__);
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
                $new = get_api('wgn/clans/info',array('clan_id' => $config['clan']));
            }
            //print_r($new); die;
            if(isset($new['data'][$val['id']]['members']) and !empty($new['data'][$val['id']]['members']))
            {
                foreach($new['data'][$val['id']]['members'] as $player){
                    foreach($map as $key => $file){
                        if(sha1($player['account_id']) == $file){
                            unset($map[$key]);
                        }
                    }
                }
            }
            $db->insert('UPDATE '.$val['prefix'].'config SET value = "'.now().'" WHERE name = "autoclean";',__line__,__file__);
        }
        if(!empty($map)) {
            foreach($map as $file){
                unlink($directory.$file);
            }
        }

        //clean db from cron data
        if($config['cron_autoclean'] == 1) {
            require(ROOT_DIR.'/admin/func_admin.php');

            if($config['cron_cleanleft'] == 1) {
                clean_db_left_players();
            }

            if($config['cron_cleanold'] == 1) {
                clean_db_old_cron($config['cron_cleanold_d']);
            }

            if($config['cron_clean_log'] == 1) {
                cron_file_recreat();
            }
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
function resort_tanks_db($val){
    $i = 0;
    if(isset($val['data'])){
        if(is_array($val['data'])){                
            if($i == 0){
                $ove = $val;
            }
            foreach($val['data'] as $wgid => $kin){
                if(!isset($ove['data'][$wgid])){
                    $ove['data'][$wgid] = $kin;
                }
                $ove['data'][$wgid]['name_i18n'] = $kin['name'] ? $kin['name'] : str_replace('_',' ',$kin['tag']);
                $ove['data'][$wgid]['short_name_i18n'] = $kin['short_name'] ? $kin['short_name'] : str_replace('_',' ',$kin['tag']);
                if($i == 0){
                    unset($ove['data'][$wgid]['images']);
                }
                $ove['data'][$wgid] = array_special_merge($kin['images'],$ove['data'][$wgid]);
                $ove['data'][$wgid]['title'] = $kin['tag'];
                $ove['data'][$wgid]['level'] = $kin['tier'];
                $ove['data'][$wgid]['nation_i18n'] = $kin['nation'];
                $ove['data'][$wgid]['image'] = $ove['data'][$wgid]['big_icon'];
                $ove['data'][$wgid]['image_small'] = $ove['data'][$wgid]['small_icon'];
                $ove['data'][$wgid]['contour_image'] = $ove['data'][$wgid]['contour_icon'];
                $ove['data'][$wgid]['is_premium'] = $kin['is_premium'];
                unset($ove['data'][$wgid]['short_name'],$ove['data'][$wgid]['tag'],$ove['data'][$wgid]['tier']);
                //`tank_id`, `nation_i18n`, `level`, `nation`, `is_premium`, `title`, `name_i18n`, `type`, `image`, `contour_image`, `image_small`
            }
        }
    }
    return $ove; 
}
function fix_wg_sheet(&$ove){
    if(!isset($ove['data']['16913'])){
        $ove['data']['16913']['tank_id'] = 16913;
        $ove['data']['16913']['name_i18n'] = 'Waffenträger E-100';
        $ove['data']['16913']['title'] = 'Waffentrager_E100';
        $ove['data']['16913']['level'] = 10;
        $ove['data']['16913']['type'] = 'AT-SPG';
        $ove['data']['16913']['nation'] = 'germany';
        $ove['data']['16913']['nation_i18n'] = 'germany';
        $ove['data']['16913']['image'] = 'http://static-ptl-ru.gcdn.co/static/2.42.0/encyclopedia/tankopedia/vehicle/germany-waffentrager_e100.png';
        $ove['data']['16913']['image_small'] = 'http://static-ptl-ru.gcdn.co/static/2.42.0/encyclopedia/tankopedia/vehicle/small/germany-waffentrager_e100.png';
        $ove['data']['16913']['contour_image'] = 'http://static-ptl-ru.gcdn.co/static/2.42.0/encyclopedia/tankopedia/vehicle/contour/germany-waffentrager_e100.png';
        $ove['data']['16913']['is_premium'] = 0;
    }   
}  
function update_tanks_db($tanks = array(), $force = 0) {
    global $db,$config,$cache;

    if(empty($tanks)) {
        $tanks = tanks();
    }
    $tanks_api = get_api('wot/encyclopedia/vehicles',array('fields' => 'name,nation,tag,tank_id,tier,type,images,short_name,is_premium'));

    if ((isset($tanks_api['status'])) && ($tanks_api['status'] == 'ok')) {
        $tanks_api = resort_tanks_db($tanks_api);
        //print_r($tanks_api); die;
        $updatearr = array();

        if(isset($_POST['update_tanks_db']) or $force == 1){
            $db->insert('TRUNCATE TABLE `tanks`;',__line__,__file__);
            $tanks = array();
        }
        fix_wg_sheet($tanks_api);
        foreach ($tanks_api['data'] as $tank_id => $val) {
            if(!isset($tanks[$tank_id])){        
                $updatearr [$tank_id] = array_map('remove_qutes',$val);
                if ($val['is_premium']== true) {
                    $updatearr [$tank_id]['is_premium']      = 1;
                }   else {
                    $updatearr [$tank_id]['is_premium']      = 0;
                }
            }
        }

        if(!empty($updatearr)){
            $sql = "INSERT INTO `tanks` (`tank_id`, `nation_i18n`, `level`, `nation`, `is_premium`, `title`, `name_i18n`, `type`, `image`, `contour_image`, `image_small`) VALUES ";
            foreach ($updatearr as $tank_id => $val) {
                $sql .= "('{$val['tank_id']}', '{$val['nation_i18n']}', '{$val['level']}', '{$val['nation']}', '{$val['is_premium']}',  '{$val['title']}', '{$val['name_i18n']}', '{$val['type']}', '{$val['image']}', '{$val['contour_image']}', '{$val['image_small']}'), ";
            }
            $sql = substr($sql, 0, strlen($sql)-2);
            $sql .= ';';
            $db->insert($sql,__line__,__file__);
        }

    }
}

function update_tanks_single($tank_id) {
    $tmp = get_api('wot/encyclopedia/tankinfo',array('tank_id'=>$tank_id),array('nation_i18n','name','level','nation','is_premium','name_i18n','type','tank_id','contour_image','image','image_small'));
    if ((isset($tmp['status'])) && ($tmp['status'] == 'ok') && (!empty($tmp['status']['data'][$tank_id]))) {
        global $db;
        $tmp = $tmp['data'][$tank_id];

        $pieces = explode(':', $tmp['name']);
        $tmp['title'] = $pieces['1'];
        unset($tmp['name']);

        if ($tmp['is_premium']== true) {
            $tmp['is_premium'] = 1;
        }   else {
            $tmp['is_premium'] = 0;
        }

        $db->insert('INSERT INTO `tanks` (`'.implode('`,`',array_keys($tmp)).'`) VALUES ("'.implode('","',$tmp).'");',__line__,__file__);
    }
}
function achievements() {
    global $db;

    $tmp = $db->select('SELECT * FROM `achievements` ORDER BY `section_order` ASC, `order` ASC;',__line__,__file__);
    $ret = array();
    foreach ($tmp as $val) {
        //unserialize options
        if(!empty($val['options'])) {
            $val['options'] = unserialize($val['options']);
        }
        $ret[$val['name']] = $val;
    }
    return $ret;
}

function update_achievements_db($ach = array()) {
    global $db,$config,$lang;

    if(empty($ach)) {
        $ach = achievements();
    }

    $ach_res = get_api('wot/encyclopedia/achievements');

    if(isset($ach_res['status']) and ($ach_res['status'] == 'ok') and !empty($ach_res['data'])) {

        $updatearr = array();
        foreach($ach_res['data'] as $val) {
            if(!isset($ach[$val['name']])) {
                $updatearr[] = $val;
            }
        }
        //echop($updatearr);
        if(!empty($updatearr)) {
            $sql = 'INSERT INTO `achievements`
            (`name`, `section`, `section_i18n`, `options`, `section_order`, `image`, `name_i18n`, `type`, `order`, `description`, `condition`, `hero_info`)
            VALUES  ';

            foreach($updatearr as $val) {
                if(!empty($val['name'])) {
                    if(empty($val['options'])) {
                        $options = '';
                    } else {
                        $options = serialize($val['options']);
                    }
                    //add more categories
                    if(preg_match('/tankExpert/',$val['name'])) {
                        $val['section'] = 'expert';
                        $val['section_i18n'] = $lang['ach_section_expert'];
                        $val['section_order'] += 20;
                    }
                    if(preg_match('/mechanicEngineer/',$val['name'])) {
                        $val['section'] = 'mechanic';
                        $val['section_i18n'] = $lang['ach_section_mechanic'];
                        $val['section_order'] += 20;
                    }
                    if(preg_match('/histBattle/',$val['name'])) {
                        $val['section'] = 'hist';
                        $val['section_i18n'] = $lang['ach_section_hist'];
                        $val['section_order'] += 10;
                    }
                    //fix links for medals
                    if(empty($val['image'])) {
                        $val['image'] = $val['options']['0']['image'];
                    }
                    $sql .= "(".$db->quote($val['name']).",
                    '{$val['section']}',
                    ".$db->quote($val['section_i18n']).",
                    ".$db->quote($options).",
                    '{$val['section_order']}',
                    '{$val['image']}',
                    ".$db->quote($val['name_i18n']).",
                    '{$val['type']}',
                    '{$val['order']}',
                    ".$db->quote($val['description']).",
                    ".$db->quote($val['condition']).",
                    ".$db->quote($val['hero_info'])."), ";
                }
            }

            $sql = substr($sql, 0, strlen($sql)-2);
            $sql .= ';';
            $db->insert($sql,__line__,__file__);
        }
    }
}

function achievements_split($res,$ach) {
    $ret = array('sections' => array(), 'split' => array());
    $counter = array('id' => array(), 'split' => array(), 'count' => array());
    $num = $n = $m = 0;

    //list of ach. in clan
    //except 'class' section
    foreach($res as $val) {
        if(!empty($val['data']['achievements'])) {
            foreach($val['data']['achievements'] as $id => $t) {
                if(!in_array($id,$counter['id']) and isset($ach[$id]) and $ach[$id]['section'] != 'class') {
                    $counter['id'][] = $id;
                    if(isset($counter['count'][$ach[$id]['section']])) { $counter['count'][$ach[$id]['section']] += 1; } else { $counter['count'][$ach[$id]['section']] = 1;}
                }
            }
        }
    }

    if(empty($counter['id'])) return array(); //no ach. in clan - return empty array

    foreach($ach as $val) {
        //list of sections to display
        if(!isset($ret['sections'][$val['section']]) and in_array($val['name'],$counter['id'])) {
            $ret['sections'][$val['section']] = $val['section_i18n'];
        }
        //list of ach. to display
        if(in_array($val['name'],$counter['id'])) {
            $counter['split'][$val['section']][] = $val['name'];
        }
        //counters
        if($val['section'] == 'expert') {
            ++$n;
        }
        if($val['section'] == 'mechanic') {
            ++$m;
        }
    }
    //how many ach. in one section
    $num = $n;
    if($m > $num) { $num = $m; }
    if($num == 0) { $num = 8; }

    //chunk ach. to sections
    foreach($counter['count'] as $id => $n) {
        if($n > $num) {
            $ret['split'][$id] = array_chunk($counter['split'][$id], ceil($n/ceil($n/$num)));
        } else {
            $ret['split'][$id]['0'] = $counter['split'][$id];
        }
    }

    return $ret;
}

function achievements_ajax_player($ach,$filter = array()) {
    $ret = array('sections' => array(), 'split' => array());

    foreach($ach as $val) {
        if(!empty($filter) and !isset($filter[$val['name']])) {
            continue;
        }
        if(in_array($val['name'], array('marksOnGun','markOfMastery'))) {
            continue;
        }
        if(!isset($ret[$val['section']])) {
            $ret['sections'][$val['section']] = $val['section_i18n'];
        }
        $ret['split'][$val['section']][] = $val['name'];
    }

    return $ret;
}
function get_battle_types($array){
    foreach($array['data']['statistics'] as $key => $val){
        if(is_array($val)){
            $bat[$key] = $key;
        }
    } 
    return $bat;
}