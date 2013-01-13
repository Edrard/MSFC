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
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$lang['page_title']; ?></title>
    <?php if (!isset($config['theme'])) {
              $config['theme'] = 'ui-lightness'; } ?>
    <link rel="stylesheet" href="./theme/<?=$config['theme']; ?>/jquery-ui.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="./theme/style.css" type="text/css" media="print, projection, screen" />
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery.metadata.js"></script>
    <script type="text/javascript" src="./js/jquery.qtip.js"></script>
    <script type="text/javascript" src="./js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="./js/jquery.tablesorter.widgets.js"></script> 
    <script type="text/javascript" src="./js/jquery.ui.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.button.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.core.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.menu.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.position.js"></script>
    <?php if ($config['lang'] == 'ru') { ?>
    <script type="text/javascript" src="./js/jquery.ui.ru.js"></script>
    <?php }; ?>
    <script type="text/javascript" src="./js/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="./js/jquery.vticker.js"></script>

    <script type="text/javascript" id="js">
        $(function() {
            $.extend($.tablesorter.themes.jui, {

            table      : 'ui-widget ui-widget-content', // table classes
            header     : 'ui-widget-header ui-state-default', // header classes
            footerRow  : '', 
            footerCells: '', 
            icons      : 'ui-icon', // icon class added to the <i> in the header 
            sortNone   : 'ui-icon-triangle-2-n-s',
            sortAsc    : 'ui-icon-triangle-1-n',
            sortDesc   : 'ui-icon-triangle-1-s',
            active     : 'ui-state-active', // applied when column is sorted
            hover      : 'ui-state-hover',  // hover class 
            filterRow  : '', 
            even       : 'ui-widget-content', // odd row zebra striping
            odd        : 'ui-priority-secondary'   // even row zebra striping
            });

            $.extend($.tablesorter.themes.bootstrap, {
            table      : 'ui-widget ui-widget-content', // table classes
            header     : 'ui-widget-header ui-state-default', // header classes
            footerRow  : '', 
            footerCells: '', 
            icons      : '', // icon class added to the <i> in the header
            sortNone   : '',
            sortAsc    : '',
            sortDesc   : '',
            active     : '', // applied when column is sorted
            hover      : '',  // hover class
            filterRow  : '', 
            even       : 'ui-widget-content', // odd row zebra striping
            odd        : 'ui-priority-secondary'   // even row zebra striping
            });
         });
        $(document).ready(function()
        {
            $("#roster").tablesorter({sortList:[[1,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#best_main").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#best_medal").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#active_main").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#active_medal_1").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#active_medal_2").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#active_medal_3").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#active_medal_4").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            
            $("#overall").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#perform").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#battel").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#achiv_epic").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#achiv_major").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#achiv_hero").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#achiv_special").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });

            $("#rating").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#rating1").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#rating_all").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#average_perform").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#blocked").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });

            $("#all_tanks_stat").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#perform_all").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            $("#all_medals_stat").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });

            $( "#login_dialog" ).dialog({
                 title: 'Login',
                 autoOpen: false,
                 draggable: false,
                 resizable: false,
                 width: 500,
                 show: "blind",
                 hide: "blind",
                 modal: true,
                 position: { my: "center top", at: "center top", of: "#allcontainer" , offset: "0, 100", collision: "flip" }
            });
            $('#login_opener').click(function() {
                $("#login_dialog").dialog('open');
                // prevent the default action, e.g., following a link
                return false;
            });
            $.datepicker.setDefaults($.datepicker.regional["<?php echo $config['lang']; ?>"]);
            $('.bb[title]').qtip({
                position: {
                    target: 'mouse',
                    adjust: { screen: true, mouse: true }
                },
                style: {
                    classes: 'ui-overlay',
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

    function magic(elem)
    {
        $(".ui-menu-item").each(function(){
          if($(this).hasClass("ui-state-active")){
             $(this).removeClass("ui-state-active");
          }
        });
        $(elem).parent('li').addClass("ui-state-active");
    };
    function magic2(elem)
    {
        elem = document.getElementById('tohide');
        if (elem) {
           if (elem.style.display == 'none'){
               $("#tohide").fadeIn("fast");
               $("#tohide2").fadeIn("fast");
               $("#chan").removeClass("ui-icon-triangle-1-e");
               $("#chan").addClass("ui-icon-triangle-1-w");
           } else {
               $("#tohide").fadeOut("fast");
               $("#tohide2").fadeOut("fast");
               $("#chan").removeClass("ui-icon-triangle-1-w");
               $("#chan").addClass("ui-icon-triangle-1-e");
           }
        }


    };

    $(function() {
        $("#menu").menu();
        $("#menu").menu( "option", "role", "menu" );
        $("#allcontainer").tabs({
           ajaxOptions: {
              error: function( xhr, status, index, anchor ) {
                 $( anchor.hash ).html(
                    "<?php echo $lang['error_1'];?>");
              }
           }
        });
        $('#allcontainer ul li a').click(function () {window.location.hash = $(this).attr('href');window.scrollTo(0, 0);});
    });
    </script>
</head>
<body>
<noscript>
  <?php show_message($lang['js_off']); ?>
</noscript>