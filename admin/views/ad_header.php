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
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$lang['admin_title']; ?></title>
    <link rel="stylesheet" href="../css/jqmodal.css" type="text/css" />
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="stylesheet" href="../css/jq.css" type="text/css" media="print, projection, screen" /> 
    <link rel="stylesheet" href="../css/jquery-ui.css" type="text/css" media="print, projection, screen" /> 

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <?php if ($config['lang'] == 'ru') { ?>
    <script type="text/javascript" src="../js/jquery-ui-ru.js"></script>
    <?php }; ?>
    <script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
    <script type="text/javascript" src="../js/jquery.metadata.js"></script>
    <script type="text/javascript" src="../js/jqmodal.js"></script>
    <script>
        $(document).ready(function() 
        {  
            $("#tabs").tabs({  
                select: function(event, ui){
                    if(ui.index == 6){ window.location = "index.php?logout=true"; } 
                }

            });
            $('#tabs ul li a').click(function () {window.location.hash = $(this).attr('href');window.scrollTo(0, 0);}); 
        });
    </script>
    <?php if(isset($current_user)){ ?>
        <script>
            $(document).ready(function() 
            {  
                <?php foreach($current_user as $val){?>
                    $('#dialog_<?=$val['user']?>').jqm({trigger: 'a.trigger_<?=$val['user']?>'}); 
                    <?php } ?>
            });
        </script>
        <?php    }  ?>
    <script type="text/javascript" id="js">     

        $(document).ready(function() 
        {
            $("#files").tablesorter({
                sortList: [[2, 0]],
                widgets: ['zebra'],
                textExtraction: function(node) {
                    return $(node).find("span.hidden").text();
                }
            });
            $("#users").tablesorter({
                sortList: [[1, 0]],
                widgets: ['zebra'],
            });
            <?php if(!empty($adm_top_tanks)){ ?>
                $("#top_tanks").tablesorter({sortList:[[6,0],[3,0]], widgets: ['zebra']});
                <?php } ?>
            <?php if(!empty($tanks_list)){ ?>
                $("#tanks_list").tablesorter({
                    sortList: [[2, 0]],
                    widgets: ['zebra'],
                    textExtraction: function(node) {
                        return $(node).find("span.hidden").text();
                    }
                });
                <?php } ?>
        }
        );  
    </script>
    <script type="text/javascript" id="js">
        $(document).ready(function(){

            $('#loadeng').click(function(){  
                <?php foreach($tabs_lang['en'] as $key => $val){ ?>
                    $('#<?=$key;?>php').val('<?=$val?>');
                    <?php } ?>
                return false;
            });
            $('#loadrus').click(function(){  
                <?php foreach($tabs_lang['ru'] as $key => $val){ ?>
                    $('#<?=$key;?>php').val('<?=$val?>');
                    <?php } ?>
                return false;
            });  

        });  
    </script>
</head>