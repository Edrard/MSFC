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
* @version     $Rev: 3.0.2 $
*
*/


if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
    define('ROOT_DIR', dirname(__FILE__));
}else{
    define('ROOT_DIR', '.');
}
// MAIN - this parametr show us thats we running main.php, to turn on mysql.config.php permisiion check
define('MAIN', '');

//Multiget check
$sender = '';
$senderLoad = '';
if(isset($_GET['multi']) && !isset($_GET['page'])){
    $sender = '?multi='.$_GET['multi'];
    $senderLoad = '&from_index=1';
} else {
    $senderLoad = '?from_index=1';
}

//Checker
include(ROOT_DIR.'/including/check.php');

//MYSQL
include(ROOT_DIR.'/function/mysql.php');

// Include Module functions
require(ROOT_DIR.'/function/func.php');
require(ROOT_DIR.'/function/func_main.php');

// Including main config files
include(ROOT_DIR.'/function/config.php');
require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

//Loding language pack
foreach(scandir(ROOT_DIR.'/translate/') as $files){
    if (preg_match ("/_".$config['lang'].".php/", $files)){
        require(ROOT_DIR.'/translate/'.$files);
    }
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
        <script type="text/javascript">
            var url = 'main.php<?=$sender;?>';
            var urlLoad = 'main.php<?=$sender;?><?=$senderLoad;?>';
        </script>
        <script language="JavaScript" type="text/javascript">
            function reFresh() {
                location.reload(true)
            }
            window.setInterval("reFresh()",<?php echo ($exec_refresh*1000) ?>);
        </script>

        <style>
            #main {
                left: 0px;
                margin: 0 auto;
                position: relative;
                height: 100%;
                text-align: center;
            }
        </style>
    </head>
    <body onload="location.href = url;" style="overflow:hidden;overflow-y:hidden">
        <div id="main" class="ui-accordion-content ui-widget-content ui-accordion-content-active">
            <table style="width: 100%; height:100%;" cellpadding="4" cellspacing="0" class="ui-widget-content">
                <tr style="vertical-align: bottom;">
                    <td align="center" style="height: 50%; width:500px;">
                        <?php if($config['lang'] == 'ru'){
                            $multi_get = '';
                            if(isset($_GET['multi'])){
                                $multi_get = '?multi='.$_GET['multi'];
                            }?>
                            <iframe src="./news.php<?=$multi_get?>" frameborder="0" scrolling="no" style="height:64px; width:450px;" ></iframe><br>
                            <?php } ?>
                    </td>
                </tr>
                <tr style="vertical-align: top;">
                    <td align="center">
                        <div class="ui-state-highlight ui-corner-all">
                            <?=$lang['index_loading'];?><img src="./images/ajax-loader.gif">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>
    <script>
        if(document.layers) {
            document.write('<Layer src="' + urlLoad + '" visibility="hide"></Layer>');
        }
        else if(document.all || document.getElementById) {
            document.write('<iframe src="' + urlLoad + '" style="visibility:hidden;"></iframe>');
        }
        else {
            location.href = url;
        }
    </script>
</html>