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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$lang['page_title']; ?></title>
    <?php if (!isset($config['theme'])) {
        $config['theme'] = 'ui-lightness'; } ?>
    <link rel="stylesheet" href="./theme/<?=$config['theme']; ?>/jquery-ui.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="./theme/style.css" type="text/css" media="print, projection, screen" />
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery.metadata.js"></script>
    <script type="text/javascript" src="./js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="./js/jquery.tablesorter.widgets.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.js"></script>
    <?php if ($config['lang'] == 'ru') { ?>
        <script type="text/javascript" src="./js/jquery.ui.ru.js"></script>
    <?php }; ?>
    <script type="text/javascript" src="./js/jquery.vticker.js"></script>
    <script type="text/javascript" src="./js/msfc.shared.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

              $("#roster").tablesorter({sortList:[[4,0],[3,1],[0,0]]});
              $("#best_main").tablesorter();
              $("#best_medal").tablesorter();
              $("#active_main").tablesorter();
              $("#active_medal_1").tablesorter();
              $("#overall").tablesorter();
              $("#perform").tablesorter();
              $("#battel").tablesorter();
              $("#achiv_epic").tablesorter();
              $("#achiv_major").tablesorter();
              $("#achiv_hero").tablesorter();
              $("#achiv_special").tablesorter();
              $("#rating").tablesorter();
              $("#rating1").tablesorter();
              $("#rating_all").tablesorter();
              $("#average_perform").tablesorter();
              $("#blocked").tablesorter();
              $("#all_tanks_stat").tablesorter();
              $("#perform_all").tablesorter();
              $("#all_medals_stat").tablesorter();
              $("#msfc7").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[0,0]], widgetOptions: {uitheme : 'bootstrap'}});
              $("#msfc8").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[0,0]], widgetOptions: {uitheme : 'bootstrap'}});
              $("#msfc9").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[0,0]], widgetOptions: {uitheme : 'bootstrap'}});
              $("#available_tanks").tablesorter();

              $( "#login_dialog" ).dialog({
                  title: "<?php echo $lang['login']; ?>",
                  autoOpen: false,
                  draggable: false,
                  resizable: false,
                  width: 500,
                  show: "blind",
                  hide: "blind",
                  modal: true
              });

              $('#login_opener').click(function() {
                  $("#login_dialog").dialog('open');
                  return false;
              });
              $.datepicker.setDefaults($.datepicker.regional["<?php echo $config['lang']; ?>"]);

              $("#menu").menu();
              $("#allcontainer").tabs({
                  ajaxOptions: {
                      error: function( xhr, status, index, anchor ) {
                          $( anchor.hash ).html(
                              "<?php echo $lang['error_1'];?>");
                      }
                  }
              });
              $('#allcontainer ul li a').click(function () {window.location.hash = $(this).attr('href');window.scrollTo(0, 0);});

              if (typeof window.currentTabID === "undefined") {
                 window.currentTabID = getTabID();
                 check_Width($("table.table-id-"+window.currentTabID), $("div#tabs-"+window.currentTabID));
              }
              if (typeof window.MenuStatus === "undefined") {
                 window.MenuStatus = 'visible';
              }
              <?php
                foreach($tabs as $key => $val) {
                  if(is_numeric($key)) {
                    echo "
                      $('#id-$key').click(function() {
                         check_Width($('table.table-id-$key'), $('div#tabs-$key'));
                      });
                    ";
                  }
                }
              ?>
        });

        function magic(elem){
           $(".ui-menu-item").each(function(){
              if($(this).hasClass("ui-state-active")){
                 $(this).removeClass("ui-state-active");
              }
           });
           $(elem).parent('li').addClass("ui-state-active");
           window.currentTabID = getTabID();
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
                    window.MenuStatus = 'visible';
                } else {
                    $("#tohide").fadeOut("fast");
                    $("#tohide2").fadeOut("fast");
                    $("#chan").removeClass("ui-icon-triangle-1-w");
                    $("#chan").addClass("ui-icon-triangle-1-e");
                    window.MenuStatus = 'hidden';
                }
            }
            if(is_numeric(window.currentTabID)) {
               check_Width($("table.table-id-"+window.currentTabID), $("div#tabs-"+window.currentTabID));
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

          if(window.MenuStatus == 'hidden') {
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
        function getTabID() {
          var id = $("#menu .ui-state-active").val();
          if(is_numeric(id) && (id > 0)) {
            return id;
          } else {
            return 'ajax';
          }
        }
    </script>
</head>
<body>
<noscript>
    <?php show_message($lang['js_off']); ?>
</noscript>