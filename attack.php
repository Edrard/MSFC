<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-11-20 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, SHW  $
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
//Starting script time execution timer
$begin_time = microtime(true);

//Checker
include(ROOT_DIR.'/including/check.php');

//MYSQL
include(ROOT_DIR.'/function/mysql.php');

//Multiget CURL
require(ROOT_DIR.'/function/curl.php');
require(ROOT_DIR.'/function/mcurl.php');

// Include Module functions
require(ROOT_DIR.'/function/func.php');
require(ROOT_DIR.'/function/func_main.php');
require(ROOT_DIR.'/function/func_get.php');

// Including main config files
include(ROOT_DIR.'/function/config.php');
require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

//Loding language pack
foreach(scandir(ROOT_DIR.'/translate/') as $files){
    if (preg_match ("/_".$config['lang'].".php/", $files)){
        require(ROOT_DIR.'/translate/'.$files);
    }
}
$ll = file_get_contents($config['wg'].'/globalmap/game_api/clan/'.$config['clan'].'/battles');
$ll = json_decode($ll,TRUE);
?>
<?php if(isset($ll['planned_battles'])){?>
    <script type="text/javascript" id="js">
        $(document).ready(function()
            {
                $("#attack").tablesorter({sortList:[[1,0]]});
        });
    </script>
    <div align="center">
        <table id="attack" cellspacing="1" cellpadding="2" width="100%">
            <thead>
                <tr>
                    <th width="25%"><?=$lang['title_name']; ?></th>
                    <th width="25%"><?=$lang['map']; ?></th>
                    <th width="100"><?=$lang['time']; ?></th>
                    <th width="100"><?=$lang['income']; ?></th>
                </tr>
            </thead>
            <tbody>

                <? foreach($ll['planned_battles'] as $val){ ?>
                    <tr>
                        <td align="center"><?=$val['province_name']?></td>
                        <td align="center"><?=$val['arena_name']?></td>
                        <td align="center"><?=$val['battle_time']?></td>
                        <td align="center" style="color: #ba904d;"><?=isset($val['province_revenue']) ? $val['province_revenue'] : 0;?> <img src="./images/currency-gold.png" border="0"></td>
                    </tr>
                    <? } ?>
            </tbody>
        </table>
    </div>
    <br>
    <? } ?>