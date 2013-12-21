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
                <?php $multi_clan_prefix = '';
                    if($val['main'] == 0){
                        $multi_clan_prefix = '&multi='.str_replace('_','',$val['prefix']);
                } ?>
                <a style="margin: 0 5px; min-height: 48px; min-width: 48px;" id="<?=$val['prefix'].'1';?>" href="./index.php?page=main<?=$multi_clan_prefix;?>">
                    <img height="24" src="<?=$multiclan_info[$val['id']]['data'][$val['id']]['emblems']['small'];?>" /><span style="margin: auto 4px; display:block; color:<?=$multiclan_info[$val['id']]['data'][$val['id']]['clan_color']?>"><?=$multiclan_info[$val['id']]['data'][$val['id']]['abbreviation']?></span>
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
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-9"><?=$lang['admin_cron_control'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-2"><?=$lang['admin_tab_tabs'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-3"><?=$lang['admin_tab_user'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-4"><?=$lang['admin_db'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" href="#tabs-6"><?=$lang['admin_tab_top_tanks'];?></a></li>
                        <li id="ccontrol" class="ui-corner-all"><a onclick="magic(this)" href="#tabs-8"><?=$lang['admin_cln_control'];?></a></li>
                        <li style="margin-top: 100px;" class="ui-corner-all"><a onclick="magic(this)" id="out" href="#tabs-10"><?=$lang['admin_module'];?></a></li>
                        <li class="ui-corner-all"><a onclick="magic(this)" id="out2" href="#tabs-5"><?=$lang['admin_logout'];?></a></li>
                    </ul>
                </td>
                <td valign="top">
                    <div id="tabs-6">
                        <div align="center">
                            <?php if($adm_avalTanks['count'] > 1) { ?>
                              <div class="ui-corner-all ui-widget-content">
                                <br /><h3><?=$lang['adm_tank_top_index_add'];?></h3>
                                <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-6" method="post">
                                    <?php foreach($adm_avalTanks['index'] as $index){ ?>
                                        <?=$index;?> - <input type="text" value="<?echo isset($adm_avalTanks['names'][$index])?$adm_avalTanks['names'][$index]:$index;?>" name="Array[title][<?=$index;?>]" style="width: 100px;"><br />
                                        <?php } ?>
                                    <p><input type="submit" value="<?=$lang['adm_tank_top_submit']?>" name="available_tanks_add_index"></p>
                                </form>
                              </div><br clear=all>
                            <?php } ?>
                          <div class="ui-corner-all ui-widget-content">
                            <?php if (!empty($adm_top_tanks)){?>
                                <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-6" method="post">
                                  <h3><?=$lang['admin_tab_tanks'];?></h3>
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
                                        <?php foreach($adm_top_tanks as $index => $val) {
                                                foreach($val as $val2) { ?>
                                                  <tr>
                                                    <td align="center"><?=$val2['name_i18n'];?></td>
                                                    <td align="center"><?=$lang_s['class'][$val2['type']]?></td>
                                                    <td align="center"><?=$val2['lvl']?></td>
                                                    <td align="center"><div class="hidden"><?=$val2['order']?></div><input type="text" value="<?=$val2['order']?>" name="Array[<?=$val2['index'];?>][<?=$val2['tank_id'];?>][order]" style="width: 30px;"></td>
                                                    <td align="center"><input type="checkbox" <?=$val2['show']?> name="Array[<?=$val2['index'];?>][<?=$val2['tank_id'];?>][show]"></td>
                                                    <td align="center"><input type="text" value="<?=$val2['shortname']?>" name="Array[<?=$val2['index'];?>][<?=$val2['tank_id'];?>][shortname]"></td>
                                                    <td align="center"><div class="hidden"><?=$val2['index']?></div>
                                                        <select name="Array[<?=$val2['index'];?>][<?=$val2['tank_id'];?>][index]"><? for($i = 1; $i <= 10; $i++){?><option value="<?=$i?>" <?if($i==$val2['index']){echo'selected="selected"';}?>><?=$i?></option><?}?></select>
                                                    </td>
                                                    <td align="center"><a href="./index.php?removetoptank=1&tank_id=<?=$val2['tank_id']?>&index=<?=$val2['index']?>&page=main<?=$multi_get;?>#tabs-6" onclick="return confirm('<?=$lang['admin_confirm_delete'].' '.$val2['name_i18n'];?>?')"><img src="../images/cred.png" /></a></td>
                                                  </tr>
                                        <?      }
                                              } ?>
                                        </tbody>
                                    </table>
                                    <p><input type="submit" value="<?=$lang['adm_tank_top_submit']?>" name="toptanksupd"></p>
                                </form>
                            <?php } else {
                                  echo '<div class="ui-state-error ui-corner-all" align="center">';
                                  if (empty($tanks_list)) {
                                      echo $lang['admin_no_tanks'].'</div>';
                                  } else {
                                      echo $lang['admin_no_toptanks'].'</div>';
                                  } }; ?>
                          </div>
                          <div class="ui-corner-all ui-widget-content">
                            <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-6" method="post">
                              <h3><?=$lang['adm_tank_top_add'];?></h3>
                              <select name="adm_top_tanks_action">
                                <option value="add" selected><?=$lang['adm_tank_top_add1'];?></option>
                                <option value="delete"><?=$lang['adm_tank_top_add2'];?></option>
                              </select>
                              <?=$lang['adm_tank_top_add3'];?>
                                <select name="adm_top_tanks_lvl">
                                  <? for($i = 10; $i >= 1; $i--){?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                  <? } ?>
                                </select>
                              <?=$lang['adm_tank_top_add4'];?>
                                <select name="adm_top_tanks_type">
                                  <? foreach($lang_s['class'] as $name => $val) { ?>
                                     <option value="<?=$name?>"><?=$val?></option>
                                  <? } ?>
                                </select>
                              <p><input type="submit" value="<?=$lang['adm_tank_top_submit']?>" name="toptanksadd"></p>
                            </form>
                          </div><br clear=all>
                        </div>
                    </div>
                    <div id="tabs-1">
                      <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-1" method="post">
                      <div class="ui-corner-all ui-widget-content div4settings">
                        <div class="settingsLine">
                           <div><?=$lang['admin_lang'];?></div>
                           <div>
                              <select name="lang">
                                  <option value="ru" <?php if($config['lang'] == 'ru'){ echo 'selected="selected"';} ?>>Русский</option>
                                  <option value="pl" <?php if($config['lang'] == 'pl'){ echo 'selected="selected"';} ?>>Polski</option>
                                  <option value="en" <?php if($config['lang'] == 'en'){ echo 'selected="selected"';} ?>>English</option>
                              </select>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_server'];?></div>
                           <div>
                              <select id="iserver" name="server">
                                  <option value="ru" <?php if($config['server'] == 'ru'){ echo 'selected="selected"';} ?>>RU</option>
                                  <option value="eu" <?php if($config['server'] == 'eu'){ echo 'selected="selected"';} ?>>EU</option>
                                  <option value="us" <?php if($config['server'] == 'us'){ echo 'selected="selected"';} ?>>US</option>
                                  <option value="as" <?php if($config['server'] == 'as'){ echo 'selected="selected"';} ?>>AS</option>
                                  <option value="kr" <?php if($config['server'] == 'kr'){ echo 'selected="selected"';} ?>>KR</option>
                              </select>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_clan_id'];?></div>
                           <div>
                              <input id="iclan" type="text" name="clan" value="<?=$config['clan']; ?>" size="18" />
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_cache'];?></div>
                           <div>
                              <input type="text" name="cache" value="<?=$config['cache']; ?>" size="2" /> <?=$lang['admin_hour'];?>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_curl_lib'];?></div>
                           <div>
                              <select name="pars">
                                  <option value="curl" <?php if($config['pars'] == 'curl'){ echo 'selected="selected"';} ?>>Curl</option>
                                  <option value="mcurl" <?php if($config['pars'] == 'mcurl'){ echo 'selected="selected"';} ?>>MCurl</option>
                                  <option value="curl2" <?php if($config['pars'] == 'curl2'){ echo 'selected="selected"';} ?>>Curl2</option>
                              </select>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_multiget'];?></div>
                           <div>
                              <input type="text" name="multiget" value="<?=$config['multiget']; ?>" size="2" />
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_application_id'];?></div>
                           <div>
                              <input type="text" name="application_id" value="<?=$config['application_id']; ?>" size="30" />
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_top'];?></div>
                           <div>
                              <input type="text" name="top" value="<?=$config['top']; ?>" size="2" />
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['timezone'];?></div>
                           <div>
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
                           </div>
                        </div>
                        <?php if($config['lang'] == 'ru'){?>
                        <div class="settingsLine">
                           <div><?=$lang['admin_news'];?></div>
                           <div>
                              <input <?=($config['news']=='1')?'checked="checked"':'';?> type="checkbox" name="news" value="1" size="2" />
                           </div>
                        </div>
                        <?php } ?>
                        <div class="settingsLine">
                           <div><?=$lang['admin_themes'];?></div>
                           <div>
                              <?php if (count($dir_val)>0) { ?>
                                  <select name="theme">
                                      <?php foreach ($dir_val as $val){
                                          if (substr($val, -4)<>'.css') {?>
                                          <option value="<?=$val;?>" <?php if($config['theme'] == $val){ ?>selected="selected" <?}?>><?=$val;?> </option>
                                      <?php } } ?>
                                  </select>
                              <?php }?>
                           </div>
                        </div>
                        <p align="center"><input type="submit" value="<?=$lang['admin_submit'];?>" name="consub"></p>
                     </div>
                     </form>
                    </div>
                    <div id="tabs-2">
                      <div align="center">
                        <div style="float:left;width:49%;" class="ui-corner-all ui-widget-content">
                          <h3><?=$lang['admin_file_upload_new_tab'];?></h3>
                          <br>
                          <form enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>#tabs-2" method="POST">
                            <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                            <?=$lang['admin_file_upload'];?>: <input name="uploadedfile" type="file" /><br /><br />
                            <input type="submit" value="<?=$lang['admin_file_upload_butt'];?>" name="fileup" />
                          </form>
                        </div>
                        <div style="float:right;width:49%;" class="ui-corner-all ui-widget-content">
                          <h3><?=$lang['admin_file_creat_new_tab'];?></h3>
                          <br>
                          <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-2" method="post">
                            <?=$lang['admin_ajax_new_file'];?>: <input type="text" size="20" name="file" /><br /><br />
                            <input type="submit" value="<?=$lang['admin_creat'];?>" name="ajaxcre">
                          </form>
                        </div>
                      </div><br clear=all>
                      <div align="center" class="ui-corner-all ui-widget-content">
                        <h3><?=$lang['admin_file_edit_new_tab'];?></h3>

                        <div align="center">
                          <div style="float:left;"><?=$lang['admin_load_tabs_names']; ?></div>
                          <?php foreach ($tablangs as $val) { ?>
                             <button style="float:left;" id="load<?=$val; ?>"><?=$lang[$val]; ?></button>
                          <? }?>
                        </div><br clear=all>
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
                                      if ($tab_var['status'] == '1') $tab_check = 'checked="checked"';
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
                                      </select>
                                      <span class="hidden"><?=$tab_var['auth'];?></span>
                                    </td>
                                    <td align="center">
                                      <?php if ($tab_var['type'] == '0'){?>
                                        Normal<input type="hidden" name="<?=str_replace(".", "_", $tab_var['file']);?>_type" value="0">
                                      <?php }   else{ ?>
                                       Ajax<input type="hidden" name="<?=str_replace(".", "_", $tab_var['file']);?>_type" value="1">
                                      <?php } ?>
                                      <span class="hidden"><?=$tab_var['type'];?></span>
                                    </td>
                                    <td align="center"><a href="./index.php?del=1&id=<?=$tab_var['id'];?>&type=<?=$tab_var['type'];?>&page=main#tabs-2" onclick="return confirm('<?=$lang['admin_confirm_delete'].' '.$tab_var['name'];?>?')"><img src="../images/cred.png" /></a></td>
                                  </tr>
                                <?php }
                                      foreach($tabs_check as $tab_var => $value){
                                        if ($value == 0){ ?>
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
                                              </select>
                                              <span class="hidden"><?=$tab_var;?></span>
                                            </td>
                                            <td align="center">Normal<input type="hidden" name="<?=str_replace(".", "_", $tab_var);?>_type" value="0"><span class="hidden">0</span></td>
                                            <td align="center"><a href="./index.php?del=2&file=<?=str_replace("/", "", str_replace(".", "", $tab_var));?>&page=main#tabs-2" onclick="return confirm('<?=$lang['admin_confirm_delete'].' '.$tab_var;?>?')"><img src="../images/cred.png" /></a></td>
                                          </tr>
                                        <?php }
                                      } ?>
                              </tbody>
                            </table>
                            <p align="center"><input type="submit" value="<?=$lang['admin_submit'];?>" name="tabsub" /></p>
                          </form>
                          <div class="ui-state-error ui-corner-all"><?=$lang['admin_tab_delete_n'];?></div>
                        </div>
                      </div>
                    <div id="tabs-3">
                        <div align="center">
                          <div class="ui-corner-all ui-widget-content">
                            <h3><?=$lang['admin_new_user_title']?></h3>
                            <br>
                              <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-3" method="post">
                                <table cellspacing="1" cellpadding="1">
                                    <tbody>
                                        <tr>
                                            <td align="right"><?=$lang['admin_new_user_name'];?>:</td><td><input type="text" size="20" name="user" /></td>
                                        </tr><tr>
                                            <td align="right"><?=$lang['admin_new_user_pass'];?>:</td> <td><input type="password" size="20" name="password" /></td>
                                        </tr><tr>
                                            <td align="right"><?=$lang['admin_new_user_group'];?>:</td>
                                            <td align="left"><select name="group">
                                                    <option value="admin">Admin</option>
                                                    <option value="user">User</option>
                                                </select></td>
                                        </tr>
                                        <?php if(count($multiclan) > 1) { ?>
                                        <tr><td align="right"><?=$lang['admin_user_access'];?>:</td>
                                            <td align="left">
                                            <select name="prefix">
                                                <option value="all">All</option>
                                                <? foreach($multiclan as $mclan) { ?>
                                                <option value="<?=$mclan['prefix'];?>"><?=$multiclan_info[$mclan['id']]['data'][$mclan['id']]['abbreviation'];?></option>
                                                <? } ?>
                                            </select>
                                        </td></tr>
                                        <?php } ?>
                                        <tr>
                                         <td align="right"><?=$lang['admin_user_upload_replays'];?>:</td>
                                         <td align="left">
                                            <select name="replays">
                                                <option value="1" selected="selected"><?=$lang['yes'];?></option>
                                                <option value="0"><?=$lang['no'];?></option>
                                            </select>
                                         </td>
                                        </tr>
                                        <tr><td align="center" colspan="2"><br><input type="submit" value="<?=$lang['admin_creat'];?>" name="newuser"><br>
                                        </td></tr>
                                    </tbody>
                                </table>
                              </form>
                          </div><br>
                            <table id="users" width="100%" cellspacing="1">
                                <thead>
                                    <tr>
                                        <th align="center"><?=$lang['admin_user_name'];?></th>
                                        <th align="center"><?=$lang['admin_user_group'];?></th>
                                        <th align="center"><?=$lang['admin_user_access'];?></th>
                                        <th align="center"><?=$lang['admin_user_upload_replays'];?></th>
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
                                            <td align="center"><img src="../images/<?=($val['replays'])?'yes':'no2';?>.png"></td>
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
                                                        <option value="<?=$mclan['prefix'];?>" <?php if($mclan['prefix'] == $val['prefix']){ echo 'selected="selected"';} ?>><?=$multiclan_info[$mclan['id']]['data'][$mclan['id']]['abbreviation'];?></option>
                                                        <? } ?>
                                                    </select>
                                                </td></tr>
                                                <?php } ?>
                                                <tr>
                                                 <td align="right"><?=$lang['admin_user_upload_replays'];?>:</td>
                                                 <td>
                                                    <select name="replays">
                                                        <option value="1" <?=($val['replays'])?'selected="selected"':'';?>><?=$lang['yes'];?></option>
                                                        <option value="0" <?=(!$val['replays'])?'selected="selected"':'';?>><?=$lang['no'];?></option>
                                                    </select>
                                                 </td>
                                                </tr>
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
                      <div class="ui-corner-all ui-widget-content div4settings">
                        <div class="settingsLine">
                           <div><?=$lang['admin_db_recreat'];?></div>
                           <div>
                              <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                <input type="submit" value="<?=$lang['admin_db_but'];?>" name="recdb">
                              </form>
                              <br /><?=$lang['admin_db_warning'];?>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_clear_cache'];?></div>
                           <div>
                              <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                  <input type="submit" value="<?=$lang['admin_clear_cache_plbut'];?>" name="admclearcache">
                              </form>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_clear_cache'];?> </div>
                           <div>
                              <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                  <?=$lang['admin_clear_a_cache_form'];?>
                                  <input type="submit" value="<?=$lang['admin_clear_cache_actbut'];?>" name="admclearacache">
                              </form>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_db_up'];?></div>
                           <div>
                              <form enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="POST">
                                  <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                  <?=$lang['admin_file_upload'];?>: <input name="uploadedfile" type="file" />
                                  &nbsp;&nbsp;&nbsp;
                                  <input type="submit" value="<?=$lang['admin_db_up_but'];?>" name="newup" />
                              </form>
                              <br /><?=$lang['admin_db_up_warning'];?>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_tanks_db_up'];?></div>
                           <div>
                              <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                  <input type="submit" value="<?=$lang['admin_db_up_but'];?>" name="update_tanks_db">
                              </form>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_clean_db_left_players'];?></div>
                           <div>
                              <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                  <input type="submit" value="<?=$lang['admin_tab_del'];?>" name="clean_db_left_players">
                              </form>
                           </div>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['admin_clean_db_old_cron'];?></div>
                           <div>
                              <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-4" method="post">
                                  <?=$lang['clear_old_cron_date'];?>
                                  <input type="submit" value="<?=$lang['admin_tab_del'];?>" name="clean_db_old_cron">
                              </form>
                           </div>
                        </div>
                      </div>
                    </div>
                    <div id="tabs-8">
                      <div align="center" id="dccontrol">
                        <div style="width:100%;" class="ui-corner-all ui-widget-content">
                          <h3><?=$lang['admin_add_clan'];?></h3>
                          <br>
                            <form id="multiclan" action="<?=$_SERVER['REQUEST_URI']?>#tabs-8" method="post">
                              <table width="400" cellspacing="1">
                                <tr>
                                  <td align="right"><?=$lang['admin_multi_id'];?>:</td>
                                  <td align="left"><input type="text" value="<?=(isset($_POST['id']) ? $_POST['id'] : '') ?>" name="id" size="12"></td>
                                </tr>
                                <tr>
                                  <td align="right"><?=$lang['admin_server'];?></td>
                                  <td align="left">
                                    <select name="server">
                                      <?php if(!isset($_POST['server'])){ ?>
                                        <option value="ru">RU</option>
                                        <option value="eu">EU</option>
                                        <option value="us">US</option>
                                        <option value="as">AS</option>
                                        <option value="kr">KR</option>
                                      <?php } else { ?>
                                        <option value="ru" <?=($_POST['server'] == 'ru' ? 'selected="selected"' : '') ?>>RU</option>
                                        <option value="eu" <?=($_POST['server'] == 'eu' ? 'selected="selected"' : '') ?>>EU</option>
                                        <option value="us" <?=($_POST['server'] == 'us' ? 'selected="selected"' : '') ?>>US</option>
                                        <option value="as" <?=($_POST['server'] == 'as' ? 'selected="selected"' : '') ?>>AS</option>
                                        <option value="kr" <?=($_POST['server'] == 'kr' ? 'selected="selected"' : '') ?>>KR</option>
                                      <?php } ?>
                                    </select>
                                  </td>
                                </tr>
                                <tr>
                                  <td align="right"><?=$lang['admin_multi_prefix'];?>:</td>
                                  <td align="left"><input type="text" value="<?=(isset($_POST['prefix']) ? $_POST['prefix'] : '') ?>" name="prefix" size="20"></td>
                                </tr>
                                <tr>
                                  <td align="right"><?=$lang['admin_multi_index'];?>:</td>
                                  <td align="left"><input type="text" value="<?=(isset($_POST['sort']) ? $_POST['sort'] : '') ?>" name="sort" size="3"></td>
                                </tr>
                              </table><br />
                              <input type="hidden" value="1" name="multiadd">
                              <input type="submit" value="<?=$lang['admin_multi_add_new'];?>" name="multiadd">
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
                          </div><br clear=all><br>
                          <div class="ui-corner-all ui-widget-content">
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
                                              <td align="center"><?=$multiclan_info[$mclan['id']]['data'][$mclan['id']]['abbreviation']?></td>
                                              <td align="center"><?=$multiclan_info[$mclan['id']]['data'][$mclan['id']]['members_count']?></td>
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
                            </form>
                          </div>
                      </div>
                    </div>
                    <div id="tabs-9">
                      <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-9" method="post">
                      <div class="ui-corner-all ui-widget-content div4settings">
                        <div class="settingsLine">
                           <div><?=$lang['admin_cron'];?></div>
                           <div>
                              <input <?=($config['cron']== '1')?'checked="yes"':'';?> type="checkbox" name="cron" value="1" size="2" onclick="magic3()"/>
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div><?=$lang['admin_cron_time'];?></div>
                           <div>
                              <input type="text" name="cron_time" value="<?=$config['cron_time']; ?>" size="2" /> <?=$lang['admin_hour'];?>
                              <div class="ui-state-highlight ui-corner-all" style="display:inline-block;" align="center"><?=$lang['admin_cron_time_warning'];?></div>
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div><?=$lang['admin_cron_auth'];?></div>
                           <div>
                              <input <?=($config['cron_auth']=='1')?'checked="yes"':'';?> type="checkbox" name="cron_auth" value="1" size="2" />
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div><?=$lang['admin_cron_multi'];?></div>
                           <div>
                              <input <?=($config['cron_multi']=='1')?'checked="yes"':'';?> type="checkbox" name="cron_multi" value="1" size="2" />
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div>&nbsp;</div>
                           <div>
                              <b><?=$lang['admin_cron_period']?></b>
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div><?=$lang['admin_cron_we_loosed'];?></div>
                           <div>
                              <input type="text" name="we_loosed" value="<?=$config['we_loosed']; ?>" size="10" /> <?=$lang['admin_sec'];?>
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div><?=$lang['admin_cron_new_players'];?></div>
                           <div>
                              <input type="text" name="new_players" value="<?=$config['new_players']; ?>" size="10" /> <?=$lang['admin_sec'];?>
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div><?=$lang['admin_cron_main_progress'];?></div>
                           <div>
                              <input type="text" name="main_progress" value="<?=$config['main_progress']; ?>" size="10" /> <?=$lang['admin_sec'];?>
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div><?=$lang['admin_cron_medal_progress'];?></div>
                           <div>
                              <input type="text" name="medal_progress" value="<?=$config['medal_progress']; ?>" size="10" /> <?=$lang['admin_sec'];?>
                           </div>
                        </div>
                        <div class="settingsLine admin_cdhide">
                           <div><?=$lang['admin_cron_new_tanks'];?></div>
                           <div>
                              <input type="text" name="new_tanks" value="<?=$config['new_tanks']; ?>" size="10" /> <?=$lang['admin_sec'];?>
                           </div>
                        </div>
                        <p align="center" class="admin_cdhide"><input type="submit" value="<?=$lang['admin_submit'];?>" name="consub_2"></p>
                      </div>
                      </form>
                    <br />
                      <div class="ui-corner-all ui-widget-content div4settings admin_cdhide">
                        <div class="settingsLine">
                          <h3><?=$lang['current_cron'];?></h3>
                          <?php if(file_exists(ROOT_DIR.'/cron.log') || is_readable(ROOT_DIR.'/cron.log')){ ?>
                              <textarea rows="10" cols="85" name="text">
                                  <?=file_get_contents(ROOT_DIR.'/cron.log'); ?>
                              </textarea>
                          <?php } ?>
                        </div>
                        <div class="settingsLine">
                           <div><?=$lang['recreat_cron'];?></div>
                           <div>
                              <form action="<?=$_SERVER['REQUEST_URI']?>#tabs-9" method="post">
                                 <input type="submit" value="<?=$lang['recreat_cron'];?>" name="cron_recreat" />
                              </form>
                           </div>
                        </div>
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