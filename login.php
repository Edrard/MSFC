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
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.1.0 $
    *
    */
?>
<div>
    <div align="center"><?=$lang['log_to_tab'];?></div>
    <div class="adinsider">  
        <?php
            $multi_get = '';
            if(isset($_GET['multi'])){
                $multi_get = '&multi='.$_GET['multi'];
            }
        ?>
        <form action="<?=$_SERVER['PHP_SELF'];?>?auth<?=$multi_get?>" method="post">
            <table width="300px" cellspacing="4" cellpadding="0" class="ui-widget-content">
                <tr>
                    <td colspan="2" align="center">
                        <br>      
                    </td>
                </tr>
                <tr class="adbox">
                    <td width="80" align="left">&nbsp;&nbsp;<?=$lang['log_login'];?>: </td>
                    <td align="left">
                    <input type="text" name="user" value="" maxlength="40"  />         </td>
                </tr>
                <tr class="adbox">
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
    <br>
    <?php if ($auth->error()){ ?>
        <div class="ui-state-error ui-corner-all" align="center">
            <? echo $auth->error(); ?>
        </div>
        <?php }?>

    <?php if (isset($data['msg'])){ ?>
        <div class="ui-state-error ui-corner-all" align="center">
            <? echo '<br>'.error($data['msg']); ?>
        </div>
        <?php }?>
    </div>