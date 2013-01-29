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
<div align="center" style="min-height: 100%; width:100%; padding: 0; margin: 0; border: 0px inset black !important; "
                    class="ui-accordion-content ui-helper-reset ui-widget-content ui-accordion-content-active">
    <div style="height: 25%; ">
      <?php if ($auth->error()){ ?>
        <div class="ui-state-error ui-corner-all" style="width:100%; padding:0px; margin: 0px; " align="center">
          <?php echo $auth->error(); ?>
        </div>
      <?php }
            if (isset($data['msg'])){ ?>
        <div class="ui-state-error ui-corner-all" style="width:100%; padding:0px; margin: 0px; " align="center">
          <?=error($data['msg']); ?>
        </div>
      <?php } ?>
    </div>
    <div>
        <img style="width:500px; height:89px;" src="../images/logo.png"/>
    </div>
    <div style="height: 5%; "></div>
    <div class="adinsider">
        <?php
            $multi_get = '';
            if(isset($_GET['multi'])){
                $multi_get = '&multi='.$_GET['multi'];
            }
        ?>
        <form action="<?=$_SERVER['PHP_SELF'];?>?auth<?=$multi_get?>" method="post">
            <table width="300px" border="0" cellspacing="4" cellpadding="0">
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