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


    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
        if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
        define('ROOT_DIR', dirname(__FILE__));
    }else{
        define('ROOT_DIR', '.');    
    }

    //Checker
    include(ROOT_DIR.'/including/check.php');
    
    //MYSQL
    include(ROOT_DIR.'/function/mysql.php');
    
    //Multiget CURL
    require(ROOT_DIR.'/function/curl.php');
    require(ROOT_DIR.'/function/mcurl.php');

    // Include Module functions
    require(ROOT_DIR.'/function/func.php');
    require(ROOT_DIR.'/function/func_get.php');
    // Including main config files
    include(ROOT_DIR.'/function/config.php');
    //Loding language pack
    $config['align'] = '';
    $lang['error_1'] = '';
    $lates_news = get_url('http://wot-news.com/ajax/lastnews',1);
    if(isset($lates_news['status']) and $lates_news['status'] == 'error') {
      $lates_news = array();
    }
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php if (!isset($config['theme'])) {
                   $config['theme'] = 'ui-lightness'; } ?>
    <link rel="stylesheet" href="./theme/<?=$config['theme']; ?>/jquery-ui.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="./theme/style.css" type="text/css" media="print, projection, screen" />
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery.metadata.js"></script>
    <script type="text/javascript" src="./js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="./js/jquery.tablesorter.widgets.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.js"></script>

    <script type="text/javascript" src="./js/jquery.vticker.js"></script>
    <script type="text/javascript" src="./js/msfc.shared.js"></script>
</head>
<body>
<div class="ui-accordion-content ui-widget-content ui-accordion-content-active">
<table style="border-width: 0; " cellpadding="0" cellspacing="0" width="450px" class="ui-widget-content">
    <tbody>
        <tr>
            <td>Последние новости в "Мире Танков"</td>
        </tr>
        <tr>
            <td>
                <div id="rotate" class="width:450px">
                    <ul>
                        <?php shuffle($lates_news); foreach($lates_news as $vals){
                                foreach($vals as $val){ ?>
                                <li><a target="_blank" href="<?=$val['link']?>"><?=$val['subject']?></a></li>
                                <?php
                                }
                            }
                        ?>
                    </ul>
                </div>
            </td>
        </tr>

    </tbody>
</table>
</div>
</body>                   
</html>