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
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.2.1 $
    *
    */
    if (preg_match ("/mysql.php/", $_SERVER['PHP_SELF']))
    {
        //header ("Location: /index.php");
        exit;
    }

    if(file_exists(ROOT_DIR.'/mysql.config.php')) {
      include(ROOT_DIR.'/mysql.config.php');
    } else {
      $dbhost = $dbuser = $dbpass = $dbname = $dbprefix = '';
    }

    if(!isset($dbprefix)) {
      $dbprefix = 'msfc_';
    }
    $sqlchar = 'utf8';

    //Проверяем заданы ли переменные для доступа к БД
    if( empty($dbhost) and empty($dbuser) and empty($dbpass) and empty($dbname) ) {
        if(defined('MAIN')){
            header ( 'Location: admin/index.php' );
        }
        include(ROOT_DIR.'/admin/including/ad_mysql.php');
        include(ROOT_DIR.'/admin/views/ad_mysql.php');
        die;
    }

    //$db = new PDO ( 'mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass);
    if (!class_exists('MyPDO')) {
        class MyPDO extends PDO
        {
            var $prefix;
            var $sqls;
            var $count;
            var $oldprefix;
            private $pattern = '';
            private $pattern2 = '';
            private $replacement = '';
            public  $replacement2 = '$1msfcmt_$2$3';
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
              $this->pattern = '/([`\'"])(col_medals|col_players|col_ratings|col_tank[\w%]*|config|tabs|top_tanks|top_tanks_presets|gk)([`\'"])/';
              $this->pattern2 = '/([`\'"])(achievements|users|multiclan|tanks|stronghold)([`\'"])/';
              $this->replacement = '$1'.$this->prefix.'$2$3';

              parent::__construct($dsn, $user, $password, $driver_options);
            }

            public function add_prefix($statement) {
              $this->count += 1;
              $statement = preg_replace($this->pattern, $this->replacement, $statement);
              $statement = preg_replace($this->pattern2, $this->replacement2, $statement);
              return $statement;
            }

            public function prepare($statement, $driver_options = array())
            {
              $statement = $this->add_prefix($statement);
              return parent::prepare($statement, $driver_options);
            }

            public function exec($statement)
            {
              $statement = $this->add_prefix($statement);
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

            public function select($statement,$line = NULL,$file = NULL,$type = 'all') {
              $statement = $this->add_prefix($statement);
              $result = array();
              try{
                $test = parent::query($statement);
                if(is_object($test)) {
                  switch($type) {
                    case 'fetch':
                    $result = $test->fetch();
                    break;
                    case 'column':
                    $result = $test->fetchColumn();
                    break;
                    case 'rows':
                    $result = $test->fetchAll(PDO::FETCH_COLUMN);
                    break;
                    default:
                    $result = $test->fetchAll();
                    break;
                  }
                }
              } catch (PDOException $e) {
                require(ROOT_DIR.'/views/header_error.php');
                show_message($e->getMessage(),$line,$file,$statement);
                require(ROOT_DIR.'/views/footer.php');
                die;
              }
              return $result;
            }

            public function insert($statement,$line = NULL,$file = NULL) {
              $statement = $this->add_prefix($statement);
              $res = FALSE;
              try{
                $res = parent::query($statement);
              } catch (PDOException $e) {
                require(ROOT_DIR.'/views/header_error.php');
                show_message($e->getMessage(),$line,$file,$statement);
                require(ROOT_DIR.'/views/footer.php');
                die;
              }
              return $res;
            }

            public function query($statement) {
              $this->count += 1;
              $this->sqls[$this->count] = $statement;
              $args = func_get_args();
              if (count($args) > 1) {
                try{
                  return call_user_func_array('parent::query', $args);
                } catch (PDOException $e) {
                  require(ROOT_DIR.'/views/header_error.php');
                  show_message($e->getMessage(),null,null,$statement);
                  require(ROOT_DIR.'/views/footer.php');
                  die;
                }
              } else {
                try{
                  return parent::query($statement);
                } catch (PDOException $e) {
                  require(ROOT_DIR.'/views/header_error.php');
                  show_message($e->getMessage(),null,null,$statement);
                  require(ROOT_DIR.'/views/footer.php');
                  die;
                }
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
        $db_conn = array(
          PDO::ATTR_ERRMODE             =>  PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE  =>  PDO::FETCH_ASSOC
        );
        $db = new MyPDO ( 'mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass, $db_conn ,$dbprefix);
    } catch (PDOException $e) {
        die(show_message($e->getMessage()));
    }
    $db->query ( 'SET character_set_connection = '.$sqlchar );
    $db->query ( 'SET character_set_client = '.$sqlchar );
    $db->query ( 'SET character_set_results = '.$sqlchar );
    $db->query ( 'SET SESSION wait_timeout = 120;');

    if(isset($_GET['multi']) and preg_match("/[a-zA-Z0-9]{1,5}/i", $_GET['multi']) and (strlen($_GET['multi'])<=5)) {

        $tmp_prefix = $_GET['multi'].'_';

        $multi = $db->select('SELECT COUNT(id) FROM `multiclan` WHERE prefix = "'.$tmp_prefix.'";',__line__,__file__,'column');
        if($multi == 1){
            $db->change_prefix($tmp_prefix);
        } else {
            unset($_GET['multi']);
        }
    }
?>