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
    * @version     $Rev: 3.2.0 $
    *
    */


    // Сортируйте элементы массива так, как бы вы хотели их видеть в интерфейсе.
    // Если закладка должна вызывать другую страницу при помощи ajax, то первый ключ должен быть ссылкой на сайт
    // Второй ключь долженн быть названием закладки
    // Значение - имя файла в папке /tabs, не забываете расширение. Если это ajax, то оставьте пустым

    $tabs = read_tabs('WHERE `status` = "1"');
    //usort($current_tab,'sort_id');

    foreach($tabs as $key => $val) {
      if(substr($val['file'],0,2) != './' and !file_exists(ROOT_DIR.'/tabs/'.$val['file'])) {
        show_message(sprintf($lang['tab_del'],$val['name'],$val['file']));
        unset($tabs[$key]);
      }
    }
?>

