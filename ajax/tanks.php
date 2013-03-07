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
    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        include_once (LOCAL_DIR.'/func_ajax.php');

        define('ROOT_DIR', base_dir('ajax'));

    }else{
        define('LOCAL_DIR', '.');
        include_once (LOCAL_DIR.'/func_ajax.php');

        define('ROOT_DIR', '..');

    }
    include_once(ROOT_DIR.'/including/check.php');
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/mysql.php');
    $db->change_prefix($_POST['db_pref']);
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');                      

    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    } 
    include_once(ROOT_DIR.'/function/cache.php');

    //cache
    $cache = new Cache(ROOT_DIR.'/cache/');
    $new_roster = $cache->get('get_last_roster_'.$config['clan'],0);
    //print_r($new_roster);
    foreach($new_roster['data']['members'] as $val){
        $res[$val['account_name']] = $cache->get($val['account_name'],0,ROOT_DIR.'/cache/players/');
    }
    if($_POST['type'] != 'all'){
        $type = array($_POST['type']);   
    }else{
        $type = array('AT-SPG','SPG','lightTank','mediumTank','heavyTank');
    }
    if($_POST['nation'] != 'all'){
        $nation = array($_POST['nation']);
    }else{
        $nation = array('germany','usa','china','ussr','france','uk');
    }
    if($_POST['lvl'] != 'all'){
        $lvl = array($_POST['lvl']);
    }else{
        $lvl = array('1','2','3','4','5','6','7','8','9','10');
    }
    $tanks_group = tanks_group_full($res,$nation,$type,$lvl);

    //print_r($res);
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#tankslist").tablesorter();
    });
</script>   
<table id="tankslist" width="100%" cellspacing="1" class="table-id-<?=$_POST['key'];?>">
    <thead>
        <tr>
            <th><?=$lang['name']; ?></th>
            <?php foreach($tanks_group as $type => $types){
                    foreach($types as $lvl => $tank) {
                        foreach($tank as $column => $tmp){ ?>
                        <th><?=$column; ?></th>
                        <?php }
                    }
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $name => $val){ ?>
            <tr>
                <td><a href="<?php echo $config['base'].$name.'/'; ?>"
                    target="_blank"><?=$name; ?></a></td>
                <?php foreach($tanks_group as $type => $types){
                        foreach($types as $lvl => $tmp){
                            foreach($tmp as $column => $one){ ?>
                            <td>
                                <?php
                                    if(isset($val['tank'][$lvl][$type][$column])){
                                        if($val['tank'][$lvl][$type][$column]['total'] == 0){
                                            $percent = 0;
                                        }else{
                                            $percent = round($val['tank'][$lvl][$type][$column]['win']*100/$val['tank'][$lvl][$type][$column]['total'],2);
                                        }

                                        echo $percent.'% ('.$val['tank'][$lvl][$type][$column]['total'].'/'.$val['tank'][$lvl][$type][$column]['win'].')';
                                    }else{
                                        echo '';
                                    }

                                ?>
                            </td>
                            <?php
                            }
                        }
                    }
                ?>
            </tr>
            <?php } ?>
    </tbody>  
    </table>