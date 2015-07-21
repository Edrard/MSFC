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
* @version     $Rev: 3.2.1 $
*
*/

function base_dir($local = null)
{
    if($local == null){
        $local = dirname($_SERVER['PHP_SELF']);
    }
    $full = dirname(__FILE__);

    return preg_replace('/'.$local.'[\/\\ ]?$/','',$full);
} 
function error($msg)
{
    $data = '<div align="center" class="ui-state-error ui-corner-all">';
    foreach ( $msg as $value ) {
        $data .= $value."<br />";
    }
    $data .= '</div>';
    return $data;
    /*TODO: Нужна ли эта функция, когда есть почти аналогичная show_message()? Посмотреть, может убрать ее.*/
}
function insert_config($config)
{
    global $db;

    if(isset($config['consub'])){
        if(!isset($config['news'])){
            $config['news'] = 0;
        }
        if(!isset($config['dst'])){
            $config['dst'] = 0;
        }
    }
    if(isset($config['consub_2'])){
        if(!isset($config['cron'])){
            $config['cron'] = 0;
        }
        if(!isset($config['cron_multi'])){
            $config['cron_multi'] = 0;
        }
        if(!isset($config['cron_autoclean'])){
            $config['cron_autoclean'] = 0;
        }
        if(!isset($config['cron_cleanleft'])){
            $config['cron_cleanleft'] = 0;
        }
        if(!isset($config['cron_cleanold'])){
            $config['cron_cleanold'] = 0;
        }
        if(!isset($config['cron_clean_log'])){
            $config['cron_clean_log'] = 0;
        }
    }
    if(isset($config['consub_3'])){
        if(!isset($config['company'])){
          $config['company'] = 0;
        } else {
          $config['company'] = 1;
        }
        if(!is_numeric($config['company_count']) or $config['company_count'] < 1) {
          $config['company_count'] = 1;
        }
    }
    $prefix = array();
    if(isset($config['all_multiclans'])){
        //Получаем список префиксов из таблицы multiclan
        $prefix = $db->select('SELECT prefix FROM `multiclan`;',__line__,__file__,'rows');
    }
    if(empty($prefix)) { $prefix = array($db->prefix); }

    unset($config['consub'],$config['consub_2'],$config['consub_3'],$config['all_multiclans'],$config['tab_redirect_id']);

    foreach($prefix as $t) {
      $db->change_prefix($t);
      foreach($config as $name => $var){
          $db->insert('UPDATE `config` SET value = "'.$var.'" WHERE name = "'.$name.'";',__line__,__file__);
          if($name == 'clan'){
              $db->insert('UPDATE `multiclan` SET id = "'.$var.'" WHERE main = "1";',__line__,__file__);
          }
      }
    }
}
function new_user($post)
{
    global $db,$auth;
    unset($post['newuser']);
    $status_user = $db->select('SELECT COUNT(id) FROM `users` WHERE user = "'.$post['user'].'";',__line__,__file__,'column');
    if($status_user == 0){
        $post['password'] = $auth->encrypt($post['password']);
        $post['email'] = $post['user'].'@local.com';
        if($auth->rights != 'all') { $post['prefix'] = $db->prefix; }
        $db->insert("INSERT INTO `users` (`".(implode("`,`",array_keys($post)))."`) VALUES ('".(implode("','",$post))."');",__line__,__file__);
        return FALSE; 
    }   
    return TRUE;
}
function edit_user($post)
{
    global $db,$auth,$lang;
    unset($post['edituser']);
    $status_user = $db->select('SELECT COUNT(id) FROM `users` WHERE user = "'.$post['oldname'].'";',__line__,__file__,'column');
    if($status_user == 1){
        $oldname = $post['oldname'];
        unset($post['oldname']);
        if(strlen($post['password']) > 12){
            $message['text'] = $lang['error_toolong'];
            $message['color'] = 'red';
            return $message;
        }
        if(strlen($post['password']) > 0){
            $post['password'] = $auth->encrypt($post['password']);
        }else{
            unset($post['password']);
        }
        $post['email'] = $post['user'].'@local.com';
        if($auth->rights != 'all') { $post['prefix'] = $db->prefix; }
        $nm = 0;
        $insert = '';
        foreach($post as $column => $val){
            if($nm == 0){
                $insert .= "`".$column."` = '".$val."'";  
                $nm++;  
            }else{
                $insert .= ', `'.$column."` = '".$val."'";
            }
        }
        $sql = "UPDATE `users` SET ".$insert." WHERE user = '".$oldname."';";
        $db->insert($sql,__line__,__file__);
    }
    return '';
}
function delete_user($get)
{
    global $db;
    if($get['userdel'] == 1 and isset($get['id']) and is_numeric($get['id'])){
        $status_user = $db->select('SELECT COUNT(id) FROM `users` WHERE id = "'.$get['id'].'";',__line__,__file__,'column');
        if($status_user > 0){
            $db->insert('DELETE FROM `users` WHERE id = "'.$get['id'].'";',__line__,__file__);
            return FALSE;
        }
    }
    return TRUE;
    //header ("Location: ./index.php?page=main#tabs-2");
}
function delete_multi($get){
    global $db,$cache;
    if($get['removeclan'] == 1){
        if(isset($get['clan'])){
            if(is_numeric($get['clan'])){
                $status_clan = $db->select('SELECT `prefix` FROM `multiclan` WHERE id = "'.$get['clan'].'";',__line__,__file__,'column');
                //print_r($status_clan);
                if(isset($status_clan) and !empty($status_clan))
                {
                   $tables = $db->select('SHOW TABLES LIKE "'.substr($status_clan, 0, strlen($status_clan)-1).'\_%";',__line__,__file__,'rows');

                   if(!empty($tables)) {
                     foreach($tables as $tab){
                          $db->insert('DROP TABLE IF EXISTS `'.$tab.'`;',__line__,__file__);
                      }
                    }
                    $db->insert('DELETE FROM `multiclan` WHERE id = "'.$get['clan'].'";',__line__,__file__);
                    $cache->clear('get_last_roster_'.$get['clan']);
                    $cache->clear('eff_ratings_'.$get['clan'],ROOT_DIR.'/cache/other/');
                }
            }
        }
    }
}
function add_multiclan($post, $lang){
   global $db, $dbprefix;
   //print_r($post); die;
   unset($post['multiadd']);
   if ($post['id'] && $post['prefix'] && $post['sort']){
       if (is_numeric($post['id'])){
           if (preg_match('/^\d/', $post['prefix']) == 0 && strlen(preg_replace('/(.*)_/','$1',$post['prefix'])) <= 5){
               if (ctype_alnum(preg_replace('/(.*)_/','$1',$post['prefix']))){
                   $status_clan = $db->select('SELECT COUNT(id) FROM `multiclan` WHERE id = "'.$post['id'].'";',__line__,__file__,'column');
                   $status_prefix = $db->select('SELECT COUNT(id) FROM `multiclan` WHERE prefix = "'.$post['prefix'].'";',__line__,__file__,'column');
                   if ($status_clan == 0 and $status_prefix == 0) {
                       $db->insert('INSERT INTO `multiclan` (`'.(implode('`,`',array_keys($post))).'`) VALUES ("'.(implode('","',$post)).'");',__line__,__file__);
                       insert_file(LOCAL_DIR.'/sql/clan.sql');
                       $db->insert('UPDATE `config` SET value = "'.$post['id'].'" WHERE name = "clan";',__line__,__file__);
                       $db->insert('UPDATE `config` SET value = "'.$post['server'].'" WHERE name = "server";',__line__,__file__);
                       $multi_get = '';
                       if (isset($_GET['multi'])){
                           $multi_get = '&multi='.$_GET['multi'];
                       }
                   }
               }
           }
       }
   }
   header ( 'Location: index.php?page=main#tabs-8'.$multi_get );
   exit;
}


function delete_tab($get)
{
    global $db;
    if($get['del'] == 1){
        if($get['type'] == 0){
            $info = $db->select('SELECT * FROM `tabs` WHERE id = "'.$get['id'].'";',__line__,__file__,'fetch');
            $target_path = ROOT_DIR.'/tabs/'.$info['file'];
            unlink($target_path);
        }
        $db->insert('DELETE FROM `tabs` WHERE id = "'.$get['id'].'";',__line__,__file__);
    }elseif($get['del'] == 2){
        $file = '';
        $new = explode('php',$get['file']);
        array_pop($new);
        foreach($new as $var){
            $file .= $var; 
        }
        $target_path = ROOT_DIR.'/tabs/'.$file.'.php';
        unlink($target_path);
    }
    //header ("Location: ./index.php?page=main#tabs-2");
}

function creat_ajax_tab($post)
{
    global $db;
    unset($post['ajaxcre']);
    $post['file'] = trim($post['file']);
    $status_tab = $db->select('SELECT COUNT(*) FROM `tabs` WHERE file = "'.$post['file'].'";',__line__,__file__,'column');
    if($status_tab == 0){
        $max = $db->select('SELECT MAX(id) FROM `tabs`;',__line__,__file__,'column');
        $max = (int) $max;
        $post['id'] = $max + 10;
        $post['name'] = '...';
        $post['type'] = '1';
        $post['status'] = '0';
        $sql = "INSERT INTO `tabs` (`".(implode("`,`",array_keys($post)))."`) VALUES ('".(implode("','",$post))."');";
        $db->insert($sql,__line__,__file__);
    }   else {
        return TRUE;
    }
    return FALSE;
}

function tabs_info_db($post)
{
    global $db;
    $error = 0;
    $old_prefix = $db->prefix;
    unset($post['tabsub']);

    if(isset($post['all_multiclans'])) {
      unset($post['all_multiclans']);
      //Получаем список префиксов из таблицы multiclan
      $prefix = $db->select('SELECT prefix FROM `multiclan`;',__line__,__file__,'rows');
    }
    if(empty($prefix)) { $prefix = array($db->prefix); }

    foreach($post as $key => $var){
        $tmp = explode('_',$key);
        $type = array_pop($tmp);
        $tmp_key = implode('_',$tmp);
        if(!isset($post[$tmp_key.'_status']) && !isset($new[$tmp_key]['status'])){
            $new[$tmp_key]['status'] = 0;        
        }
        if($type == 'status'){
            $var = 1;
        }
        if($type == 'id'){
            if(!isset($checker)){
                $checker[] = $var;
            }else{
                if(!in_array($var,$checker)){
                    $checker[] = $var;
                }else{
                    $error = 2;
                }
            }
        }
        if(strlen($var) > 0){
            $new[$tmp_key][$type] = $var;
        }
    }  
    if($error == 0){
     foreach($prefix as $t) {
      $db->change_prefix($t);
        foreach($new as $vals){
            //print_r($vals);
            $sql = "INSERT INTO `tabs` (`".(implode("`,`",array_keys($vals)))."`) VALUES ('".(implode("','",$vals))."')
                     ON DUPLICATE KEY UPDATE ";
            foreach($vals as $column => $val) {
              $sql .= "`$column` = '$val', ";
            }
            $sql = substr($sql, 0, strlen($sql)-2);
            $sql .= ';';
            $db->insert($sql,__line__,__file__);
        }
     }
     $db->change_prefix($old_prefix);
    }
    //echop($db->sqls);
    return $error;
}

function read_tabs_dir()
{
    foreach(scandir(ROOT_DIR.'/tabs/') as $file){
        if (preg_match ("/\.php/", $file)){
            $files_list[] = $file;
        }
    }
    return $files_list;
}
function check_tabs_db($tabs)
{
    global $db;
    foreach($tabs as $tab){
        $status_tab[$tab] = $db->select('SELECT COUNT(`file`) FROM `tabs` WHERE file = "'.$tab.'";',__line__,__file__,'column');
    }
    return $status_tab;
    /*TODO: Не совсем понимаю назначение функции, она проверяет не пытаются ли залить вкладку с одинаковым именем файла? Нужна ли она? Надо бы разобраться.*/
    /*TODO: Или, возможно, запрос можно и по другому построить?.*/
}
function read_users()
{
    global $db,$auth;
    $where = '';
    if($auth->rights != 'all') { $where = 'WHERE prefix = "'.$db->prefix.'" '; }
    return $db->select('SELECT * FROM `users` '.$where.' ORDER BY id ASC;',__line__,__file__);
    /*TODO: Проверить нужна ли эта функция одного запроса, и должна ли она быть отдельной,
    или отнести ее к класу авторизации, что бы вызывать ее через $auth->read_users()*/
}
function insert_file($filename)
{
    global $db;
    $templine = '';
    $lines = file($filename);
    foreach ($lines as $line)
    {
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;
        $templine .= $line;
        if (substr(trim($line), -1, 1) == ';')
        {
            $db->insert($templine,__line__,__file__);
            $templine = '';
        }
    }
}
function cron_file_recreat()
{
    if(file_exists(ROOT_DIR.'/cron.log')) {
        unlink(ROOT_DIR.'/cron.log');
    }
    if(!file_exists(ROOT_DIR.'/cron.log')) {
        if($fh = fopen(ROOT_DIR.'/cron.log', 'a')){
            fclose($fh);
        }
        chmod(ROOT_DIR.'/cron.log', 0777);
    }
}

function recreat_db()
{
    global $db;

    $multi_exist = $db->select('SHOW TABLES LIKE "%multiclan";',__line__,__file__,'column');

    if($multi_exist == 'msfcmt_multiclan') {

        $all_prefix = $db->select('SELECT `prefix` FROM `multiclan`;',__line__,__file__,'rows');
        foreach($all_prefix as $t) {
           $tables = $db->select('SHOW TABLES LIKE "'.substr($t, 0, strlen($t)-1).'\_%";',__line__,__file__,'rows');

           if(!empty($tables)) {
             foreach($tables as $tab){
                  $db->insert('DROP TABLE IF EXISTS `'.$tab.'`;',__line__,__file__);
             }
           }
        }
    }

    $multi_tables = $db->select('show tables like "msfcmt\_%";',__line__,__file__,'rows');

    if(!empty($multi_tables)) {
      foreach($multi_tables as $tab){
          $db->insert('DROP TABLE IF EXISTS `'.$tab.'`;',__line__,__file__);
      }
    }
}
function insert_multicaln($id_clan,$server,$dbprefix)
{
    global $db;
    if(!$dbprefix){
        $dbprefix = 'msfc_';
    }
    $insert = array(
        'id' => $id_clan,
        'prefix' => $dbprefix,
        'main' => 1,
        'sort' => 0,
        'server' => $server
    );
    $db->insert('INSERT INTO `multiclan` ('.(implode(',',array_keys($insert))).') VALUES ("'.(implode('","',$insert)).'");',__line__,__file__);
    /*TODO: Нафиг эта функция, для одного запроса? Посмотреть где она используется и убрать по возможности */
}
function edit_multi_clan($post)
{
    global $db;
    foreach($post['Array'] as $id => $val){
        $db->insert('UPDATE `multiclan` SET `sort` = "'.$val['order'].'" WHERE id = "'.$id.'";',__line__,__file__);
    }
    /*TODO: Нафиг эта функция, для одного запроса? Посмотреть где она используется и убрать по возможности */
}
/***** Exinaus *****/
function get_top_tanks_list() {
    global $db;
    $top_tanks=array();

    $sql='SELECT t.level, t.type, t.name_i18n, t.tank_id, tt.show, tt.order, tt.shortname, tt.index
    FROM `top_tanks` tt, `tanks` t
    WHERE t.tank_id = tt.tank_id;';

    $top_tanks_unsorted = $db->select($sql,__line__,__file__);

    foreach($top_tanks_unsorted as $val) {
        $top_tanks[$val['index']][$val['tank_id']]['tank_id'] = $val['tank_id'];
        $top_tanks[$val['index']][$val['tank_id']]['name_i18n'] = $val['name_i18n'];
        $top_tanks[$val['index']][$val['tank_id']]['lvl'] = $val['level'];
        $top_tanks[$val['index']][$val['tank_id']]['type'] = $val['type'];
        $top_tanks[$val['index']][$val['tank_id']]['show'] = ($val['show'] == 1) ? 'checked="checked"' : '';
        $top_tanks[$val['index']][$val['tank_id']]['order'] = $val['order'];
        $top_tanks[$val['index']][$val['tank_id']]['shortname'] = isset($val['shortname']) ? $val['shortname'] : '';
        $top_tanks[$val['index']][$val['tank_id']]['index'] = $val['index'];
    }
    return $top_tanks;
}

function update_top_tanks($array) {
    global $db;
    $prefix = array();

    if(isset($array['all_multiclans'])){
        $prefix = $db->select('SELECT prefix FROM `multiclan`;',__line__,__file__,'rows');
    }
    if(empty($prefix)) { $prefix = array($db->prefix); }

    $array = $array['Array'];

    foreach($prefix as $t) {
      $db->change_prefix($t);
      $db->insert('delete from `top_tanks`;',__line__,__file__);
      foreach ($array as $index =>$misc) {
        foreach ($misc as $tank_id => $val) {
            $val['show'] = isset($val['show']) ? 1 : 0;
            $sql = 'INSERT INTO `top_tanks` (`tank_id`, `order`, `show`, `shortname`, `index`)
            VALUES ("'.$tank_id.'", "'.$val['order'].'", "'.$val['show'].'", "'.$val['shortname'].'", "'.$val['index'].'");';
            $db->insert($sql,__line__,__file__);
            /*TODO: Пересмотреть формирование запроса, возможно вместо множества запросов сделать один с кучей VALUES */
        }
      }
    }
}

function delete_top_tank($tank_id, $index) {
    global $db;
    $db->insert('DELETE FROM `top_tanks` WHERE tank_id = '.$tank_id.' AND `index` = '.$index.';',__line__,__file__);
    /*TODO: Нафиг эта функция, для одного запроса? Посмотреть где она используется и убрать по возможности */
}

function add_top_tanks($lvl,$type) {
    global $db;

    $tanks = $db->select('SELECT * FROM `tanks` WHERE level = "'.$lvl.'" AND type = "'.$type.'";',__line__,__file__);
    $tmp = get_available_tanks_index();
    $index = 0;
    for ($i=1; $i<=10; $i++){
        if (!isset($tmp['index'][$i])) {
            $index = $i;
            break;
        }
    }
    //print_r($index);
    if ((count($tanks)) > 0 &&($index <> 0)) {
        unset($q);
        $i = count($tanks);
        $j = 1;
        $sql = 'INSERT INTO `top_tanks` (`tank_id`, `index`,  `order`) VALUES ';
        foreach($tanks as $val) {
            $sql .= "('{$val['tank_id']}', '".($index)."', '".($j*10)."')";
            if($i != $j) { $sql .= ', '; $j++; } else { $sql .= ';'; }
        }
        $db->insert($sql,__line__,__file__);
    }
}

function delete_top_tanks($lvl, $type) {
    global $db;
    $tanks = $db->select('SELECT tank_id FROM `tanks` WHERE level = "'.$lvl.'" AND type = "'.$type.'";',__line__,__file__);
    if (count($tanks) > 0) {
        unset($q);
        $i = count($tanks);
        $j = 1;
        $sql = 'DELETE FROM `top_tanks` where ';
        foreach ($tanks as $val) {
            $sql .= 'tank_id = "'.$val['tank_id'].'"';
            if($i != $j) { $sql .= ' OR '; $j++; } else { $sql .= ';'; }
        }
        $db->insert($sql,__line__,__file__);
    }
}

function clean_db_left_players() {
    global $db,$cache,$config;

    $new = $cache->get('get_last_roster_'.$config['clan'],0);
    if($new === FALSE or empty($new['data']['members'])) { return; }

    $roster_id = array_keys(roster_resort_id($new['data']['members']));

    if(count($roster_id)>0) {
        $tmp = $db->select('SHOW TABLES LIKE "'.$db->prefix.'col%";',__line__,__file__,'rows');

        if(count($tmp)>0) {
            $roster_id_tmp = implode(',',$roster_id);

            foreach($tmp as $val) {
                $db->insert('DELETE FROM '.$val.' WHERE account_id NOT IN('.$roster_id_tmp.');',__line__,__file__);
            }
        }
    }
}
function clean_db_old_cron($date) {
    global $db;

    if(!is_numeric($date)) { $date = 30; }
    $del = now()-$date*24*60*60;

    $tmp = $db->select('SHOW TABLES LIKE "'.$db->prefix.'col%";',__line__,__file__,'rows');

    if(count($tmp)>0) {
        foreach($tmp as $val) {
            $db->insert('DELETE FROM '.$val.' WHERE updated_at < "'.$del.'";',__line__,__file__);
        }
    }
}
function utf8_substr($str, $offset, $length = null)
{
    #в начале пробуем найти стандартные функции
    if (function_exists('iconv_substr'))
    {
        #(PHP 5)
        return iconv_substr($str, $offset, $length, 'utf-8');
    }
    if (function_exists('mb_substr'))
    {
        #(PHP 4 >= 4.0.6, PHP 5)
        return mb_substr($str, $offset, $length, 'utf-8');
    }
    preg_match_all('~[\x09\x0A\x0D\x20-\x7E]             # ASCII
                     | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
                     |  \xE0[\xA0-\xBF][\x80-\xBF]       # excluding overlongs
                     | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
                     |  \xED[\x80-\x9F][\x80-\xBF]       # excluding surrogates
                     |  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
                     | [\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
                     |  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
                    ~xs', $str, $m);
    if ($length !== null)
    {
        $a = array_slice($m[0], $offset, $length);
    }
    else
    {
        $a = array_slice($m[0], $offset);
    }
    return implode('', $a);
}
?>