<?php

/**
* Array to MySQL  (Atm)
* Creating, updating and inserting datat from multidamentional array to MySQL table
* @author Aleksandr (wot-news.com)
* @version 0.1.4
*  
* Usable Function
* construct_data($data), $data - array with data. Checking data size and convert multidementional array to 1 level array
* check_mysql(), $field - list of fields to compare. Checking if Table exist, if not - Creat in another way update table if needed 
* truncate_table() - Delete data in table
* delete_table() - Delete table
* insert_data($data) - $data - array with data.Insert data to table;
* change_row($rowname,$rowopt), create_row($rowname,$rowopt) - $rowname - name of row, $rowopt - Options (example array('type' => 'INT','size' => 10))
* 
* get_data($order,$custom), $custom - Custom SQL request, $order - Order like $order = 'ORDER BY `|type` ASC'
* 
* Usage Example
* 
* Setting Class and table name to 'stronghold'
*   $atm = new Atm($db,'stronghold');    
* Creating data, checking mysql, Truncating table, Inserting allready created data, and delete table in the end
*   $atm->construct_data($str_get['data'])->check_mysql()->truncate_table()->insert_data()->delete_table();
* Setting table name to 'strong'
*   $atm->tbname = 'strong';
* Same like befor but for 'strong' table
*   $atm->construct_data($str_get['data'])->check_mysql()->truncate_table()->insert_data()->delete_table();
*/
class Atm
{
    public $unset = array(); // Unsetting key in construct
    public $mtype = 'MyISAM'; // Table type
    public $prefix = 'msfcmt_'; // Starting prefix
    public $tbname = ''; // Table name
    public $separator = '|'; // Separator for columns

    function __construct($db,$tbname = '')
    {  
        $this->db = $db;  
        $this->tbname = $tbname;
    }

    public function construct_data($data = array()){
        if(empty($data)){
            return FALSE;
        }
        if(!is_array($data)){
            return FALSE;
        }
        $in = $this->all_fields = $this->_pre_construct($data);

        if(isset($in[0])){
            $tmp = $in[0];
            unset($in[0]);
            foreach($in as $val){
                foreach($val as $key => $lav){
                    if(isset($tmp[$key])){
                        $tmp[$key] = $this->compare_type_mysql($key,$tmp[$key],$lav);
                    }else{
                        $tmp[$key] = $lav; 
                    }
                }    
            }
        }
        $this->fields = $tmp;
        return $this;
    }
    private function _pre_construct($data){
        $fields = array();
        if(isset($data)){
            foreach($data as $pid => $val){
                $this->unset_in_array($val,$this->unset);
                $fields[] = $this->array_move($val,'');    
            }
        }
        return $fields;
    }
    private function check_table(){
        $test = $this->db->select('SHOW TABLES LIKE "'.$this->prefix.$this->tbname.'";',__line__,__file__,'column');
        return $test ? TRUE : FALSE;
    }
    private function list_columns(){  
        $list = $this->db->select("SHOW COLUMNS FROM `".$this->prefix.$this->tbname."` ;",__line__,__file__);
        foreach($list as $val){
            preg_match('/(?<=\()(.+)(?=\))/is', $val['Type'], $match);
            $new[$val['Field']]['size'] = isset($match[0]) ? $match[0] : '';
            $new[$val['Field']]['type'] =  strtoupper(preg_replace('/\(.*\)/', '', $val['Type']));
        } 
        return $new;
    }
    private function creat_table(){
        $in = array();
        foreach($this->fields as $key => $val){
            if($val['type'] == 'TEXT'){
                $in[] = '`'.$key.'` TEXT NOT NULL';
            }else{
                $in[] = '`'.$key.'` '.$val['type'].'('.$val['size'].') NOT NULL';
            }    
        }

        $sql = "CREATE TABLE IF NOT EXISTS `".$this->prefix.$this->tbname."` (
        ".(implode(',',$in))."
        ) ENGINE=".$this->mtype." DEFAULT CHARSET=utf8;"; 
        $this->db->insert($sql,__line__,__file__);
    }
    public function create_row($rowname,$rowopt) {    // rowopt - array(type,size)  type - MySQL type, size if needed
        if($rowopt['size']){
            $rowopt['size'] = ' ('.$rowopt['size'].') ';
        }
        $sql = 'ALTER TABLE `'.$this->prefix.$this->tbname.'`  ADD `'.$rowname.'` '.$rowopt['type'].$rowopt['size'].' NOT NULL';
        $this->db->insert($sql,__line__,__file__);
    }
    public function change_row($rowname,$rowopt){     // rowopt - array(type,size)  type - MySQL type, size if needed
        if($rowopt['size']){
            $rowopt['size'] = ' ('.$rowopt['size'].') ';
        }
        $sql = 'ALTER TABLE `'.$this->prefix.$this->tbname.'` CHANGE `'.$rowname.'` `'.$rowname.'` '.$rowopt['type'].$rowopt['size'].' NOT NULL';
        $this->db->insert($sql,__line__,__file__);
    }
    public function check_mysql($field = array()){
        if(!empty($field)){
            $this->fields = $field;                
        }
        if($this->check_table() === TRUE){
            $current = $this->list_columns();
            $upd = array();
            $new = array();
            foreach($this->fields as $key => $lav){
                if(isset($current[$key])){
                    $upd[$key] = $this->compare_type_mysql($key,$current[$key],$lav,TRUE);
                    if(empty($upd[$key])){
                        unset($upd[$key]);
                    }
                }else{
                    $new[$key] = $lav; 
                }
            }  
            if(!empty($new)){
                foreach($new as $key => $val){
                    $this->create_row($key,$val);
                }
            } 
            if(!empty($upd)){
                foreach($upd as $key => $val){
                    $this->change_row($key,$val);
                }
            } 
        }else{
            $this->creat_table();
        }
        return $this;
    }
    public function truncate_table(){
        $sql = 'TRUNCATE TABLE '.$this->prefix.$this->tbname;
        $this->db->insert($sql,__line__,__file__);
        return $this;
    }
    public function delete_table(){
        $sql = 'DROP TABLE '.$this->prefix.$this->tbname;
        $this->db->insert($sql,__line__,__file__);
        return $this;
    }
    public function insert_data($data = array()){
        if(!empty($data)){
            $this->all_fields = $this->_pre_construct($data);
        }   
        if(!empty($this->all_fields)){
            foreach($this->all_fields as $val){
                foreach($val as $key => $var){
                    $rows[] = '`'.$key.'`';
                    $in[] = $this->db->quote($var['data']);
                }
                $sql = 'INSERT INTO `'.$this->prefix.$this->tbname.'` ('.implode(', ',$rows).') VALUES ('.implode(', ',$in).');';   
                $q = $this->db->insert($sql,__line__,__file__);
                unset($rows,$in);
            }    
        }   
        return $this;
    }
    private function detect_type_mysql($string){

        if(is_float($string)){
            return array('type' => 'VARCHAR','size' => 50);    
        }
        if(is_numeric($string)){
            if($string < 0){
                $string *= -1;    
            }
            if(strlen($string)< 8){
                return array('type' => 'INT','size' => 10);
            }else{
                return array('type' => 'BIGINT','size' => 19);
            }
        }
        if(mb_strlen($string) < 200){
            return array('type' => 'VARCHAR', 'size' => (mb_strlen($string) + 50));
        }
        return array('type' => 'TEXT', 'size' => '');
    }
    private function compare_type_mysql($name,$comp_1,$comp_2,$unset = FALSE){
        $level = array('INT' => 10,'BIGINT' => 19,'VARCHAR' => 50,'TEXT' => 300);
        if($level[$comp_1['type']] > $level[$comp_2['type']]){

            $tmp['type'] = $comp_1['type'];
            $tmp['size'] = $level[$comp_1['type']];
            if($unset === TRUE){
                return array();
            }

        } else if($level[$comp_1['type']] < $level[$comp_2['type']]){

            $tmp['type'] = $comp_2['type'];
            $tmp['size'] = $level[$comp_2['type']];

        } else if($level[$comp_1['type']] == $level[$comp_2['type']]){
            $tmp['type'] = $comp_2['type'];
            $tmp['size'] = max($comp_1['size'],$comp_2['size']);
            if($unset === TRUE){
                if($comp_1['size'] == $comp_2['size']){
                    return array();
                }
            }
        }
        if($tmp['type'] == 'TEXT'){
            $tmp['size'] = ''; 
        }
        return $tmp;
    }
    private function array_move($comp,$keyin){
        if(is_array($comp)){
            foreach($comp as $key => $val){
                if(is_array($val)){
                    if(!empty($keyin)){
                        $key = $keyin.$this->separator.$key;
                    }
                    $tmp = $this->array_move($val,$key);
                    if(is_array($tmp)){ 
                        foreach($tmp as $tkey =>$tval){
                            $new[trim($tkey,'_')] = $tval;
                        } 
                    }
                }else{ 
                    if(trim($val)){
                        if(!empty($keyin)){
                            $key = $keyin.$this->separator.$key;
                        }
                        $new[trim($key,'_')] = $this->detect_type_mysql($val);
                        $new[trim($key,'_')]['data'] = $val;
                    }
                }
            }
            if(isset($new)){ return $new; }; return;
        }    
    }
    private function unset_in_array(&$array, $keys) { 
        if(!empty($keys)){
            if(is_array($array)){
                foreach ($array as $key => &$value) { 
                    if (is_array($value)) { 
                        $this->unset_in_array($value, $keys); 
                    } else {
                        if (in_array($key, $keys)){
                            unset($array[$key]);
                        }
                    } 
                }
            }
        }
    }
    public function get_data($order = '',$custom = FALSE){  // $order = 'ORDER BY `|type` ASC'
        if($custom == FALSE){
            return $this->reconstruct_array($this->db->select('SELECT * FROM `'.$this->tbname.'` '.$order.';',__line__,__file__));    
        }else{
            return $this->reconstruct_array($this->db->select($custom,__line__,__file__)); 
        }
    }
    private function reconstruct_array($array){
        $new = array();
        foreach($array as $kk => $val){ 
            foreach($val as $key => $var){
                $p = explode('|',$key);
                if(!isset($p[1])){
                    $new[$kk][$p[0]] = $var;
                }else{     
                    if(!isset($new[$kk][$p[0]])){
                        $new[$kk][$p[0]] = array();
                    }  
                    $new[$kk][$p[0]] = $this->_reconstruct_array($p,$var,0,$new[$kk][$p[0]]);                
                }
            }
        }
        return $new;
    }
    private function _reconstruct_array($p,$var,$n,$curr){
        unset($p[$n]);
        if(!isset($p[$n+2])){
            $curr[$p[$n+1]] = $var;
        }else{
            if(!isset($curr[$p[$n+1]])){
                $curr[$p[$n+1]] = array();
            }
            $curr[$p[$n+1]] = $this->_reconstruct_array($p,$var,1,$curr[$p[$n+1]]);                
        } 
        return  $curr;
    }
}