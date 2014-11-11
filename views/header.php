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
    * @version     $Rev: 3.1.2 $
    *
    */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $lang['page_title'];
    if (isset($multiclan_info[$config['clan']]['data'][$config['clan']]['abbreviation']))
        echo " [",$multiclan_info[$config['clan']]['data'][$config['clan']]['abbreviation'],"]"; ?></title>
    <?php if (!isset($config['theme'])) {
        $config['theme'] = 'ui-lightness'; } ?>
    <link rel="stylesheet" href="./theme/<?=$config['theme']; ?>/jquery-ui.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="./theme/style.css" type="text/css" media="print, projection, screen" />
    <link rel="shortcut icon" href="/favicon.ico" />
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
        $("#allcontainer").css({'height': $(window).height(), 'width': $(window).width(), 'overflow-x': 'hidden', 'overflow-y': 'hidden' });
        $(document).ready(function() {
              <?php
                $tabs_keys = read_tabs('WHERE `status` = "1" AND `type` = "0"');
                $tmp = array_keys($tabs_keys);

                echo '$("#tabs-sort-'.array_pop($tmp).'")';

                foreach($tmp as $id) {
                  echo '.add("#tabs-sort-'.$id.'")';
                }

                echo '.tablesorter();';
              ?>

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

              if (typeof window.currentTabID === "undefined") {
                 window.currentTabID = getTabID();
              }
              if (typeof window.MenuStatus === "undefined") {
                 window.MenuStatus = 'visible';
              }

              $("#allcontainer").css({'height': '100%', 'width': '100%', 'overflow-x': 'visible', 'overflow-y': 'visible' });
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
        };
        function plmagic(elem){
           $.ajax({
                cache: true,
                type: "POST",
                data: ({
                  nickname   : $(elem).attr("alt")
                }),
                url: "ajax/ajax_player.php",
                beforeSend : function(data){
                  $("#player_result").dialog('open');
                  $("#player_result").html("<center><?=$lang['index_loading'];?><br /><img src='./images/ajax-loader.gif'></center>");
                },
                success: function(msg){
                    $("#player_result").empty();
                    $("#player_result").html(msg);
                    $("#allcontainer").css({'min-height': ($('.pstat').outerHeight(true) + 200) + 'px'});
                },
                complete: function() {
                  $('body,html').scrollTop( 0 );
                  updateall();
                }
            });
        }

        function is_numeric(input){
          return typeof(input)=='number';
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
    <?php //including tabs js
      foreach($tabs_keys as $key => $val) {
        if(file_exists(ROOT_DIR.'/tabs/header/'.$val['file']) and $logged >= $val['auth']) {
          include(ROOT_DIR.'/tabs/header/'.$val['file']);
        }
      }
    ?>
    <script type="text/javascript">
    $(document).ready(function() {
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
    });
    </script>
</head>
<body>
<noscript>
    <?php show_message($lang['js_off']); ?>
</noscript>