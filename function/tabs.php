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
<?php
    // Сортируйте элементы массива так, как бы вы хотели их видеть в интерфейсе.
    // Если закладка должна вызывать другую страницу при помощи ajax, то первый ключ должен быть ссылкой на сайт
    // Второй ключь долженн быть названием закладки
    // Значение - имя файла в папке /tabs, не забываете расширение. Если это ajax, то оставьте пустым

    $current_tab = read_tabs();
    //usort($current_tab,'sort_id');

    foreach($current_tab as $curr_tab_val){
        if($curr_tab_val['auth'] == 'all'){
            $curr_tab_val['auth'] = 0;
        }elseif($curr_tab_val['auth'] == 'user'){
            $curr_tab_val['auth'] = 1;
        }elseif($curr_tab_val['auth'] == 'admin'){
            $curr_tab_val['auth'] = 2;
        }else{
            $curr_tab_val['auth'] = 0;
        }

        if($curr_tab_val['status'] == 1){
            if($logged >= $curr_tab_val['auth']){
                if($curr_tab_val['type'] == 0){
                    $tabs[$curr_tab_val['id']][$curr_tab_val['name']]  = $curr_tab_val['file'];   
                }elseif($curr_tab_val['type'] == 1){
                    $tabs[$curr_tab_val['file']][$curr_tab_val['name']]  = '';    
                }
            }else{
                $tabs[$curr_tab_val['id']][$curr_tab_val['name']]  = array();    
            }
        }
    }

    /* Защита от дураков, удаливших табы, но не удаливших запись о них из БД */
    foreach($tabs as $key => $val) {
      foreach($val as $link => $file) {
        if(is_numeric($key)) {
          if(!file_exists(ROOT_DIR.'/tabs/'.$file) and !is_array($file)) {
            show_message(sprintf($lang['tab_del'],$link,$file));
            unset($tabs[$key]);
          }
        }
      }
    }
    //print_r($tabs);
?>

