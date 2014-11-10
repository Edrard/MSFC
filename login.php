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
    * @copyright   2011-2014 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.1.2 $
    *
    */
?>
<div>
    <?php 
        $divo = '<div class="ui-state-error ui-corner-all" align="center">';
        $divc = '</div><br />';
        if ($auth->error()) {
            echo $divo, $auth->error(), $divc;
        }
        if (isset($data['msg'])) {
            echo $divo, '<br />', error($data['msg']), $divc;
        }
    ?>
    <div align="center"><?=$lang['log_to_tab'];?></div>
    <div class="login_insider">
        <?php 
           $multi_get = '';
           if(isset($_GET['multi'])) {
               $multi_get = '&multi='.$_GET['multi'];
           }
        ?>
        <form action="<?=$_SERVER['PHP_SELF'];?>?auth<?=$multi_get,((isset($key) and is_numeric($key))?'#tabs-'.$key:'')?>" method="post">
            <table style="width:100%; border-width: 0; padding: 0 20px; border-radius: 11px;" cellspacing="4" cellpadding="0" class="ui-widget-content">
                <tr>
                    <td colspan="2" align="center">
                        <br>      
                    </td>
                </tr>
                <tr>
                    <td width="80" align="left">&nbsp;&nbsp;<?=$lang['log_login'];?>: </td>
                    <td align="left">
                    <input type="text" name="user" value="" maxlength="40"  />         </td>
                </tr>
                <tr>
                    <td width="80" align="left">&nbsp;&nbsp;<?=$lang['log_pass'];?>: </td>
                    <td align="left">
                    <input type="password" name="pass" value="" maxlength="40"  />          </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" name="login" value="<?=$lang['log_auth'];?>"  />
                    </td>
                </tr>
            </table> 
        </form>
    </div>
</div>