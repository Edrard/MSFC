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
    * @copyright   2011-2014 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.2.1 $
    *
    */


    if (preg_match ("/auth.php/", $_SERVER['PHP_SELF']))
    {
      header ("Location: /index.php");
      exit;
    }

    /*
    #
    # Copyright Iulian Ciobanu (CIGraphics) 2009
    # Email: cigraphics@gmail.com
    # Please leave the copyright and email intact.
    #
    */
    class Auth {

        var $type = 'cookie';
        private $connection;
        private $errors = array();
        var $minval = 2;
        var $maxval = 12;
        var $minpass = 0;
        var $salt = '#@()DIJK#)(F#&*()DS#@JKS)@(I()#@DU)*(&@#)(#U)J';
        var $emailAuth = false;
        var $rights = '';
        var $replays = 0;

        function __construct() {
            global $config, $db;
            require(ROOT_DIR.'/admin/translate/auth_'.$config['lang'].".php");
            $this->lang = $lang;
            $this->db = $db;
            if ( $this->type == 'session' ) {
                session_start();
            }
            $this->check();
        }

        public function login($user, $pass) {
            $email = $this->emailAuth;
            $err = false;
            $user = ($user);
            $password = $this->encrypt($pass);
            if ( $email == true ) {
                if ( !$this->email($user) ) {
                    $this->errors[] = $this->lang['login_err_email'];
                    $err = true;
                } else {
                    $col = 'email';
                }
            } else {
                if ( !$this->name($user) ) {
                    $this->errors[] = $this->lang['login_err_ni1']. $this->minval. $this->lang['login_err_ni2']. $this->maxval;
                    $err = true;
                } else {
                    $col = 'user';
                }
            }
            if ( strlen($pass) < $this->minpass ) {
                $this->errors[] = $this->lang['login_err_mpass'];
                $err = true;
            }

            if ( $err == false ) {
                $sql = sprintf("SELECT * FROM `users` WHERE %s = '%s'", $col, $user);
                $result = $this->db->select($sql,__line__,__file__,'fetch');
                if ( count($result) == 0 ) {
                    $this->errors[] = ucfirst($col). $this->lang['login_err_dexist'];
                } else {
                    $row = $result;
                    $this->rights = $row['prefix'];
                    $this->replays = $row['replays'];
                    if($row['prefix'] == 'all') {$row['prefix'] = $this->db->prefix;}
                    if ( $row['password'] == $password && $row['prefix'] == $this->db->prefix ) {
                        if ( $this->type == 'session' ) {
                            $this->set_session($col, $user);
                            $this->set_session('password', $password);
                            $this->set_session('group', $row['group']);
                        } elseif ( $this->type == 'cookie' ) {
                            $this->set_cookie($col, $user);
                            $this->set_cookie('password', $password);
                            $this->set_cookie('group', $row['group']);
                        }
                        $multi_get = '';
                        if(isset($_GET['multi'])){
                            $multi_get = '?multi='.$_GET['multi'];
                        }
                        header('Location: '.$_SERVER['PHP_SELF'].$multi_get);
                        exit;
                    } else {
                        $this->rights = '';
                        $this->replays = 0;
                        if($row['prefix'] != $this->db->prefix) {
                          $this->errors[] = $this->lang['login_err_iper'];
                        } else {
                          $this->errors[] = $this->lang['login_err_ipas'];
                        }
                    }
                }

            }
        }

        public function encrypt($value) {
            $enc = md5($this->salt.md5($value));
            return sha1($enc);
        }

        // Email validation
        private function email($email) {
            $reg = "#^(((([a-z\d][\.\-\+_]?)*)[a-z0-9])+)\@(((([a-z\d][\.\-_]?){0,62})[a-z\d])+)\.([a-z\d]{2,6})$#i";
            if ( !preg_match($reg, $email) ) {
                return false;
            } else {
                return true;
            }
        }

        // Name validation
        private function name($name) {
            $min = $this->minval - 2;
            if ( !preg_match("#^[\d\w_]{".$min.",".$this->maxval."}\$#i", $name) ) {
                return false;
            } else {
                return true;
            }
        }

        private function set_session($name, $value) {
            $_SESSION[$name] = $value;
        }

        private function destroy_session() {
            session_unset();
            session_destroy();
        }

        private function set_cookie($name, $value, $time = 3600 ) {
            setcookie($name, $value, time()+$time, '/');
        }

        private function destroy_cookie($name) {
            setcookie($name, '', time()-1, '/');
        }

        public function logout() {
            if ( $this->emailAuth == false ) {
                $col = 'user';
            } else {
                $col = 'email';
            }
            if ( $this->type == 'session' ) {
                $this->destroy_session();
            } elseif ( $this->type == 'cookie' ) {
                $this->destroy_cookie('password');
                $this->destroy_cookie('group');
                $this->destroy_cookie($col);
            }
            $multi_get = '';
            if(isset($_GET['multi'])){
                $multi_get = '?multi='.$_GET['multi'];
            }
            header('Location: '.$_SERVER['PHP_SELF'].$multi_get);
            exit;
        }

        private function check() {
            if ( $this->emailAuth == false ) {
                $col = 'user';
            } else {
                $col = 'email';
            }
            if ( $this->type == 'cookie' ) {
                if ( isset($_COOKIE['password']) ) {
                    $sql = sprintf("SELECT * FROM `users` WHERE %s = '%s'", $col, $_COOKIE[$col] );
                    $result = $this->db->select($sql,__line__,__file__,'fetch');
                    $row = $result;
                    $this->rights = $row['prefix'];
                    $this->replays = $row['replays'];
                    if($row['prefix'] == 'all') {$row['prefix'] = $this->db->prefix;}
                    if ( $row[$col] !== $_COOKIE[$col] || $row['password'] !== $_COOKIE['password'] || $row['prefix'] != $this->db->prefix ) {
                        $this->rights = '';
                        $this->replays = 0;
                        $this->logout();
                    }
                }
            } elseif ( $this->type == 'session' ) {
                if ( isset($_SESSION['password']) ) {
                    $sql = sprintf("SELECT * FROM `users` WHERE %s = '%s'", $col, $_COOKIE[$col] );
                    $result = $this->db->select($sql,__line__,__file__,'fetch');
                    $row = $result;
                    if ( $row[$col] !== $_SESSION[$col] || $row['password'] !== $_SESSION['password'] ) {
                        $this->logout();
                    }
                }
            }
        }

        public function error() {
            $data = '';
            if ( is_array($this->errors) && !empty($this->errors) ) {
                $data = '<div align="center">';
                foreach ( $this->errors as $value ) {
                    $data .= $value."<br />";
                }
                $data .= '</div>';
            }
            return $data;
        }

        public function isLoggedIn() {
            $ret = false;
            if ( $this->emailAuth == false ) {
                $col = 'user';
            } else {
                $col = 'email';
            }
            if ( $this->type == 'cookie' ) {
                if ( isset($_COOKIE['password']) ) {
                    $ret = true;
                }
            } elseif ( $this->type == 'session' ) {
                if ( isset($_SESSION['password']) ) {
                    $ret = true;
                }
            }
            return $ret;
        }
        public function isLoggedInAdmin($true = 0) {
            $ret = false;
            if ( $this->emailAuth == false ) {
                $col = 'user';
            } else {
                $col = 'email';
            }
            if ( $this->type == 'cookie' ) {
                if ( isset($_COOKIE['password']) && $_COOKIE['group'] == 'admin') {
                    $ret = true;
                }elseif(isset($_COOKIE['password']) && $_COOKIE['group'] != 'admin'){
                    $this->errors[] = $this->lang['login_err_plogin'];
                }
            } elseif ( $this->type == 'session' ) {
                if ( isset($_SESSION['password']) && $_SESSION['group'] == 'admin') {
                    $ret = true;
                }elseif(isset($_COOKIE['password']) && $_COOKIE['group'] != 'admin'){
                    $this->errors[] = $this->lang['login_err_plogin'];
                }
            }
            return $ret;
        }
    }
?>