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
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.3 $
		* @translation [sk] relic242
    *
    */


    $lang['admin_title'] = 'Centrum administrácie'; 
    $lang['admin_tab_opt'] = 'Hlavné nastavenia';
    $lang['admin_tab_tabs'] = 'Nastavenie modulov';
    $lang['admin_tab_user'] = 'Používateľské prístupy';
    $lang['admin_tab_tanks'] = 'Zoznam vozidiel pre CW';
    $lang['admin_db'] = 'Nastavenia databázy';
    $lang['admin_module'] = 'Návrat na hlavnú stránku';
    $lang['admin_logout'] = 'Odhlásiť';
    $lang['admin_server'] = 'Server';
    $lang['admin_clan_id'] = 'Klanové ID';
    $lang['admin_base_url'] = 'základná URL';
    $lang['admin_lang'] = 'Jazyk';
    $lang['admin_cache'] = 'Doba vyrovnávacej pamäťe';
    $lang['admin_tabs_align'] = 'Zarovnanie v tabuľke';
    $lang['admin_curl_lib'] = 'Curl knižnica';
    $lang['admin_offset'] = 'Časový posun';
    $lang['admin_cron'] = 'Zbieranie údajov o hráčoch v čase';
    $lang['admin_cron_time'] = 'Minimálna perióda zbierania údajov';
    $lang['admin_cron_time_warning'] = 'Upozornenie!!! Nemeniť tento údaj';
    $lang['admin_multiget'] = 'Počet súčasne nahrávaných hráčov ';
    $lang['admin_news'] = 'Novinky';
    $lang['admin_submit'] = 'Odoslať';
    $lang['admin_creat'] = 'Vytvoriť';
    $lang['admin_file_upload'] = 'Vyber súbor na nahratie';
    $lang['admin_file_upload_butt'] = 'Nahratie súboru';
    $lang['admin_file_upload_new_tab'] = 'Nahrať nový modul';
    $lang['admin_file_creat_new_tab'] = 'Vytvoriť nový Ajax modul';
    $lang['admin_file_edit_new_tab'] = 'Editovať moduly';
    $lang['admin_msg_upl_1'] = 'The file';
    $lang['admin_msg_upl_2'] = 'has been uploaded';
    $lang['admin_msg_upl_3'] = 'There was an error uploading the file, please try again!';
    $lang['admin_tab_base'] = 'Zap/Vyp';
    $lang['admin_tab_file'] = 'Skript';
    $lang['admin_tab_id'] = 'Poradové číslo';
    $lang['admin_tab_name'] = 'Názov';
    $lang['admin_tab_type'] = 'Typ';
    $lang['admin_tab_auth'] = 'Prístup';
    $lang['admin_tab_del'] = 'Vymazať';
    $lang['admin_tab_delete_n'] = 'Ak vymažeš modul, vymažeš ho z databázy a zároveň z úložiska!!!';
    $lang['admin_ajax_new_file'] = 'Zadaj názov súboru';
    $lang['admin_ajax_new_error'] = 'Modul s týmto názvom už existuje';   
    $lang['admin_tabs_insert_error'] = 'Chyba. Zdá sa že ste niečo nevyplnili';
    $lang['admin_tabs_insert_error2'] = 'Chyba. Nemôže byť to isté poradové číslo';
    $lang['admin_confirm_delete'] = 'Naozaj chceš vymazať';
    $lang['admin_user_name'] = 'Meno';
    $lang['admin_user_group'] = 'Skupina';
    $lang['admin_user_edit'] = 'Editovať';
    $lang['admin_user_del'] = 'Vymazať';
    $lang['admin_change_lang'] = 'Po zmene jazyka sa prosím odhláste a znovu prihláste';

    $lang['admin_new_user_title'] = 'Vytvoriť nového užívateľa';
    $lang['admin_new_user_name'] = 'Meno';
    $lang['admin_new_user_pass'] = 'Heslo';
    $lang['admin_new_user_group'] = 'Skupina';
    $lang['admin_new_user_error_1'] = 'Prepáčte, ale nemôžete pridať užívateľa s menom';
    $lang['admin_new_user_confirm_1'] = 'Požívateľ s menom -';
    $lang['admin_new_user_confirm_2'] = 'bol vytvorený';
    $lang['admin_del_user_error'] = 'Nenašiel som užívateľa na vymazanie';

    $lang['admin_new_user_edit'] = 'Editácia užívateľa';

    $lang['admin_db_recreat'] = 'Znovuvytvoriť databázu';
    $lang['admin_db_warning'] = '<div class="ui-state-error ui-corner-all" align="center">Upozornenie!!! Všetky údaje budú vymazané</div>';
    $lang['admin_db_but'] = 'Znovuvytvoriť';

    $lang['admin_db_up'] = 'Aktualizácia databázy [upgrade]';
    $lang['admin_db_up_warning'] = '<div class="ui-state-error ui-corner-all" align="center">Upozornenie!!! Použiť len v prípade upgrade databázy</div>';
    $lang['admin_db_up_but'] = 'Upgrade';
    $lang['admin_clean_cache'] = 'Vymazať vyrovnávaciu pamäť';

    $lang['admin_con_error'] = 'Prepáčte, ale nemôžem získať údaje z Wargaming stránky';

    $lang['admin_db_update_msg'] = 'Údaje boli pridané do databázy';

    $lang['admin_db_creat'] = 'Inštalovať databázu';
    $lang['admin_db_cwarning'] = '<div class="ui-state-error ui-corner-all" align="center">Upozornenie!!! Všetky údaje budú vymazané</div>';
    $lang['admin_db_cbut'] = 'Inštalovať';
    $lang['admin_dir_cache'] = 'Skontroluj adresár cache, musí byť zapisovateľný [cmod 777]';
    $lang['admin_dir_sql'] = 'Skontroluj admin/sql adresár, musí byť zapisovateľný [cmod 777]';

    $lang['admin_new_version_1'] = 'Nová verzia';
    $lang['admin_new_version_2'] = 'modulu je prístupná, prosím navštívte';

    $lang['tank_list_title'] = 'Názov';
    $lang['tank_list_nation'] = 'Národnosť';
    $lang['tank_list_lvl'] = 'Úroveň/tier';
    $lang['tank_list_type'] = 'Typ';
    $lang['tank_list_link'] = 'Odkaz';

    $lang['admin_clear_cache'] = 'Vymazať vyrovnávaciu pamäť';
    $lang['admin_clear_cache_plbut'] = 'Vymazať vyrovnávaciu pamäť hráčov';
    $lang['admin_clear_cache_actbut'] = 'Vymazať vyrovnávaciu pamäť aktivity';
    $lang['admin_clear_a_cache_form'] = 'Vymazať vyrovnávaciu pamäť aktivity, okrem posledných <input type="text" size="1" name="clear_a_cache_date" value="7" /> dní.';

    $lang['admin_themes'] = 'Téma:';

    $lang['admin_cln_control'] = 'Nastavenia sledovaných klanov';
    $lang['admin_current_calns'] = 'Aktuálny klan';

    $lang['admin_add_clan'] = 'Pridať nový klan';

    $lang['admin_multi_id'] = 'Klanové ID';
    $lang['admin_multi_teg'] = 'Skratka';
    $lang['admin_multi_mem_count'] = 'Počet hráčov';
    $lang['admin_multi_prefix'] = 'Prefix v DB';
    $lang['admin_multi_index'] = 'Poradové číslo';
    $lang['admin_multi_main'] = 'Stav';
    $lang['admin_multi_server'] = 'Server';
    $lang['admin_multi_main_msg'] = 'Hlavný'; 
    $lang['admin_multi_add_new'] = 'Pridať';
    $lang['admin_multi_link'] = 'Odkaz';
    $lang['admin_no_tanks'] = 'Žiadne údaje o vozidlách v DB';
    $lang['admin_no_toptanks'] = 'Žiadne údaje o TOP vozidlách v DB';

    $lang['admin_cron_control'] = 'Zbieranie údajov v čase';
    $lang['admin_cron_auth'] = 'Prihlasovanie do cronu';
    $lang['admin_cron_multi'] = 'Viacnásobné spustenie cronu (pri viac klanoch)';
    $lang['admin_cron_cache'] = 'Použitie vyrovnávacej pamäťe';
    $lang['admin_min'] = 'minút';
    $lang['admin_hour'] = 'hodín';
    $lang['admin_sec'] = 'sekúnd';

    $lang['current_cron'] = 'Aktuálny cron log súbor';
    $lang['recreat_cron'] = 'Znovuvytvorenie cron log súboru';

    $lang['yes'] = 'Áno';
    $lang['no'] = 'Nie';
    $lang['admin_allow_user_upload'] = 'Povoliť skupine užívateľov nahrávať záznamy bitiek';

    $lang['admin_cron_we_loosed'] = 'Hráči, ktorý nás opustili';
    $lang['admin_cron_new_players'] = 'Nový hráči [prednastavené: 2 dni]';
    $lang['admin_cron_main_progress'] = 'Hlavný pokrok'; 
    $lang['admin_cron_medal_progress'] = 'Medailové prírastky';
    $lang['admin_cron_new_tanks'] = 'Nové vozidlá';
    
    $lang['admin_cron_period'] = 'Použité obdobie výstupných údajov';
    $lang['admin_user_access'] = 'Prístup';
    $lang['admin_load_tabs_names'] = 'Výber jazyka názvov modulov:';

    $lang['admin_tanks_db_up'] = 'Aktualizácia údajov o vozidlách v databáze';
    $lang['admin_user_upload_replays'] = 'Nahrať záznam bitky';
    $lang['clear_old_cron_date'] = 'Vymazať Cron údaje staršie ako <input type="text" size="1" name="clear_old_cron_date" value="30" /> dní.';
    $lang['admin_clean_db_left_players'] = 'Vyčistiť DB od hráčov, ktorí opustili klan';
    $lang['admin_clean_db_old_cron'] = 'Vyčistiť DB od starých Cron údajov';
    $lang['admin_application_id'] = 'ID aplikácie [základné ID: "demo"]<br />z http://eu.wargaming.net/developers/';
    $lang['admin_top'] = 'Počet TOP hráčov na hlavnej stránke';

    $lang['admin_tab_company'] = 'Nastavenie zobrazenia čiat';
    $lang['admin_company'] = 'Zobrazenie čiat v jednotlivých moduloch';
    $lang['admin_company_count'] = 'Počet zobrazených čiat';
    $lang['admin_company_add'] = 'Zmena nastavenia názvov jednotlivých čiat';
    $lang['admin_company_clan_list'] = 'Hráči nerozdelený do čiat';
    $lang['admin_company_split'] = 'Rozdelenie medzi čatami';
    $lang['admin_company_save'] = 'Uložiť zmeny';
    $lang['admin_company_no_list'] = 'Žiadne informácie o klane';
    $lang['admin_company_tabs'] = 'Vyberte, kde sa majú zobrazovať čaty';
?>