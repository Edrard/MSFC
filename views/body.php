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
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<?php
    if(isset($message)){
        echo $message;
    }
?>
<div id="allcontainer" style="min-height: 100%; width:100%; padding: 0; margin: 0; border: 0px inset black !important;"
    class="ui-accordion-content ui-widget-content ui-accordion-content-active">
    <?php if(count($multiclan) > 1){ ?>
        <div style="padding-left:26px" class="ui-accordion-content ui-widget-content ui-corner-top ui-accordion-content-active">
            <?php
                foreach($multiclan as $val){
                ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#<?=$val['prefix'].'1';?>").button();
                        <?php if(isset($_GET['multi'])){ ?>
                            $("#<?=$_GET['multi'].'_1'?>").addClass('ui-state-focus');
                            <?php }else{ ?>
                            $("#<?=$multiclan_main['prefix'].'1'?>").addClass('ui-state-focus');
                            <?php } ?>
                    });
                </script>
                <?php
                    $multi_get = '';    
                    if($val['main'] != 1){
                        $multi_get = '?multi='.str_replace('_','',$val['prefix']);   
                    }
                ?>                                                             
                <a style="margin: 0 5px;" id="<?=$val['prefix'].'1';?>" href="/index.php<?=$multi_get?>">
                    <img height="24" src="http://<?=$config['gm_url'].$multiclan_info[$val['id']]['data']['emblems']['bw_tank']?>" /><span style="margin: auto 4px; display:block; color:<?=$multiclan_info[$val['id']]['data']['color']?>"><?=$multiclan_info[$val['id']]['data']['abbreviation']?></span>
                </a>
                <?php
                }
            ?>
        </div>
        <?php } ?>
    <table style="width: 100%;" cellpadding="4" cellspacing="0">
        <tbody>
            <tr style="height: 100px;" valign="center">
                <td width="16px" class="ui-state-default ui-corner-all" onclick="magic2(this)" rowspan="2" >
                    <div id="chan" style="background-origin: content-box; padding: 0; margin: 0; " class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-w">
                        &nbsp;
                    </div>
                </td>
                <td width="222px" align="center" id="tohide"><?=$lang['total_p']; ?>: <?= $new['data']['members_count']; ?></td>
                <td align="center">
                    <img class="bb" src="<?php print_R($config['clan_img'].$config['clan']);?>/emblem_64x64.png"
                    height="64px" width="64px" border="0"; title='<?= $new["data"]["description_html"];?>'/> <br>
                    <?php print_R('<font color="'.$new['data']['color'].'">');
                          print_R('<br>'.$new['data']['name'].'</font>') ?>
                </td>
                <td width="300px"><img src="./images/logo_small.png" /></td>
                <td width="420px"><?php if($config['lang'] == 'ru' && $config['news'] == '1'){ ?>
                        <?php
                            $multi_get = '';    
                            if(isset($_GET['multi'])){
                                $multi_get = '?multi='.$_GET['multi'];   
                            }
                        ?> 
                        <iframe src="./news.php<?=$multi_get?>" frameborder="0" scrolling="no" width="100%" align="middle" height="50px"></iframe>
                    <?php } ?></td>
                <td>
                    <?php if($logged > 0){ ?>
                        <table border="0" cellpadding="4" cellspacing="0">
                            <tbody>
                                <tr>   
                                    <td><strong><?=$lang['hi'];?> <?=$_COOKIE['user'];?></strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <? if($logged == 2) { ?>
                                            <?php
                                                $multi_get = '';
                                                if(isset($_GET['multi'])){
                                                    $multi_get = '?multi='.str_replace('_','',$_GET['multi']);
                                                }
                                            ?> 
                                            <a href="./admin/index.php<?=$multi_get?>" target="_blank"><?=$lang['gotoadmin'];?></a>&nbsp;&nbsp;&nbsp;
                                            <? } ?>
                                        <?php    
                                            $multi_get = '';
                                            if(isset($_GET['multi'])){
                                                $multi_get = '&multi='.str_replace('_','',$_GET['multi']);
                                            }
                                        ?>     
                                        <a href="./main.php?logout=true<?=$multi_get?>"><?=$lang['logout'];?></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php }else{ ?>
                        <div id="login_dialog" class="ui-dialog hidden" style="border: 1px solid; border-collapse: collapse;">
                            <div><?php include(ROOT_DIR.'/login.php'); ?></div>
                        </div>
                        <div>
                            <a href="#" id="login_opener"><?=$lang['login'];?></a>
                        </div>
                        <?php  } ?>
                </td>
            </tr>
            <tr>
                <td valign="top" id="tohide2">
                    <ul id="menu">
                        <?php $i=0;
                            foreach($tabs as $key => $val){
                                foreach(array_keys($val) as $link){
                                    if(is_numeric($key)){ ?>
                                    <li><a <?php if($i==0) echo 'id="id-0"'; ?> onclick="magic(this)" href="#tabs-<?=$key; ?>"><?=$link; ?></a></li>
                                    <?php }else{  ?>
                                    <li><a onclick="magic(this)" href="<?=$key; ?>"><?=$link; ?></a></li>
                                    <?php  }
                                    $i++;
                                }  
                        } ?>
                    </ul>
                </td>
                <td valign="top" colspan="5">
                    <div>
                        <?php
                            foreach($tabs as $key => $val){
                                foreach($val as $file){    
                                    if(is_numeric($key)){ 
                                        if(!is_array($file)){?>
                                        <div id="tabs-<?=$key; ?>">
                                            <a href="#tabs-<?=$key; ?>"></a>
                                            <?php include_once(ROOT_DIR.'/tabs/'.$file); ?>
                                        </div>
                                        <?php  }else{ ?>
                                        <div id="tabs-<?=$key; ?>">
                                            <?php include(ROOT_DIR.'/login.php'); ?>
                                        </div>
                                        <?php }
                                    }
                                }
                        } ?>
                    </div>
                </td>
            </tr>
            <?php
                if( function_exists('memory_get_usage') ) {
                    $mem_usage = memory_get_peak_usage(true);
                    if ($mem_usage < 1024)
                        $memory_usage = $mem_usage." bytes";
                    elseif ($mem_usage < 1048576)
                        $memory_usage = round($mem_usage/1024,2)." кб";
                    else
                        $memory_usage = round($mem_usage/1048576,2)." ".$lang['mb'];
                }   
            ?>
            <tr>
                <td valign="top" colspan="6">
                    <div align="center" class="ui-accordion-content ui-widget-content ui-corner-bottom ui-accordion-content-active">
                        © 2011-<?=date('Y') ?> <a href="http://wot-news.com/">Wot-news.com</a> <?=$lang['version']; ?> <?php echo VER; ?><br>
                        <?php $end_time = microtime(true); echo $lang['ex_time'].' - '.round($end_time - $begin_time,4).' '.$lang['sec']; ?><br>
                        <?php if(isset($memory_usage)){echo $lang['memory'].' '.$memory_usage;} ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>