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
* @version     $Rev: 3.0.1 $
*
*/


function rating($array,$config){
    $max = array('mdmg' => 0,'mspo' => 0,'mcap' => 0,'mdef' => 0);
    foreach($array as $name => $tmp){
        if (isset($tmp['data']['statistics']['all']['battles'])) {
            $stat = $tmp['data']['statistics']['all'];
        }   else {
            $stat['battles'] = 0;
        }
        //$rating[$name]['mid'] = middel_tank_lvl($stat['tank']);
        if($stat['battles'] != 0 ){
            $rat['mdmg'][$name] = $rating[$name]['mdmg'] = number_format($stat['damage_dealt']/$stat['battles'], 2, '.', '');
            $rat['mspo'][$name] = $rating[$name]['mspo'] = number_format($stat['spotted']/$stat['battles'], 2, '.', '');
            $rat['mcap'][$name] = $rating[$name]['mcap'] = number_format($stat['capture_points']/$stat['battles'], 2, '.', '');
            $rat['mdef'][$name] = $rating[$name]['mdef'] = number_format($stat['dropped_capture_points']/$stat['battles'], 2, '.', '');

            if($rating[$name]['mdmg'] >= $max['mdmg']){
                $max['mdmg'] = $rating[$name]['mdmg'];
            }

            if($rating[$name]['mspo'] >= $max['mspo']){
                $max['mspo'] = $rating[$name]['mspo'];
            }

            if($rating[$name]['mcap'] >= $max['mcap']){
                $max['mcap'] = $rating[$name]['mcap'];
            }

            if($rating[$name]['mdef'] >= $max['mdef']){
                $max['mdef'] = $rating[$name]['mdef'];
            }
        }
    }
    foreach($rating as $name => $val){
        if ($max['mdmg'] != 0){
            $new['mdmg'] = $val['mdmg']*100/$max['mdmg'];
        }   else {
            $new['mdmg'] = 0;
        }

        if ($max['mspo'] != 0){
            $new['mspo'] = $val['mspo']*100/$max['mspo'];
        }   else {
            $new['mspo'] = 0;
        }

        if ($max['mcap'] != 0){
            $new['mcap'] = $val['mcap']*100/$max['mcap'];
        }   else {
            $new['mcap'] = 0;
        }

        if ($max['mdef'] != 0){
            $new['mdef'] = $val['mdef']*100/$max['mdef'];
        }   else {
            $new['mdef'] = 0;
        }
        $total[$name] =  round(($new['mcap']/5  +  $new['mdef']/5  +  $new['mspo']/5 + $new['mdmg']*0.40),1);
    }
    arsort($rat['mdmg']);
    arsort($rat['mspo']);
    arsort($rat['mcap']);
    arsort($rat['mdef']);
    $ratt = $rat;
    $rat['mdmg'] = array_slice($rat['mdmg'],0,$config['top'],true);
    $rat['mspo'] = array_slice($rat['mspo'],0,$config['top'],true);
    $rat['mcap'] = array_slice($rat['mcap'],0,$config['top'],true);
    $rat['mdef'] = array_slice($rat['mdef'],0,$config['top'],true);

    arsort($total);
    $news['total'] = array_slice($total,0,$config['top']);
    $news['rat'] = &$rat;
    $news['rat_all'] = &$ratt;
    $news['max'] = &$max;
    unset($rat, $ratt, $max);
    return $news;
}

function middel_tank_lvl($array){
    global $tanks;
    //print_r($array);
    if (!is_array($array)){
        return 0;
    }
    $total = $mid = 0;
    $data = array();

    for ($i=1;$i<=10;$i++){
        $data[$i]['total'] = 0;
        $data[$i]['win'] = 0;
    }
    foreach ($array as $key => $val) {
        if(!isset($tanks[$val['tank_id']])){
            update_tanks_db();
            $tanks = tanks();
        }
        $data[$tanks[$val['tank_id']]['level']]['total'] +=  $val['statistics']['battles'];
        $data[$tanks[$val['tank_id']]['level']]['win']   +=  $val['statistics']['wins'];
        $total += $val['statistics']['battles'];
    }
    if ($total != 0){
        foreach ($data as $key => $val){
            $mid += $key*$val['total']/$total;
        }
    }
    //print_r($mid);
    //print_r($data);
    //print_r($total);
    return round($mid,2);
}

function eff_rating($res) {
    $feff = array();
    $tarr = array('xp', 'damage_dealt', 'wins', 'frags', 'spotted', 'dropped_capture_points', 'capture_points');
    foreach($res as $name => $val){
        $per_stat = $val['data'];
        if (isset($per_stat['statistics']['all']['battles']) && isset($per_stat['statistics']['clan']['battles']) && isset($per_stat['statistics']['company']['battles']) &&
        (($per_stat['statistics']['all']['battles'] - $per_stat['statistics']['clan']['battles'] - $per_stat['statistics']['company']['battles']) != 0 ) &&
        ($per_stat['statistics']['all']['battles'] != 0) ) {
            $effbrone['battles'] = $per_stat['statistics']['all']['battles'] - $per_stat['statistics']['clan']['battles'] - $per_stat['statistics']['company']['battles'];
            $effect['battles']   = $per_stat['statistics']['all']['battles'];

            foreach ($tarr as $valarr) {
                $effbrone[$valarr]  = ($per_stat['statistics']['all'][$valarr] - $per_stat['statistics']['clan'][$valarr] - $per_stat['statistics']['company'][$valarr]) /$effbrone['battles'];
                $effect[$valarr] = $per_stat['statistics']['all'][$valarr]/$effect['battles'];
            }

            $effect['lvl'] = 0;
            if (isset($per_stat['tanks'])){
                $effect['lvl'] = middel_tank_lvl($per_stat['tanks']);
            }
            $feff[$name] = array();
            if ($effect['lvl'] != 0){
                if($effect['lvl'] < 6)   { $min6 = $effect['lvl']; } else { $min6 = 6; }
                if($effect['dropped_capture_points'] < 2.2) { $min2 = $effect['dropped_capture_points']; } else { $min2 = 2.2; }
                if($effect['lvl'] < 3)   { $min3 = $effect['lvl']; } else { $min3 = 3; }
                if($effect['lvl'] < 5)   { $min5 = $effect['lvl']; } else { $min5 = 5; }

                $feff[$name]['eff'] = number_format(($effect['damage_dealt']*(10/($effect['lvl'] + 2))*(0.23+2*$effect['lvl']/100) + $effect['frags']*0.25*1000 + $effect['spotted']*0.15*1000 + log($effect['capture_points']+1,1.732)*0.15*1000 + $effect['dropped_capture_points']*0.15*1000),2, '.', '');
                $feff[$name]['wn6'] = number_format(((1240-1040/(pow($min6,0.164)))*$effect['frags']+$effect['damage_dealt']*530/(184*exp(0.24*$effect['lvl'])+130)+$effect['spotted']*125+$min2*100+((185/(0.17+exp((($effect['wins']*100)-35)*-0.134)))-500)*0.45+(6-$min6)*-60),2, '.', '');
                $feff[$name]['brone'] = round((log($effbrone['battles'])/10)*(($effbrone['xp']*1)+($effbrone['damage_dealt']*($effbrone['wins']*2+$effbrone['frags']*0.9+$effbrone['spotted']*0.5+$effbrone['dropped_capture_points']*0.5+$effbrone['capture_points']*0.5))),0);
                $feff[$name]['xvm_eff'] = ($feff[$name]['eff']<440) ? 0 : round(max(min($feff[$name]['eff']*($feff[$name]['eff']*($feff[$name]['eff']*($feff[$name]['eff']*($feff[$name]['eff']*(0.00000000000000004787*$feff[$name]['eff'] - 0.00000000000035544) + 0.00000000102606) - 0.0000014665) + 0.0010827) - 0.3133) + 20.49, 100), 0),0);
                $feff[$name]['xvm_wn6'] = ($feff[$name]['wn6']>2140) ? 100 : round(max(min($feff[$name]['wn6']*($feff[$name]['wn6']*($feff[$name]['wn6']*( -0.00000000001334*$feff[$name]['wn6'] + 0.00000005673) - 0.00007575) + 0.08392) - 9.362, 100), 0),0);
                $feff[$name]['wn7'] = number_format(((1240-1040/(pow($min6,0.164)))*$effect['frags']+$effect['damage_dealt']*530/(184*exp(0.24*$effect['lvl'])+130)+$effect['spotted']*125*$min3/3+$min2*100+((185/(0.17+exp((($effect['wins']*100)-35)*-0.134)))-500)*0.45-(((5 - $min5)*125)/(1 + exp(($effect['lvl']-pow($effect['battles']/220,(3/$effect['lvl'])))*1.5)))),2, '.', '');
            }
        }   else {
            $feff[$name]['eff'] = 0;
            $feff[$name]['wn6'] = 0;
            $feff[$name]['brone'] = 0;
            $feff[$name]['xvm_eff'] = 0;
            $feff[$name]['xvm_wn6'] = 0;
            $feff[$name]['wn7'] = 0;
        }
    }
    return $feff;
}
?>