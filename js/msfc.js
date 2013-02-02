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
          if (is_dir('./js/')){
              $direc = '.';
              echo $lang["page_title"];
          } elseif (is_dir('../js/')){
             $direc = '..';
             echo $lang['admin_title'];
          } else { $direc = ''; }
       ?>
    </title>
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
    <script type="text/javascript" src="<?=$direc; ?>/js/jquery.tools.min.js"></script>
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
            $.datepicker.setDefaults($.datepicker.regional["<?php echo $config['lang']; ?>"]);

            $(".bb[title]").tooltip({
                track: false,
                delay: 0,
                fade: 250,
                items: "[title]",
                content: function() {
                  var element = $( this );
                  if (element.is("[title]")) {
                    return element.attr("title");
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

            $.tablesorter.defaults.headerTemplate = "<div style='padding: 0px; padding-right:12px;'>{content}</div>{icon}";
            $.tablesorter.defaults.widgets = ["uitheme", "zebra"];
            $.tablesorter.defaults.widthFixed = false;
            $.tablesorter.defaults.sortList = [[0,0]];

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
    </script>