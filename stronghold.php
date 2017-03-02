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
* @version     $Rev: 3.2.3 $
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
require(ROOT_DIR.'/function/func_strong.php');

// Including main config files
include(ROOT_DIR.'/function/config.php');
require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

// Array to MySQL
require(ROOT_DIR.'/function/atm.php');

//Loding language pack
foreach(scandir(ROOT_DIR.'/translate/') as $files){
    if (preg_match ("/_".$config['lang'].".php/", $files)){
        require(ROOT_DIR.'/translate/'.$files);
    }
}
$str = get_api('wot/stronghold/claninfo',array('clan_id'=>$config['clan']));
if(isset($str['status']) and $str['status'] == 'ok' and !empty($str['data'][$config['clan']])) {
    $stronghold = $str['data'][$config['clan']];
    $stronghold['map'] = 'https://wgsh-wot'.$config['server'].'.wargaming.net/index-external.html#/'.$config['lang'].'/'.$config['clan'];
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#set_stronghold table").tablesorter();
        $('.bb[title]').tooltip({
            track: false,
            delay: 0,
            fade: 250,
            items: "[title]",
            open: function (event, ui) {
                ui.tooltip.css("min-width", "400px");
            },
            content: function() {
                var element = $( this );
                if ( element.is( "[title]" ) ) {
                    return element.attr( "title" );
                }
            }
        });
    });
</script>      
<div id="set_stronghold">
    <div id="clan_strong_stronghold">
        <center>
            <div class="flex-center stronghold_back">
                <iframe src="<?=$stronghold['map']?>" scrolling="no" style="width: 100%; height: 100%;"></iframe>
            </div>
        </center>
    </div>                    
    <div id="strong_stat_stronghold">
          <div class="uitable">
            <p class="cdiv" style="font-weight: bold;"><?php echo $lang['skirmish_statistics']?></p> 
            <table class="tablesorter" style="width: 100%;">
                <thead>                                
                    <tr>
                        <th style="width: 80%;"><?php echo $lang['title_name']?></th>
                        <th><?php echo $lang['clan_value']?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($stronghold['skirmish_statistics'] as $id => $val){ ?>
                        <tr>
                            <td><?php echo $lang['skirmish_statistics.'.$id]?></td>
                            <td><?php echo $val?></td>      
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="cdiv" style="font-weight: bold;"><?php echo $lang['battles_for_strongholds_statistics']?></p> 
            <table class="tablesorter" style="width: 100%;">
                <thead>                                
                    <tr>
                        <th style="width: 80%;"><?php echo $lang['title_name']?></th>
                        <th><?php echo $lang['clan_value']?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($stronghold['battles_for_strongholds_statistics'] as $id => $val){ ?>
                        <tr>
                            <td><?php echo $lang['battles_for_strongholds_statistics.'.$id]?></td>
                            <td><?php echo $val?></td>      
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="cdiv" style="font-weight: bold;"><?php echo $lang['battles_series_for_strongholds_statistics']?></p> 
            <table class="tablesorter" style="width: 100%;">
                <thead>                                
                    <tr>
                        <th style="width: 80%;"><?php echo $lang['title_name']?></th>
                        <th><?php echo $lang['clan_value']?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($stronghold['battles_series_for_strongholds_statistics'] as $id => $val){ ?>
                        <tr>
                            <td><?php echo $lang['battles_series_for_strongholds_statistics.'.$id]?></td>
                            <td><?php echo $val?></td>      
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div> 
    </div>
</div>