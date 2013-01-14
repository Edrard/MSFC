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
        <?php if (!isset($config['theme'])) {
              $config['theme'] = 'ui-lightness'; } ?>
    <link rel="stylesheet" href="../theme/<?=$config['theme']; ?>/jquery-ui.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="../theme/style.css" type="text/css" media="print, projection, screen" />

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery.metadata.js"></script>
    <script type="text/javascript" src="../js/jquery.modal.js"></script>
    <script type="text/javascript" src="../js/jquery.qtip.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.widgets.js"></script>
    <script type="text/javascript" src="../js/jquery.ui.js"></script>
    <?php if ($config['lang'] == 'ru') { ?>
    <script type="text/javascript" src="../js/jquery.ui.ru.js"></script>
    <?php }; ?>
    <script type="text/javascript" src="../js/jquery.vticker.js"></script>

    <?php if(isset($current_user)){ ?>
        <script type="text/javascript" id="js">
            $(document).ready(function() 
            {  
                <?php foreach($current_user as $val){?>
                    $('#dialog_<?=$val['user']?>').jqm({trigger: 'a.trigger_<?=$val['user']?>'}); 
                    <?php } ?>
            });
        </script>
        <?php    }  ?>

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
    function magic(elem)
    {
        $(".ui-menu-item").each(function(){
          if($(this).hasClass("ui-state-active")){
             $(this).removeClass("ui-state-active");
          }
        });
        $(elem).parent('li').addClass("ui-state-active");
        if(elem.id == 'out'){ window.location = "index.php?logout=true"; }
    };
    $(function() {
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
    });


        $(document).ready(function() 
        {
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

            $("#files").tablesorter({
                sortList: [[2, 0]],
                widthFixed: false,
                headerTemplate : '{content} {icon}',
                widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'},
                textExtraction: function(node) {
                    return $(node).find("span.hidden").text();
                }
            });
            $("#users").tablesorter({
                sortList: [[1, 0]],
                widthFixed: false,
                headerTemplate : '{content} {icon}',
                widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
            });
            <?php if(!empty($adm_top_tanks)){ ?>
                $("#top_tanks").tablesorter({
                sortList:[[6,0],[3,0]],
                widthFixed: false,
                headerTemplate : '{content} {icon}',
                widgets: ['uitheme', 'zebra'],
                widgetOptions: {uitheme : 'jui'}
                });
                <?php } ?>
            <?php if(!empty($tanks_list)){ ?>
                $("#tanks_list").tablesorter({
                    sortList: [[2, 0]],
                    widthFixed: false,
                    headerTemplate : '{content} {icon}',
                    widgets: ['uitheme', 'zebra'],
                    widgetOptions: {uitheme : 'jui'},
                    textExtraction: function(node) {
                        return $(node).find("span.hidden").text();
                    }
                });
                <?php } ?>
        });
    </script>

</head>