<?php
function stronghold_generate($building,$clan,$id){
    $new['buildings'] = $building;
    if(empty($new['buildings'][1]['image_url'])){
        $new['buildings'][1]['image_url'] = '/images/strong/comand.png';
    }
    //print_r($new['buildings']); die;
    $way = array();
    foreach($clan['data'] as $id => $val){     
        if(is_array($val)){
            foreach($val['buildings'] as $type => $low){
                if($type != 1){
                    if(!isset($way[$low['direction_name']])){
                        $way[$low['direction_name']] = 1;    
                    }else{
                        $way[$low['direction_name']] += 1;
                    }

                }
            } 
            ksort($way);  
        }
    }
    if(empty($way)){
        return FALSE;
    }
    $filename = array();
    foreach($way as $go => $val){
        $filename[] = $go.'-'.$val;    
    }
    $filename = implode('_',$filename).'ab';
    $new['map'] = 'images/strong/'.$filename.'.jpg';
    $new['clan'] = $clan['data'][$id];

    return $new;
}
function build_pos($way,$num,$cord){

    $info['A'][2]['top'] = '49.7';
    $info['A'][2]['left'] = '7.5'; 
    $info['A'][1]['top'] = '57.9';
    $info['A'][1]['left'] = '25.9'; 

    $info['B'][1]['top'] = '43.7';
    $info['B'][1]['left'] = '29.1'; 
    $info['B'][2]['top'] = '29.5';
    $info['B'][2]['left'] = '33.8'; 

    $info['C'][2]['top'] = '33.9';
    $info['C'][2]['left'] = '59.2'; 
    $info['C'][1]['top'] = '47.6';
    $info['C'][1]['left'] = '45.9'; 

    $info['D'][2]['top'] = '47.4';
    $info['D'][2]['left'] = '78.7'; 
    $info['D'][1]['top'] = '55.7';
    $info['D'][1]['left'] = '61.5'; 

    return $info[$way][$num][$cord];
}
function build_mod($build,$cord){
    $info[2]['x'] = 0;
    $info[2]['y'] = 0;
    $info[3]['x'] = -0.1;
    $info[3]['y'] = 0.7;
    $info[4]['x'] = -0.4;
    $info[4]['y'] = 0.6;
    $info[5]['x'] = -0.9;
    $info[5]['y'] = 0.3;
    $info[6]['x'] = 0.5;
    $info[6]['y'] = 0.7;
    $info[7]['x'] = 0;
    $info[7]['y'] = 1.2;
    $info[8]['x'] = 0;
    $info[8]['y'] = 0.5;
    $info[9]['x'] = -0.1;
    $info[9]['y'] = 0.2;
    $info[11]['x'] = -0.2;
    $info[11]['y'] = 1;
    $info[12]['x'] = -0.6;
    $info[12]['y'] = 1.1; 
    return $info[$build][$cord]; 
}
function heartgen($num){
    $f = '';
    for($i=1;$i<=$num;$i++){
        $f .= '♥';
    }
    return $f;
}