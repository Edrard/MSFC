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
    if( function_exists('memory_get_usage') ) {
        $mem_usage = memory_get_peak_usage(true);
        if ($mem_usage < 1024)
            echo $mem_usage." bytes";
        elseif ($mem_usage < 1048576)
            $memory_usage = round($mem_usage/1024,2)." кб";
        else
            $memory_usage = round($mem_usage/1048576,2)." ".$lang['mb'];
    }   
?>
<div align="center">
    © 2011-<?php echo date('Y') ?> <a href="http://wot-news.com/">Wot-news.com</a> <?php echo $lang['version']; ?> <?php echo VER; ?><br>
    <?php $end_time = microtime(true); echo $lang['ex_time'].' - '.round($end_time - $begin_time,4).' '.$lang['sec']; ?><br>
    <?php if(isset($memory_usage)){echo $lang['memory'].' '.$memory_usage;} ?>
    </div>
    
        </div>
    </body>
</html>
