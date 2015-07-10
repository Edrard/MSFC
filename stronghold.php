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
* @version     $Rev: 3.2.0 $
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

// Stronghold info update

$strinfo = stronghold();
//$stronghold = '';
// update list of all stronghold info in game from api if need
if (empty($strinfo)) {
    update_stronghold_db();
    $strinfo = stronghold();
}  

$stronghold = get_api('wot/stronghold/info',array('clan_id'=>$config['clan']));

if(isset($stronghold['status']) and $stronghold['status'] == 'ok' and !empty($stronghold['data'][$config['clan']])) {
    $stronghold = stronghold_generate($strinfo,$stronghold,$config['clan']);
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
                <div class="stronghold_img" style=" background-image: url('<?php echo $stronghold['map']?>');">
                </div> 
                <?php $in = array(); foreach($stronghold['clan']['buildings'] as $id => $val){ 
                    if($val['type'] != 1){ 
                        $dir = $val['direction_name'];
                        if(!isset($in[$dir])){
                            $in[$dir] = 1;
                        }
                        $img = $stronghold['buildings'][$val['type']]['image_url'];
                        $title = "<div>
                        <p style='font-size:1.15em;font-weight:bold;'>".$stronghold['buildings'][$val['type']]['title']."<p>
                        <p>".$lang['lvl'].": <strong>".$val['level']."</strong></p>
                        <p>".$stronghold['buildings'][$val['type']]['short_description']."</p>
                        <p><span style='font-style: italic;padding: 0 0 2px 0; font-size:1.02em;font-weight:bold'>".$stronghold['buildings'][$val['type']]['reserve']['title']."</span><br><img src='".$stronghold['buildings'][$val['type']]['reserve']['image_url']."' style='width:15%;height:15%;float:left;margin:3px 5px 3px 3px;'>".$stronghold['buildings'][$val['type']]['reserve']['description']."</p>
                        </div>"
                        ?>
                        <div class="bb" title="<?php echo $title?>" style="position:absolute;top:<?php echo (build_pos($dir,$in[$dir],'top') - build_mod($val['type'],'y'))?>%;left:<?php echo (build_pos($dir,$in[$dir],'left') - build_mod($val['type'],'x'))?>%;">
                            <img style="width:135px;height: 98px;" src="<?php echo $img?>">
                        </div>
                        <?php $in[$dir]++; ?>
                        <?php }else{ ?>
                        <?php
                        $title = "<div>
                        <p style='font-size:1.15em;font-weight:bold;'>".$stronghold['buildings'][$val['type']]['title']."<p>
                        <p>".$lang['lvl'].": <strong>".$val['level']."</strong></p>
                        </div>"
                        ?>
                        <div class="bb" title="<?php echo $title?>" style="position:absolute;top:64.7%;left:41.3%;width:155px;height: 98px;">
                        </div>

                        <?php   }
                } 
                ?>
            </div>
        </center>
        <div class="uitable"> 
            <table class="tablesorter" style="margin-top: -70px;">
                <thead>                                
                    <tr>
                        <th><?php echo $lang['title_name']?></th>
                        <th><?php echo $lang['lvl']?></th>
                        <th><?php echo $lang['description']?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($stronghold['clan']['buildings'] as $id => $val){ ?>
                        <tr>
                            <td style="width: 25%"><img style="height: 50px; width: 69px; margin-right: 4px; float:left;" src="<?php echo $stronghold['buildings'][$val['type']]['image_url']?>"><span style="float:left;"><?php echo $stronghold['buildings'][$val['type']]['title']?></span></td>
                            <td><?php echo $val['level']?></td>
                            <td><?php echo $stronghold['buildings'][$val['type']]['description']?></td>
                        </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>  
    </div>                    
    <div id="strong_stat_stronghold">
        <?php echo $stronghold['clan']['defense_mode_is_activated'] == 1 ? '<h4 style="color:green">'.$lang['defense_mode_is_activated'] : '<h4 style="color:red">'.$lang['defense_mode_is_deactivated'] ?></h4>
        <div class="uitable">
            <p class="cdiv" style="font-weight: bold;"><?php echo $lang['skirmish']?></p> 
            <table class="tablesorter">
                <thead>                                
                    <tr>
                        <th><?php echo $lang['title_name']?></th>
                        <th><?php echo $lang['clan_value']?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($stronghold['clan']['skirmish'] as $id => $val){ ?>
                        <tr>
                            <td><?php echo $lang['skirmish_'.$id]?></td>
                            <td><?php echo $val?></td>      
                        </tr>
                        <?php } ?>
                </tbody>
            </table>
            <p class="cdiv" style="font-weight: bold;"><?php echo $lang['defense']?></p> 
            <?php if($stronghold['clan']['defense']){ ?>
                <table class="tablesorter">
                    <thead>                                
                        <tr>
                            <th><?php echo $lang['title_name']?></th>
                            <th><?php echo $lang['clan_value']?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($stronghold['clan']['defense'] as $id => $val){ ?>
                            <tr>
                                <td><?php echo $lang['defense_'.$id]?></td>
                                <td><?php echo $val?></td>      
                            </tr>
                            <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
        </div> 
    </div>
</div>