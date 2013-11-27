<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-11-20 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.0 $
    *
    */


    $lang['admin_title'] = 'Модуль статистики Админ панель';
    $lang['admin_tab_opt'] = 'Настройки';
    $lang['admin_tab_tabs'] = 'Закладки';
    $lang['admin_tab_user'] = 'Управление пользователями';
    $lang['admin_tab_tanks'] = 'Список танков';
    $lang['admin_db'] = 'База данных';
    $lang['admin_module'] = 'К модулю статистики';
    $lang['admin_logout'] = 'Выйти';
    $lang['admin_server'] = 'Сервер';
    $lang['admin_clan_id'] = 'Номер клана';
    $lang['admin_base_url'] = 'Базовый URL';
    $lang['admin_lang'] = 'Язык';
    $lang['admin_cache'] = 'Время кеширования';
    $lang['admin_tabs_align'] = 'Направление закладок';
    $lang['admin_curl_lib'] = 'Curl библиотека';
    $lang['admin_offset'] = 'Корректировка времени';
    $lang['admin_cron'] = 'Собирать статистику игроков во времени';
    $lang['admin_cron_time'] = 'Минимальный период сбора данных'; 
    $lang['admin_cron_time_warning'] = 'Предупреждение!!! Не меняйте это значение';
    $lang['admin_multiget'] = 'Количество одновременно загружаемых игроков';
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

    $lang['admin_new_user_edit'] = 'Редактировать пользователя';

    $lang['admin_db_recreat'] = 'Пересоздать базу';
    $lang['admin_db_warning'] = '<div class="ui-state-error ui-corner-all" align="center">Предупреждение!!! Все данные будут удалены</div>';
    $lang['admin_db_but'] = 'Пересоздать';

    $lang['admin_db_up'] = 'Апгрейд базы';
    $lang['admin_db_up_warning'] = '<div class="ui-state-error ui-corner-all" align="center">Предупреждение!!! Используйте только при переходе на новую версию</div>';
    $lang['admin_db_up_but'] = 'Апгрейд';
    $lang['admin_clean_cache'] = 'Очистить кеш';

    $lang['admin_con_error'] = 'Простите, но есть проблемы со связью к сайту Wargaming';

    $lang['admin_db_update_msg'] = 'Данные успешно внесены в базу';

    $lang['admin_db_creat'] = 'Установить базу';
    $lang['admin_db_cwarning'] = '<div class="ui-state-error ui-corner-all" align="center">Предупреждение!!! Все данные будут удалены</div>';
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
    $lang['admin_clear_cache_plbut'] = 'Очистить кэш игроков';
    $lang['admin_clear_cache_actbut'] = 'Очистить кэш активности';
    $lang['admin_clear_a_cache_form'] = 'Удалить кэшированные данные о активности, кроме последних <input type="text" size="1" name="clear_a_cache_date" value="7" /> дней.';

    $lang['admin_themes'] = 'Тема:';

    $lang['admin_cln_control'] = 'Управление кланами';
    $lang['admin_current_calns'] = 'Текущие кланы';

    $lang['admin_add_clan'] = 'Добавить новый клан';

    $lang['admin_multi_id'] = 'ID клана';
    $lang['admin_multi_teg'] = 'Аббревиатура';
    $lang['admin_multi_mem_count'] = 'Количество игроков';
    $lang['admin_multi_prefix'] = 'Префикс в базе';
    $lang['admin_multi_index'] = 'Порядковый номер';
    $lang['admin_multi_main'] = 'Статус';
    $lang['admin_multi_server'] = 'Сервер';
    $lang['admin_multi_main_msg'] = 'Основной';
    $lang['admin_multi_add_new'] = 'Добавить';
    $lang['admin_multi_link'] = 'Ссылка';
    $lang['admin_no_tanks'] = 'Нет информации о танках в БД модуля';
    $lang['admin_no_toptanks'] = 'Нет информации о топтанках в БД модуля';
    $lang['admin_cron_control'] = 'Сбор данных во времени';
    $lang['admin_cron_auth'] = 'Аутентификация';
    $lang['admin_cron_multi'] = 'Мультиклан';
    $lang['admin_cron_cache'] = 'Использование кеша';
    $lang['admin_min'] = 'мин.';
    $lang['admin_hour'] = 'час.';
    $lang['admin_sec'] = 'сек.';

    $lang['current_cron'] = 'Текущий лог крона';
    $lang['recreat_cron'] = 'Пересоздать крон лог файл';

    $lang['yes'] = 'Да';
    $lang['no'] = 'Нет';
    $lang['admin_allow_user_upload'] = 'Разрешить группе "User" загружать реплеи';

    $lang['admin_cron_we_loosed'] = 'Ушедшие игроки';
    $lang['admin_cron_new_players'] = 'Новые игроки';
    $lang['admin_cron_main_progress'] = 'Прогресс'; 
    $lang['admin_cron_medal_progress'] = 'Новые награды';
    $lang['admin_cron_new_tanks'] = 'Новые танки';
    
    $lang['admin_cron_period'] = 'Периоды используемые при выводе данных';
    $lang['admin_user_access'] = 'Доступ';
    $lang['admin_load_tabs_names'] = 'Выберите язык для названий вкладок:';

    $lang['admin_tanks_db_up'] = 'Обновить информацию о технике';
    $lang['admin_user_upload_replays'] = 'Загрузка реплеев';
    $lang['clear_old_cron_date'] = 'Удалить Крон данные старше <input type="text" size="1" name="clear_old_cron_date" value="30" /> дней.';
    $lang['admin_clean_db_left_players'] = 'Очистить БД от ушедших игроков';
    $lang['admin_clean_db_old_cron'] = 'Очистить БД от старых Крон данных';
    $lang['admin_application_id'] = 'ID приложения';
    $lang['admin_top'] = 'Выводить в приветственном табе топ по игрокам';
?>