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
    * @version     $Rev: 2.0.2 $
    *
    */
?>
<?php
    if (isset($_POST['recdb'])){
        recreat_db();
        insert_file(LOCAL_DIR.'/sql/clan.sql');
        insert_multicaln('37','ru',$dbprefix);
        if (!headers_sent()) {
           header ( 'Location: index.php' );
           exit;
        } else { ?>
           <script type="text/javascript">
             location.replace("index.php");
           </script>
<?      }
    }
?>