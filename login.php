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
    * @version     $Rev: 2.0.1 $
    *
    */
?>
    <div class="admain">
        <br><br>
        <div align="center"><?=$lang['log_to_tab'];?></div>
        <br><br>
        <div class="adinsider">
            <form action="<?=$_SERVER['PHP_SELF'];?>?auth" method="post">
                <table width="300" border="0" cellspacing="4" cellpadding="0">
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
    </div>
    <div align="center">
    <?php
        if ($auth->error()){
            echo $auth->error();
        }

        if (isset($data['msg'])){
            echo '<br>'.error($data['msg']);
        }
    ?>
    </div>
