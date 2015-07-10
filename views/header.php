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
    * @version     $Rev: 3.2.0 $
    *
    */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $lang['page_title'];
    if (isset($multiclan_info[$config['clan']]['data'][$config['clan']]['tag']))
        echo " [",$multiclan_info[$config['clan']]['data'][$config['clan']]['tag'],"]"; ?></title>
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
        $("#allcontainer").css({'width': $(window).width(), 'overflow-x': 'hidden'});
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

              $("#allcontainer").css({'width': '100%', 'overflow-x': 'visible'});
        });

        function magic(elem){
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
                    $("#tohide, #tohide2").fadeIn("fast");
                    $("#chan").removeClass("ui-icon-triangle-1-e").addClass("ui-icon-triangle-1-w");
                } else {
                    $("#tohide, #tohide2").fadeOut("fast");
                    $("#chan").removeClass("ui-icon-triangle-1-w").addClass("ui-icon-triangle-1-e");
                }
            }
        };

        function is_numeric(input){
          return typeof(input)=='number';
        };
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