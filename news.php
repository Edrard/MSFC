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
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
        if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
        define('ROOT_DIR', dirname(__FILE__));
    }else{
        define('ROOT_DIR', '.');    
    }
    //Starting script time execution timer
    $begin_time = microtime(true);

    //Cheсker

    //MYSQL
    //Connecting to MySQL

    //HTML Dom
    //Multiget CURL

    // Include Module functions
    include_once(ROOT_DIR.'/function/func.php');

    //Loding language pack
    $config['align'] = '';
    $lang['error_1'] = '';
    $lates_news = json_decode(get_url('http://wot-news.com/ajax/lastnews'),TRUE);
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="./css/jq.css" type="text/css" media="print, projection, screen" /> 
    <link rel="stylesheet" href="./css/style.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="./css/jquery-ui.css" type="text/css" media="print, projection, screen" /> 
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery-ui.js"></script>
    <script type="text/javascript" src="./js/jquery-ui-ru.js"></script>
    <script type="text/javascript" src="./js/jquery.tablesorter.js"></script> 
    <script type="text/javascript" src="./js/jquery.metadata.js"></script>
    <script type="text/javascript" src="./js/jquery.qtip.js"></script>
    <script type="text/javascript" src="./js/jquery.vticker.js"></script>

    <script>
        $(document).ready(function()
        {    
            $('#rotate').vTicker({
                speed: 500,
                pause: 5000,
                showItems: 1,
                animation: 'fade',
                mousePause: false,
                height: 0,
                direction: 'down'
            });
        });   
    </script> 
    </head>

<table border="0" cellpadding="0" cellspacing="0" width="450px">
    <tbody>
        <tr>
            <td>Последние новости в "Мире Танков"</td> 
        </tr><tr>
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
</body>                   
</html>
