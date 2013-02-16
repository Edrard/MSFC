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
<div id="adminalltabs" style="min-height: 100%; width:100%; padding:0px; margin: 0px; border: 0px inset black !important; "
    class="ui-accordion-content ui-widget-content ui-accordion-content-active">
    <?php if(count($multiclan) > 1){ ?>
        <div style="padding-left:26px" class="ui-accordion-content ui-widget-content ui-corner-top ui-accordion-content-active">
            <?php
                foreach($multiclan as $val){ ?>
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
                <?php $multi_get = '';
                    if($val['main'] == 0){
                        $multi_get = '&multi='.str_replace('_','',$val['prefix']);
                } ?>
                <a style="margin: 0 5px" id="<?=$val['prefix'].'1';?>" href="./index.php?page=main<?=$multi_get?>">
                    <img height="24" src="http://<?=$config['gm_url'].$multiclan_info[$val['id']]['data']['emblems']['bw_tank']?>" /><span style="margin: auto 4px; display:block; color:<?=$multiclan_info[$val['id']]['data']['color']?>"><?=$multiclan_info[$val['id']]['data']['abbreviation']?></span>
                </a>
                <?php
            } ?>
        </div>
        <?php } ?>

    <table style="height: 100%; width: 100%;" cellpadding="4" cellspacing="0" class="ui-widget-content">
        <tbody>
            <?php
                if(isset($message['text']) && isset($message['color'])){ ?>
                <tr valign="center">
                    <td colspan="2" align="center">
                        <?php if ($message['color'] == 'red') {
                                echo '<div class="ui-state-error ui-corner-all" align="center">';
                            } else {
                                echo '<div class="ui-state-highlight ui-corner-all" align="center">';
                            }
                            echo '<h3>'.$message['text'].'</h3></div>'; ?>
                    </td>
                </tr>
                <?php } ?>
            <tr style="height: 100px;" valign="center">
                <td colspan="2" align="center">
                    <img src="../images/logo.png" width="500px"/>
                </td>
            </tr>
            <tr>
                <td valign="top" width="222px">
                    <ul id="ad_menu" class="tabsmenu ui-corner-all">
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-1"><?=$lang['admin_tab_opt'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-9"><?=$lang['admin_cron_control'];?>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-2"><?=$lang['admin_tab_tabs'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-3"><?=$lang['admin_tab_user'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-4"><?=$lang['admin_db'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-7"><?=$lang['admin_tab_tanks'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-6"><?=$lang['admin_tab_top_tanks'];?></a></li>
                        <li id="ccontrol" class="ui-corner-all"><a onclick="magic(this)" href="#tabs-8"><?=$lang['admin_cln_control'];?></a></li>
                        <li style="margin-top: 100px;" class="ui-corner-all"><a onclick="magic(this)" id="out" href="#tabs-5"><?=$lang['admin_logout'];?></a></li>
                    </ul>
                </td>
                <td valign="top">
                    <div id="tabs-7">
                        <div align="center">
                            <?php if (!empty($tanks_list)){?>
                                <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-7" method="post">
                                    <table id="tanks_list" width="100%" cellspacing="1">
                                        <thead>
                                            <tr>
                                                <th align="center"><?=$lang['tank_list_title'];?></th>
                                                <th align="center"><?=$lang['tank_list_nation'];?></th>
                                                <th align="center"><?=$lang['tank_list_lvl'];?></th>
                                                <th align="center"><?=$lang['tank_list_type'];?></th>
                                                <th align="center"><?=$lang['tank_list_link'];?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($tanks_list as $val) { ?>
                                                <tr>
                                                    <td align="center"><span class="hidden"><?=$val['tank'];?></span><?=$val['tank']?></td>
                                                    <td align="center"><span class="hidden"><?=$val['nation'];?></span><input type="text" size="10" value="<?=$val['nation']?>" name="Array[<?=$val['id']?>][nation]"></td>
                                                    <td align="center"><span class="hidden"><?=$val['lvl'];?></span><input type="text" size="2" value="<?=$val['lvl']?>" name="Array[<?=$val['id']?>][lvl]"></td>
                                                    <td align="center"><span class="hidden"><?=$val['type'];?></span><input type="text" size="12" value="<?=$val['type']?>" name="Array[<?=$val['id']?>][type]"></td>
                                                    <td align="center"><span class="hidden"><?=$val['link'];?></span><input type="text" size="82" value="<?=$val['link']?>" name="Array[<?=$val['id']?>][link]"></td>
                                                </tr>
                                                <?php } ?>
                                        </tbody>
                                    </table>
                                    <p><input type="submit" value="<?=$lang['adm_tank_top_submit']?>" name="tanklist"></p>
                                </form>
                                <?php } else { echo '<div class="ui-state-highlight ui-corner-all" align="center">'.$lang['admin_no_tanks'].'</div>'; }; ?>
                        </div>
                    </div>
                    <div id="tabs-6">
                        <div align="center">
                            <?php if (!empty($adm_top_tanks)){?>
                                <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-6" method="post">
                                    <table id="top_tanks" width="100%" cellspacing="1">
                                        <thead>
                                            <tr>
                                                <th align="center"><?=$lang['admin_tab_top_tanks_name'];?></th>
                                                <th align="center"><?=$lang['admin_tab_top_class'];?></th>
                                                <th align="center"><?=$lang['admin_tab_top_lvl'];?></th>
                                                <th align="center"><?=$lang['admin_tab_top_order'];?></th>
                                                <th align="center" class="{sorter: false}"><?=$lang['admin_tab_top_show'];?></th>
                                                <th align="center"><?=$lang['admin_tab_top_shortname'];?></th>
                                                <th align="center"><?=$lang['admin_tab_top_index'];?></th>
                                                <th align="center"><?=$lang['admin_tab_del'];?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <? foreach($adm_top_tanks as $adm_tname => $val) { ?>
                                                <tr>
                                                    <td align="center"><?=$adm_tname?></td>
                                                    <td align="center"><?=$lang_s['class'][$val['type']]?></td>
                                                    <td align="center"><?=$val['lvl']?></td>
                                                    <td align="center"><div class="hidden"><?=$val['order']?></div><input type="text" value="<?=$val['order']?>" name="Array[<?=$val['title']?>][order]" style="width: 30px;"></td>
                                                    <td align="center"><input type="checkbox" <?=$val['show']?> name="Array[<?=$val['title']?>][show]"></td>
                                                    <td align="center"><input type="text" value="<?=$val['shortname']?>" name="Array[<?=$val['title']?>][shortname]"></td>
                                                    <td align="center"><div class="hidden"><?=$val['index']?></div>
                                                        <select name="Array[<?=$val['title']?>][index]"><? for($i = 1; $i <= 10; $i++){?><option value="<?=$i?>" <?if($i==$val['index']){echo'selected="selected"';}?>><?=$i?></option><?}?></select>
                                                    </td>
                                                    <td align="center"><a href="./index.php?removetoptank=1&tank=<?=$val['title']?>&page=main#tabs-6" onclick="return confirm('<?=$lang['admin_confirm_delete'].' '.$adm_tname;?>?')"><img src="../images/cred.png" /></a></td>
                                                </tr>
                                                <? } ?>
                                        </tbody>
                                    </table>
                                    <p><input type="submit" value="<?=$lang['adm_tank_top_submit']?>" name="toptanksupd"></p>
                                </form>
                                <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-6" method="post">
                                    <h3><?=$lang['adm_tank_top_add'];?></h3>
                                    <select name="adm_top_tanks_action">
                                        <option value="add" selected><?=$lang['adm_tank_top_add1'];?></option><option value="delete"><?=$lang['adm_tank_top_add2'];?></option></select>
                                    <?=$lang['adm_tank_top_add3'];?>
                                    <select name="adm_top_tanks_lvl"><? for($i = 10; $i >= 1; $i--){?><option value="<?=$i?>"><?=$i?></option><?}?></select>
                                    <?=$lang['adm_tank_top_add4'];?>
                                    <select name="adm_top_tanks_type">
                                        <? foreach($lang_s['class'] as $name => $val) { ?>
                                            <option value="<?=$name?>"><?=$val?></option>
                                            <? } ?>
                                    </select>
                                    <p><input type="submit" value="<?=$lang['adm_tank_top_submit']?>" name="toptanksadd"></p>
                                    <span style="color:red;"><?=$lang['adm_tank_top_add6'];?></span><br>
                                    <span style="color:red;"><?=$lang['adm_tank_top_add5'];?></span>
                                </form>
                                <?php } else { echo '<div class="ui-state-highlight ui-corner-all" align="center">'.$lang['admin_no_tanks'].'</div>'; }; ?>
                            <?php if($adm_avalTanks['count'] > 1) { ?>
                                <br /><h3><?=$lang['adm_tank_top_index_add'];?></h3>
                                <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-6" method="post">
                                    <?php foreach($adm_avalTanks['index'] as $index){ ?>
                                        <?=$index;?> - <input type="text" value="<?echo isset($adm_avalTanks['names'][$index])?$adm_avalTanks['names'][$index]:$index;?>" name="Array[title][<?=$index;?>]" style="width: 100px;"><br />
                                        <?php } ?>
                                    <p><input type="submit" value="<?=$lang['adm_tank_top_submit']?>" name="available_tanks_add_index"></p>
                                </form>
                                <?php } ?>
                        </div>
                    </div>
                    <div id="tabs-1">
                        <div align="left">
                            <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-1" method="post">
                                <table width="98%" style="border-width: 0; " cellpadding="6" class="ui-widget-content">
                                    <tbody>
                                        <tr>
                                            <td width="300"><?=$lang['admin_lang'];?></td>
                                            <td>                                                 
                                                <select name="lang">
                                                    <option value="ru" <?php if($config['lang'] == 'ru'){ echo 'selected="selected"';} ?>>Русский</option>
                                                    <option value="en" <?php if($config['lang'] == 'en'){ echo 'selected="selected"';} ?>>English</option>
                                                </select><br><?=$lang['admin_change_lang'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?=$lang['admin_server'];?></td>
                                            <td>                                                 
                                                <select id="iserver" name="server">
                                                    <option value="ru" <?php if($config['server'] == 'ru'){ echo 'selected="selected"';} ?>>RU</option>
                                                    <option value="eu" <?php if($config['server'] == 'eu'){ echo 'selected="selected"';} ?>>EU</option>
                                                    <option value="us" <?php if($config['server'] == 'us'){ echo 'selected="selected"';} ?>>US</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?=$lang['admin_clan_id'];?></td>
                                            <td><input id="iclan" type="text" name="clan" value="<?=$config['clan']; ?>" size="18" /></td>
                                        </tr>
                                        <tr>
                                            <td><?=$lang['admin_cache'];?></td>
                                            <td><input type="text" name="cache" value="<?=$config['cache']; ?>" size="2" /> <?=$lang['admin_hour'];?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$lang['admin_curl_lib'];?></td>
                                            <td>
                                                <select name="pars">
                                                    <option value="curl" <?php if($config['pars'] == 'curl'){ echo 'selected="selected"';} ?>>Curl</option>
                                                    <option value="mcurl" <?php if($config['pars'] == 'mcurl'){ echo 'selected="selected"';} ?>>MCurl</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?=$lang['admin_multiget'];?></td>
                                            <td><input type="text" name="multiget" value="<?=$config['multiget']; ?>" size="2" /></td>
                                        </tr>
                                        <tr>
                                            <td><?=$lang['timezone'];?></td>
                                            <td>
                                                <select name="time">
                                                <?php foreach($lang_tz['timezones_list'] as $offset => $region) { ?>
                                                  <option value="<?=$offset;?>" <?php if($config['time']==$offset){ echo 'selected="selected"';}?>><?=$region;?></option>
                                                <?php } ?>
                                                </select>
                                                <br />
                                                <?php
                                                   $tz_current = new DateTime(NULL, new DateTimeZone(date_default_timezone_get()));
                                                   $american_pm = $tz_current->format('H') > 12 ? ' ('. $tz_current->format('g:i a'). ')' : '';
                                                   echo $lang['cur_timezone'].$tz_current->format('d.m.Y H:i:s').$american_pm;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php if($config['lang'] == 'ru'){?>
                                            <tr>
                                                <td><?=$lang['admin_news'];?></td>
                                                <?php
                                                    if($config['news'] == '1'){
                                                        $news = 'checked="yes"';
                                                    }else{
                                                        $news = '';
                                                    }
                                                ?>
                                                <td><input <?=$news?> type="checkbox" name="news" value="1" size="2" /></td>
                                            </tr>
                                            <?php } ?>
                                        <tr>
                                            <td><?=$lang['admin_allow_user_upload'];?></td>
                                            <td><select name="a_rights">
                                                    <option value="2" <?if($config['a_rights']==2){echo'selected="selected"';}?>><?=$lang['no'];?></option>
                                                    <option value="1" <?if($config['a_rights']==1){echo'selected="selected"';}?>><?=$lang['yes'];?></option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td><?=$lang['admin_themes'];?></td>
                                            <td>
                                                <?php
                                                    if (count($dir_val)>0) { ?>
                                                    <select name="theme">
                                                        <?php foreach ($dir_val as $val){
                                                            if (substr($val, -4)<>'.css') {?>

                                                            <option value="<?=$val;?>" <?php if($config['theme'] == $val){ ?>selected="selected" <?}?>><?=$val;?> </option>
                                                            <?php }  } ?>
                                                    </select>
                                                <?php }?> </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p align="center"><input type="submit" value="<?=$lang['admin_submit'];?>" name="consub"></p>
                            </form>
                        </div>
                    </div>
                    <div id="tabs-2">
                        <div align="center">
                            <h3><?=$lang['admin_file_upload_new_tab'];?></h3>
                            <br>
                            <form enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>#tabs-2" method="POST">
                                <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                                <?=$lang['admin_file_upload'];?>: <input name="uploadedfile" type="file" /><br />
                                <br>
                                <input type="submit" value="<?=$lang['admin_file_upload_butt'];?>" name="fileup" />
                            </form><br>
                            <div>
                                <h3><?=$lang['admin_file_creat_new_tab'];?></h3>
                                <div>
                                    <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-2" method="post">
                                        <?=$lang['admin_ajax_new_file'];?>: <input type="text" size="20" name="file" /> <input type="submit" value="<?=$lang['admin_creat'];?>" name="ajaxcre">
                                    </form>
                                </div>
                            </div><br>
                            <div>
                                <h3><?=$lang['admin_file_edit_new_tab'];?></h3>
                            </div><br>
                            <div >
                                <div>
                                    <div style="float:left;"><button id="loadeng">Load english names</button></div>
                                    <div style="float:right;"><button id="loadrus">Загрузить русские имена</button></div>
                                </div><br>
                                <form enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>#tabs-2" method="POST">
                                    <table id="files" width="100%" cellspacing="1">
                                        <thead>
                                            <tr>
                                                <th><?=$lang['admin_tab_base'];?></th>
                                                <th><?=$lang['admin_tab_file'];?></th>
                                                <th class="{sorter: 'digit'}"><?=$lang['admin_tab_id'];?></th>
                                                <th><?=$lang['admin_tab_name'];?></th>
                                                <th><?=$lang['admin_tab_auth'];?></th>
                                                <th><?=$lang['admin_tab_type'];?></th>
                                                <th align="center"><?=$lang['admin_tab_del'];?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($current_tab as $tab_var){ ?>
                                                <tr>
                                                    <?php
                                                        $tab_check = '';
                                                        if($tab_var['status'] == '1'){
                                                            $tab_check = 'checked="checked"';
                                                        }
                                                    ?> 
                                                    <td align="center"><input type="checkbox" name="<?=str_replace(".", "_", $tab_var['file']);?>_status" value="accept" <?=$tab_check;?> /><span class="hidden"><?=$tab_check;?></span></td>
                                                    <td align="center"><?=$tab_var['file'];?><input type="hidden" name="<?=str_replace(".", "_", $tab_var['file']);?>_file" value="<?=$tab_var['file'];?>"><span class="hidden"><?=$tab_var['file'];?></span></td>
                                                    <td align="center"><input type="text" name="<?=str_replace(".", "_", $tab_var['file']);?>_id" value="<?=$tab_var['id'];?>" size="5" /><span class="hidden"><?=$tab_var['id'];?></span></td>
                                                    <td align="center"><input id="<?=str_replace("/", "", str_replace(".", "", $tab_var['file']));?>" type="text" name="<?=str_replace(".", "_", $tab_var['file']);?>_name" value="<?=$tab_var['name'];?>" size="40" /><span class="hidden"><?=$tab_var['name'];?></span></td>
                                                    <td align="center">
                                                        <select name="<?=str_replace(".", "_", $tab_var['file']);?>_auth">
                                                            <option value="all" <?php if($tab_var['auth'] == 'all'){ echo 'selected="selected"';} ?>>All</option>
                                                            <option value="user" <?php if($tab_var['auth'] == 'user'){ echo 'selected="selected"';} ?>>User</option>
                                                            <option value="admin" <?php if($tab_var['auth'] == 'admin'){ echo 'selected="selected"';} ?>>Admin</option>
                                                        </select> <span class="hidden"><?=$tab_var['auth'];?></span>
                                                    </td>
                                                    <td align="center">
                                                        <?php if($tab_var['type'] == '0'){?>
                                                            Normal<input type="hidden" name="<?=str_replace(".", "_", $tab_var['file']);?>_type" value="0">
                                                            <?php }else{ ?>
                                                            Ajax<input type="hidden" name="<?=str_replace(".", "_", $tab_var['file']);?>_type" value="1">
                                                            <?php } ?>
                                                        <span class="hidden"><?=$tab_var['type'];?></span>
                                                    </td>
                                                    <td align="center"><a href="./index.php?del=1&id=<?=$tab_var['id'];?>&type=<?=$tab_var['type'];?>&page=main#tabs-2" onclick="return confirm('<?=$lang['admin_confirm_delete'].' '.$tab_var['name'];?>?')"><img src="../images/cred.png" /></a></td>
                                                </tr>
                                                <?php } ?>
                                            <?php foreach($tabs_check as $tab_var => $value){ ?>
                                                <?php if($value == 0){ ?>
                                                    <tr>
                                                        <td align="center"><input type="checkbox" name="<?=str_replace(".", "_", $tab_var);?>_status" value="accept" /><span class="hidden"><?=$tab_var;?></span></td>
                                                        <td align="center"><?=$tab_var;?><input type="hidden" name="<?=str_replace(".", "_", $tab_var);?>_file" value="<?=$tab_var;?>"><span class="hidden"><?=$tab_var;?></span></td>
                                                        <td align="center"><input type="text" name="<?=str_replace(".", "_", $tab_var);?>_id"  size="5" /><span class="hidden"><?=$tab_var;?></span></td>
                                                        <td align="center"><input id="<?=str_replace("/", "", str_replace(".", "", $tab_var));?>" type="text" name="<?=str_replace(".", "_", $tab_var);?>_name" size="40" /><span class="hidden"><?=$tab_var;?></span></td>
                                                        <td align="center">
                                                            <select name="<?=str_replace(".", "_", $tab_var);?>_auth">
                                                                <option value="all">All</option>
                                                                <option value="user">User</option>
                                                                <option value="admin">Admin</option>
                                                            </select><span class="hidden"><?=$tab_var;?></span>
                                                        </td>
                                                        <td align="center">Normal<input type="hidden" name="<?=str_replace(".", "_", $tab_var);?>_type" value="0"><span class="hidden">0</span></td>
                                                        <td align="center"><a href="./index.php?del=2&file=<?=str_replace("/", "", str_replace(".", "", $tab_var));?>&page=main#tabs-2" onclick="return confirm('<?=$lang['admin_confirm_delete'].' '.$tab_var;?>?')"><img src="../images/cred.png" /></a></td>
                                                    </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                        </tbody>
                                    </table>
                                    <p align="center"><input type="submit" value="<?=$lang['admin_submit'];?>" name="tabsub" /></p>
                                </form>
                                <span style="color:red;"><?=$lang['admin_tab_delete_n'];?></span>
                            </div>
                        </div>
                    </div>
                    <div id="tabs-3">
                        <div align="center">
                            <h3><?=$lang['admin_new_user_title']?></h3>
                            <br>  
                            <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-3" method="post">
                                <table style="border-width: 0; " cellspacing="1" cellpadding="1" class="ui-widget-content">
                                    <tbody>
                                        <tr>
                                            <td><?=$lang['admin_new_user_name'];?>:</td><td><input type="text" size="20" name="user" /></td>
                                        </tr><tr>
                                            <td><?=$lang['admin_new_user_pass'];?>:</td> <td><input type="password" size="20" name="password" /></td>
                                        </tr><tr>
                                            <td><?=$lang['admin_new_user_group'];?>:</td> <td>
                                                <select name="group">
                                                    <option value="admin">Admin</option>
                                                    <option value="user">User</option>
                                                </select></td>
                                        </tr>
                                        <?php if(count($multiclan) > 1) { ?>
                                        <tr><td><?=$lang['admin_user_access'];?>:</td> <td>
                                            <select name="prefix">
                                                <option value="all">All</option>
                                                <? foreach($multiclan as $mclan) { ?>
                                                <option value="<?=$mclan['prefix'];?>"><?=$multiclan_info[$mclan['id']]['data']['abbreviation'];?></option>
                                                <? } ?>
                                            </select>
                                        </td></tr>
                                        <?php } ?>
                                        <tr>
                                            <td align="center" colspan="2"><input type="submit" value="<?=$lang['admin_creat'];?>" name="newuser"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form><br>
                            <table id="users" width="100%" cellspacing="1">
                                <thead>
                                    <tr>
                                        <th align="center"><?=$lang['admin_user_name'];?></th>
                                        <th align="center"><?=$lang['admin_user_group'];?></th>
                                        <th align="center"><?=$lang['admin_user_access'];?></th>
                                        <th align="center"><?=$lang['admin_user_edit'];?></th>
                                        <th align="center"><?=$lang['admin_user_del'];?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($current_user as $val){?>
                                        <tr>
                                            <td align="center"><?=$val['user'];?></td>
                                            <td align="center"><?=$val['group'];?></td>
                                            <td align="center"><?=$val['prefix'];?></td>
                                            <td align="center"><a href="#" class="trigger_<?=$val['user']?>"><?=$lang['admin_user_edit'];?></a></td>
                                            <td align="center"><a href="./index.php?userdel=1&id=<?=$val['id'];?>&page=main#tabs-3" onclick="return confirm('<?=$lang['admin_confirm_delete'].' '.$val['user'];?>?')"><img src="../images/cred.png" /></a></td>
                                        </tr>
                                        <?php } ?>
                                </tbody>
                            </table>
                            <?php foreach($current_user as $val){?>
                                <div id="dialog_<?=$val['user']?>">
                                    <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-3" method="post">
                                        <div align="center"><h3><?=$lang['admin_new_user_edit'];?></h3></div>
                                        <table style="border-width: 0; " cellspacing="6" cellpadding="2" class="ui-widget-content">
                                            <tbody>
                                                <tr><td><?=$lang['admin_new_user_name'];?>:</td><td><input type="text" size="20" name="user" value="<?=$val['user']?>" /></td></tr>
                                                <tr><td><?=$lang['admin_new_user_pass'];?>:</td> <td><input type="password" size="20" name="password" /></td></tr>
                                                <tr><td><?=$lang['admin_new_user_group'];?>:</td> <td>
                                                    <select name="group">
                                                        <option value="admin" <?php if($val['group'] == 'admin'){?> selected="selected" <?php }; ?>>Admin</option>
                                                        <option value="user"  <?php if($val['group'] == 'user') {?> selected="selected" <?php }; ?>>User</option>
                                                    </select></td></tr>
                                                <?php if(count($multiclan) > 1) { ?>
                                                <tr><td><?=$lang['admin_user_access'];?>:</td> <td>
                                                    <select name="prefix">
                                                        <option value="all" <?php if($val['prefix'] == 'all'){ echo 'selected="selected"';} ?>>All</option>
                                                        <? foreach($multiclan as $mclan) { ?>
                                                        <option value="<?=$mclan['prefix'];?>" <?php if($mclan['prefix'] == $val['prefix']){ echo 'selected="selected"';} ?>><?=$multiclan_info[$mclan['id']]['data']['abbreviation'];?></option>
                                                        <? } ?>
                                                    </select>
                                                </td></tr>
                                                <?php } ?>
                                                <tr><td align="center" colspan="2"><input type="submit" value="<?=$lang['admin_submit'];?>" name="edituser"></td></tr>
                                            </tbody>
                                        </table>
                                        <input type="hidden" name="oldname" value="<?=$val['user']?>">
                                    </form>
                                </div>
                                <?php } ?>
                        </div>
                    </div>
                    <div id="tabs-4">
                        <div align="center">
                            <h3><?=$lang['admin_db_recreat'];?></h3>
                            <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                <input type="submit" value="<?=$lang['admin_db_but'];?>" name="recdb"><br />
                                <?=$lang['admin_db_warning'];?>
                            </form> <br><br>
                            <h3><?=$lang['admin_clear_cache'];?></h3>
                            <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                <input type="submit" value="<?=$lang['admin_clear_cache_but'];?>" name="admclearcache"><br />
                            </form> <br><br>
                            <h3><?=$lang['admin_clear_a_cache'];?></h3>
                            <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                <?=$lang['admin_clear_a_cache_form'];?><br />
                                <input type="submit" value="<?=$lang['admin_clear_cache_but'];?>" name="admclearacache"><br />
                            </form> <br><br>                
                            <h3><?=$lang['admin_db_up'];?></h3>
                            <form enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="POST">
                                <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                <?=$lang['admin_file_upload'];?>: <input name="uploadedfile" type="file" /><br />
                                <br>
                                <input type="submit" value="<?=$lang['admin_db_up_but'];?>" name="newup" /><br />
                                <?=$lang['admin_db_up_warning'];?>
                            </form><br>
                        </div>
                    </div>
                    <div id="tabs-8">
                        <div align="center" id="dccontrol">
                            <h3><?=$lang['admin_add_clan'];?></h3>
                            <br>
                            <form id="multiclan" action="<?=$_SERVER['REQUEST_URI']?>#tabs-8" method="post">
                                <table width="300" cellspacing="1" class="ui-widget-content">
                                    <tr>
                                        <td align="right"><?=$lang['admin_multi_id'];?>:</td><td><input type="text" value="<?=(isset($_POST['id']) ? $_POST['id'] : '') ?>" name="id" size="12"></td>
                                    </tr><tr> 
                                        <td align="right"><?=$lang['admin_server'];?></td>
                                        <td>                                                 
                                            <select name="server">
                                                <?php if(!isset($_POST['server'])){ ?>
                                                    <option value="ru">RU</option>
                                                    <option value="eu">EU</option>
                                                    <option value="us">US</option> 
                                                    <?php }else{ ?>
                                                    <option value="ru" <?=($_POST['server'] == 'ru' ? 'selected="selected"' : '') ?>>RU</option>
                                                    <option value="eu" <?=($_POST['server'] == 'eu' ? 'selected="selected"' : '') ?>>EU</option>
                                                    <option value="us" <?=($_POST['server'] == 'us' ? 'selected="selected"' : '') ?>>US</option>
                                                    <?php } ?>                    
                                            </select>
                                        </td>
                                    </tr><tr> 
                                        <td align="right"><?=$lang['admin_multi_prefix'];?>:</td><td><input type="text" value="<?=(isset($_POST['prefix']) ? $_POST['prefix'] : '') ?>" name="prefix" size="20"></td>
                                    </tr><tr>
                                        <td align="right"><?=$lang['admin_multi_index'];?>:</td><td><input type="text" value="<?=(isset($_POST['sort']) ? $_POST['sort'] : '') ?>" name="sort" size="3"></td>
                                    </tr>
                                </table><br />
                                <input type="hidden" value="1" name="multiadd">
                                <input type="submit" value="<?=$lang['admin_multi_add_new'];?>" name="multiadd"><br />
                            </form>
                            <script>
                                $("#multiclan").validate();
                                if ($("#multiclan").valid()) {
                                  $("#multiclan").submit(function(e) {
                                    var form = $(this);
                                    if (!e.isDefaultPrevented()) {
                                        $("label.error").remove();
                                        // submit with AJAX
                                        $.getJSON("../ajax/mc_valid.php?" + form.serialize(), function(json) {
                                            // everything is ok. (server returned true)
                                            if (json["id"] === "true")  {
                                                document.forms["multiclan"].submit();
                                            } else {
                                                form.data("validator").showErrors(json);
                                                $("label.error").css("display","inline");
                                                $("label.error").addClass("ui-state-error ui-corner-all");
                                            }
                                        });
                                        e.preventDefault();
                                    }
                                  });
                                }
                            </script>
                            <br><br>
                            <h3><?=$lang['admin_current_calns'];?></h3>
                            <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-8" method="post">
                                <table id="multiclan_table" width="100%" cellspacing="1">
                                    <thead>
                                        <tr>
                                            <th align="center"><?=$lang['admin_multi_index'];?></th>
                                            <th align="center"><?=$lang['admin_multi_link'];?></th>
                                            <th align="center"><?=$lang['admin_multi_server'];?></th>
                                            <th align="center"><?=$lang['admin_multi_id'];?></th>
                                            <th align="center"><?=$lang['admin_multi_teg'];?></th>
                                            <th align="center"><?=$lang['admin_multi_mem_count'];?></th>
                                            <th align="center"><?=$lang['admin_multi_prefix'];?></th>
                                            <th align="center"><?=$lang['admin_multi_main'];?></th>
                                            <th align="center"><?=$lang['admin_user_del'];?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <? foreach($multiclan as $mclan) { ?>
                                            <tr>
                                                <td align="center"><div class="hidden"><?=$mclan['sort']?></div><input type="text" value="<?=$mclan['sort']?>" name="Array[<?=$mclan['id']?>][order]" style="width: 30px;"></td>
                                                <?php if($mclan['main'] == 0){?>
                                                    <td align="center"><a target="_blank" href="../index.php?multi=<?=str_replace('_','',$mclan['prefix'])?>"><?=$lang['admin_multi_link']?></a></td>
                                                    <?php }else{ ?>
                                                    <td align="center"><a target="_blank" href="../index.php" ><?=$lang['admin_multi_link']?></a></td>
                                                    <?php } ?>
                                                <td align="center"><?=$mclan['server']?></td>
                                                <td align="center"><?=$mclan['id']?></td>
                                                <td align="center"><?=$multiclan_info[$mclan['id']]['data']['abbreviation']?></td>
                                                <td align="center"><?=$multiclan_info[$mclan['id']]['data']['members_count']?></td>
                                                <td align="center"><?=$mclan['prefix']?></td>
                                                <?php
                                                    $cmsg_status = '';
                                                    if($mclan['main'] == 1){
                                                        $cmsg_status = $lang['admin_multi_main_msg'];   
                                                    }
                                                ?>

                                                <td align="center"><?=$cmsg_status?></td>
                                                <?php if($mclan['main'] == 0){?>
                                                    <td align="center"><a href="./index.php?removeclan=1&clan=<?=$mclan['id']?>&page=main#tabs-8" onclick="return confirm('<?=$lang['admin_confirm_delete'];?>?')"><img src="../images/cred.png" /></a></td>
                                                    <?php }else{?>
                                                    <td align="center"></td>
                                                    <?php } ?>
                                            </tr>
                                            <? } ?>
                                    </tbody>
                                </table><br>
                                <input type="submit" value="<?=$lang['admin_submit'];?>" name="mcsort"><br />
                            </form> <br><br>
                        </div>
                    </div>
                    <div id="tabs-9">
                        <div>
                            <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-9" method="post">
                                <table width="98%" style="border-width: 0; " cellpadding="6" class="ui-widget-content">
                                    <tbody>
                                        <tr>
                                            <td width="300"><?=$lang['admin_cron'];?></td>
                                            <?php
                                                if($config['cron'] == '1'){
                                                    $cron = 'checked="yes"';
                                                }else{
                                                    $cron = '';
                                                }
                                            ?>
                                            <td><input <?=$cron; ?> type="checkbox" name="cron" value="1" size="2" onclick="magic3()"/></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><?=$lang['admin_cron_time'];?></td>
                                            <td><input type="text" name="cron_time" value="<?=$config['cron_time']; ?>" size="2" /> <?=$lang['admin_hour'];?>
                                            <div class="ui-state-highlight ui-corner-all" style="display:inline-block; width:75%;" align="center"><?=$lang['admin_cron_time_warning'];?></div></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><?=$lang['admin_cron_auth'];?></td>
                                            <?php
                                                if($config['cron_auth'] == '1'){
                                                    $cron_auth = 'checked="yes"';
                                                }else{
                                                    $cron_auth = '';
                                                }
                                            ?>
                                            <td><input <?=$cron_auth?> type="checkbox" name="cron_auth" value="1" size="2" /></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><?=$lang['admin_cron_multi'];?></td>
                                            <?php
                                                if($config['cron_multi'] == '1'){
                                                    $cron_multi = 'checked="yes"';
                                                }else{
                                                    $cron_multi = '';
                                                }
                                            ?>
                                            <td><input <?=$cron_multi?> type="checkbox" name="cron_multi" value="1" size="2" /></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><br></td>
                                            <td></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td colspan="2"><b><?=$lang['admin_cron_period']?></b></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><?=$lang['admin_cron_we_loosed'];?></td>
                                            <td><input type="text" name="we_loosed" value="<?=$config['we_loosed']; ?>" size="10" /> <?=$lang['admin_sec'];?></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><?=$lang['admin_cron_new_players'];?></td>
                                            <td><input type="text" name="new_players" value="<?=$config['new_players']; ?>" size="10" /> <?=$lang['admin_sec'];?></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><?=$lang['admin_cron_main_progress'];?></td>
                                            <td><input type="text" name="main_progress" value="<?=$config['main_progress']; ?>" size="10" /> <?=$lang['admin_sec'];?></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><?=$lang['admin_cron_medal_progress'];?></td>
                                            <td><input type="text" name="medal_progress" value="<?=$config['medal_progress']; ?>" size="10" /> <?=$lang['admin_sec'];?></td>
                                        </tr>
                                        <tr class="admin_cdhide">
                                            <td><?=$lang['admin_cron_new_tanks'];?></td>
                                            <td><input type="text" name="new_tanks" value="<?=$config['new_tanks']; ?>" size="10" /> <?=$lang['admin_sec'];?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p align="center"><input type="submit" value="<?=$lang['admin_submit'];?>" name="consub_2"></p>
                            </form>
                            <br>
                            <h3 class="admin_cdhide"><?=$lang['current_cron'];?></h3>
                            <?php if(file_exists(ROOT_DIR.'/cron.log') || is_readable(ROOT_DIR.'/cron.log')){ ?>
                                <textarea class="admin_cdhide" rows="10" cols="85" name="text">
                                    <?=file_get_contents(ROOT_DIR.'/cron.log'); ?>
                                </textarea>
                                <?php } ?>
                            <br><br>
                            <form class="admin_cdhide" action="<?=$_SERVER['REQUEST_URI']?>#tabs-9" method="post">
                                <h3><?=$lang['recreat_cron'];?></h3>
                                <input type="submit" value="<?=$lang['recreat_cron'];?>" name="cron_recreat" />
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            <?php if($ver['value'] != VER){ ?>
                <tr valign="bottom">
                    <td colspan="2" align="center">
                        <div align="center" style="width:100%;" class="ui-state-error ui-corner-all">
                            <?=$lang['admin_new_version_1'].' '.$ver['value'].' '.$lang['admin_new_version_2']?> <a href="http://wot-news.com/main/clanstat">WoT-News.Com</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>