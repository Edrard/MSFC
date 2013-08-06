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
    if (preg_match ("/mysql.php/", $_SERVER['PHP_SELF']))
    {
        //header ("Location: /index.php");
        exit;
    }
    if(file_exists(ROOT_DIR.'/function/mysql.config.php') and is_readable(ROOT_DIR.'/function/mysql.config.php')) {
      include_once(ROOT_DIR.'/function/mysql.config.php');
    } else {

    $dbhost ='localhost';
    // username and password to log onto db SERVER
    $dbuser ='root';
    $dbpass  ='';
    // name of database
    $dbname='';
    //en - Prefix must be min 1 symbol, max 5 symbols, with _ at the end. Only a-z, A-Z and numbers allowed. For example: $dbprefix = 'msfc_';
    //ru - Префикс должен быть не менее 1 и не более 5 символов, в конце префикса должен быть символ _. Разрешены только английские буквы и цифры.
    //Для примера: $dbprefix = 'msfc_';
    $dbprefix = '';
    $sqlchar = 'utf8';

    }
    //$db = new PDO ( 'mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass);
    if (!class_exists('MyPDO')) {
        class MyPDO extends PDO
        {
            var $prefix;
            var $sqls;
            var $count;
            var $oldprefix;
            private $pattern = array();
            private $replacement = array();
            private $matches;

            public function __construct($dsn, $user = null, $password = null, $driver_options = array(),$dbprefix = null)
            {
                $this->count = 0;
                $this->sqls = array();

                if (preg_match("/[a-zA-Z0-9]{1,5}_/i", $dbprefix, $this->matches)) {
                    $this->prefix = $this->matches[0];
                } else {
                    $this->prefix = 'msfc_';
                }
                $this->pattern = '/([`\'"])(col_medals|col_players|col_rating_tank[\w%]*|col_tank[\w%]*|config|tabs|top_tanks|top_tanks_presets|tanks|gk)([`\'"])/';
                $this->replacement = '$1'.$this->prefix.'$2$3';

                parent::__construct($dsn, $user, $password, $driver_options);
            }

            public function prepare($statement, $driver_options = array())
            {
                $this->count += 1;
                $statement = preg_replace($this->pattern, $this->replacement, $statement);
                $statement = preg_replace('/`users`/', '`msfcmt_users`', $statement); 
                $this->sqls[$this->count] = $statement;
                return parent::prepare($statement, $driver_options);
            }
            public function query($statement)
            {
                $this->count += 1;
                $statement = preg_replace($this->pattern, $this->replacement, $statement);
                $statement = preg_replace('/`users`/', '`msfcmt_users`', $statement); 
                $this->sqls[$this->count] = $statement;
                $args = func_get_args();

                if (count($args) > 1) {
                    return call_user_func_array(array($this, 'parent::query'), $args);
                } else {
                    return parent::query($statement);
                }
            }
            public function exec($statement)
            {
                $this->count += 1;
                $statement = preg_replace($this->pattern, $this->replacement, $statement);
                $statement = preg_replace('/`users`/', '`msfcmt_users`', $statement); 
                $this->sqls[$this->count] = $statement;
                return parent::exec($statement);
            }
            public function current_prefix(){
                return $this->prefix;
            }
            public function change_prefix($new_prefix) {
                if (preg_match("/[a-zA-Z0-9]{1,5}_/i", $new_prefix, $this->matches)) {
                    $this->oldprefix = $this->prefix;
                    $this->prefix = $this->matches[0];
                    $this->replacement = '$1'.$this->prefix.'$2$3';
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
    }
    if (isset($_POST['multiadd'])){
        if($_POST['id'] && $_POST['prefix'] && $_POST['sort']){
            if(is_numeric($_POST['id'])){
                if(preg_match('/^\d/', $_POST['prefix']) == 0 && strlen(preg_replace('/(.*)_/','$1',$_POST['prefix'])) <=5){
                    if(ctype_alnum(preg_replace('/(.*)_/','$1',$_POST['prefix']))){
                        $_POST['prefix'] = strtolower($_POST['prefix']);
                        
                        if(preg_match("/[a-zA-Z0-9]{1,5}_/i", $_POST['prefix'])){
                            $dbprefix = $_POST['prefix'];
                        }else{
                            $dbprefix = $_POST['prefix'].'_';
                        }
                        $_POST['prefix'] = $dbprefix;
                    }
                }
            }
        }
    }                 

    try {
        $db = new MyPDO ( 'mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass, array() ,$dbprefix);
    } catch (PDOException $e) {
        //echo $e->getMessage();
        die(show_message($e->getMessage()));
    }
    $db->query ( 'SET character_set_connection = '.$sqlchar );
    $db->query ( 'SET character_set_client = '.$sqlchar );
    $db->query ( 'SET character_set_results = '.$sqlchar );
    $db->query ( 'SET SESSION wait_timeout = 180;');//60

    if(isset($_GET['multi']) and preg_match("/[a-zA-Z0-9]{1,5}/i", $_GET['multi']) and (strlen($_GET['multi'])<=5)) {

        $tmp_prefix = $_GET['multi'].'_';

        $sql = "SELECT COUNT(id) FROM multiclan WHERE prefix = '".$tmp_prefix."';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $multi = $q->fetchColumn();
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        if($multi == 1){
          $db->change_prefix($tmp_prefix);
        } else {
          unset($_GET['multi']);
        }
    }
?>