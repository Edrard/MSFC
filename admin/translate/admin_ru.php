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
<?php
    $lang['admin_title'] = 'Модуль статистики Админ панель';
    $lang['admin_tab_opt'] = 'Настройки';
    $lang['admin_tab_tabs'] = 'Закладки';
    $lang['admin_tab_user'] = 'Управление пользователями';
    $lang['admin_tab_tanks'] = 'Список танков';
    $lang['admin_db'] = 'База данных';
    $lang['admin_logout'] = 'Выйти';
    $lang['admin_server'] = 'Сервер';
    $lang['admin_clan_id'] = 'Номер клана';
    $lang['admin_base_url'] = 'Базовый URL';
    $lang['admin_lang'] = 'Язык';
    $lang['admin_cache'] = 'Время кеширования';
    $lang['admin_tabs_align'] = 'Направление закладок';
    $lang['admin_curl_lib'] = 'Curl библиотека';
    $lang['admin_offset'] = 'Корректировка времени';
    $lang['admin_ver'] = 'Вертикально';
    $lang['admin_hor'] = 'Горизонтально';
    $lang['admin_cron'] = 'Собирать статистику игроков во времени';
    $lang['admin_cron_time'] = 'Минимальный период сбора данных'; 
    $lang['admin_cron_time_warning'] = 'Предупреждение!!! Не меняйте это значение';
    $lang['admin_news'] = 'Новостная лента';
    $lang['admin_submit'] = 'Отправить';
    $lang['admin_creat'] = 'Создать';
    $lang['admin_file_upload'] = 'Выбрать файл для загрузки';
    $lang['admin_file_upload_butt'] = 'Загрузить';
    $lang['admin_file_upload_new_tab'] = 'Загрузить новую вкладку';
    $lang['admin_file_creat_new_tab'] = 'Создать Ajax вкладку';
    $lang['admin_file_edit_new_tab'] = 'Редактировать вкладки';
    $lang['admin_msg_upl_1'] = 'Файл';
    $lang['admin_msg_upl_2'] = 'был загружен';
    $lang['admin_msg_upl_3'] = 'Возникли проблемы с загрузкой файла, попробуйте позже';
    $lang['admin_tab_base'] = 'Вкл/Выкл';
    $lang['admin_tab_file'] = 'Файл';
    $lang['admin_tab_id'] = 'Порядковый номер';
    $lang['admin_tab_name'] = 'Название';
    $lang['admin_tab_type'] = 'Тип';
    $lang['admin_tab_auth'] = 'Доступ';
    $lang['admin_tab_del'] = 'Удалить';
    $lang['admin_tab_delete_n'] = 'Когда вы удаляете закладку, вы удаляете ее из Базы и Диска';
    $lang['admin_ajax_new_file'] = 'Введите имя файла';
    $lang['admin_ajax_new_error'] = 'закладка с таким названием уже существует';
    $lang['admin_tabs_insert_error'] = 'Ошибка. Не все поля заполнены';
    $lang['admin_tabs_insert_error2'] = 'Ошибка. Не должно быть одинаковых порядковых номеров';
    $lang['admin_confirm_delete'] = 'Вы уверены что хотите удалить';
    $lang['admin_user_name'] = 'Имя';
    $lang['admin_user_group'] = 'Группа';
    $lang['admin_user_edit'] = 'Редактировать';
    $lang['admin_user_del'] = 'Удалить';
    $lang['admin_change_lang'] = 'После смены языка, пожалуйста выйдите и зайдите снова в админ панель';

    $lang['admin_new_user_title'] = 'Создать нового пользователя';
    $lang['admin_new_user_name'] = 'Имя';
    $lang['admin_new_user_pass'] = 'Пароль';
    $lang['admin_new_user_group'] = 'Группа';
    $lang['admin_new_user_error_1'] = 'Простите, но вы не можете добавить пользователя с именем';
    $lang['admin_new_user_confirm_1'] = 'Пользователь с именем -';
    $lang['admin_new_user_confirm_2'] = 'добавлен';
    $lang['admin_del_user_error'] = 'Пользователь для удаления отсутствует';

    $lang['admin_new_user_edit'] = 'Редактировать пользоватлея';

    $lang['admin_db_recreat'] = 'Пересоздать базу';
    $lang['admin_db_warning'] = '<span style="color:red;">Предупреждение!!! Все данные будут удалены</span>';
    $lang['admin_db_but'] = 'Пересоздать';

    $lang['admin_db_sync'] = 'Удалить игроков которые не входят в текущий состав клана';
    $lang['admin_db_sync_warning'] = '<span style="color:red;">Предупреждение!!! Все данные игроков, которые не входят состав клана, будут удалены</span>';
    $lang['admin_db_sync_but'] = 'Удалить';

    $lang['admin_db_up'] = 'Апгрейд базы';
    $lang['admin_db_up_warning'] = '<span style="color:red;">Предупреждение!!! Используйте только при переходе на новую версию</span>';
    $lang['admin_db_up_but'] = 'Апгрейд';
    $lang['admin_clean_cache'] = 'Очистить кеш';

    $lang['admin_con_error'] = 'Простите, но есть проблемы со связью к сайту Wargaming';

    $lang['admin_db_update_msg'] = 'Данные успешно внесены в базу';

    $lang['admin_db_creat'] = 'Установить базу';
    $lang['admin_db_cwarning'] = '<span style="color:red;">Предупреждение!!! Все данные будут удалены</span>';
    $lang['admin_db_cbut'] = 'Установить';
    $lang['admin_dir_cache'] = 'Проверьте папку cache, сейчас нет прав на запись';
    $lang['admin_dir_sql'] = 'Проверьте папку admin/sql, сейчас нет прав на запись';
    
    $lang['admin_new_version_1'] = 'Новая версия';
    $lang['admin_new_version_2'] = 'уже доступна, пожалуйста посетите';



    $lang['tank_list_title'] = 'Название';
    $lang['tank_list_nation'] = 'Нация';
    $lang['tank_list_lvl'] = 'Уровень';
    $lang['tank_list_type'] = 'Тип';
    $lang['tank_list_link'] = 'Ссылка';

    $lang['admin_clear_cache'] = 'Удалить кэшированные данные';
    $lang['admin_clear_cache_but'] = 'Очистить кэш';
?>