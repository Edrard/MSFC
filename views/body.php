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
    * @version     $Rev: 2.0.3 $
    *
    */
?>
<body>
<?php
    if(isset($message)){
        echo $message;
    }
?>

<div id="tabs" class="main_container">
    <div style="min-height: 52px;">
        <div class="num" style="float:left;"><?php echo $lang['total_p']; ?>: <?php echo count($new['data']['request_data']['items']) ?></div>
        <div style="float:left; margin-top: 7px;margin-left: 60px;">
            <?php if($config['lang'] == 'ru' && $config['news'] == '1'){ ?>
                <iframe src="./news.php" frameborder="0" scrolling="no" width="100%" align="middle" height="50px"></iframe> 
                <?php } ?>
        </div>
        <div style="float:right;">
            <?php if($logged > 0){ ?>
                <table border="0" cellpadding="4" cellspacing="0" width="440" >
                    <tbody>
                        <tr>
                            <td rowspan="2"><img src="./images/logo_small.png" /></td>
                            <td><strong><?=$lang['hi'];?> <?=$_COOKIE['user'];?></strong></td>
                        </tr>
                        <tr>
                            <td><a href="./main.php?logout=true"><?=$lang['logout'];?></a></td>
                        </tr>
                    </tbody>
                </table>
                <?php }else{ ?>
                <img src="./images/logo_small.png" style="margin-right: 40px;" />
                <style>
                  .ui-dialog-titlebar {
                    background-color:  #8DBDD8;
                    border: 1px solid black;
                    border-bottom: 0px;
                    border-collapse: collapse;
                  }

                </style>
                <div id="login_dialog" class="ui-dialog hidden" style="background: white; border: 1px solid black; border-collapse: collapse;">
                	<div><?php include(ROOT_DIR.'/login.php'); ?></div>
                </div>
                <div style="float: right;">
                <a href="#" id="login_opener"><?=$lang['login'];?></a>
                </div>
                <?php  } ?>
        </div>
    </div>
    <ul>
        <?php foreach($tabs as $key => $val){ 
                foreach(array_keys($val) as $link){    
                    if(is_numeric($key)){ ?>
                    <li><a href="#tabs-<?php echo $key; ?>"><?php echo $link; ?></a></li>
                    <?php }else{  ?>
                    <li><a href="<?php echo $key; ?>"><?php echo $link; ?></a></li>
                    <?php  } 
                }  
        } ?>
    </ul>
    <?php foreach($tabs as $key => $val){ 
            foreach($val as $file){    
                if(is_numeric($key)){ 
                    if(!is_array($file)){?>
                    <div id="tabs-<?php echo $key; ?>">
                    <a href="#tabs-<?php echo $key; ?>"></a>
                        <?php include_once(ROOT_DIR.'/tabs/'.$file); ?>
                    </div>
                    <?php  }else{ ?>
                    <div id="tabs-<?php echo $key; ?>">
                        <?php include(ROOT_DIR.'/login.php'); ?>
                    </div>
                    <?php  } ?>
                <?php  } ?> 
            <?php  } ?>   
        <?php  } ?> 
</div>
