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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>
       <?php if (!isset($config["theme"])) { $config["theme"] = "ui-lightness"; };
          if (is_file('./views/body.php')){
              $direc = '.';
              echo $lang["page_title"].'</title> ';
          } elseif (is_file('../views/body.php')){
             $direc = '..';
             echo $lang["admin_title"].'</title>';
          } else { $direc = ''; }
       ?>


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="<?=$direc; ?>/theme/<?=$config['theme']; ?>/jquery-ui.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="<?=$direc; ?>/theme/style.css" type="text/css" media="print, projection, screen" />

    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.metadata.js"></script>
    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.tablesorter.widgets.js"></script>
    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.ui.js"></script>
    <?php if ($config["lang"] == "ru") { ?>
    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.ui.ru.js"></script>
    <?php }; ?>
    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.vticker.js"></script>
    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.validate.min.js"></script>

    <?php  if ($direc == '..') {
             if (isset($current_user)){ ?>
                 <script type="text/javascript" id="js">
                   $(document).ready(function()
                     {
                       <?php foreach($current_user as $val){?>
                         $('#dialog_<?=$val['user']?>').dialog({appendTo: "#adminalltabs", autoOpen: false});
                         $('.trigger_<?=$val['user']?>').click(function(){
                             $('#dialog_<?=$val['user']?>').dialog("open");
                             return false;
                         });
                       <?php } ?>
                     });
                 </script>
                       <?php } ?>
                 <script type="text/javascript" id="js">
                   $(function() {
                       <?php if(isset($_GET['multi'])){ ?>
                           $("#iserver").prop('disabled', true);
                           $("#iclan").prop('disabled', true);
                           $("#ccontrol").hide();
                           $("#dccontrol").hide();
                           <?php } ?>
                   });

                   $(document).ready(function()
                     {
                       $('#loadeng').click(function(){
                           <?php foreach($tabs_lang['en'] as $key => $val){ ?>
                               $('#<?=$key;?>php').val('<?=$val?>');
                               <?php } ?>
                       });
                       $('#loadrus').click(function(){  
                           <?php foreach($tabs_lang['ru'] as $key => $val){ ?>
                               $('#<?=$key;?>php').val('<?=$val?>');
                               <?php } ?>
                       });
                     });
                 </script>
    <?php } ?>

    <script type="text/javascript" id="js">
        $(function() {
          $.extend($.tablesorter.themes.jui, {
            table      : "ui-widget ui-widget-content table-borders", // table classes
            header     : "ui-widget-header ui-state-default", // header classes
            footerRow  : "",
            footerCells: "",
            icons      : "ui-icon", // icon class added to the <i> in the header
            sortNone   : "ui-icon-triangle-2-n-s",
            sortAsc    : "ui-icon-triangle-1-n",
            sortDesc   : "ui-icon-triangle-1-s",
            active     : "ui-state-active", // applied when column is sorted
            hover      : "ui-state-hover",  // hover class
            filterRow  : "",
            even       : "ui-widget-content", // odd row zebra striping
            odd        : "ui-priority-secondary"   // even row zebra striping
          });

          $.extend($.tablesorter.themes.bootstrap, {
            table      : "ui-widget ui-widget-content table-borders",
            header     : "ui-widget-header ui-state-default",
            footerRow  : "",
            footerCells: "",
            icons      : "",
            sortNone   : "",
            sortAsc    : "",
            sortDesc   : "",
            active     : "",
            hover      : "",
            filterRow  : "",
            even       : "ui-widget-content",
            odd        : "ui-priority-secondary"
          });
        });

        $(document).ready(function()
        {
            $.tablesorter.defaults.headerTemplate = "<div style='padding: 0px; padding-right:12px;'>{content}</div>{icon}";
            $.tablesorter.defaults.widgets = ["uitheme", "zebra"];
            $.tablesorter.defaults.widthFixed = false;
            $.tablesorter.defaults.sortList = [[0,0]];

            $("#roster").tablesorter({sortList:[[1,0]], widgetOptions: {uitheme : 'jui'}});
            $("#best_main").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#best_medal").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#active_main").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#active_medal_1").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#active_medal_2").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#active_medal_3").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#active_medal_4").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#active_medal_5").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#active_medal_6").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#overall").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#perform").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#battel").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#achiv_epic").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#achiv_major").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#achiv_hero").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#achiv_special").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#rating").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#rating1").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#rating_all").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#average_perform").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#blocked").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#all_tanks_stat").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#perform_all").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#all_medals_stat").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#msfc7").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[0,0]], widgetOptions: {uitheme : 'bootstrap'}});
            $("#msfc8").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[0,0]], widgetOptions: {uitheme : 'bootstrap'}});
            $("#msfc9").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[0,0]], widgetOptions: {uitheme : 'bootstrap'}});
            $("#acc_medals").accordion({collapsible: true, active: false, autoHeight: false});

            $("#poss").tablesorter({ sortList:[[1,0]], widgetOptions: {uitheme : "jui"}});
            $("#attack").tablesorter({ sortList:[[1,0]], widgetOptions: {uitheme : 'jui'}});
            $("#files").tablesorter({sortList: [[2, 0]], widgetOptions: {uitheme : 'jui'},
                textExtraction: function(node) {
                    return $(node).find("span.hidden").text();
                }
            });
            $("#users").tablesorter({widgetOptions: {uitheme : 'jui'}});
            $("#multiclan_table").tablesorter({widgetOptions: {uitheme : 'jui'}});
            <?php if(!empty($adm_top_tanks)){ ?>
                     $("#top_tanks").tablesorter({ sortList:[[6,0],[3,0]], widgetOptions: {uitheme : 'jui'}});
            <?php }
                  if(!empty($tanks_list)){ ?>
                      $("#tanks_list").tablesorter({sortList: [[2, 0]],widgetOptions: {uitheme : 'jui'},
                          textExtraction: function(node) {
                              return $(node).find("span.hidden").text();
                          }
                      });
            <?php } ?>

            $.datepicker.setDefaults($.datepicker.regional["<?php echo $config['lang']; ?>"]);

            $(".bb[title]").tooltip({
                track: false,
                delay: 0,
                fade: 250,
                content: function() {
                   var element = $( this );
                   if ( element.is( "[title]" ) ) {
                        return element.attr( "title" );
                   }
                }
            });

            $("#rotate").vTicker({
                speed: 500,
                pause: 5000,
                showItems: 1,
                animation: "fade",
                mousePause: false,
                height: 0,
                direction: "down"
            });

            $("#login_dialog").dialog({
                title: "<?php if (isset($lang['login'])) { echo $lang['login'];} else { echo 'Login:';}; ?>",
                autoOpen: false,
                draggable: false,
                resizable: false,
                width: 500,
                show: "blind",
                hide: "blind",
                modal: true,
                position: ['center', 'center']
            });
            $('#login_opener').click(function() {
                $("#login_dialog").dialog('open');
                return false;
            });

            var temp2 = $(".ui-widget-content").css("background-color");
            var rgbColors = new Object();
            var exist = false;
            if (temp2.substring(0,1) == "#") {
                rgbColors[0] = parseInt(temp2.substring(1, 3), 16);
                rgbColors[1] = parseInt(temp2.substring(3, 5), 16);
                rgbColors[2] = parseInt(temp2.substring(5, 7), 16);
                exist = true;
            }
            if (temp2.substring(0,1) == "r") {
                var bodycolor = temp2.split(", ");
                rgbColors[0] = bodycolor[0].replace("rgb(", "");
                rgbColors[1] = bodycolor[1];
                rgbColors[2] = bodycolor[2].replace(")","");
                exist = true;
            }
            if (exist) {
                if ((rgbColors[0] > 240)&&(rgbColors[1] > 240)&&(rgbColors[2] > 240)) {
                     var link = document.createElement("link");
                     link.setAttribute("rel", "stylesheet");
                     link.setAttribute("type", "text/css");
                     link.setAttribute("href", "<?=$direc; ?>/theme/toolight.css");
                     document.getElementsByTagName("head")[0].appendChild(link);
                }
                if ((rgbColors[0] < 17)&&(rgbColors[1] < 17)&&(rgbColors[2] < 17)) {
                     var link = document.createElement("link");
                     link.setAttribute("rel", "stylesheet");
                     link.setAttribute("type", "text/css");
                     link.setAttribute("href", "<?=$direc; ?>/theme/toodark.css");
                     document.getElementsByTagName("head")[0].appendChild(link);
                }
             };
        });

        function magic(elem)
        {
            $(".ui-menu-item").each(function(){
                if($(this).hasClass("ui-state-active")){
                    $(this).removeClass("ui-state-active");
                }
            });
            $(elem).parent('li').addClass("ui-state-active");
            if(elem.id == 'out'){ window.location = "index.php?logout=true<?php if (isset($multi_get)) {echo $multi_get ;} ;?>"; }
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
        function is_numeric(input){
          return typeof(input)=='number';
        };
        function check_Width(input, element) {
          var windowWidth = $(window).width();
          var menuWidth =   $("td#tohide2").width();
          var tohideWidth = input.width();
          var resultWidth = 0;
          var showWidth =   0;

          if(is_numeric(menuWidth) && menuWidth > 250) {
            menuWidth = 1;
          }

          if(is_numeric(windowWidth) && is_numeric(tohideWidth) && is_numeric(menuWidth)){
            resultWidth = windowWidth - tohideWidth - menuWidth - 50;
            if (resultWidth <= 0) {
              showWidth = windowWidth - menuWidth - 50;
              element.css({'overflow-x': 'scroll', 'max-width': showWidth+'px'});
            } else {
              element.css({'overflow-x': 'visible', 'max-width': 'auto'});
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
                }, 
            });
            $("#ad_menu").menu();
            $("#adminalltabs").tabs({
                ajaxOptions: {
                    error: function( xhr, status, index, anchor ) {
                        $( anchor.hash ).html(
                            "<?php echo $lang['error_1'];?>");
                    }
                }
            });
            $('#adminalltabs ul li a').click(function () {window.location.hash = $(this).attr('href');window.scrollTo(0, 0);});
            $('#allcontainer ul li a').click(function () {window.location.hash = $(this).attr('href');window.scrollTo(0, 0);});
        });
    </script>
</head>
<body>
<noscript>
    <?php show_message($lang['js_off']); ?>
</noscript>