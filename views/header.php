<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-11-22 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
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
              $("#roster").tablesorter({sortList:[[5,0],[4,1],[1,0]], headers:{ 0: { sorter: false}}});

              $("#best_main")
              .add("#best_medal")
              .add("#active_main")
              .add("#active_medal_1")
              .add("#overall")
              .add("#perform")
              .add("#battel")
              .add("#achiv_epic")
              .add("#achiv_major")
              .add("#achiv_hero")
              .add("#achiv_special")
              .add("#rating")
              .add("#rating1")
              .add("#rating_all")
              .add("#average_perform")
              .add("#blocked")
              .add("#all_tanks_stat")
              .add("#perform_all")
              .add("#all_medals_stat")
              .add("#available_tanks").tablesorter();

              $("#avt1").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}}, widgetOptions: {uitheme : 'bootstrap'}});
              <? for ($i=2; $i<=6; $i++) {?>
              $("#avt<?=$i;?>").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[1,0]], widgetOptions: {uitheme : 'bootstrap'}});
              <? }
                 for ($i=7; $i<=9; $i++) {?>
              $("#avt<?=$i;?>").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[0,0]], widgetOptions: {uitheme : 'bootstrap'}});
              <? }?>

              $( "#login_dialog" ).dialog({
                  title: "<?php echo $lang['login']; ?>",
                  appendTo: "#allcontainer",
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
                         $('#table_resized').remove();
                         check_Width($('table.table-id-$key'), $('div#tabs-$key'));
                      });
                    ";
                  }
                }
              ?>
              $( "#player_result" ).dialog({
                    title: "<?=$lang['st_title'];?>",
                    appendTo: "#allcontainer",
                    autoOpen: false,
                    draggable: false,
                    resizable: false,
                    width: 1024,
                    closeOnEscape: true,
                    modal: true,
                    zIndex: 1,
                    create: function( event, ui ) {},
                    open: function( event, ui ) {$("#roster").trigger("destroy");},
                    beforeClose: function( event, ui ) {$("#roster").tablesorter({sortList:[[5,0],[4,1],[1,0]], headers:{ 0: { sorter: false}}});}
                  });
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
        function plmagic(elem){
           $.ajax({
                cache: true,
                type: "POST",
                data: ({
                  nickname   : $(elem).attr("alt")
                }),
                url: "ajax/ajax_player.php",
                success: function(msg){
                    $("#player_result").addClass("ui-state-disabled");
                    $("#player_result").html(msg).show();
                },
                complete: function() {
                  $("#player_result").removeClass("ui-state-disabled");
                  $("#player_result").dialog('open');
                  updateall();
                }
            });
        }

        function is_numeric(input){
          return typeof(input)=='number';
        };
        function check_Width(input, element) {
          return;
          if(input.length && element.length) {
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
                  $('#table_resized').remove();
                  element.before('<div id="table_resized" class="ui-state-highlight ui-corner-all" style="text-align:center;" align="center"><?=$lang["table_resized"];?></div>');

                } else {
                  element.css({'overflow-x': 'visible', 'max-width': 'auto'});
                  $('#table_resized').remove();
                }
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