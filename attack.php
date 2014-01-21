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
* @version     $Rev: 3.0.2 $
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
$battel = array();
if (is_valid_url($config['td']) == true){
    $battel = get_clan_v2($config['clan'], 'battles', $config);
}

//include_once(ROOT_DIR.'/views/header.php');
?>
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
                <th width="40"><?=$lang['type']; ?></th>
                <th><?=$lang['time']; ?></th>
                <th><?=$lang['province']; ?></th>

            </tr>
        </thead>
        <tbody>
        <?php if (isset($battel['data'])){
                  if (empty($battel['data'][$config['clan']])) {
                      echo '<tr><td colspan="3" align="center">'.$lang['no_war'].'</td></tr>';
                  }   else {
                      foreach ($battel['data'][$config['clan']] as $misc => $val){
                          if (strlen($val['time']) > 1){
                              $date = date('H:i',$val['time']);
                          } else {
                              $date = '--:--';
                          }
                          if ($val['type'] == 'landing'){
                              $type = '<img src="./images/landing.png">';
                          } elseif ($val['type'] == 'for_province'){
                              $type = '<img src="./images/attacked.png">';
                          } elseif ($val['type'] == 'meeting_engagement'){
                              $type = '<img src="./images/combats_running.png">';
                          };
                      ?>
                      <tr>
                          <td align="center"><?=$type; ?></td>
                          <td><?=$date; ?></td>
                          <td><a href="<?=$config['clan_link']; ?>maps/globalmap/?province=<?=$val['provinces'][0]; ?>" target="_blank"><?=$val['arenas'][0]['name']; ?></a></td>
                      </tr>
                      <?php
                      };
                  }
              }  else {
                 $message = $lang['error_1'];
                 if (isset ($battel['error']['message'])) $message .= ' ('.$battel['error']['message'].')';
                 echo '<tr><td colspan="3" align="center">'.$message.'</td></tr>';
              }; ?>
        </tbody>
    </table>
</div>