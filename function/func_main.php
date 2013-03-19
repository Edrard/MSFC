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
    function prepare_stat() {
        global $db;

        $sql = "SELECT id,tank,nation,title FROM `tanks`;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $current_tmp = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        $current = array();
        $tmp = array();
        foreach($current_tmp as $val){
            $current[$val['id']] = $val['title'].'_'.$val['nation'];
        }
        //Получаем из бд список таблиц col_tank_* и col_rating_tank_*, составляем массив наций для которых они существуют.

        $col_rating_tank = array();
        $col_tank = array();

        $sql = "show tables like 'col_tank_%';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $tmp = $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        foreach($tmp as $t) {
            $temp = explode('_',$t['0']);
            $col_tank[] = end($temp);
        }

        $t = array();
        $t['current'] = $current;
        $t['col_tank'] = $col_tank;
        unset($col_tank,$current,$current_tmp,$tmp);

        return $t;
    }

    function insert_stat($data,$roster,$config,$transit = array('current' => array(),'col_tank' => array())){

        global $db;
        if(count($data['data']) > 0){
            $current = $transit['current'];
            $col_tank = $transit['col_tank'];

            if(isset($data['data']['vehicles'])){
                $tsql = '';
                $sql = '';
                foreach($data['data']['vehicles'] as $val){
                    if(!in_array($val['name'].'_'.$val['nation'],$current)){

                        $tank = array(
                            'tank' => trim($val['localized_name']),
                            'nation' => $val['nation'],
                            'type' => $val['class'],
                            'lvl' => $val['level'],
                            'link' => $val['image_url'],
                            'title' => $val['name'],
                        );
                        $sqlt = "INSERT INTO `tanks` (".(implode(",",array_keys($tank))).") VALUES ('".(implode("','",$tank))."');";
                        $q = $db->prepare($sqlt);
                        if ($q->execute() !== TRUE) {
                            die(show_message($q->errorInfo(),__line__,__file__,$sqlt));
                        }
                        unset($q);
                        $id = $db->lastInsertId(); //шикарная функция на самом деле, возвращает значение автоинкремент поля из последнего запроса
                        $current[$id] = $val['name'].'_'.$val['nation']; //добавляем танк
                        add_tanks_preset($tank); //проверяем необходимо ли добавлять пересет для таба наличия техники

                        if(!in_array($val['nation'],$col_tank)) {
                            $sql .= "CREATE TABLE IF NOT EXISTS `col_tank_".$val['nation']."` (
                            `account_id` INT(12),
                            `up` INT( 12 ) NOT NULL,
                            KEY `up` (`up`) ) ENGINE=MYISAM;";
                            $sql .= "CREATE TABLE IF NOT EXISTS `col_rating_tank_".$val['nation']."` (
                            `account_id` INT(12),
                            `up` INT( 12 ) NOT NULL,
                            KEY `up` (`up`) ) ENGINE=MYISAM;";
                            $col_tank[] = $val['nation']; //добавляем в массив, дабы не создавать еще раз.
                        }

                        $tsql .= "ALTER TABLE `col_tank_".$val['nation']."`
                        ADD `".$id."_w` INT( 12 ) NOT NULL,
                        ADD `".$id."_t` INT( 12 ) NOT NULL;";
                        $tsql .= "ALTER TABLE `col_rating_tank_".$val['nation']."`
                        ADD `".$id."_sp` INT( 12 ) NOT NULL,
                        ADD `".$id."_dd` INT( 12 ) NOT NULL,
                        ADD `".$id."_sb` INT( 12 ) NOT NULL,
                        ADD `".$id."_fr` INT( 12 ) NOT NULL;";
                    }
                }
                /* Запросы к БД вынесенные за цикл */
                if($sql != '') {
                    $q = $db->prepare($sql);
                    if ($q->execute() !== TRUE) { die(show_message($q->errorInfo(),__line__,__file__,$sql)); }
                }
                if($tsql != '') {
                    $q = $db->prepare($tsql);
                    if ($q->execute() !== TRUE) { die(show_message($q->errorInfo(),__line__,__file__,$tsql)); }
                }
            }
            unset($transit); $transit = array('current' => array(),'col_tank' => array());
            $transit['current'] = $current;
            $transit['col_tank'] = $col_tank;
        }
        return $transit;
    }


    function pars_data2($result,$fname,$stat_config,$trans,$roster)
    {
        //Даты
        $new['data']['name'] = $roster['account_name']; 
        $new['data']['account_id'] = $roster['account_id'];

        $new['date']['reg'] = $trans['reg'].' '.date('d.m.Y',$result['data']['created_at']);
        $new['date']['reg_num'] = $result['data']['created_at'];
        $new['date']['local'] = $trans['dateof'].' '.date('d.m.Y',$result['data']['updated_at']);
        $new['date']['local_num'] = $result['data']['updated_at'];
        //Общие результаты
        $new['overall'][$trans['games_p']] = $result['data']['summary']['battles_count'];
        $new['overall'][$trans['victories']] = $result['data']['summary']['wins'];
        $new['overall'][$trans['defeats']] = $result['data']['summary']['losses'];
        $new['overall'][$trans['battles_s']] = $result['data']['summary']['survived_battles'];


        //Боевая эффективность
        $new['perform'][$trans['destroyed']] = $result['data']['battles']['frags'];
        $new['perform'][$trans['spotted']] = $result['data']['battles']['spotted'];
        $new['perform'][$trans['hit_ratio']] = $result['data']['battles']['hits_percents'];
        $new['perform'][$trans['damage']] = $result['data']['battles']['damage_dealt'];
        $new['perform'][$trans['capture']] = $result['data']['battles']['capture_points'];
        $new['perform'][$trans['defend']] = $result['data']['battles']['dropped_capture_points'];

        //Боевой опыт
        $new['exp'][$trans['total_exp']] = $result['data']['experience']['xp'];
        $new['exp'][$trans['exp_battle']] = $result['data']['experience']['battle_avg_xp'];
        $new['exp'][$trans['exp_max']] = $result['data']['experience']['max_xp'];


        //Рейтинг
        $new['rating'][$trans['gr']]['type'] = 'GR';
        $new['rating'][$trans['gr']]['link'] = $stat_config['rating_link'].'gr.png';
        $new['rating'][$trans['gr']]['name'] = $trans['gr'];
        $new['rating'][$trans['gr']]['value'] = $result['data']['ratings']['integrated_rating']['value'];
        $new['rating'][$trans['gr']]['place'] = $result['data']['ratings']['integrated_rating']['place'];

        $new['rating'][$trans['wb']]['type'] = 'W/B';
        $new['rating'][$trans['wb']]['link'] = $stat_config['rating_link'].'wb.png';
        $new['rating'][$trans['wb']]['name'] = $trans['wb'];
        $new['rating'][$trans['wb']]['value'] = $result['data']['ratings']['battle_avg_performance']['value'].'%';
        $new['rating'][$trans['wb']]['place'] = $result['data']['ratings']['battle_avg_performance']['place'];

        $new['rating'][$trans['eb']]['type'] = 'E/B';
        $new['rating'][$trans['eb']]['link'] = $stat_config['rating_link'].'eb.png';
        $new['rating'][$trans['eb']]['name'] = $trans['eb'];
        $new['rating'][$trans['eb']]['value'] = $result['data']['ratings']['battle_avg_xp']['value'];
        $new['rating'][$trans['eb']]['place'] = $result['data']['ratings']['battle_avg_xp']['place'];

        $new['rating'][$trans['win']]['type'] = 'WIN';
        $new['rating'][$trans['win']]['link'] = $stat_config['rating_link'].'win.png';
        $new['rating'][$trans['win']]['name'] = $trans['win'];
        $new['rating'][$trans['win']]['value'] = $result['data']['ratings']['battle_wins']['value'];
        $new['rating'][$trans['win']]['place'] = $result['data']['ratings']['battle_wins']['place'];

        $new['rating'][$trans['gpl']]['type'] = 'GPL';
        $new['rating'][$trans['gpl']]['link'] = $stat_config['rating_link'].'gpl.png';
        $new['rating'][$trans['gpl']]['name'] = $trans['gpl'];
        $new['rating'][$trans['gpl']]['value'] = $result['data']['ratings']['battles']['value'];
        $new['rating'][$trans['gpl']]['place'] = $result['data']['ratings']['battles']['place'];

        $new['rating'][$trans['cpt']]['type'] = 'CPT';
        $new['rating'][$trans['cpt']]['link'] = $stat_config['rating_link'].'cpt.png';
        $new['rating'][$trans['cpt']]['name'] = $trans['cpt'];
        $new['rating'][$trans['cpt']]['value'] = $result['data']['ratings']['ctf_points']['value'];
        $new['rating'][$trans['cpt']]['place'] = $result['data']['ratings']['ctf_points']['place'];

        $new['rating'][$trans['dmg']]['type'] = 'DMG';
        $new['rating'][$trans['dmg']]['link'] = $stat_config['rating_link'].'dmg.png';
        $new['rating'][$trans['dmg']]['name'] = $trans['dmg'];
        $new['rating'][$trans['dmg']]['value'] = $result['data']['ratings']['damage_dealt']['value'];
        $new['rating'][$trans['dmg']]['place'] = $result['data']['ratings']['damage_dealt']['place'];

        $new['rating'][$trans['dpt']]['type'] = 'DPT';
        $new['rating'][$trans['dpt']]['link'] = $stat_config['rating_link'].'dpt.png';
        $new['rating'][$trans['dpt']]['name'] = $trans['dpt'];
        $new['rating'][$trans['dpt']]['value'] = $result['data']['ratings']['dropped_ctf_points']['value'];
        $new['rating'][$trans['dpt']]['place'] = $result['data']['ratings']['dropped_ctf_points']['place'];

        $new['rating'][$trans['frg']]['type'] = 'FRG';
        $new['rating'][$trans['frg']]['link'] = $stat_config['rating_link'].'frg.png';
        $new['rating'][$trans['frg']]['name'] = $trans['frg'];
        $new['rating'][$trans['frg']]['value'] = $result['data']['ratings']['frags']['value'];
        $new['rating'][$trans['frg']]['place'] = $result['data']['ratings']['frags']['place'];

        $new['rating'][$trans['spt']]['type'] = 'SPT';
        $new['rating'][$trans['spt']]['link'] = $stat_config['rating_link'].'spt.png';
        $new['rating'][$trans['spt']]['name'] = $trans['spt'];
        $new['rating'][$trans['spt']]['value'] = $result['data']['ratings']['spotted']['value'];
        $new['rating'][$trans['spt']]['place'] = $result['data']['ratings']['spotted']['place'];

        $new['rating'][$trans['exp']]['type'] = 'EXP';
        $new['rating'][$trans['exp']]['link'] = $stat_config['rating_link'].'exp.png';
        $new['rating'][$trans['exp']]['name'] = $trans['exp'];
        $new['rating'][$trans['exp']]['value'] = $result['data']['ratings']['xp']['value'];
        $new['rating'][$trans['exp']]['place'] = $result['data']['ratings']['xp']['place'];


        foreach($result['data']['vehicles'] as $veh){
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['link'] = 'http://'.$stat_config['gm_url'].$veh['image_url'];
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['lvl'] = $veh['level'];
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['type'] = $veh['localized_name'];
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['name'] = $veh['name'];
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['total'] = $veh['battle_count'];
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['win'] = $veh['win_count'];
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['nation'] = $veh['nation'];
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['class'] = $veh['class'];  
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['spotted'] = (int) str_replace(' ','',$veh['spotted']);
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['damageDealt'] = (int) str_replace(' ','',$veh['damageDealt']);
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['survivedBattles'] = (int) str_replace(' ','',$veh['survivedBattles']);
            $new['tank'][$veh['level']][$veh['class']][$veh['localized_name']]['frags'] = (int) str_replace(' ','',$veh['frags']);     
        }

        foreach($result['data']['achievements'] as $name => $medal){
            if($name == 'maxDiehardSeries'){
                $medn['diehard']['max'] = $medal;
                $medn['diehard']['max_name'] = 'maxDiehardSeries';    
            }elseif($name == 'maxInvincibleSeries'){
                $medn['invincible']['max'] = $medal;
                $medn['invincible']['max_name'] = 'maxInvincibleSeries';    
            }elseif($name == 'maxPiercingSeries'){
                $medn['armorPiercer']['max'] = $medal;
                $medn['armorPiercer']['max_name'] = 'maxPiercingSeries';    
            }elseif($name == 'maxKillingSeries'){
                $medn['handOfDeath']['max'] = $medal; 
                $medn['handOfDeath']['max_name'] = 'maxKillingSeries';   
            }elseif($name == 'maxSniperSeries'){
                $medn['titleSniper']['max'] = $medal; 
                $medn['titleSniper']['max_name'] = 'maxSniperSeries';   
            }elseif($name == 'tankExperts' && is_array($medal)){
                foreach($medal as $spname => $spmed){
                    $medn[$name.'_'.$spname]['value'] = (int)$spmed;
                }
            }elseif($name == 'mechanicEngineers' && is_array($medal)){
                foreach($medal as $spname => $spmed){
                    $medn[$name.'_'.$spname]['value'] = (int)$spmed;
                }
            }else{
                $medn[$name]['value'] = $medal;
            }
        }

        foreach(array_keys($medn) as $name){  
            if(isset($trans['medal_'.$name])){
                $medn[$name]['title'] = $trans['medal_'.$name];
            }     
        }

        $tename = 'tankExperts';
        $mename = 'mechanicEngineers';

        $medn[$tename.'_usa']['img'] = 'images/medals/TankExperts_usa.png';
        $medn[$tename.'_france']['img'] = 'images/medals/TankExperts_france.png';
        $medn[$tename.'_ussr']['img'] = 'images/medals/TankExperts_ussr.png';
        $medn[$tename.'_china']['img'] = 'images/medals/TankExperts_china.png';
        $medn[$tename.'_uk']['img'] = 'images/medals/TankExperts_uk.png';
        $medn[$tename.'_germany']['img'] = 'images/medals/TankExperts_germany.png';

        $medn[$mename.'_usa']['img'] = 'images/medals/MechanicEngineers_usa.png';
        $medn[$mename.'_france']['img'] = 'images/medals/MechanicEngineers_france.png';
        $medn[$mename.'_ussr']['img'] = 'images/medals/MechanicEngineers_ussr.png';
        $medn[$mename.'_china']['img'] = 'images/medals/MechanicEngineers_china.png';
        $medn[$mename.'_uk']['img'] = 'images/medals/MechanicEngineers_uk.png';
        $medn[$mename.'_germany']['img'] = 'images/medals/MechanicEngineers_germany.png';

        $medn['medalCarius']['img'] = 'images/medals/MedalCarius'.$medn['medalCarius']['value'].'.png';
        $medn['medalHalonen']['img'] = 'images/medals/MedalHalonen.png';
        $medn['invader']['img'] = 'images/medals/Invader.png';
        $medn['medalFadin']['img'] = 'images/medals/MedalFadin.png';
        $medn['armorPiercer']['img'] = 'images/medals/ArmorPiercer.png';
        $medn['medalEkins']['img'] = 'images/medals/MedalEkins'.$medn['medalEkins']['value'].'.png';
        $medn['mousebane']['img'] = 'images/medals/Mousebane.png';
        $medn['medalKay']['img'] = 'images/medals/MedalKay'.$medn['medalKay']['value'].'.png';
        $medn['defender']['img'] = 'images/medals/Defender.png';
        $medn['medalLeClerc']['img'] = 'images/medals/MedalLeClerc'.$medn['medalLeClerc']['value'].'.png';
        $medn['supporter']['img'] = 'images/medals/Supporter.png';
        $medn['steelwall']['img'] = 'images/medals/Steelwall.png';
        $medn['medalAbrams']['img'] = 'images/medals/MedalAbrams'.$medn['medalAbrams']['value'].'.png';
        $medn['medalPoppel']['img'] = 'images/medals/MedalPoppel'.$medn['medalPoppel']['value'].'.png';
        $medn['medalOrlik']['img'] = 'images/medals/MedalOrlik.png';
        $medn['handOfDeath']['img'] = 'images/medals/HandOfDeath.png';
        $medn['sniper']['img'] = 'images/medals/Sniper.png';
        $medn['warrior']['img'] = 'images/medals/Warrior.png';
        $medn['titleSniper']['img'] = 'images/medals/TitleSniper.png';
        $medn['medalWittmann']['img'] = 'images/medals/MedalBoelter.png';
        $medn['medalBoelter']['img'] = 'images/medals/MedalBoelter.png';
        $medn['medalBurda']['img'] = 'images/medals/MedalBurda.png';
        $medn['scout']['img'] = 'images/medals/Scout.png';
        $medn['beasthunter']['img'] = 'images/medals/Beasthunter.png';
        $medn['kamikaze']['img'] = 'images/medals/Kamikaze.png';
        $medn['raider']['img'] = 'images/medals/Raider.png';
        $medn['medalOskin']['img'] = 'images/medals/MedalOskin.png';
        $medn['medalBillotte']['img'] = 'images/medals/MedalBillotte.png';
        $medn['medalLavrinenko']['img'] = 'images/medals/MedalLavrinenko'.$medn['medalLavrinenko']['value'].'.png';
        $medn['medalKolobanov']['img'] = 'images/medals/MedalKolobanov.png';
        $medn['invincible']['img'] = 'images/medals/Invincible.png';
        $medn['lumberjack']['img'] = 'images/medals/Invincible.png';
        $medn['tankExpert']['img'] = 'images/medals/TankExpert.png';
        $medn['diehard']['img'] = 'images/medals/Diehard.png';
        $medn['medalKnispel']['img'] = 'images/medals/MedalKnispel'.$medn['medalKnispel']['value'].'.png'; 
        $medn['sinai']['img'] = 'images/medals/Sinai.png';

        $medn['evileye']['img'] = 'images/medals/Evileye.png';
        $medn['medalDeLanglade']['img'] = 'images/medals/MedalDeLanglade.png';
        $medn['medalTamadaYoshio']['img'] = 'images/medals/MedalTamadaYoshio.png';
        $medn['medalNikolas']['img'] = 'images/medals/MedalNikolas.png';
        $medn['medalLehvaslaiho']['img'] = 'images/medals/MedalLehvaslaiho.png';
        $medn['medalDumitru']['img'] = 'images/medals/MedalDumitru.png';
        $medn['medalPascucci']['img'] = 'images/medals/MedalPascucci.png';
        $medn['medalLafayettePool']['img'] = 'images/medals/MedalLafayettePool.png';
        $medn['medalRadleyWalters']['img'] = 'images/medals/MedalRadleyWalters.png';
        $medn['medalTarczay']['img'] = 'images/medals/MedalTarczay.png';
        $medn['medalBrunoPietro']['img'] = 'images/medals/MedalBrunoPietro.png';
        $medn['medalCrucialContribution']['img'] = 'images/medals/MedalCrucialContribution.png';
        $medn['medalBrothersInArms']['img'] = 'images/medals/MedalBrothersInArms.png';
        $medn['heroesOfRassenay']['img'] = 'images/medals/HeroesOfRassenay.png';
        $medn['bombardier']['img'] = 'images/medals/Bombardier.png';

        $medn['huntsman']['img'] = 'images/medals/Huntsman.png';
        $medn['luckyDevil']['img'] = 'images/medals/LuckyDevil.png';
        $medn['ironMan']['img'] = 'images/medals/IronMan.png';
        $medn['sturdy']['img'] = 'images/medals/Sturdy.png';
        $medn['pattonValley']['img'] = 'images/medals/PattonValley.png';
        $medn['mechanicEngineer']['img'] = 'images/medals/MechanicEngineer.png';

        $medn[$tename.'_usa']['type'] = 'expert';
        $medn[$tename.'_france']['type'] = 'expert';
        $medn[$tename.'_ussr']['type'] = 'expert';
        $medn[$tename.'_china']['type'] = 'expert';
        $medn[$tename.'_uk']['type'] = 'expert';
        $medn[$tename.'_germany']['type'] = 'expert';

        $medn[$mename.'_usa']['type'] = 'mechanic';
        $medn[$mename.'_france']['type'] = 'mechanic';
        $medn[$mename.'_ussr']['type'] = 'mechanic';
        $medn[$mename.'_china']['type'] = 'mechanic';
        $medn[$mename.'_uk']['type'] = 'mechanic';
        $medn[$mename.'_germany']['type'] = 'mechanic';
        $medn['mechanicEngineer']['type'] = 'mechanic';

        $medn['medalCarius']['type'] = 'major';
        $medn['medalHalonen']['type'] = 'epic';
        $medn['invader']['type'] = 'hero';
        $medn['medalFadin']['type'] = 'epic';
        $medn['armorPiercer']['type'] = 'special';
        $medn['medalEkins']['type'] = 'major';
        $medn['mousebane']['type'] = 'special2';
        $medn['medalKay']['type'] = 'major';
        $medn['defender']['type'] = 'hero';
        $medn['medalLeClerc']['type'] = 'major';
        $medn['supporter']['type'] = 'hero';
        $medn['steelwall']['type'] = 'hero';
        $medn['medalAbrams']['type'] = 'major';
        $medn['medalPoppel']['type'] = 'major';
        $medn['medalOrlik']['type'] = 'epic';
        $medn['handOfDeath']['type'] = 'special';
        $medn['sniper']['type'] = 'hero';
        $medn['warrior']['type'] = 'hero';
        $medn['titleSniper']['type'] = 'special';
        $medn['medalWittmann']['type'] = 'epic';
        $medn['medalBoelter']['type'] = 'epic';
        $medn['medalBurda']['type'] = 'epic';
        $medn['scout']['type'] = 'hero';
        $medn['beasthunter']['type'] = 'special2';
        $medn['kamikaze']['type'] = 'special';
        $medn['raider']['type'] = 'special';
        $medn['medalOskin']['type'] = 'epic';
        $medn['medalBillotte']['type'] = 'epic';
        $medn['medalLavrinenko']['type'] =  'major';
        $medn['medalKolobanov']['type'] = 'epic';
        $medn['invincible']['type'] = 'special';
        $medn['lumberjack']['type'] = 'special';
        $medn['tankExpert']['type'] = 'expert';
        $medn['diehard']['type'] = 'special';
        $medn['medalKnispel']['type'] =  'major';
        $medn['sinai']['type'] = 'special2';

        $medn['evileye']['type'] = 'hero';
        $medn['medalDeLanglade']['type'] = 'epic2';
        $medn['medalTamadaYoshio']['type'] = 'epic2';
        $medn['medalNikolas']['type'] = 'epic2';
        $medn['medalLehvaslaiho']['type'] = 'epic2';
        $medn['medalDumitru']['type'] = 'epic2';
        $medn['medalPascucci']['type'] = 'epic2';
        $medn['medalLafayettePool']['type'] = 'epic2';
        $medn['medalRadleyWalters']['type'] = 'epic2';
        $medn['medalTarczay']['type'] = 'epic2';
        $medn['medalBrunoPietro']['type'] = 'epic2';
        $medn['medalCrucialContribution']['type'] = 'epic';
        $medn['medalBrothersInArms']['type'] = 'epic';
        $medn['heroesOfRassenay']['type'] = 'epic2';
        $medn['bombardier']['type'] = 'special';

        $medn['huntsman']['type'] = 'special2';
        $medn['luckyDevil']['type'] = 'special2';
        $medn['ironMan']['type'] = 'special2';
        $medn['sturdy']['type'] = 'special2';
        $medn['pattonValley']['type'] = 'special2';

        foreach($medn as $name => $val){
            if(isset($val['type'])){
                $nmedn[$val['type']][$name] = $val;
            }
        }
        unset($nmedn['special']['lumberjack']);
        unset($nmedn['epic']['medalWittmann']);
        $new['medals'] = $nmedn;
        //print_r($new);
        return $new;
    }

    function get_last_roster($time)
    {
        global $db;
        global $config;
        $error = 1;

        $sql = "
        SELECT p.name, p.account_id, p.role, p.member_since
        FROM `col_players` p,
        (SELECT max(up) as maxup
        FROM `col_players`
        WHERE up <= ".$time."
        LIMIT 1) maxresults
        WHERE p.up = maxresults.maxup
        ORDER BY p.up DESC;";

        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            return $q->fetchAll();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
    }

    function roster_sort($array)
    {
        $new = array();
        foreach($array as $val){
            $new[$val['account_name']] = $val;
        }
        return $new;  //new
    }
    function roster_resort_id($roster)
    {
        foreach($roster as $val){
            $new[$val['account_id']] = $val;
        }
        return $new;
    }

    function tanks_group($array){
        $name = array();
        foreach($array as $val){
            if(isset($val['tank'])){
                if(is_array($val['tank'])) {
                    foreach($val['tank'] as $lvl => $types){
                        foreach($types as $type => $tanks){
                            foreach($tanks as $tank){
                                $name[$type][$lvl][($tank['type'])] = true;
                            }
                        }
                    }
                }
            }
        }

        return $name;
    }
    function tanks_group_full($array,$nation_s,$type_s,$lvl_s){
        $name = array();
        foreach($array as $val){
            if(isset($val['tank'])){
                if(is_array($val['tank'])) {
                    foreach($val['tank'] as $lvl => $types){
                        foreach($types as $type => $tanks){
                            foreach($tanks as $tank){
                                if(in_array($tank['lvl'],$lvl_s) && in_array($tank['class'],$type_s) && in_array($tank['nation'],$nation_s)){
                                    $name[$type][$lvl][($tank['type'])] = true;
                                }
                            }
                        }
                    }
                }
            }
        }
        ksort($name);
        foreach(array_keys($name) as $types){
            ksort($name[$types]);   
        }
        return $name;
    }

    function restr($array)
    {
        foreach(array_keys($array) as $val){
            if(is_array($array[$val])){
                foreach(array_keys($array[$val]) as $v){
                    if(is_numeric($v)){
                        unset($array[$val][$v]);
                    }
                }
            }else{
                if(is_numeric($val)){
                    unset($array[$val]);
                }
            }
        }
        return $array;
    }
    function tanks_nations() {
        global $db;
        $sql='SELECT DISTINCT nation FROM `tanks`;';
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            return $q->fetchAll();
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
    }
    function tanks_types() {
        global $db;
        $sql='SELECT DISTINCT type FROM `tanks`;';
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            return $q->fetchAll();
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
    }
    function tanks_lvl() {
        global $db;
        $sql='SELECT DISTINCT lvl FROM `tanks`;';
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            return $q->fetchAll();
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
    }
    /***** Exinaus *****/
    function get_available_tanks()
    {
        global $db;
        $top_tanks=array();

        $sql='SELECT tt.lvl, tt.type, tt.shortname, t.tank, tt.index
        FROM `top_tanks` tt, `tanks` t
        WHERE t.title = tt.title AND tt.show = "1"
        ORDER BY tt.index ASC, tt.order ASC, t.tank ASC;';
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $top_tanks_unsorted = $q->fetchAll();
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        foreach($top_tanks_unsorted as $val) {
            $top_tanks[$val['tank']]['lvl'] = $val['lvl'];
            $top_tanks[$val['tank']]['type'] = $val['type'];
            $top_tanks[$val['tank']]['short'] = isset($val['shortname']) ? $val['shortname'] : '';
            $top_tanks[$val['tank']]['index'] = $val['index'];
        }

        return $top_tanks;
    }
    function get_available_tanks_index()
    {
        global $db;
        $top_tanks=array();

        $sql='SELECT DISTINCT tt.index
        FROM `top_tanks` tt
        WHERE tt.show = "1"
        ORDER BY tt.index ASC;';
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $top_tanks_unsorted = $q->fetchAll();
        }else{
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        $count = 0;

        foreach($top_tanks_unsorted as $val) {
            $top_tanks['index'][] = $val['index'];
            $count++;
        }
        $top_tanks['count'] = $count;

        return $top_tanks;
    }
    function add_tanks_preset($tank) {
      global $db;

      $presets = array();
      $sql = 'SELECT `type`, `lvl`, `show`, `index` FROM `top_tanks` GROUP BY `type`, `lvl`, `show`, `index` ORDER BY `show` DESC, `index` ASC;';
      $q = $db->prepare($sql);
      if ($q->execute() == TRUE) {
          $presets = $q->fetchAll(PDO :: FETCH_ASSOC);
      } else {
          die(show_message($q->errorInfo(),__line__,__file__,$sql));
      }
      foreach($presets as $val) {
        if($val['lvl'] == $tank['lvl'] and $val['type'] == $tank['type']) {
            $sql = 'INSERT IGNORE INTO `top_tanks` (`title`, `lvl`, `type`, `index`, `show`) VALUES ';
            $sql .= "('{$tank['title']}', '{$tank['lvl']}', '{$tank['type']}', '{$val['index']}', '{$val['show']}');";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            break;
        }
      }
    }
    /**** end ****/
    function roster_num($var)
    {
        $data['recruit'] = '8';
        $data['private'] = '7';
        $data['recruiter'] = '6';    
        $data['treasurer'] = '5';
        $data['diplomat'] = '4';
        $data['commander'] = '3'; 
        $data['vice_leader'] = '2';  
        $data['leader'] = '1';

        return $data[$var];

    }
    function read_multiclan($dbprefix = FALSE)
    {
        global $db;
        if($dbprefix == FALSE){
            $sql = "SELECT * FROM multiclan ORDER BY sort ASC;";
        }else{
            $sql = "SELECT * FROM multiclan WHERE prefix = '".$dbprefix."' ORDER BY sort ASC;";
        }
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }else{ 
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }     
    }
    function autoclean($time,$multi,$config,$directory)
    {
        global $cache,$db;
        //$global = array(); 
        if(($config['autoclean'] + $time) <= now()){
            $map = directory_map($directory);
            foreach($multi as $val){
                $new = $cache->get('get_last_roster_'.$val['id'],0);
                if($new === FALSE) 
                { 
                    $new = get_api_roster($val['id'],$config); 
                }else{
                    $cache->clear('get_last_roster_'.$val['id']);
                    $cache->set('get_last_roster_'.$val['id'], $new);
                }
                //print_r($new); die;
                foreach($new['data']['members'] as $player){
                    foreach($map as $key => $file){
                        if(sha1($player['account_name']) == $file){
                            unset($map[$key]);
                        }
                    }
                } 
                $sql = "UPDATE ".$val['prefix']."config SET value = '".now()."' WHERE name = 'autoclean';";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
            }              
            foreach($map as $file){ 
                unlink($directory.$file);   
            }      
        }
    }
    function multi_main($multi){
        foreach($multi as $key => $val){
            if($val['main'] == '1'){
                return $multi[$key];
            }
        }
    }
?>