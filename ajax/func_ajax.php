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
* @version     $Rev: 3.1.2 $
*
*/


function base_dir($local = null)
{
    if($local == null){
        $local = dirname($_SERVER['PHP_SELF']);
    }
    $full = dirname(__FILE__);

    return preg_replace('/'.$local.'[\/\\ ]?$/','',$full);
}
?>
