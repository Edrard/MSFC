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
    function base_dir($local = null)
    {
        if($local == null){
            $local = dirname($_SERVER['PHP_SELF']);
        }
        $full = dirname(__FILE__);
        $public_base = str_replace($local, "", $full);

        return $public_base;
    } 
    function error($msg) 
    {
        $data = '<div align="center" class="ui-state-error ui-corner-all">';
        foreach ( $msg as $value ) {
            $data .= $value."<br />";
        }
        $data .= '</div>';
        return $data;
    }
    function insert_config($config)
    {
        global $db;

        if(isset($config['consub'])){
            if(!isset($config['news'])){
                $config['news'] = 0;
            }
        }
        if(isset($config['consub_2'])){
            if(!isset($config['cron'])){
                $config['cron'] = 0;
            }
            if(!isset($config['cron_multi'])){
                $config['cron_multi'] = 0;
            }
            if(!isset($config['cron_auth'])){
                $config['cron_auth'] = 0;
            }
        }      
        unset($config['consub'],$config['consub_2']);
        foreach($config as $name => $var){
            $sql = "UPDATE `config` SET value = '".$var."' WHERE name = '".$name."';";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            if($name == 'clan'){
                $sql = "UPDATE multiclan SET id = '".$var."' WHERE main = '1';";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
            }
        }
    }
    function new_user($post)
    {
        global $db,$auth;
        unset($post['newuser']);
        $sql = "SELECT COUNT(id) FROM `users` WHERE user = '".$post['user']."';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $status_user = $q->fetchColumn();  
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        if($status_user == 0){
            $post['password'] = $auth->encrypt($post['password']);
            $post['email'] = $post['user'].'@local.com';
            if($auth->rights != 'all') { $post['prefix'] = $db->prefix; }
            $sql = "INSERT INTO `users` (`".(implode("`,`",array_keys($post)))."`) VALUES ('".(implode("','",$post))."');";

            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            } 
            return FALSE; 
        }   
        return TRUE;
    }
    function edit_user($post)
    {
        global $db,$auth;
        unset($post['edituser']);
        $sql = "SELECT COUNT(id) FROM `users` WHERE user = '".$post['oldname']."';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $status_user = $q->fetchColumn();  
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        if($status_user == 1){
            $oldname = $post['oldname'];
            unset($post['oldname']);
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
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }   

        }   

    }
    function delete_user($get)
    {
        global $db;
        if($get['userdel'] == 1){
            if(isset($get['id'])){
                if(is_numeric($get['id'])){
                    $sql = "SELECT COUNT(id) FROM `users` WHERE id = '".$get['id']."';";
                    $q = $db->prepare($sql);
                    if ($q->execute() == TRUE) {
                        $status_user = $q->fetchColumn();  
                    }else{
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }     
                    if($status_user > 0){

                        $sql = "DELETE FROM `users` WHERE id = '".$get['id']."';";
                        $q = $db->prepare($sql);
                        if ($q->execute() != TRUE) {
                            die(show_message($q->errorInfo(),__line__,__file__,$sql));
                        } 
                        return FALSE; 
                    }
                }
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
                    $sql = "SELECT * FROM multiclan WHERE id = '".$get['clan']."';";   
                    //echo $sql;      
                    $q = $db->prepare($sql);
                    if ($q->execute() == TRUE) {
                        $status_clan = $q->fetchAll(PDO :: FETCH_ASSOC);  
                    }else{
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }
                    //print_r($status_clan);
                    $sql = "SHOW TABLES LIKE '".$status_clan['0']['prefix']."%';";   
                    echo $sql;      
                    $q = $db->prepare($sql);
                    if ($q->execute() == TRUE) {
                        $list = $q->fetchAll(PDO :: FETCH_ASSOC);  
                    }else{
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }
                    //print_r($list);
                    foreach($list as $val){
                        foreach($val as $v){
                            $sql = "DROP TABLE IF EXISTS ".$v.";";
                            //echo $sql;
                            $q = $db->prepare($sql);
                            if ($q->execute() != TRUE) {
                                die(show_message($q->errorInfo(),__line__,__file__,$sql));
                            }     
                        }
                    }
                    if(!empty($status_clan)){

                        $sql = "DELETE FROM multiclan WHERE id = '".$get['clan']."';";
                        $q = $db->prepare($sql);
                        if ($q->execute() != TRUE) {
                            die(show_message($q->errorInfo(),__line__,__file__,$sql));
                        } 
                    }
                    $cache->clear('get_last_roster_'.$get['clan']);
                }
            }
        }
    }
    function add_multiclan($post,$lang){

        global $db,$dbprefix;  
        //print_r($post); die;                              
        unset($post['multiadd']);
        if($post['id'] && $post['prefix'] && $post['sort']){
            if(is_numeric($post['id'])){     
                if(preg_match('/^\d/', $post['prefix']) == 0 && strlen(preg_replace('/(.*)_/','$1',$post['prefix'])) <= 5){  
                    if(ctype_alnum(preg_replace('/(.*)_/','$1',$post['prefix']))){                   
                        $sql = "SELECT COUNT(id) FROM multiclan WHERE id = '".$post['id']."';";
                        $q = $db->prepare($sql);
                        if ($q->execute() == TRUE) {
                            $status_clan = $q->fetchColumn();  
                        }else{
                            die(show_message($q->errorInfo(),__line__,__file__,$sql));
                        }

                        $sql = "SELECT COUNT(id) FROM multiclan WHERE prefix = '".$post['prefix']."';";
                        $q = $db->prepare($sql);
                        if ($q->execute() == TRUE) {
                            $status_prefix = $q->fetchColumn();  
                        }else{
                            die(show_message($q->errorInfo(),__line__,__file__,$sql));
                        }

                        if($status_clan == 0 ){
                            if($status_prefix == 0){
                                $sql = "INSERT INTO multiclan (`".(implode("`,`",array_keys($post)))."`) VALUES ('".(implode("','",$post))."');";
                                $q = $db->prepare($sql);
                                if ($q->execute() != TRUE) {
                                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                                } 
                                insert_file(LOCAL_DIR.'/sql/clan.sql');
                                $sql = "UPDATE `config` SET 
                                value = '".$post['id']."'
                                WHERE name = 'clan';";
                                $q = $db->prepare($sql);
                                if ($q->execute() != TRUE) {
                                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                                } 
                                $sql = "UPDATE `config` SET 
                                value = '".$post['server']."'
                                WHERE name = 'server';";
                                $q = $db->prepare($sql);
                                if ($q->execute() != TRUE) {
                                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                                }  
                                $multi_get = '';
                                if(isset($_GET['multi'])){
                                    $multi_get = '&multi='.$_GET['multi'];
                                }
                                if (!headers_sent()) {
                                    header ( 'Location: index.php?page=main#tabs-8'.$multi_get );
                                    exit;
                                } else { print_R('<script type="text/javascript">
                                    location.replace("Location: index.php?page=main#tabs-8'.$multi_get.'");
                                    </script>');
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!headers_sent()) {
            header ( 'Location: index.php?page=main#tabs-8' );
            exit;
        } else { ?>
        <script type="text/javascript">
            location.replace("index.php?page=main#tabs-8");
        </script>
        <?      }
    }


    function delete_tab($get)
    {
        global $db;
        if($get['del'] == 1){
            if($get['type'] == 0){
                $sql = "SELECT * FROM `tabs` WHERE id = '".$get['id']."';";
                $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $info = $q->fetch();  
                }else{
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }     
                $target_path = ROOT_DIR.'/tabs/'.$info['file'];
                unlink($target_path);
            }
            $sql = "DELETE FROM `tabs` WHERE id = '".$get['id']."';";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            } 
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
        $sql = "SELECT COUNT(*) FROM `tabs` WHERE file = '".$post['file']."';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $status_tab = $q->fetchColumn();  
        }else{ 
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        if($status_tab == 0){ 
            $sql = "SELECT MAX(id) FROM `tabs`;";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $max = $q->fetchColumn();  
            }else{ 
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            } 
            $max = (int) $max;
            $post['id'] = $max + 10;
            $post['name'] = '...';
            $post['type'] = '1';
            $post['status'] = '0';
            $sql = "INSERT INTO `tabs` (`".(implode("`,`",array_keys($post)))."`) VALUES ('".(implode("','",$post))."');";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }     
        }else{
            return TRUE;
        }
        return FALSE;
    }

    function tabs_info_db($post)
    {
        global $db;
        $error = 0;
        unset($post['tabsub']);
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
            foreach($new as $vals){ 
                //print_r($vals);
                if(count($vals) == 6){
                    $sql = "SELECT COUNT(*) FROM `tabs` WHERE file = '".$vals['file']."';";
                    $q = $db->prepare($sql);
                    if ($q->execute() == TRUE) {
                        $num = $q->fetchColumn();  
                    }else{ 
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }  
                    if($num == 0){
                        $sql = "INSERT INTO `tabs` (`".(implode("`,`",array_keys($vals)))."`) VALUES ('".(implode("','",$vals))."');";
                        $q = $db->prepare($sql);
                        if ($q->execute() != TRUE) {
                            die(show_message($q->errorInfo(),__line__,__file__,$sql));
                        }     

                    }else{
                        $nm = 0;
                        $insert = '';
                        foreach($vals as $column => $val){
                            if($nm == 0){
                                $insert .= "`".$column."` = '".$val."'";  
                                $nm++;  
                            }else{
                                $insert .= ', `'.$column."` = '".$val."'";
                            }    
                        }
                        $sql = "UPDATE `tabs` SET ".$insert." WHERE file = '".$vals['file']."';";
                        //echo $sql;
                        $q = $db->prepare($sql);
                        if ($q->execute() != TRUE) {
                            die(show_message($q->errorInfo(),__line__,__file__,$sql));
                        }     
                    }

                }else{
                    $error = 1;
                }
            }
        }
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
            $sql = "SELECT COUNT(*) FROM `tabs` WHERE file = '".$tab."';";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $status_tab[$tab] = $q->fetchColumn();  
            }else{
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }  
        }
        return $status_tab;
    }
    function read_users()
    {
        global $db,$auth;
        $where = '';
        if($auth->rights != 'all') { $where = 'WHERE prefix = "'.$db->prefix.'" '; }
        $sql = "SELECT * FROM `users` $where ORDER BY id ASC;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            return $q->fetchAll();
        }else{ 
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }  
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
                $q = $db->prepare($templine);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$templine));
                } 
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
        global $db,$config;

        $sql = "SHOW TABLES LIKE 'multiclan';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $multi_exist = $q->fetchColumn();
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        if($multi_exist == 'multiclan') {

            $sql = "SELECT `prefix` FROM `multiclan`;";
            $q = $db->prepare($sql);
            if ($q->execute() == TRUE) {
                $all_prefix = $q->fetchAll(PDO::FETCH_ASSOC);
            }else{
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            foreach($all_prefix as $t) {

                $sql = "show tables like '".$t['prefix']."%';";
                $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $tables = $q->fetchAll();
                }else{
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                foreach($tables as $tab){
                    $sql = "DROP TABLE IF EXISTS ".$tab[0].";";
                    //echo $sql;
                    $q = $db->prepare($sql);
                    if ($q->execute() != TRUE) {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }
                }
            }
        }

        $sql = "DROP TABLE IF EXISTS multiclan;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $all_prefix = $q->fetchAll(PDO::FETCH_ASSOC);
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        $sql = "DROP TABLE IF EXISTS `users`;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $all_prefix = $q->fetchAll(PDO::FETCH_ASSOC);
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
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
        $sqlt = "INSERT INTO multiclan (".(implode(",",array_keys($insert))).") VALUES ('".(implode("','",$insert))."');";
        $q = $db->prepare($sqlt);
        if ($q->execute() !== TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sqlt));
        }    
    }
    function edit_multi_clan($post)
    {
        global $db;
        foreach($post['Array'] as $id => $val){
            $sql = 'UPDATE multiclan
            SET
            `sort` = "'.$val['order'].'"
            WHERE id = "'.$id.'";';
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }        
        }    
    }
    /***** Exinaus *****/
    function get_top_tanks_list() {
        global $db;
        $top_tanks=array();

        $sql='SELECT tt.lvl, tt.type, tt.shortname, tt.show, tt.order, t.tank, tt.title, tt.index
        FROM `top_tanks` tt, `tanks` t
        WHERE t.title = tt.title;';
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $top_tanks_unsorted = $q->fetchAll();
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        foreach($top_tanks_unsorted as $val) {
            $top_tanks[$val['tank']]['lvl'] = $val['lvl'];
            $top_tanks[$val['tank']]['title'] = $val['title'];
            $top_tanks[$val['tank']]['type'] = $val['type'];
            $top_tanks[$val['tank']]['show'] = ($val['show'] == 1) ? 'checked="checked"' : '';
            $top_tanks[$val['tank']]['order'] = $val['order'];
            $top_tanks[$val['tank']]['shortname'] = isset($val['shortname']) ? $val['shortname'] : '';
            $top_tanks[$val['tank']]['index'] = $val['index'];
        }

        return $top_tanks;
    }
    function update_top_tanks($config)
    {
        global $db;

        foreach($config as $name => $var){
            $var['show'] = isset($var['show']) ? 1 : 0;
            $sql = 'UPDATE `top_tanks`
            SET
            `show` = "'.$var['show'].'",
            `order` = "'.$var['order'].'",
            `shortname` = "'.$var['shortname'].'",
            `index` = "'.$var['index'].'"
            WHERE title = "'.$name.'";';
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            unset($q);
        }
    }

    function delete_top_tank($info) {
        global $db;

        $sql = 'DELETE FROM `top_tanks`
        WHERE title = "'.$info.'";';
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
    }
    function add_top_tanks($lvl,$type) {
        global $db;

        $sql = 'select t.title
        from `tanks` t
        left join `top_tanks` tt
        on t.title = tt.title
        where tt.title is null AND t.lvl = "'.$lvl.'" AND t.type = "'.$type.'";';
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $tanks = $q->fetchAll(PDO :: FETCH_ASSOC);
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        //print_r($tanks);

        if(count($tanks) > 0) {
            unset($q);
            $i = count($tanks);
            $j = 1;
            $sql = 'INSERT INTO `top_tanks` (`title`, `lvl`, `type`) VALUES ';

            foreach($tanks as $val) {
                $sql .= "('{$val['title']}', '$lvl', '$type')";
                if($i != $j) { $sql .= ', '; $j++; } else { $sql .= ';'; }
            }
            //echo $sql;
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
        }
    }
    function delete_top_tanks($lvl,$type) {
        global $db;

        $sql = 'DELETE FROM `top_tanks`
        WHERE lvl = "'.$lvl.'" AND type = "'.$type.'";';
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
    }
    function update_tanks_db() {
      global $db,$cache,$config;

      $new = $cache->get('get_last_roster_'.$config['clan'],0);
      if($new['status'] == 'ok' &&  $new['status_code'] == 'NO_ERROR'){ //begin 1
        foreach($new['data']['members'] as $val){
           $tmp = $cache->get($val['account_name'],0,ROOT_DIR.'/cache/players/');
           if($tmp != FALSE or !empty($tmp)){
               $res[$val['account_name']] = $tmp;
           }
        }

        $tanks_sorted = array();
        $tanks_list = array();

        foreach($res as $val) {
          if(isset($val['tank']) and !empty($val['tank']) and is_array($val['tank'])) {
            foreach($val['tank'] as $lvl => $types){
              foreach($types as $type => $tanks){
                foreach($tanks as $tank){
                  if(!in_array($tank['name'],$tanks_list)){
                      $tanks_list[] = $tank['name'];
                      $tanks_sorted[$tank['name']]['lvl'] = $tank['lvl'];
                      $tanks_sorted[$tank['name']]['type'] = $tank['type'];
                      $tanks_sorted[$tank['name']]['class'] = $tank['class'];
                      $tanks_sorted[$tank['name']]['nation'] = $tank['nation'];
                      $tanks_sorted[$tank['name']]['link'] = preg_replace('/http:\/\/worldoftanks.[a-z]*/', '', $tank['link']);
                  }
                }
              }
            }
          }
        }

        foreach($tanks_sorted as $name => $val) {
            $sql = "UPDATE `tanks` SET
                `tank` = '{$val['type']}',
                `nation` = '{$val['nation']}',
                `lvl` = '{$val['lvl']}',
                `type` = '{$val['class']}',
                `link` = '{$val['link']}'
            WHERE title = '$name';" ;
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
        }
      } // end1
    }
    function clean_db_left_players() {
      global $db,$cache,$config;

      $new = $cache->get('get_last_roster_'.$config['clan'],0);
      if($new === FALSE or empty($new['data']['members'])) { return; }

      $roster_id = array_keys(roster_resort_id($new['data']['members']));

      if(count($roster_id)>0) {
          $sql = 'SHOW TABLES LIKE "'.$db->prefix.'col%";';
          $q = $db->prepare($sql);
          if ($q->execute() == TRUE) {
              $tmp = $q->fetchAll(PDO::FETCH_COLUMN,0);
          } else {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }

          if(count($tmp)>0) {
              $roster_id_tmp = implode(',',array_keys($roster_id));

              foreach($tmp as $val) {
                $sql = 'DELETE FROM '.$val.' WHERE account_id NOT IN('.$roster_id_tmp.');';
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                //echo $sql,'<br />';
              }
          }
      }
    }
    function clean_db_old_cron($date) {
      global $db;

      if(!is_numeric($date)) { $date = 30; }
      $del = now()-$date*24*60*60;

      $sql = 'SHOW TABLES LIKE "'.$db->prefix.'col%";';
      $q = $db->prepare($sql);
      if ($q->execute() == TRUE) {
          $tmp = $q->fetchAll(PDO::FETCH_COLUMN,0);
      } else {
          die(show_message($q->errorInfo(),__line__,__file__,$sql));
      }

      if(count($tmp)>0) {
        foreach($tmp as $val) {
          $sql = 'DELETE FROM '.$val.' WHERE up < "'.$del.'";';
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
        }
      }
    }
?>