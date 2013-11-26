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
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<?php
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
    include_once(ROOT_DIR.'/including/check.php');

    //MYSQL
    include_once(ROOT_DIR.'/function/mysql.php');

    //Multiget CURL
    include_once(ROOT_DIR.'/function/curl.php');
    include_once(ROOT_DIR.'/function/mcurl.php');

    // Include Module functions
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/func_get.php');

    // Including main config files
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    //Loding language pack
    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    }
    $poss = array();
    if (is_valid_url($config['td']) == true){
        $poss = get_clan_v2($config['clan'], 'provinces', $config);
    }
    //include_once(ROOT_DIR.'/views/header.php');
?>
<script type="text/javascript" id="js">
   $(document).ready(function()
   {
       $("#poss").tablesorter({sortList:[[1,0]]});
   });
</script>
<div align="center">
    <table id="poss" cellspacing="1" cellpadding="2" width="100%">
        <thead> 
            <tr>
                <th width="40"><?=$lang['type']; ?></th>
                <th><?=$lang['title_name']; ?></th>
                <th><?=$lang['map']; ?></th>
                <th><?=$lang['prime_time']; ?></th>
                <th><?=$lang['income']; ?></th>
                <th><?=$lang['owned']; ?></th>

            </tr> 
        </thead>
        <tbody>
        <?php if (isset($poss['data'])) {
                  if (empty($poss['data'])) {
                      echo '<tr><td colspan="6" align="center">'.$lang['no_province'].'</td></tr>';
                  }   else {
                      $total = 0;
                      foreach($poss['data'] as $misc => $val){
                          $total += $val['revenue'];
                          echo '<tr>';
                          echo '<td><img src="./images/'.$val['type'].'.png"></td>';
                          echo '<td><a href="'.$config['clan_link'].'maps/globalmap/?province='.$misc.'" target="_blank">'.$val['name'].'</a></td>';
                          echo '<td>'.$val['arena_name'].'</td>';
                          echo '<td align="center">'.date('H:i',$val['prime_time']).'</td>';
                          echo '<td align="center" style="color: #ba904d;">'.$val['revenue'].' <img src="./images/currency-gold.png"></td>';
                          echo '<td align="center">'.$val['occupancy_time'].' '.$lang['days'].'</td>';
                          echo '</tr>';
                      }
                  }
              }   else {
                  $message = $lang['error_1'];
                  if (isset ($poss['error']['message'])) $message .= ' ('.$poss['error']['message'].')';
                  echo '<tr><td colspan="6" align="center">'.$message.'</td></tr>';
              }; ?>
        </tbody>
    </table>
    <?php if (isset($total)) {
              if ($total != 0 ) {
                  echo '<p>'.$lang['total_gold'].': <span style="color: #ba904d;">'.$total.'</span>'.'<img src="./images/currency-gold.png"></p>';
              };
          }; ?>
</div>