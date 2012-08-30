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
    * @version     $Rev: 2.0.0 $
    *
    */
?>
<?php
    function pars_data($url,$config,$lang,$name,$roster,$pars = 'all')
    {
        //print_r($url);
        $html = str_get_html($url);
        $newr = $html->find('div[class=b-data-create]',0);
        $newu = $html->find('div[class=b-data-date]',0);
        $veh = '';
        //if(VEH == 'alter'){
        //    $veh = $html->find('table[class=t-statistic]',1); 
        //}
        $array['date'] = pars_date($newr->outertext,$newu->outertext,$config); 
        if(count($array['date']) < 1){
            $new = array();
        }else{
            $new['date'] = $array['date'];
            if(LAST_PLAYED == 'on' && MYSQL == 'on' && MYSQL_ERROR != 'on' && $pars != 'all'){
                insert_date($array,$name,SERVER,$config);    
            }
        }
        if($pars == 'all'){
            $array['stats'] = parseTable($html,$config,$veh);  
            if(MYSQL == 'on' && MYSQL_ERROR != 'on'){
                insert_rating_old($array,$lang,$name,SERVER,$config,$roster);
            }
            if(count($array['stats']) < 1){
                $array['stats'] = array();
                $new = array();
            }
            $html->clear();
            unset($html,$newr,$newu,$array['date']);
            unset($array['stats'][0]);
            if(count(($array['stats']))>0){
                foreach($array['stats'][1] as $val){
                    $tmp = str_replace(':','',trim($val[$lang['overall_title']]));
                    $num = explode('(',trim($val[0]));
                    $new['overall'][$tmp] = str_replace(' ','',$num[0]);     
                }
                unset($array['stats'][1]);
                foreach($array['stats'][2] as $val){
                    $tmp = str_replace(':','',trim($val[$lang['perform_title']]));
                    $num = explode('(',trim($val[0]));
                    $new['perform'][$tmp] = str_replace(' ','',$num[0]);     
                }
                unset($array['stats'][2]);
                foreach($array['stats'][3] as $val){
                    $tmp = str_replace(':','',trim($val[$lang['battel_title']]));
                    $num = explode('(',trim($val[0]));
                    $new['exp'][$tmp] = str_replace(' ','',$num[0]);     
                }
                unset($array['stats'][3]);
                foreach($array['stats'][4] as $val){
                    $tmp = trim($val[$lang['place']]);

                    $new['rating'][$tmp]['type'] = $val[$lang['rating_title']];
                    $new['rating'][$tmp]['link'] = $val[$lang['value']]; 
                    $new['rating'][$tmp]['name'] = $val[$lang['place']];
                    $new['rating'][$tmp]['value'] = str_replace(' ','',$val[0]);
                    $new['rating'][$tmp]['place'] = str_replace(' ','',$val[1]);    
                }
                unset($array['stats'][4]);
                if(isset($array['stats'][5])){
                    foreach($array['stats'][5] as $val){
                        $tmp = trim($val[$lang['battle']]);

                        $new['tank'][number_transform($val[0])][$tmp]['type'] = $val[$lang['battle']];
                        $new['tank'][number_transform($val[0])][$tmp]['total'] = str_replace(' ','',$val[$lang['win']]); 
                        $new['tank'][number_transform($val[0])][$tmp]['link'] = $val[$lang['tank']];
                        $new['tank'][number_transform($val[0])][$tmp]['win'] = str_replace(' ','',$val[1]);
                        $new['tank'][number_transform($val[0])][$tmp]['nation'] = str_replace(' ','',$val[2]);
                        $new['tank'][number_transform($val[0])][$tmp]['lvl'] = number_transform($val[0]);    
                    }
                    unset($array['stats'][5]);
                }
            }
        }
        return $new;

    }

    function insert_rating_old($data,$trans,$fname,$SERVER,$stat_config,$roster){   
        //if($this->session->userdata('group') == 'administrator'){ 
        global $db;
        if(count($data['stats']) > 0){
            $mod = 1;
            $dbb['account_id'] = $roster['account_id'];
            $dbb['name'] = $fname;
            $dbb['role'] = $roster['role'];
            $dbb['SERVER'] = $SERVER;
            $dbb['reg'] = trim(str_replace($stat_config['reg'],'',$data['date']['reg']));
            $dbb['local'] = trim(str_replace($stat_config['dateof'],'',$data['date']['local']));
            $dbb['member_since'] = $roster['member_since'];
            $dbb['reg'] = strtotime($dbb['reg']);
            $dbb['local'] = strtotime($dbb['local']);
            $dbb['up'] = now();
            $dbb['total'] = str_replace(' ','',$data['stats'][0+$mod][0][0]);

            $tmp = explode('(',str_replace(' ','',$data['stats'][0+$mod][1][0]));
            $dbb['win'] = $tmp[0];
            $tmp = explode('(',str_replace(' ','',$data['stats'][0+$mod][2][0]));
            $dbb['lose'] = $tmp[0];
            $tmp = explode('(',str_replace(' ','',$data['stats'][0+$mod][3][0]));
            $dbb['alive'] = $tmp[0];

            $dbb['des'] = str_replace(' ','',$data['stats'][1+$mod][0][0]);
            $dbb['spot'] = str_replace(' ','',$data['stats'][1+$mod][1][0]);
            $dbb['accure'] = (int) str_replace(' ','',$data['stats'][1+$mod][2][0]);
            $dbb['dmg'] = str_replace(' ','',$data['stats'][1+$mod][3][0]);
            $dbb['cap'] = str_replace(' ','',$data['stats'][1+$mod][4][0]);
            $dbb['def'] = str_replace(' ','',$data['stats'][1+$mod][5][0]);
            $dbb['exp'] = str_replace(' ','',$data['stats'][2+$mod][0][0]);
            $dbb['averag_exp'] = str_replace(' ','',$data['stats'][2+$mod][1][0]);
            $dbb['max_exp'] = str_replace(' ','',$data['stats'][2+$mod][2][0]);
            $dbb['gr_v'] = str_replace(' ','',$data['stats'][3+$mod][0][0]);
            $dbb['gr_p'] = str_replace(' ','',$data['stats'][3+$mod][0][1]);
            $dbb['wb_v'] = (int) str_replace(' ','',$data['stats'][3+$mod][1][0]);
            $dbb['wb_p'] = str_replace(' ','',$data['stats'][3+$mod][1][1]);
            $dbb['eb_v'] = str_replace(' ','',$data['stats'][3+$mod][2][0]);
            $dbb['eb_p'] = str_replace(' ','',$data['stats'][3+$mod][2][1]);
            $dbb['win_v'] = str_replace(' ','',$data['stats'][3+$mod][3][0]);
            $dbb['win_p'] = str_replace(' ','',$data['stats'][3+$mod][3][1]);
            $dbb['gpl_v'] = str_replace(' ','',$data['stats'][3+$mod][4][0]);
            $dbb['gpl_p'] = str_replace(' ','',$data['stats'][3+$mod][4][1]);
            $dbb['cpt_p'] = str_replace(' ','',$data['stats'][3+$mod][5][1]);
            $dbb['cpt_v'] = str_replace(' ','',$data['stats'][3+$mod][5][0]);
            $dbb['dmg_p'] = str_replace(' ','',$data['stats'][3+$mod][6][1]);
            $dbb['dmg_v'] = str_replace(' ','',$data['stats'][3+$mod][6][0]);
            $dbb['dpt_p'] = str_replace(' ','',$data['stats'][3+$mod][7][1]);
            $dbb['dpt_v'] = str_replace(' ','',$data['stats'][3+$mod][7][0]);
            $dbb['frg_p'] = str_replace(' ','',$data['stats'][3+$mod][8][1]);
            $dbb['frg_v'] = str_replace(' ','',$data['stats'][3+$mod][8][0]);
            $dbb['spt_p'] = str_replace(' ','',$data['stats'][3+$mod][9][1]);
            $dbb['spt_v'] = str_replace(' ','',$data['stats'][3+$mod][9][0]);
            $dbb['exp_p'] = str_replace(' ','',$data['stats'][3+$mod][10][1]);
            $dbb['exp_v'] = str_replace(' ','',$data['stats'][3+$mod][10][0]);


            $sql = "SELECT COUNT(id) FROM players WHERE name = '".$fname."' AND SERVER = '".$SERVER."';";
            $q = $db->prepare($sql);
            $q->execute();
            $status = $q->fetchColumn();

            //print_r($status);
            if($status > 0){
                $player = $db->query("SELECT id FROM players WHERE name = '".$fname."' AND SERVER = '".$SERVER."';")->fetch();
                //print_r($id);
                $nm = 0;
                $insert = '';
                foreach($dbb as $column => $val){
                    if($nm == 0){
                        $insert .= $column." = '".$val."'";  
                        $nm++;  
                    }else{
                        $insert .= ', '.$column." = '".$val."'";
                    }    
                }
                $sql = "UPDATE players SET ".$insert." WHERE id = '".$player['id']."';";
                //echo $sql;
                $q = $db->prepare($sql);
                $q->execute();   
            }else{ 
                $sql = "INSERT INTO players (".(implode(",",array_keys($dbb))).") VALUES ('".(implode("','",$dbb))."');";
                //echo $sql;
                $q = $db->prepare($sql);
                $q->execute();
                $player['id'] = $db->lastInsertId();  
            }              

            $current_tmp = $db->query("SELECT id,tank,nation FROM tanks;")->fetchAll();   
            foreach($current_tmp as $val){
                $current[$val['id']] = $val['tank'].'_'.$val['nation'];    
            }


            if(!isset($current)){
                $current = array();
            }
            //print_r($current);
            $tmp = array();
            $current_flip = &array_flip($current);
            if(isset($data['stats'][4+$mod])){
                foreach($data['stats'][4+$mod] as $val){

                    if(in_array(trim($val[$trans['battle']]).'_'.$val[2],$current)){
                        $tmp[$val[2]][$current_flip[trim($val[$trans['battle']]).'_'.$val[2]].'_t'] = str_replace(' ','',$val[$trans['win']]);
                        $tmp[$val[2]][$current_flip[trim($val[$trans['battle']]).'_'.$val[2]].'_w'] = str_replace(' ','',$val[1]);        
                    }else{
                        $tank = array(
                        'tank' => trim($val[$trans['battle']]),
                        'nation' => $val[2],
                        'lvl' => trim($val[0]),
                        'link' => $val[$trans['tank']],
                        );
                        //print_r($tank);
                        $db->prepare("INSERT INTO tanks (".(implode(",",array_keys($tank))).") VALUES ('".(implode("','",$tank))."');")->execute();
                        $id = $db->lastInsertId();
                        $nation_db = $db->query("show tables like 'tank_".$val[2]."';")->fetchAll(); 
                        if(count($nation_db) < 1){
                            $db->prepare("CREATE TABLE tank_".$val[2]." (id INT(12) PRIMARY KEY);")->execute();    
                        }
                        $db->prepare("ALTER TABLE `tank_".$val[2]."` ADD `".$id."_t` INT( 12 ) NOT NULL;")->execute();
                        $db->prepare("ALTER TABLE `tank_".$val[2]."` ADD `".$id."_w` INT( 12 ) NOT NULL;")->execute();
                        $tmp[$val[2]][$id.'_t'] = str_replace(' ','',$val[$trans['win']]);
                        $tmp[$val[2]][$id.'_w'] = str_replace(' ','',$val[1]); 

                    }
                }
            }

            $nation_db_now = $db->query("show tables like 'tank\_%';")->fetchAll();

            foreach($nation_db_now as $db_now){
                $sql = "SELECT COUNT(id) FROM ".$db_now[0]." WHERE id = '".$player['id']."';";
                $q = $db->prepare($sql);
                $q->execute();
                $status_nation[$db_now[0]] = $q->fetchColumn();
            }
            //print_r($status_nation);
            //print_r($tmp);
            foreach($tmp as $key => $t){
                if($status_nation['tank_'.$key] > 0){
                    $nm = 0;
                    $insert = '';
                    foreach($t as $column => $val){
                        if($nm == 0){
                            $insert .= $column." = '".$val."'";  
                            $nm++;  
                        }else{
                            $insert .= ', '.$column." = '".$val."'";
                        }    
                    }
                    //$db->prepare("UPDATE players SET ".$insert." WHERE id = '".$id['id']."';")->execute();
                    $db->prepare("UPDATE tank_".$key." SET ".$insert." WHERE id = '".$player['id']."';")->execute();    
                }else{
                    $t['id'] = $player['id'];
                    $q = $db->prepare("INSERT INTO tank_".$key." (".(implode(",",array_keys($t))).") VALUES ('".(implode("','",$t))."');")->execute();
                }
            }

        }
    }
    function parseTable($html,$config,$veh)
    {
        $table = array();
        $spec = 1;    
        //echo $spec;
        $html = str_replace('&nbsp;', ' ',$html);
        $veh = &str_replace('&nbsp;', ' ',$veh->outertext);
        // Find the table
        preg_match_all("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);
        //print_r($table_html);
        if($veh != ''){
            $table_html[0][5] = $veh;
        }
        for($n= 1 - $spec; $n < count($table_html[0]); $n++){
            // Get title for each row
            preg_match_all("/<th.*?>(.*?)<\/[\s]*th>/", $table_html[0][$n], $matches);
            //print_r($matches);
            $row_headers = $matches[1];
            //print_r($row_headers);
            // Iterate each row
            preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $table_html[0][$n], $matches);
            //print_r($matches);
            $tmp = array();
            foreach($matches[0] as $row_html)
            {
                //echo $row_html;
                preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/s", $row_html, $td_matches);
                preg_match_all('/<td class="js\-[\'"]?([^\'" >]+)[\'" >]/', $row_html, $nation);
                //print_r($nation);
                $row = array();
                //print_r($td_matches);

                for($i=0; $i<count($td_matches[1]); $i++)
                {
                    preg_match_all('/src=[\'"]?([^\'" >]+)[\'" >]/', $td_matches[1][$i], $img);
                    if(isset($img[1][0])){
                        $td = $config['td'].$img[1][0];
                        $tm = strip_tags(html_entity_decode($td_matches[1][$i]));   
                    }else{
                        $td = strip_tags(html_entity_decode($td_matches[1][$i]));
                    }
                    if(isset($row_headers[$i])){
                        $row[$row_headers[$i]] = $td;
                    }else{
                        $row[] = $td;
                    }
                    if(isset($tm)){
                        if(strlen($tm)>0){
                            $row[] = $tm;
                            $tm = '';
                        }
                    }
                }
                if(isset($nation[1][0])){
                    if(strlen($nation[1][0]) > 0){
                        $row[] = $nation[1][0];
                    }
                }
                //print_r($row);
                if(count($row) > 0)
                    $tmp[] = $row;
            }
            $table[$n - 1 + $spec] = $tmp;
        }
        //print_r($table);
        return $table;
    }
    function pars_date($newr,$newu,$config)
    {
        //Дата регистрации: 22.09.2010
        //Данные по состоянию на 06.05.2011, 07:59 
        //$reg = "/".$config['reg'].".*/";
        //$dateof = "/".$config['dateof'].".*/";
        //echo $reg;   
        preg_match_all('/data-timestamp=[\'"]?([^\'" >]+)[\'" >]/', $newr, $registred);
        preg_match_all('/data-timestamp=[\'"]?([^\'" >]+)[\'" >]/', $newu, $local);
        //print_r($registred[1][0]);    
        if(isset($registred[1][0])){
            $array['reg'] = $config['reg'].' '.date("d.m.Y",number_format($registred[1][0],0,'',''));
            $array['local'] = $config['dateof'].' '.date("d.m.Y H:m",number_format($local[1][0],0,'',''));
            $array['reg_num'] = number_format($registred[1][0],0,'','');
            $array['local_num'] = number_format($local[1][0],0,'','');
            //echo $array['reg'].' '.$array['local']; 
            //print_r($array);
            return $array;

        }
    } 
    function insert_date($data,$fname,$SERVER,$stat_config)
    {
        global $db;
        if(count($data['date']) > 0){

            $dbb['local'] = trim(str_replace($stat_config['dateof'],'',$data['date']['local']));
            $dbb['local'] = strtotime($dbb['local']);

            $sql = "SELECT COUNT(id) FROM players WHERE name = '".$fname."' AND SERVER = '".$SERVER."';";
            $q = $db->prepare($sql);
            $q->execute();
            $status = $q->fetchColumn();

            //print_r($status);
            if($status > 0){
                $player = $db->query("SELECT id FROM players WHERE name = '".$fname."' AND SERVER = '".$SERVER."';")->fetch();
                //print_r($id);
                $nm = 0;
                $insert = '';
                foreach($dbb as $column => $val){
                    if($nm == 0){
                        $insert .= $column." = '".$val."'";  
                        $nm++;  
                    }else{
                        $insert .= ', '.$column." = '".$val."'";
                    }    
                }
                $sql = "UPDATE players SET ".$insert." WHERE id = '".$player['id']."';";
                //echo $sql;
                $q = $db->prepare($sql);
                $q->execute();   
            }else{ 
                $sql = "INSERT INTO players (".(implode(",",array_keys($dbb))).") VALUES ('".(implode("','",$dbb))."');";
                //echo $sql;
                $q = $db->prepare($sql);
                $q->execute();
                $player['id'] = $db->lastInsertId();  
            }              
        }   
    }
?>
