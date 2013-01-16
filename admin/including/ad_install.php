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
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <div align="center"><br><br><br><br><br><br>
            <?php if(is_writable(ROOT_DIR.'/cache/') && is_writable(LOCAL_DIR.'/sql/')){   
                if(!is_dir(ROOT_DIR.'/cache/players')){
                        mkdir(ROOT_DIR.'/cache/players',0777);
                        chmod(ROOT_DIR.'/cache/players', 0777);
                    }
                    if(is_writable(ROOT_DIR.'/cache/players/')){
                    ?>
                    <h3><?=$lang['admin_db_creat'];?></h3>
                    <form action="./index.php" method="post">
                        <input type="submit" value="<?=$lang['admin_db_cbut'];?>" name="recdb"><br /><br> 
                        <?=$lang['admin_db_cwarning'];?>
                    </form> 
                    <?php } ?>
                <?php } ?>
        </div>
    </body>
</html>