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
    <title><?php echo $lang['page_title']; ?></title>

    <link rel="stylesheet" href="./css/jq.css" type="text/css" media="print, projection, screen" /> 
    <link rel="stylesheet" href="./css/style.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="./css/jquery-ui.css" type="text/css" media="print, projection, screen" /> 
    <?php 
        if(isset($_GET['ver'])){
            $vertt = $_GET['ver'];
        }else{
            $vertt = 0;
        }
        if($config['align'] == 'ver' || $vertt == 1){ 

        ?>
        <link rel="stylesheet" href="./css/ver.css" type="text/css" media="print, projection, screen" />
        <?php }else{ ?>
        <style>
            table.wid {
                width: 75%;
            }  
        </style>
        <?php 
        }
    ?>

    <style>
        .num {
            margin-top: 16px;
            font-size: 17px;
            font-weight: bold;
        }                    
    </style>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery-ui.js"></script>
    <?php if ($config['lang'] == 'ru') { ?>
        <script type="text/javascript" src="./js/jquery-ui-ru.js"></script>
        <?php }; ?> 
    <script type="text/javascript" src="./js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="./js/jquery.metadata.js"></script>
    <script type="text/javascript" src="./js/jquery.qtip.js"></script>
    <script type="text/javascript" src="./js/jquery.vticker.js"></script>

    <script type="text/javascript" id="js">     

        $(document).ready(function() 
            { 
                $("#roster").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

                $("#best_main").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#best_medal").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

                $("#active_main").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#active_medal_1").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#active_medal_2").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#active_medal_3").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#active_medal_4").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#active_medal_5").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#active_medal_6").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#overall").tablesorter({sortList:[[0,0]], widgets: ['zebra']}); 
                $("#perform").tablesorter({sortList:[[0,0]], widgets: ['zebra']}); 
                $("#battel").tablesorter({sortList:[[0,0]], widgets: ['zebra']}); 
                $("#achiv_epic").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#achiv_major").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#achiv_hero").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#achiv_special").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

                $("#rating").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#rating1").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#rating_all").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#average_perform").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#blocked").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

                $("#all_tanks_stat").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#perform_all").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#all_medals_stat").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

                $("#attack").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
                $("#poss").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

                $( "#acc_medals" ).accordion({collapsible: true, active: false, autoHeight: false});
                $( "#login_dialog" ).dialog({
                    title: 'Login',
                    autoOpen: false,
                    draggable: false,
                    resizable: false,
                    height: 350,
                    width: 500,
                    show: "blind",
                    hide: "blind",
                    modal: true,
                    position: { my: "center top", at: "center top", of: "#tabs" , offset: "0, 200", collision: "flip" }
                });
                $('#login_opener').click(function() {
                    $("#login_dialog").dialog('open');
                    // prevent the default action, e.g., following a link
                    return false;
                });
                $.datepicker.setDefaults($.datepicker.regional["<?php echo $config['lang']; ?>"]);
        });
    </script>  
    <script>
        $(function() {
            $( "#tabs" ).tabs({
                ajaxOptions: {
                    error: function( xhr, status, index, anchor ) {
                        $( anchor.hash ).html(
                            "<?php echo $lang['error_1'];?>");
                    }
                }
            });         
            $('#tabs ul li a').click(function () {window.location.hash = $(this).attr('href');window.scrollTo(0, 0);});
        });
    </script>
    <script>
        $(document).ready(function()
            {
                $('.bb[title]').qtip({
                    position: {
                        target: 'mouse',
                        adjust: { screen: true, mouse: true }
                    },
                    style: {
                        background: 'white',
                        color: 'black',
                        textAlign: 'center',
                        padding: '5px 5px',
                        width: {
                            max: 400,
                            min: 0
                        },
                        border: {
                            width: 1,
                            radius: 2,
                            color: '#000'
                        },
                        tip: false
                    }
                });
        });   
    </script> 
    <script>
        $(document).ready(function()
            {    
                $('#rotate').vTicker({
                    speed: 500,
                    pause: 5000,
                    showItems: 1,
                    animation: 'fade',
                    mousePause: false,
                    height: 0,
                    direction: 'down'
                });
        });   
    </script> 
</head>
<body>
<noscript>
    <? show_message($lang['js_off']); ?>
    </noscript>