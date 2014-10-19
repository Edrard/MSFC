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
* @version     $Rev: 3.1.2 $
*
*/


function marks() {
    $a = array(1=>'/static/3.15.0.4.2/common/img/classes/class-3.png', 2=> '/static/3.15.0.4.2/common/img/classes/class-2.png', 3=> '/static/3.15.0.4.2/common/img/classes/class-1.png', 4=>'/static/3.15.0.4.2/common/img/classes/class-ace.png');
    return $a;
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
        $sql = "CREATE TABLE IF NOT EXISTS `col_medals` (
        `account_id` INT(12),
        `updated_at` INT( 12 ) NOT NULL,
        KEY `updated_at` (`updated_at`) ) ENGINE=MYISAM ROW_FORMAT=DYNAMIC;";
        $q = $db->prepare($sql);
        if ($q->execute() !== TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
    }

    //Получаем структуру таблицы
    $sql = "SHOW COLUMNS FROM `col_medals` ;";
    $q = $db->prepare($sql);
    if ($q->execute() != TRUE) {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

    $medals_structure = array_fill_keys($q->fetchAll(PDO::FETCH_COLUMN), 1);
    $tsql = '';

    foreach ($medals as $key => $val) {
      if(!isset($medals_structure[$key])) {
        $tmp2 = substr($key, 0, 6);

        if ($tmp2 == 'mechan' || $tmp2 == 'tank_e') {
            $size = 'tinyint(1)';
        }   else {
            $size = 'smallint(12)';
        }
        $tsql .= "ALTER TABLE `col_medals` ADD `".$key."` ".$size." UNSIGNED NOT NULL DEFAULT 0;";
      }
    }
    if ($tsql != '') {
        $q = $db->prepare($tsql);
        if ($q->execute() !== TRUE) { die(show_message($q->errorInfo(),__line__,__file__,$tsql)); }
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
        if (count($tmp)==0) {
            $sql = "CREATE TABLE IF NOT EXISTS `col_tank_".$val."` (
            `account_id` INT(12),
            `updated_at` INT( 12 ) NOT NULL,
            KEY `updated_at` (`updated_at`) ) ENGINE=MYISAM ROW_FORMAT=DYNAMIC;";
            $q = $db->prepare($sql);
            if ($q->execute() !== TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            $sqlarr = array();
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
        }

        $sqlarr = $tanks_structure = array();
        //Получаем структуру таблицы
        $sql = "SHOW COLUMNS FROM `col_tank_".$val."`;";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        $tanks_structure = array_fill_keys($q->fetchAll(PDO::FETCH_COLUMN), 1);
        foreach ($ntanks[$val] as $tank_id => $val4 ) {
            if (!isset($tanks_structure[$tank_id.'_battles'])) {
                $sqlarr[] = "ALTER TABLE `col_tank_".$val."`
                ADD `".$tank_id."_battles` smallint(12) UNSIGNED NOT NULL DEFAULT 0,
                ADD `".$tank_id."_wins` smallint( 12 ) NOT NULL DEFAULT 0,
                ADD `".$tank_id."_mark_of_mastery` tinyint(1) UNSIGNED NOT NULL DEFAULT 0;";
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
    $top_tanks = array();
    $sql = 'SELECT t.level, t.type, t.name_i18n, t.tank_id, tt.show, tt.order, tt.shortname as title, tt.index
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
    $sql = 'SELECT DISTINCT tt.index
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
                $new = get_api('clan/info',array('clan_id' => $config['clan']));
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
            $sql = "UPDATE ".$val['prefix']."config SET value = '".now()."' WHERE name = 'autoclean';";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
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
function update_tanks_db($tanks = array(), $force = 0) {
    global $db,$config,$cache;

    if(empty($tanks)) {
      $tanks = tanks();
    }
    $tanks_api = get_api('encyclopedia/tanks');

    if ((isset($tanks_api['status'])) && ($tanks_api['status'] == 'ok')) {
      $updatearr = array();

      if(isset($_POST['update_tanks_db']) or $force == 1){
          $sql = "TRUNCATE TABLE `tanks`;";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
          $tanks = array();
      }

      foreach ($tanks_api['data'] as $tank_id => $val) {
        if(!isset($tanks[$tank_id])){
            $updatearr [$tank_id] = $val;

            $pieces = explode(':', $val['name']);
            $updatearr [$tank_id]['title']      = $pieces['1'];

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
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
      }

    }
}

function update_tanks_single($tank_id) {
  $tmp = get_api('encyclopedia/tankinfo',array('tank_id'=>$tank_id),array('nation_i18n','name','level','nation','is_premium','name_i18n','type','tank_id','contour_image','image','image_small'));

  if ((isset($tmp['status'])) && ($tmp['status'] == 'ok')) {
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

    $sql = 'INSERT INTO `tanks` (`'.implode('`,`',array_keys($tmp)).'`) VALUES ("'.implode('","',$tmp).'");';
    $q = $db->prepare($sql);
    if ($q->execute() != TRUE) { die(show_message($q->errorInfo(),__line__,__file__,$sql)); }

  }
}

function achievements() {
    global $db;
    $sql = " SELECT * FROM `achievements` ORDER BY `section_order` ASC, `order` ASC;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $tmp = $q->fetchAll(PDO::FETCH_ASSOC);
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
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

  $ach_res = get_api('encyclopedia/achievements');

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
      $q = $db->prepare($sql);
      if ($q->execute() != TRUE) {
          die(show_message($q->errorInfo(),__line__,__file__,$sql));
      }
    }
  }
}

function achievements_split($res,$ach) {
  $ret = array('sections' => array(), 'split' => array());
  $counter = array('id' => array(), 'split' => array());
  $num = $n = $m = 0;

  //list of ach. in clan
  //except 'class' section
  foreach($res as $val) {
    foreach($val['data']['achievements'] as $id => $t) {
      if(!in_array($id,$counter['id']) and isset($ach[$id]) and $ach[$id]['section'] != 'class') {
        $counter['id'][] = $id;
        if(isset($counter['count'][$ach[$id]['section']])) { $counter['count'][$ach[$id]['section']] += 1; } else { $counter['count'][$ach[$id]['section']] = 1;}
      }
    }
  }

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

function achievements_ajax_player($ach) {
  $ret = array('sections' => array(), 'split' => array());

  foreach($ach as $val) {
    if($val['name'] != 'marksOnGun') {
      if(!isset($ret[$val['section']])) {
        $ret['sections'][$val['section']] = $val['section_i18n'];
      }
      $ret['split'][$val['section']][] = $val['name'];
    }
  }

  return $ret;
}
?>