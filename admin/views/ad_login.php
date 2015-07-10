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
    * @version     $Rev: 3.2.0 $
    *
    */
?>
<div align="center" style="min-height: 100%; height: 100%; width:100%; padding: 0; margin: 0; border: 0px inset black !important;"
                    class="ui-accordion-content ui-helper-reset ui-widget-content ui-accordion-content-active">                    
       <div style="min-height: 20%; padding: 0; position: relative;">
          <?php 
             $divo = '<div class="ui-state-error ui-corner-all" style="padding:0px; margin: 0px;" align="center">';
             $divc = '</div>';
             if ($auth->error()) {
                 echo $divo, $auth->error(), $divc;
             }
             if (isset($data['msg'])){ 
                 echo $divo, error($data['msg']), $divc;
             }
          ?>
       </div>
       <div> 
          <img style="width:500px; height:89px;" src="../images/logo.png" alt="" />
          <div style="min-height: 50px;"></div>
          <div class="login_insider">
             <?php
                 $multi_get = '';
                 if(isset($_GET['multi'])){
                     $multi_get = '&multi='.$_GET['multi'];
                 }
             ?>
             <form action="<?=$_SERVER['PHP_SELF'];?>?auth<?=$multi_get?>" method="post">
                <table style="width:100%; border-width: 0; padding: 0 20px; border-radius: 11px;" cellspacing="4" cellpadding="0" class="ui-widget-content">
                    <tr>
                        <td colspan="2" align="center">
                            <br>      
                        </td>
                    </tr>
                    <tr>
                        <td width="80" align="left">&nbsp;&nbsp;<?=$lang['log_login'];?>: </td>
                        <td align="left">
                           <input style="width: 100%;" type="text" name="user" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td width="80" align="left">&nbsp;&nbsp;<?=$lang['log_pass'];?>: </td>
                        <td align="left">
                           <input style="width: 100%;" type="password" name="pass" value="" maxlength="12" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <br>      
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input class="ui-button" type="submit" name="login" value="<?=$lang['log_auth'];?>" />      
                        </td>
                    </tr>
                </table> 
             </form>
          </div>
       </div>
</div>