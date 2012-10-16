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
    * @version     $Rev: 2.1.3 $
    *
    */
?>
<div class="admin_container">
    <div class="adinsider_img">
        <img src="../images/logo.png" width="500"/>
    </div>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1"><?=$lang['admin_tab_opt'];?></a></li>
            <li><a href="#tabs-2"><?=$lang['admin_tab_tabs'];?></a></li>
            <li><a href="#tabs-3"><?=$lang['admin_tab_user'];?></a></li>
            <li><a href="#tabs-4"><?=$lang['admin_db'];?></a></li>
            <li><a href="#tabs-7"><?=$lang['admin_tab_tanks'];?></a></li>
            <li><a href="#tabs-6"><?=$lang['admin_tab_top_tanks'];?></a></li>
            <li style="float:right;"><a href="#tabs-5"><?=$lang['admin_logout'];?></a></li>
        </ul>
        <?php
            if(isset($message['text']) && isset($message['color'])){
                echo '<div align="center"><h3><span style="color:'.$message['color'].';">'.$message['text'].'</span></h3></div>'; 
            }
        ?>
        <div id="tabs-7">
            <br><br>
            <div align="center">
                <form action="./index.php?page=main#tabs-7" method="post">
                    <table id="tanks_list" class="tablesorter" cellspacing="1">
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
            </div>
        </div>
        <div id="tabs-6">
            <div align="center">
                <form action="./index.php?page=main#tabs-6" method="post">
                    <table id="top_tanks" class="tablesorter" cellspacing="1">
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
                                    <td align="center"><?=$lang['class'][$val['type']]?></td>
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
                <form action="./index.php?page=main#tabs-6" method="post">
                    <h3><?=$lang['adm_tank_top_add'];?></h3>
                    <select name="adm_top_tanks_action">
                        <option value="add" selected><?=$lang['adm_tank_top_add1'];?></option><option value="delete"><?=$lang['adm_tank_top_add2'];?></option></select>
                    <?=$lang['adm_tank_top_add3'];?>
                    <select name="adm_top_tanks_lvl"><? for($i = 10; $i >= 1; $i--){?><option value="<?=$i?>"><?=$i?></option><?}?></select>
                    <?=$lang['adm_tank_top_add4'];?>
                    <select name="adm_top_tanks_type">
                        <? foreach($lang['class'] as $name => $val) { ?>
                            <option value="<?=$name?>"><?=$val?></option>
                            <? } ?>
                    </select>
                    <p><input type="submit" value="<?=$lang['adm_tank_top_submit']?>" name="toptanksadd"></p>
                    <span style="color:red;"><?=$lang['adm_tank_top_add6'];?></span><br>
                    <span style="color:red;"><?=$lang['adm_tank_top_add5'];?></span>
                </form>
            </div>
        </div>
        <div id="tabs-1">
            <div align="center">
                <br>
                <form action="./index.php?page=main#tabs-1" method="post">
                    <table width="98%" border="0" cellpadding="8">
                        <tbody>
                            <tr>
                                <td width="150"><?=$lang['admin_lang'];?></td>
                                <td>                                                 
                                    <select name="lang">
                                        <?php if($config['lang'] == 'ru'){?>
                                            <option value="ru" selected="selected">Русский</option>
                                            <?php }else{ ?>
                                            <option value="ru">Русский</option>
                                            <?php } ?>
                                        <?php if($config['lang'] == 'en'){?>
                                            <option value="en" selected="selected">English</option>
                                            <?php }else{ ?>
                                            <option value="en">English</option>
                                            <?php } ?>
                                    </select><br><?=$lang['admin_change_lang'];?>
                                </td>
                            </tr>
                            <tr>
                                <td><?=$lang['admin_server'];?></td>
                                <td>                                                 
                                    <select name="server">
                                        <?php if($config['server'] == 'ru'){?>
                                            <option value="ru" selected="selected">RU</option>
                                            <?php }else{ ?>
                                            <option value="ru">RU</option>
                                            <?php } ?>
                                        <?php if($config['server'] == 'eu'){?>
                                            <option value="eu" selected="selected">EU</option>
                                            <?php }else{ ?>
                                            <option value="eu">EU</option>
                                            <?php } ?>
                                        <?php if($config['server'] == 'us'){?>
                                            <option value="us" selected="selected">US</option>
                                            <?php }else{ ?>
                                            <option value="us">US</option>
                                            <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?=$lang['admin_clan_id'];?></td>
                                <td><input type="text" name="clan" value="<?=$config['clan']; ?>" size="18" /></td>
                            </tr>
                            <tr>
                                <td><?=$lang['admin_cache'];?></td>
                                <td><input type="text" name="cache" value="<?=$config['cache']; ?>" size="2" /></td>
                            </tr>
                            <tr>
                                <td><?=$lang['admin_tabs_align'];?></td>
                                <td>
                                    <select name="align">
                                        <?php if($config['align'] == 'ver'){?>
                                            <option value="ver" selected="selected"><?=$lang['admin_ver'];?></option>
                                            <?php }else{ ?>
                                            <option value="ver"><?=$lang['admin_ver'];?></option>
                                            <?php } ?>
                                        <?php if($config['align'] == 'hor'){?>
                                            <option value="hor" selected="selected"><?=$lang['admin_hor'];?></option>
                                            <?php }else{ ?>
                                            <option value="hor"><?=$lang['admin_hor'];?></option>
                                            <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?=$lang['admin_curl_lib'];?></td>
                                <td>
                                    <select name="pars">
                                        <?php if($config['pars'] == 'curl'){?>
                                            <option value="curl" selected="selected">Curl</option>
                                            <?php }else{ ?>
                                            <option value="curl">Curl</option>
                                            <?php } ?>
                                        <?php if($config['pars'] == 'mcurl'){?>
                                            <option value="mcurl" selected="selected">MCurl</option>
                                            <?php }else{ ?>
                                            <option value="mcurl">MCurl</option>
                                            <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?=$lang['admin_offset'];?></td>
                                <td><input type="text" name="time" value="<?=$config['time']; ?>" size="2" /></td>
                            </tr>
                            <tr>
                                <td><?=$lang['admin_cron'];?></td>
                                <?php 
                                    if($config['cron'] == '1'){
                                        $cron = 'checked="yes"';
                                    }else{
                                        $cron = '';
                                    }
                                ?>

                                <td><input <?=$cron?> type="checkbox" name="cron" value="1" size="2" /></td>
                            </tr>
                            <tr>
                                <td><?=$lang['admin_cron_time'];?></td>
                                <td><input type="text" name="cron_time" value="<?=$config['cron_time']; ?>" size="2" /><br><?=$lang['admin_cron_time_warning'];?></td>
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
                        </tbody>
                    </table>
                    <p align="center"><input type="submit" value="<?=$lang['admin_submit'];?>" name="consub"></p>
                </form>
            </div>
        </div>
        <div id="tabs-2">
            <div align="center">
                <br>
                <div>
                    <h3><?=$lang['admin_file_upload_new_tab'];?></h3>
                </div>
                <br>
                <form enctype="multipart/form-data" action="./index.php?page=main#tabs-2" method="POST">
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                    <?=$lang['admin_file_upload'];?>: <input name="uploadedfile" type="file" /><br />
                    <br>
                    <input type="submit" value="<?=$lang['admin_file_upload_butt'];?>" name="fileup" />
                </form><br>
                <div>
                    <h3><?=$lang['admin_file_creat_new_tab'];?></h3>
                    <div>
                        <form action="./index.php?page=main#tabs-2" method="post">
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
                    <form enctype="multipart/form-data" action="./index.php?page=main#tabs-2" method="POST">
                        <table id="files" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th><?=$lang['admin_tab_base'];?></th>
                                    <th><?=$lang['admin_tab_file'];?></th>
                                    <th><?=$lang['admin_tab_id'];?></th>
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
                                                <?php if($tab_var['auth'] == 'all'){?>
                                                    <option value="all" selected="selected">All</option>
                                                    <?php }else{ ?>
                                                    <option value="all">All</option>
                                                    <?php } ?>
                                                <?php if($tab_var['auth'] == 'user'){?>
                                                    <option value="user" selected="selected">User</option>
                                                    <?php }else{ ?>
                                                    <option value="user">User</option>
                                                    <?php } ?>
                                                <?php if($tab_var['auth'] == 'admin'){?>
                                                    <option value="admin" selected="selected">Admin</option>
                                                    <?php }else{ ?>
                                                    <option value="admin">Admin</option>
                                                    <?php } ?>
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
                        <input type="submit" value="<?=$lang['admin_submit'];?>" name="tabsub" />
                    </form> 
                    <span style="color:red;"><?=$lang['admin_tab_delete_n'];?></span>
                </div>
            </div>
        </div>
        <div id="tabs-3">
            <div align="center">
                <br>
                <h3><?=$lang['admin_new_user_title']?></h3>
                <br>  
                <form action="./index.php?page=main#tabs-3" method="post">
                    <table border="0" cellspacing="1" cellpadding="1">
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
                            </tr><tr>
                                <td align="center" colspan="2"><input type="submit" value="<?=$lang['admin_creat'];?>" name="newuser"></td>
                            </tr>
                        </tbody>
                    </table>
                </form><br>
                <table id="users" class="tablesorter" cellspacing="1">
                    <thead>
                        <tr>
                            <th align="center"><?=$lang['admin_user_name'];?></th>
                            <th align="center"><?=$lang['admin_user_group'];?></th>
                            <th align="center"><?=$lang['admin_user_edit'];?></th>
                            <th align="center"><?=$lang['admin_user_del'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($current_user as $val){?>
                            <tr>
                                <td align="center"><?=$val['user'];?></td>
                                <td align="center"><?=$val['group'];?></td>
                                <td align="center"><a href="#" class="trigger_<?=$val['user']?>"><?=$lang['admin_user_edit'];?></a></td>
                                <td align="center"><a href="./index.php?userdel=1&id=<?=$val['id'];?>&page=main#tabs-3" onclick="return confirm('<?=$lang['admin_confirm_delete'].' '.$val['user'];?>?')"><img src="../images/cred.png" /></a></td>
                            </tr>
                            <?php } ?>
                    </tbody>
                </table>
                <?php foreach($current_user as $val){?>
                    <div class="jqmWindow" id="dialog_<?=$val['user']?>">
                        <form action="./index.php?page=main#tabs-3" method="post">
                            <div align="center"><h3><?=$lang['admin_new_user_edit'];?></h3></div>
                            <table border="0" cellspacing="6" cellpadding="2">
                                <tbody>
                                    <tr>
                                        <td><?=$lang['admin_new_user_name'];?>:</td><td><input type="text" size="20" name="user" value="<?=$val['user']?>" /></td>
                                    </tr><tr>
                                        <td><?=$lang['admin_new_user_pass'];?>:</td> <td><input type="password" size="20" name="password" /></td>
                                    </tr><tr>
                                        <td><?=$lang['admin_new_user_group'];?>:</td> <td><select name="group">
                                                <?php if($val['group'] == 'admin'){?>
                                                    <option value="admin" selected="selected">Admin</option>
                                                    <?php }else{ ?>
                                                    <option value="admin">Admin</option>
                                                    <?php } ?>
                                                <?php if($val['group'] == 'user'){?>
                                                    <option value="user" selected="selected">User</option>
                                                    <?php }else{ ?>
                                                    <option value="user">User</option>
                                                    <?php } ?>
                                            </select></td>
                                    </tr><tr>
                                        <td align="center" colspan="2"><input type="submit" value="<?=$lang['admin_submit'];?>" name="edituser"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" name="oldname" value="<?=$val['user']?>">
                        </form>
                        <a href="#" class="jqmClose">Close</a>
                    </div>
                    <?php } ?>
            </div>
        </div>
        <div id="tabs-4">
            <div align="center">
                <h3><?=$lang['admin_clean_cache'];?></h3>
                <form action="./index.php?page=main#tabs-4" method="post">
                    <input type="submit" value="<?=$lang['admin_clean_cache'];?>" name="clean_cache"><br />
                </form> <br><br>
                <h3><?=$lang['admin_db_recreat'];?></h3>
                <form action="./index.php?page=main#tabs-4" method="post">
                    <input type="submit" value="<?=$lang['admin_db_but'];?>" name="recdb"><br />
                    <?=$lang['admin_db_warning'];?>
                </form> <br><br>
                <h3><?=$lang['admin_db_sync'];?></h3>
                <form action="./index.php?page=main#tabs-4" method="post">
                    <input type="submit" value="<?=$lang['admin_db_sync_but'];?>" name="syncdb"><br />
                    <?=$lang['admin_db_sync_warning'];?>
                </form> <br><br>
                <h3><?=$lang['admin_db_up'];?></h3>
                <form enctype="multipart/form-data" action="./index.php?page=main#tabs-4" method="POST">
                    <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                    <?=$lang['admin_file_upload'];?>: <input name="uploadedfile" type="file" /><br />
                    <br>
                    <input type="submit" value="<?=$lang['admin_db_up_but'];?>" name="newup" /><br />
                    <?=$lang['admin_db_up_warning'];?>
                </form><br>
            </div>
        </div>
    </div>
    <?php if($ver['value'] != VER){
        ?>
        <div align="center"><span style="color:red;"><?=$lang['admin_new_version_1'].' '.$ver['value'].' '.$lang['admin_new_version_2']?> <a href="http://wot-news.com/main/clanstat">WoT-News.Com</a></span>
        </div>
        <?php } ?>
</div>