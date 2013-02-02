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
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
        if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
        define('ROOT_DIR', dirname(__FILE__));
    }else{
        define('ROOT_DIR', '.');    
    }

    //Checker

    //MYSQL
    include_once(ROOT_DIR.'/function/mysql.php');
    
    //Multiget CURL
    include_once(ROOT_DIR.'/function/curl.php');
    include_once(ROOT_DIR.'/function/mcurl.php');

    // Include Module functions
    include_once(ROOT_DIR.'/function/func.php');
    // Including main config files
    include_once(ROOT_DIR.'/function/config.php');
    //Loding language pack
    $config['align'] = '';
    $lang['error_1'] = '';
    $lates_news = json_decode(get_url('http://wot-news.com/ajax/lastnews',$config),TRUE);
    include_once(ROOT_DIR.'/js/msfc.js');
    ?>
</head>
<body>
<div class="ui-accordion-content ui-widget-content ui-accordion-content-active">
<table border="0" cellpadding="0" cellspacing="0" width="450px">
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