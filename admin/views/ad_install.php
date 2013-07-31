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
<div align="center"style="min-height: 100%; width:100%; padding: 0; margin: 0; border: 0px inset black !important; "
class="ui-accordion-content ui-helper-reset ui-widget-content ui-accordion-content-active">
<?php if(is_writable(ROOT_DIR.'/cache/') && is_writable(LOCAL_DIR.'/sql/') 
        && is_writable(ROOT_DIR.'/cache/players/') && is_writable(ROOT_DIR.'/cache/activity/')){
    ?>
    <div style="height: 25%; "></div>
    <div class="adinsider">
        <form action="./index.php" method="post">
            <table width="300px" cellspacing="4" cellpadding="0" class="ui-widget-content">
                <tr>
                    <td colspan="2" align="center">
                        <h3><?=$lang['admin_db_creat'];?></h3>
                    </td>
                </tr>
                <tr>
                    <td width="80" align="left">&nbsp;&nbsp;<?=$lang['admin_lang'];?>: </td>
                    <td align="left">
                        <select name="lang">
                            <option value="ru">Русский</option>
                            <option value="en">English</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="80" align="left">&nbsp;&nbsp;<?=$lang['admin_server'];?>: </td>
                    <td align="left">
                        <select id="iserver" name="server">
                            <option value="ru">RU</option>
                            <option value="eu">EU</option>
                            <option value="us">US</option>
                            <option value="as">AS</option>
                            <option value="kr">KR</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="80" align="left">&nbsp;&nbsp;<?=$lang['admin_clan_id'];?>: </td>
                    <td align="left">
                        <input id="iclan" type="text" name="clan" value="37" size="18" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><br></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="<?=$lang['admin_db_cbut'];?>" name="recdb">
                    </td>
                </tr>
            </table>
        </form>
        <?php }  ?>
  </div>
</div>