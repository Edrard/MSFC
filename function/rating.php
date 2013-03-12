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
    function rating($array,$lang)
    {
        //print_r($array);
        $max = array('mdmg' => 0,'mspo' => 0,'mcap' => 0,'mdef' => 0);
        foreach($array as $name => $stat){
            //$rating[$name]['mid'] = middel_tank_lvl($stat['tank']);
            if($stat['overall'][$lang['games_p']] != 0 ){
                $rat['mdmg'][$name] = $rating[$name]['mdmg'] = number_format($stat['perform'][$lang['damage']]/$stat['overall'][$lang['games_p']], 2, '.', '');
                $rat['mspo'][$name] = $rating[$name]['mspo'] = number_format($stat['perform'][$lang['spotted']]/$stat['overall'][$lang['games_p']], 2, '.', '');
                $rat['mcap'][$name] = $rating[$name]['mcap'] = number_format($stat['perform'][$lang['capture']]/$stat['overall'][$lang['games_p']], 2, '.', '');
                $rat['mdef'][$name] = $rating[$name]['mdef'] = number_format($stat['perform'][$lang['defend']]/$stat['overall'][$lang['games_p']], 2, '.', '');



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
            if($max['mdmg'] != 0){
                $new['mdmg'] = $val['mdmg']*100/$max['mdmg'];
            } else {
                $new['mdmg'] = 0;
            }

            if($max['mspo'] != 0){
                $new['mspo'] = $val['mspo']*100/$max['mspo'];
            } else {
                $new['mspo'] = 0;
            }

            if($max['mcap'] != 0){
                $new['mcap'] = $val['mcap']*100/$max['mcap'];
            } else {
                $new['mcap'] = 0;
            }

            if($max['mdef'] != 0){
                $new['mdef'] = $val['mdef']*100/$max['mdef'];
            } else {
                $new['mdef'] = 0;
            }
            $total[$name] =  round(($new['mcap']/5  +  $new['mdef']/5  +  $new['mspo']/5 + $new['mdmg']*0.40),1);
        }
        arsort($rat['mdmg']);
        arsort($rat['mspo']);
        arsort($rat['mcap']);
        arsort($rat['mdef']);
        $ratt = $rat;
        $rat['mdmg'] = array_slice($rat['mdmg'],0,5,true);
        $rat['mspo'] = array_slice($rat['mspo'],0,5,true);
        $rat['mcap'] = array_slice($rat['mcap'],0,5,true);
        $rat['mdef'] = array_slice($rat['mdef'],0,5,true);

        arsort($total);
        $news['total'] = array_slice($total,0,10);
        $news['rat'] = &$rat;
        $news['rat_all'] = &$ratt;
        $news['max'] = &$max;
        unset($rat, $ratt, $max);
        return $news;
    }
    function middel_tank_lvl($array){
        //print_r($array);
        if(!is_array($array)){
            return 0;
        }
        $total = '0';
        for($i=1;$i<=10;$i++){
            $data[$i]['total'] = 0;
            $data[$i]['win'] = 0;
        }
        foreach ($array as $lvl){
            foreach($lvl as $type){
                foreach($type as $value){
                    if(isset($data[$value['lvl']]['total'])){
                        $data[$value['lvl']]['total'] +=  $value['total'];
                    }
                    if(isset($data[$value['lvl']]['win'])){  
                        $data[$value['lvl']]['win'] +=  $value['win'];
                    }
                    $total += $value['total'];  
                }
            }
        }
        $mid = '0';
        if($total != 0){
            foreach ($data as $key => $value){
                $mid += $key*$value['total']/$total;   
            }
        }else{
            $mid = 0;
        }
        //print_r($mid);
        //print_r($data);
        //print_r($total);
        return round($mid,2);    
    }
    function eff_rating($res,$lang)
    {
        foreach($res as $name => $per_stat){
            if(isset($per_stat['overall'][$lang['games_p']]) and $per_stat['overall'][$lang['games_p']] != 0 ){

                $effect['des'] = ($per_stat['perform'][$lang['destroyed']]/$per_stat['overall'][$lang['games_p']]);
                $effect['dem'] = ($per_stat['perform'][$lang['damage']]/$per_stat['overall'][$lang['games_p']]);
                $effect['spo'] = ($per_stat['perform'][$lang['spotted']]/$per_stat['overall'][$lang['games_p']]);
                $effect['def'] = ($per_stat['perform'][$lang['defend']]/$per_stat['overall'][$lang['games_p']]);
                $effect['cap'] = ($per_stat['perform'][$lang['capture']]/$per_stat['overall'][$lang['games_p']]);
                $effect['win'] = ($per_stat['overall'][$lang['victories']]/$per_stat['overall'][$lang['games_p']]);
                $effect['b']   =  $per_stat['overall'][$lang['games_p']];
                $effect['Hp']  =  $per_stat['exp'][$lang['total_exp']]/$per_stat['overall'][$lang['games_p']];

                $effect['lvl'] = 0;
                if(isset($per_stat['tank'])){
                    $effect['lvl'] = middel_tank_lvl($per_stat['tank']);
                }
                if(count($effect) > 0){
                    $feff[$name] = array();
                    if($effect['lvl'] < 6) { $min6 = $effect['lvl']; } else { $min6 = 6; }
                    if($effect['def'] < 2.2) { $min2 = $effect['def']; } else { $min2 = 2.2; }
                    if($effect['lvl'] != 0){
                        $feff[$name]['eff'] = number_format(($effect['dem']*(10/($effect['lvl'] + 2))*(0.23+2*$effect['lvl']/100) + $effect['des']*0.25*1000 + $effect['spo']*0.15*1000 + log($effect['cap']+1,1.732)*0.15*1000 + $effect['def']*0.15*1000),2, '.', '');
                        $feff[$name]['wn6'] = number_format(((1240-1040/(pow($min6,0.164)))*$effect['des']+$effect['dem']*530/(184*exp(0.24*$effect['lvl'])+130)+$effect['spo']*125+$min2*100+((185/(0.17+exp((($effect['win']*100)-35)*-0.134)))-500)*0.45+(6-$min6)*-60),2, '.', '');
                        $feff[$name]['brone'] = round((log($effect['b'])/10)*(($effect['Hp']*1)+($effect['dem']*($effect['win']*2+$effect['des']*0.9+$effect['spo']*0.5+$effect['def']*0.5+$effect['cap']*0.5))),0);
                        $feff[$name]['xvm_eff'] = ($feff[$name]['eff']<440) ? 0 : round(max(min($feff[$name]['eff']*($feff[$name]['eff']*($feff[$name]['eff']*($feff[$name]['eff']*($feff[$name]['eff']*(0.00000000000000004787*$feff[$name]['eff'] - 0.00000000000035544) + 0.00000000102606) - 0.0000014665) + 0.0010827) - 0.3133) + 20.49, 100), 0),0);
                        $feff[$name]['xvm_wn6'] = ($feff[$name]['wn6']>2140) ? 100 : round(max(min($feff[$name]['wn6']*($feff[$name]['wn6']*($feff[$name]['wn6']*( -0.00000000001334*$feff[$name]['wn6'] + 0.00000005673) - 0.00007575) + 0.08392) - 9.362, 100), 0),0);
                  }
                }
            }else{
                $feff[$name]['eff'] = 0;
                $feff[$name]['wn6'] = 0;
                $feff[$name]['brone'] = 0;
                $feff[$name]['xvm_eff'] = 0;
                $feff[$name]['xvm_wn6'] = 0;
            }
        }
        return $feff;   
    }

    function sorting_stat($array,$overall,$perform)
    {
        //print_r($array);
        foreach($array as $name => $stat){
            foreach($overall as $vl){
                $new[$vl][$name] = $stat['overall'][$vl];
                $new['total'][$vl] += $stat['overall'][$vl];
            }
            foreach($perform as $vl){
                $new[$vl][$name] = $stat['perform'][$vl];
                $new['total'][$vl] += $stat['perform'][$vl];                   
            }
        }
        $news['total'] = &$new['total'];
        foreach(array_merge($overall,$perform) as $val){
            arsort($new[$val]);
            $news['single'][$val] = array_slice($new[$val],0,10);    
        }

        return $news;
    }  
?>
