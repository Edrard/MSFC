<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2012
    * Date:        $Date: 2012-11-27
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2012-2012 SHW
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.1.5 $
    *
    */
?>
<?php
    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        include_once (LOCAL_DIR.'/func_ajax.php');
        define('ROOT_DIR', base_dir('ajax'));
    }else{
        define('LOCAL_DIR', '.');
        include_once (LOCAL_DIR.'/func_ajax.php');
        define('ROOT_DIR', '..');
    };
    include_once(ROOT_DIR.'/including/check.php');
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/mysql.php');
    $db->change_prefix($_POST['db_pref']);
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/func_get.php');
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    } 
    if (isset($_POST['diff']) ) {
        $time_diff = $_POST['diff'];
    }else{
        $time_diff  = 0;
    };
    $battel = array();

    if(is_valid_url($config['td']) == true){
        $wg_valid2 = true;
        $battel = get_clan_attack($config,$config['clan']);
    } else {
        $wg_valid2 = false;
    }

function get_json($url, $incorrect = true) {
  $ch = curl_init();
  $timeout = 10;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-Requested-With: XMLHttpRequest",
            "Accept: text/html, */*",
            "User-Agent: Mozilla/3.0 (compatible; easyhttp)",
            "Connection: Keep-Alive",
  ));
  $data = curl_exec($ch);
  $err = curl_errno($ch);
  $errmsg = curl_error($ch) ;
  curl_close($ch);
  if ($err == 0){
     if ($incorrect){ //24+9 //23+9
         $data = str_replace("\'", "'", $data);
         $num  = strpos ($data, '{');
         $data = substr($data, $num+6);
         $data = '{'.$data;
         $data = substr($data,0,-9);
         $data .= '}';
     }
     $a = (json_decode(trim($data), true));
// left for debug
/*switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - Ошибок нет';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Достигнута максимальная глубина стека';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Некорректные разряды или не совпадение режимов';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Некорректный управляющий символ';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Синтаксическая ошибка, не корректный JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Некорректные символы UTF-8, возможно неверная кодировка';
        break;
        default:
            echo ' - Неизвестная ошибка';
        break;
    }  */
    return $a;
  }   else{
      return array();
  }
}


?>
<script type="text/javascript" id="js">
   $(document).ready(function()
   {
      $("#stat6").tablesorter({headerTemplate : '<div style="padding: 0px; padding-right:12px;">{content}</div>{icon}',
    widgets : ['uitheme', 'zebra'],
    widthFixed : false,
    sortList : [[2,0]],
    theme : 'jui'});
      $('#id-<?=$_POST['key'];?>').click(function() {
         $("#stat6").trigger('applyWidgets');
         return false;
      });
   });
</script>
<div align="center">
<?php
if ($config['lang'] == 'ru') {
    $locale = get_json('http://cw.'.$config['gm_url'].'/static/wgcw/js/i18n/ru_earth_map.js');
    //http://cw.worldoftanks.ru/static/wgcw/js/i18n/ru_earth_map.js

    $nameofregion = array ( 1 =>  array( 'name' =>'Северная Европа',               'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/1/?ct=json' ),
                            2 =>  array( 'name' =>'Средиземноморье',              'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/2/?ct=json' ),
                            3 =>  array( 'name' =>'Западная Африка',               'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/3/?ct=json' ),
                            4 =>  array( 'name' =>'Восточная Африка',             'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/4/?ct=json' ),
                            5 =>  array( 'name' =>'Урал и Зауралье',                'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/5/?ct=json' ),
                            6 =>  array( 'name' =>'Сибирь и Дальний Восток', 'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/6/?ct=json' ),
                            7 =>  array( 'name' =>'Азия',                                    'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/7/?ct=json' ),
                            8 =>  array( 'name' =>'United States of America',                    'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/8/?ct=json' ),

                            10 => array( 'name' =>'Canada and Alaska',                           'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/10/?ct=json' ),
                            11 => array( 'name' =>'Южная Африка',                     'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/11/?ct=json' )
                          );
     $lang['periphery']='Сервер';
     $lang['owner']='Владелец';
}   else {
    $locale = get_json('http://cw.'.$config['gm_url'].'/static/wgcw/js/i18n/en_earth_map.js');
          //http://cw.worldoftanks.eu/static/wgcw/js/i18n/en_earth_map.js
          //http://cw.worldoftanks.com/static/wgcw/js/i18n/en_earth_map.js

    $nameofregion = array ( 1 =>  array( 'name' =>'Northern Europe',          'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/1/?ct=json' ),
                            2 =>  array( 'name' =>'Mediterranean',            'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/2/?ct=json' ),
                            3 =>  array( 'name' =>'West Africa',              'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/3/?ct=json' ),
                            4 =>  array( 'name' =>'East Africa',              'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/4/?ct=json' ),
                            5 =>  array( 'name' =>'Ural',                     'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/5/?ct=json' ),
                            6 =>  array( 'name' =>'Siberia and Far East',     'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/6/?ct=json' ),
                            7 =>  array( 'name' =>'Asia',                     'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/7/?ct=json' ),
                            8 =>  array( 'name' =>'United States of America', 'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/8/?ct=json' ),

                            10 => array( 'name' =>'Canada and Alaska',        'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/10/?ct=json' ),
                            11 => array( 'name' =>'South Africa',             'url' => 'http://cw.'.$config['gm_url'].'/clanwars/maps/provinces/regions/11/?ct=json' )
                          );
     $lang['periphery']='Periphery';
     $lang['owner']='Current owner';
}
$provinces = array (
   1  => array('YU_08', 'YU_09', 'IT_05', 'IT_04', 'RU_18', 'RU_19', 'IT_01', 'RU_14', 'RU_15', 'RU_16', 'RU_17', 'RU_10', 'RU_11', 'RU_12', 'RU_13', 'NL_01', 'IT_03', 'IT_02', 'LT_01', 'RO_03', 'YU_01', 'YU_02', 'YU_04', 'YU_05', 'YU_06', 'KZ_01', 'IT_08', 'UA_12', 'UA_11', 'UA_10', 'FI_05', 'HU_01', 'HU_03', 'HU_02', 'KZ_10', 'NO_05', 'NO_04', 'NO_06', 'NO_01', 'NO_03', 'NO_02', 'UK_06', 'UK_07', 'UK_04', 'UK_05', 'UK_02', 'UK_03', 'UK_01', 'UA_08', 'UA_09', 'UA_01', 'UA_02', 'UA_03', 'UA_04', 'EE_01', 'UA_06', 'UA_07', 'RO_01', 'CZ_02', 'CZ_01', 'RO_02', 'SK_01', 'BE_01', 'FR_11', 'FR_10', 'FR_13', 'FR_12', 'FR_15', 'FR_14', 'FR_17', 'FR_16', 'DE_01', 'DE_03', 'DE_02', 'DE_05', 'DE_04', 'DE_07', 'DE_06', 'DE_09', 'DE_08', 'MD_01', 'UA_05', 'SW_01', 'SW_02', 'SW_03', 'SW_04', 'SW_05', 'SW_06', 'SW_07', 'FR_02', 'FR_03', 'RU_47', 'RU_46', 'RU_45', 'RU_44', 'FR_06', 'FR_07', 'RU_41', 'FR_05', 'RU_43', 'FR_08', 'FR_09', 'RU_49', 'RU_42', 'DE_12', 'RU_24', 'DE_10', 'DE_11', 'RU_21', 'FR_04', 'RU_23', 'RU_22', 'RU_40', 'AT_03', 'AT_02', 'AT_01', 'CH_01', 'RU_50', 'RU_51', 'RU_53', 'RU_58', 'RU_48', 'RU_25', 'RU_34', 'RU_35', 'RU_32', 'RU_33', 'RU_30', 'RU_31', 'RU_27', 'RU_26', 'IT_07', 'RU_20', 'FI_03', 'FI_02', 'FI_01', 'DK_02', 'DK_01', 'FI_04', 'RU_03', 'RU_02', 'RU_01', 'RU_07', 'RU_06', 'RU_04', 'RU_29', 'RU_09', 'RU_08', 'RU_28', 'BY_06', 'BY_05', 'BY_04', 'BY_03', 'BY_02', 'BY_01', 'IS_01', 'PL_06', 'PL_07', 'PL_04', 'PL_05', 'PL_02', 'PL_03', 'PL_01', 'FR_01', 'PL_08', 'LV_01', 'LV_02' ),
   2  => array('YU_08', 'YU_09', 'IT_05', 'CY_01', 'IT_03', 'IT_02', 'AL_01', 'IT_06', 'SA_02', 'YU_01', 'YU_02', 'SA_01', 'YU_04', 'YU_05', 'YU_06', 'YU_07', 'IT_04', 'ES_11', 'DZ_20', 'DZ_21', 'DZ_26', 'ES_10', 'IT_01', 'JO_01', 'JO_02', 'RO_03', 'ES_05', 'SA_03', 'AM_01', 'IT_10', 'IT_11', 'DZ_15', 'IT_13', 'IT_14', 'ES_03', 'DZ_11', 'ES_01', 'ES_08', 'ES_09', 'TR_08', 'SY_01', 'IT_09', 'TR_01', 'TR_02', 'TR_03', 'TR_04', 'TR_05', 'TR_06', 'TR_07', 'UA_12', 'UA_11', 'UA_10', 'GR_05', 'GR_04', 'GR_03', 'GR_02', 'HU_03', 'HU_02', 'DZ_01', 'DZ_02', 'DZ_03', 'DZ_04', 'DZ_05', 'DZ_06', 'DZ_07', 'DZ_08', 'DZ_09', 'EG_11', 'EG_10', 'EG_15', 'EG_14', 'UA_08', 'UA_09', 'UA_01', 'UA_03', 'UA_04', 'UA_06', 'UA_07', 'RO_01', 'CZ_02', 'CZ_01', 'RO_02', 'SK_01', 'ES_06', 'DZ_25', 'SA_07', 'IQ_07', 'ES_02', 'EG_08', 'EG_09', 'ES_07', 'EG_02', 'EG_03', 'SA_04', 'EG_01', 'EG_06', 'EG_07', 'EG_04', 'EG_05', 'MR_04', 'LY_09', 'LY_08', 'MR_03', 'MR_02', 'LY_05', 'HU_01', 'LY_07', 'LY_06', 'LY_01', 'MA_02', 'LY_03', 'LY_02', 'FR_11', 'FR_10', 'FR_13', 'FR_12', 'FR_15', 'FR_14', 'FR_17', 'FR_16', 'SA_05', 'FR_18', 'MA_05', 'GE_01', 'MT_01', 'BG_01', 'BG_03', 'BG_02', 'LY_18', 'LY_16', 'LY_17', 'LY_14', 'LY_15', 'LY_12', 'LY_13', 'LY_10', 'LY_11', 'FR_02', 'FR_03', 'FR_01', 'FR_06', 'FR_07', 'FR_04', 'FR_05', 'FR_08', 'FR_09', 'DE_10', 'DE_11', 'RU_21', 'RU_20', 'RU_22', 'RU_40', 'AT_03', 'AT_02', 'IL_01', 'CH_01', 'MR_01', 'IQ_11', 'IQ_10', 'SA_08', 'LY_04', 'IR_01', 'RU_36', 'RU_37', 'MD_01', 'RU_35', 'DZ_16', 'IT_12', 'RU_38', 'RU_39', 'AZ_01', 'LB_01', 'DZ_14', 'GR_01', 'DZ_13', 'DZ_19', 'ES_04', 'DZ_12', 'IQ_08', 'IQ_09', 'SA_21', 'IQ_04', 'IQ_05', 'IQ_06', 'DZ_10', 'IQ_01', 'IQ_02', 'IQ_03', 'IT_07', 'IT_08', 'SA_22', 'RU_03', 'SA_18', 'RU_05', 'SA_11', 'SA_13', 'SA_12', 'SA_15', 'SA_14', 'ML_01', 'MA_01', 'IR_02', 'MA_03', 'MA_04', 'AT_01', 'DZ_18', 'SC_01', 'SY_02', 'TN_04', 'TN_01', 'TN_02', 'TN_03', 'ES_12', 'EH_04', 'EH_03', 'EH_02', 'EH_01' ),
   3  => array('ES_12', 'LY_11', 'DZ_22', 'DZ_23', 'DZ_20', 'DZ_21', 'DZ_26', 'DZ_24', 'DZ_25', 'NE_07', 'NE_06', 'NE_05', 'NE_04', 'NE_03', 'NE_02', 'NE_01', 'CQ_01', 'NE_09', 'NE_08', 'BF_02', 'BF_03', 'BF_01', 'BF_04', 'BF_05', 'CI_06', 'DZ_17', 'DZ_16', 'DZ_15', 'DZ_14', 'DZ_13', 'DZ_12', 'GH_05', 'GH_04', 'GH_01', 'DZ_19', 'DZ_18', 'NE_10', 'NE_11', 'NE_12', 'NE_13', 'SL_01', 'ML_04', 'SL_02', 'ML_14', 'ML_15', 'ML_16', 'ML_17', 'ML_10', 'ML_11', 'ML_12', 'ML_13', 'ML_18', 'ML_19', 'CF_03', 'DZ_05', 'CF_01', 'DZ_07', 'DZ_08', 'NG_01', 'EG_10', 'TG_01', 'EG_12', 'GW_01', 'CI_03', 'SD_06', 'SD_01', 'LY_13', 'SD_02', 'NG_10', 'ML_02', 'CG_01', 'CG_02', 'NG_11', 'CI_05', 'MR_14', 'EG_02', 'EG_01', 'EG_05', 'MR_05', 'MR_04', 'MR_07', 'MR_06', 'MR_01', 'MR_10', 'MR_03', 'MR_02', 'SD_16', 'LY_04', 'LY_07', 'SD_15', 'MR_09', 'MR_08', 'LY_03', 'LY_02', 'LY_16', 'LY_17', 'GN_03', 'GN_02', 'GN_01', 'LY_14', 'MA_05', 'TD_19', 'TD_18', 'LY_15', 'TD_11', 'LY_12', 'TD_13', 'TD_12', 'TD_15', 'TD_14', 'TD_17', 'TD_16', 'SD_24', 'SN_01', 'MR_19', 'TD_10', 'CF_02', 'MR_16', 'MR_17', 'SD_29', 'MR_15', 'MR_12', 'DZ_06', 'LY_18', 'MR_11', 'SD_23', 'SD_22', 'LY_19', 'GM_01', 'CM_02', 'CM_03', 'MR_18', 'CM_01', 'CD_01', 'CD_03', 'GA_01', 'TD_08', 'TD_09', 'TD_02', 'TD_03', 'TD_01', 'TD_06', 'TD_07', 'TD_04', 'TD_05', 'SD_30', 'GH_02', 'LY_09', 'LY_08', 'LY_22', 'LY_21', 'LY_20', 'CI_02', 'LY_05', 'CI_01', 'BJ_02', 'MR_13', 'CI_04', 'BJ_01', 'CD_02', 'LY_06', 'SD_10', 'TG_02', 'SD_11', 'SN_03', 'GH_03', 'SD_17', 'ML_09', 'ML_08', 'SN_02', 'ML_03', 'ML_21', 'ML_01', 'ML_07', 'ML_06', 'ML_05', 'ML_20', 'NG_09', 'NG_08', 'NG_05', 'NG_04', 'NG_07', 'NG_06', 'MA_04', 'NG_03', 'NG_02', 'LR_01', 'LR_02', 'TD_20', 'LY_10', 'EH_04', 'EH_03', 'EH_02', 'EH_01'),
   4  => array('SA_08', 'SD_32', 'SA_02', 'SA_03', 'SA_01', 'SA_07', 'SA_04', 'SA_05', 'ET_10', 'JO_01', 'JO_02', 'TD_20', 'DJ_01', 'QA_01', 'IR_05', 'IR_04', 'IR_09', 'IR_08', 'AE_01', 'ET_09', 'ET_08', 'ER_02', 'ET_03', 'ET_02', 'ET_01', 'ET_07', 'ET_06', 'ET_05', 'ET_04', 'IR_12', 'IR_10', 'IR_11', 'CF_03', 'CF_02', 'UG_02', 'UG_01', 'EG_11', 'EG_10', 'EG_13', 'EG_12', 'EG_15', 'EG_14', 'EG_17', 'EG_16', 'ET_14', 'ET_15', 'SO_02', 'SO_03', 'SD_09', 'SO_05', 'ET_12', 'ET_13', 'SD_05', 'SD_04', 'SD_07', 'SD_06', 'SD_01', 'SD_03', 'SD_02', 'SA_37', 'SA_36', 'SA_35', 'SA_33', 'SA_32', 'SA_31', 'SA_30', 'SA_39', 'SA_38', 'ER_01', 'OM_02', 'EG_08', 'EG_09', 'SO_06', 'EG_02', 'EG_03', 'EG_01', 'EG_06', 'EG_07', 'EG_04', 'EG_05', 'LY_09', 'LY_08', 'SD_18', 'SD_19', 'LY_05', 'SD_17', 'SD_14', 'SD_15', 'SD_12', 'SD_13', 'SD_10', 'SD_11', 'SA_20', 'SA_21', 'SA_22', 'SA_24', 'SA_25', 'SA_26', 'SA_27', 'SA_28', 'SA_29', 'LY_14', 'TD_19', 'LY_15', 'TD_11', 'TD_10', 'TD_12', 'TD_15', 'TD_16', 'OM_03', 'LY_10', 'SD_29', 'SD_28', 'LY_18', 'LY_19', 'SD_23', 'SD_22', 'SD_21', 'SD_20', 'SD_27', 'SD_26', 'SD_25', 'SD_24', 'CD_01', 'CD_03', 'CD_02', 'TD_08', 'TD_02', 'TD_03', 'TD_06', 'TD_07', 'TD_04', 'TD_05', 'SD_34', 'SD_35', 'SD_36', 'OM_01', 'SD_30', 'SD_31', 'OM_04', 'SD_33', 'IQ_11', 'IQ_10', 'LY_22', 'LY_21', 'LY_20', 'SD_16', 'LY_07', 'LY_06', 'KE_02', 'KE_03', 'KE_01', 'MN_01', 'ET_11', 'OM_05', 'MN_02', 'MN_05', 'IQ_09', 'MN_07', 'MN_06', 'MN_09', 'MN_08', 'IQ_06', 'SA_19', 'SA_18', 'SO_01', 'SA_11', 'SA_10', 'SA_13', 'SA_12', 'SA_15', 'SA_14', 'SA_16', 'IL_01', 'YE_01', 'IQ_08', 'SD_08', 'MN_12', 'MN_13', 'MN_10', 'MN_11', 'MN_16', 'MN_17', 'MN_14', 'MN_15', 'MN_18', 'SO_07', 'MN_04', 'MN_03', 'SO_08', 'SO_09', 'SO_04', 'YE_02', 'YE_03', 'YE_04'),
   5  => array('RU_12', 'RU_118', 'RU_119', 'RU_116', 'RU_117', 'RU_110', 'RU_111', 'RU_194', 'RU_88', 'RU_192', 'RU_193', 'RU_190', 'RU_191', 'RU_82', 'RU_80', 'RU_87', 'RU_198', 'RU_199', 'RU_69', 'RU_68', 'RU_61', 'RU_60', 'RU_63', 'RU_62', 'RU_64', 'RU_67', 'RU_90', 'RU_91', 'RU_94', 'RU_95', 'RU_109', 'RU_108', 'RU_92', 'RU_93', 'RU_105', 'RU_104', 'RU_107', 'RU_106', 'RU_101', 'RU_103', 'RU_102', 'RU_185', 'RU_184', 'RU_187', 'RU_186', 'RU_180', 'RU_183', 'RU_182', 'RU_189', 'RU_188', 'RU_78', 'RU_79', 'RU_73', 'RU_70', 'RU_76', 'RU_77', 'RU_74', 'RU_75', 'RU_127', 'RU_126', 'RU_125', 'RU_124', 'RU_123', 'RU_122', 'RU_121', 'RU_120', 'RU_151', 'RU_158', 'RU_159', 'RU_89', 'RU_47', 'RU_46', 'RU_45', 'RU_44', 'RU_43', 'RU_42', 'RU_41', 'RU_143', 'RU_142', 'RU_144', 'RU_53', 'RU_54', 'RU_55', 'RU_56', 'RU_57', 'RU_59', 'RU_34', 'RU_33', 'RU_178', 'RU_179', 'RU_174', 'RU_175', 'RU_176', 'RU_177', 'RU_170', 'RU_171', 'RU_172', 'RU_173', 'RU_07', 'RU_08', 'RU_169', 'RU_168', 'RU_163', 'RU_162', 'RU_161', 'RU_160', 'RU_167', 'RU_166', 'RU_165', 'RU_164', 'RU_286', 'RU_284', 'RU_282', 'RU_283', 'RU_203'),
   6  => array('RU_215', 'RU_214', 'RU_194', 'RU_195', 'RU_211', 'RU_210', 'RU_213', 'RU_191', 'RU_219', 'RU_218', 'RU_198', 'RU_199', 'RU_185', 'RU_184', 'RU_187', 'RU_186', 'RU_224', 'RU_225', 'RU_183', 'RU_227', 'RU_228', 'RU_229', 'RU_189', 'RU_188', 'RU_192', 'RU_220', 'RU_221', 'RU_222', 'RU_223', 'RU_239', 'RU_238', 'RU_237', 'RU_236', 'RU_235', 'RU_234', 'RU_233', 'RU_232', 'RU_231', 'RU_230', 'RU_226', 'RU_248', 'RU_249', 'RU_242', 'RU_243', 'RU_240', 'RU_241', 'RU_246', 'RU_247', 'RU_244', 'RU_245', 'RU_152', 'RU_153', 'RU_150', 'RU_151', 'RU_156', 'RU_157', 'RU_154', 'RU_155', 'RU_196', 'RU_197', 'RU_217', 'RU_259', 'RU_258', 'RU_216', 'RU_251', 'RU_250', 'RU_253', 'RU_252', 'RU_255', 'RU_254', 'RU_257', 'RU_193', 'RU_190', 'RU_212', 'RU_145', 'RU_144', 'RU_147', 'RU_149', 'RU_148', 'RU_264', 'RU_265', 'RU_266', 'RU_267', 'RU_260', 'RU_261', 'RU_262', 'RU_263', 'RU_268', 'RU_269', 'RU_256', 'RU_178', 'RU_179', 'RU_273', 'RU_272', 'RU_271', 'RU_270', 'RU_277', 'RU_276', 'RU_275', 'RU_274', 'RU_279', 'RU_278', 'RU_165', 'RU_285', 'RU_280', 'RU_281', 'RU_206', 'RU_207', 'RU_204', 'RU_205', 'RU_202', 'RU_203', 'RU_200', 'RU_201', 'RU_209'),
   7  => array('SA_08', 'RU_18', 'RU_19', 'SA_02', 'RU_15', 'RU_16', 'SA_01', 'SA_07', 'RU_12', 'UA_07', 'KG_01', 'JO_01', 'RU_114', 'RU_115', 'RU_112', 'RU_113', 'RU_110', 'RU_111', 'RU_89', 'RU_88', 'RU_192', 'RU_193', 'RU_190', 'RU_191', 'RU_83', 'AM_01', 'RU_81', 'RU_80', 'RU_86', 'RU_85', 'RU_84', 'RU_69', 'RU_68', 'IR_03', 'IR_02', 'IR_05', 'IR_04', 'IR_07', 'IR_06', 'RU_61', 'RU_60', 'RU_63', 'RU_62', 'RU_65', 'RU_64', 'RU_66', 'TR_08', 'KZ_09', 'SY_01', 'SA_04', 'KZ_03', 'KZ_02', 'KZ_01', 'KZ_07', 'KZ_06', 'KZ_05', 'TR_07', 'RU_94', 'RU_95', 'RU_96', 'RU_97', 'RU_90', 'RU_91', 'RU_92', 'RU_93', 'RU_105', 'RU_104', 'RU_107', 'RU_106', 'RU_98', 'RU_99', 'RU_103', 'RU_184', 'RU_151', 'RU_180', 'RU_183', 'RU_182', 'RU_189', 'RU_188', 'KZ_14', 'KZ_15', 'RU_78', 'KZ_17', 'KZ_10', 'KZ_11', 'KZ_12', 'KZ_13', 'RU_72', 'AF_07', 'RU_70', 'RU_71', 'RU_76', 'RU_77', 'RU_74', 'RU_75', 'RU_199', 'RU_138', 'RU_139', 'AE_01', 'RU_130', 'RU_131', 'RU_132', 'RU_133', 'RU_134', 'RU_135', 'RU_136', 'RU_137', 'SA_31', 'RU_08', 'SY_02', 'SA_14', 'RU_127', 'RU_126', 'RU_125', 'RU_124', 'RU_123', 'RU_122', 'RU_121', 'UZ_02', 'RU_129', 'RU_128', 'TJ_02', 'RU_109', 'SA_20', 'SA_21', 'SA_22', 'AF_06', 'AF_01', 'AF_03', 'AF_02', 'SA_29', 'RU_108', 'IR_12', 'GE_01', 'RU_37', 'TM_01', 'TM_03', 'TM_02', 'TM_05', 'KZ_16', 'RU_152', 'RU_153', 'RU_150', 'RU_79', 'RU_156', 'RU_157', 'RU_154', 'RU_155', 'PK_01', 'RU_194', 'RU_47', 'RU_46', 'RU_45', 'RU_44', 'RU_43', 'RU_42', 'RU_41', 'RU_40', 'RU_49', 'QA_01', 'RU_27', 'RU_21', 'RU_20', 'IR_10', 'RU_22', 'KZ_18', 'RU_29', 'RU_28', 'KZ_19', 'UZ_05', 'UZ_04', 'RU_143', 'RU_142', 'RU_145', 'RU_144', 'RU_147', 'RU_82', 'RU_149', 'RU_148', 'IQ_11', 'IQ_10', 'IR_11', 'MN_15', 'RU_50', 'RU_51', 'RU_52', 'RU_53', 'RU_54', 'RU_55', 'RU_56', 'RU_57', 'RU_58', 'RU_59', 'RU_48', 'RU_36', 'IR_01', 'RU_34', 'RU_35', 'RU_32', 'RU_33', 'RU_30', 'RU_31', 'RU_38', 'RU_39', 'AZ_01', 'RU_174', 'RU_175', 'PK_03', 'PK_02', 'RU_176', 'RU_177', 'PK_06', 'RU_172', 'RU_173', 'MN_01', 'AF_05', 'RU_146', 'MN_02', 'IQ_08', 'IQ_09', 'MN_07', 'AF_04', 'IQ_04', 'IQ_05', 'IQ_06', 'IQ_07', 'IQ_01', 'IQ_02', 'IQ_03', 'IR_09', 'MN_13', 'PK_05', 'IR_08', 'PK_04', 'TJ_01', 'SA_19', 'SA_18', 'RU_01', 'RU_07', 'RU_05', 'SA_11', 'SA_13', 'SA_12', 'SA_15', 'MN_03', 'MN_05', 'JO_02', 'TM_04', 'SC_01', 'MN_04', 'MN_12', 'RU_141', 'MN_10', 'MN_11', 'MN_16', 'MN_17', 'MN_14', 'RU_140', 'KZ_04', 'MN_18', 'MN_06', 'KG_02', 'UZ_03', 'RU_100', 'MN_09', 'KZ_08', 'RU_03', 'RU_203', 'MN_08', 'UZ_01', 'RU_198'),
   8  => array('US_35', 'US_34', 'US_48', 'US_49', 'US_42', 'US_43', 'US_40', 'US_41', 'US_46', 'US_47', 'US_44', 'US_45', 'US_88', 'US_89', 'US_53', 'US_86', 'US_87', 'US_84', 'US_77', 'US_76', 'US_75', 'US_74', 'US_73', 'US_72', 'US_71', 'US_70', 'US_82', 'US_79', 'US_78', 'US_80', 'US_81', 'US_95', 'CA_55', 'US_24', 'CA_54', 'US_52', 'US_83', 'CA_91', 'CA_90', 'CA_92', 'CA_95', 'CA_94', 'CA_51', 'US_94', 'US_60', 'US_61', 'US_62', 'US_63', 'US_64', 'US_65', 'US_66', 'US_67', 'US_68', 'US_69', 'US_119', 'US_118', 'CA_39', 'CA_38', 'US_124', 'US_125', 'CA_46', 'CA_26', 'US_127', 'CA_82', 'CA_83', 'CA_80', 'CA_81', 'CA_86', 'CA_87', 'CA_84', 'CA_85', 'CA_88', 'CA_89', 'US_120', 'US_121', 'US_122', 'US_123', 'US_99', 'US_98', 'US_126', 'CA_27', 'US_128', 'US_129', 'US_97', 'US_96', 'US_91', 'US_90', 'US_93', 'US_92', 'US_15', 'US_14', 'US_17', 'US_16', 'US_11', 'US_10', 'US_13', 'US_12', 'US_114', 'US_19', 'US_18', 'US_25', 'CA_37', 'US_139', 'US_138', 'US_137', 'US_136', 'US_135', 'US_85', 'US_133', 'US_117', 'US_131', 'US_130', 'US_06', 'US_07', 'CA_57', 'CA_56', 'US_116', 'CA_50', 'CA_53', 'CA_52', 'US_111', 'CA_59', 'CA_58', 'US_08', 'US_09', 'CA_67', 'US_113', 'US_134', 'US_55', 'US_112', 'US_54', 'US_57', 'US_56', 'US_50', 'US_142', 'US_143', 'US_140', 'US_141', 'US_144', 'US_110', 'US_32', 'US_31', 'US_30', 'US_37', 'US_36', 'CA_40', 'CA_41', 'US_39', 'US_38', 'CA_48', 'CA_49', 'US_132', 'US_115', 'US_28', 'US_29', 'US_106', 'US_105', 'US_107', 'US_104', 'CA_73', 'CA_72', 'CA_71', 'CA_70', 'CA_77', 'CA_76', 'CA_75', 'CA_74', 'GL_27', 'GL_26', 'CA_79', 'US_27', 'US_20', 'US_26', 'US_22', 'US_23', 'US_102', 'CA_78', 'US_100', 'US_101', 'US_21', 'US_103', 'US_59', 'US_58', 'CA_68', 'CA_69', 'CA_64', 'CA_65', 'CA_66', 'US_109', 'CA_60', 'CA_61', 'CA_62', 'CA_63', 'US_33', 'CA_47', 'CA_44', 'CA_45', 'US_51', 'US_108'),
   9  => array('no_data1','no_data2' ),
   10 => array('CA_40', 'GL_15', 'CA_19', 'CA_18', 'CA_11', 'CA_10', 'CA_13', 'CA_12', 'CA_15', 'CA_14', 'CA_17', 'CA_16', 'GL_41', 'GL_40', 'GL_43', 'GL_12', 'CA_08', 'CA_09', 'CA_02', 'CA_03', 'CA_01', 'CA_06', 'CA_07', 'CA_04', 'CA_05', 'GL_01', 'US_04', 'CA_93', 'US_05', 'CA_51', 'CA_50', 'CA_37', 'CA_36', 'CA_35', 'CA_34', 'CA_33', 'CA_32', 'CA_31', 'CA_30', 'CA_52', 'CA_39', 'CA_38', 'CA_82', 'CA_20', 'CA_21', 'CA_22', 'CA_23', 'CA_24', 'CA_25', 'CA_26', 'CA_27', 'CA_28', 'CA_29', 'US_10', 'US_03', 'GL_05', 'US_06', 'US_07', 'GL_03', 'GL_02', 'US_02', 'GL_04', 'GL_07', 'US_01', 'GL_09', 'GL_08', 'US_08', 'US_09', 'GL_06', 'GL_13', 'GL_10', 'GL_11', 'GL_16', 'GL_17', 'GL_14', 'CA_41', 'GL_18', 'GL_19', 'CA_49', 'GL_29', 'GL_28', 'GL_27', 'GL_26', 'GL_25', 'GL_24', 'GL_23', 'GL_22', 'GL_21', 'GL_20', 'GL_38', 'GL_39', 'GL_30', 'GL_31', 'GL_32', 'GL_33', 'GL_34', 'GL_35', 'GL_36', 'GL_37', 'CA_46', 'CA_44', 'CA_45', 'CA_42', 'CA_43'),
   11 => array('NA_08', 'NA_03', 'NA_02', 'NA_01', 'NA_07', 'NA_06', 'NA_05', 'NA_04', 'BW_01', 'BW_03', 'BW_02', 'BW_05', 'BW_04', 'AO_06', 'AO_07', 'AO_04', 'AO_05', 'AO_02', 'AO_03', 'AO_01', 'AO_08', 'BI_01', 'RW_01', 'CF_03', 'CF_02', 'CF_01', 'UG_02', 'UG_01', 'ZW_01', 'ZW_03', 'ZW_02', 'ZA_20', 'SO_03', 'SO_04', 'SO_05', 'SO_06', 'SO_07', 'SO_08', 'SO_09', 'CG_01', 'CG_02', 'CG_03', 'MG_02', 'MG_03', 'MG_01', 'MG_06', 'MG_04', 'MG_05', 'CQ_01', 'ZA_07', 'ZA_06', 'ZA_05', 'ZA_04', 'ZA_03', 'ZA_02', 'ZA_01', 'ZA_09', 'ZA_08', 'MZ_09', 'MZ_08', 'MZ_05', 'MZ_04', 'MZ_07', 'MZ_06', 'MZ_01', 'CM_03', 'MZ_03', 'MZ_02', 'CD_09', 'CD_08', 'CD_05', 'CD_04', 'CD_07', 'CD_06', 'GA_02', 'CD_03', 'GA_01', 'ZA_10', 'ZA_11', 'ZA_12', 'ZA_13', 'ZA_14', 'ZA_15', 'ZA_16', 'ZA_17', 'ZA_18', 'ZA_19', 'SD_34', 'SD_36', 'SD_30', 'SD_33', 'MW_02', 'MW_01', 'CD_01', 'CD_02', 'CD_12', 'CD_13', 'CD_10', 'CD_11', 'KE_04', 'KE_02', 'KE_03', 'KE_01', 'TZ_01', 'TZ_02', 'TZ_03', 'TZ_04', 'TZ_05', 'TZ_06', 'TZ_07', 'ZM_03', 'ZM_02', 'ZM_01', 'ZM_07', 'ZM_06', 'ZM_05', 'ZM_04', 'ET_10', 'ET_11')
);
if ($wg_valid2 == true){
    if (isset($battel['request_data'])){
        $to_load = $extend = array();
        foreach($battel['request_data']['items'] as $val){
           for ($i=1;$i<=11;$i++) {
                if (in_array($val['provinces'][0]['id'], $provinces[$i]))  {$to_load[$i]= $i; }
           }
        }
        if (!empty($to_load)) {
           foreach ($to_load as $namepr => $id) {
              $extend[$id] = get_json($nameofregion[$id]['url'], false);
           }
           foreach ($extend as $id ) {
              foreach ($id ['provinces'] as $name =>$val) {
                 $ext[$name] = $val;
              }
           }
           unset($extend);
        } ?>
   <table id="stat6" cellspacing="1" cellpadding="2" width="100%" class="table-id-<?=$_POST['key'];?>">
        <thead> 
            <tr>
                <th width="40"><?=$lang['type']; ?></th>
                <th><?=$lang['periphery']; ?></th>
                <th><?=$lang['time']; ?></th>
                <th><?=$lang['prime_time']; ?></th>
                <th><?=$lang['map']; ?></th>
                <th><?=$lang['income']; ?></th>
                <th><?=$lang['owner']; ?></th>
                <th><?=$lang['owned']; ?></th>
                <th><?=$lang['province']; ?></th>
            </tr> 
        </thead> 
        <tbody>

<?    foreach($battel['request_data']['items'] as $id => $val){
         if (strlen($val['time']) > 1){
            date_default_timezone_set('UTC');
            $date = date('H:i',($val['time'] + ($time_diff)*60*60));
         }  else {
            $date = '--:--';
         }
         if ($val['type'] == 'landing'){
             $type = '<img src="./images/landing.png">';
         }   elseif($val['type'] == 'for_province'){
             $type = '<img src="./images/attacked.png">';
         }   elseif($val['type'] == 'meeting_engagement'){
             $type = '<img src="./images/combats_running.png">';
         }
         ?>
            <tr>
                <td align="center"><?=$type; ?></td>
                <td align="center"><?php if (isset ($ext)) {
                                             echo $ext[$val['provinces'][0]['id']]['periphery'];
                                         }   else {
                                             echo 'Н/Д';
                                         }   ?></td>
                <td><?=$date; ?></td>
                <td><?php if (isset ($ext)) {
                              echo date('H:i',$ext[$val['provinces'][0]['id']]['prime_time']+($time_diff)*60*60);
                          }   else {
                              echo 'Н/Д';
                          }?></td>
                <td><?php if (isset ($ext) and isset($locale)) {
                              echo $locale[$ext[$val['provinces'][0]['id']]['mapId']];
                          }   else {
                              echo 'Н/Д';
                          }?></td>
                <td align="right"><?php if (isset ($ext)) {
                                            echo $ext[$val['provinces'][0]['id']]['revenue'].'<img src="./images/currency-gold.png">';
                                         }  else {
                                            echo 'Н/Д';
                                         }  ?></td>
                <td><?php if (isset ($ext)) {
                              echo '<a href="http://'.$config['gm_url'].'/community/clans/'.$ext[$val['provinces'][0]['id']]['clanId'].'/" target="_blank">';
                              echo '['.$ext[$val['provinces'][0]['id']]['clan'].']</a>';
                          }   else {
                              echo 'Н/Д';
                          }?></td>
                <td align="right"><?php if (isset ($ext)) {
                                            echo number_format((now()-$ext[$val['provinces'][0]['id']]['captured_at'])/(60*60*24),0).' '.$lang['days'];;
                                         }  else {
                                            echo 'Н/Д';
                                         }  ?></td>
                <td><?php if (isset ($ext) and isset($locale)) {
                              echo '<a href="'.$config['clan_link'].'maps/?province='.$val['provinces'][0]['id'].'" target="_blank">';
                              echo $locale['PROVINCE_NAME_'.$val['provinces'][0]['id']];
                          }   else {
                              echo '<a href="'.$config['clan_link'].'maps/?province='.$val['provinces'][0]['id'].'" target="_blank">'.$val['provinces'][0]['name'];
                          }?></a></td>
            </tr>
<?php }; //foreach
      if ((count($battel['request_data']['items']) == 0 ) || !(isset($battel['request_data']))) {  ?>
            <tr>
                <td colspan="5" align="Center"><?=$lang['no_war']; ?></td>
            </tr>
<?php }
    }//isset battel ?>
        </tbody>
    </table>
<? } else { //wgvalid ?>
<div class='ui-state-highlight ui-widget-content'><?=$lang['error_1'];?></div>
<?php }; ?>
</div>