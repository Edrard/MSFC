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
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.1.0 $
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
    <script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.widgets.js"></script>
    <script type="text/javascript" src="../js/jquery.ui.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.min.js"></script> 
    <?php if ($config['lang'] == 'ru') { ?>
        <script type="text/javascript" src="../js/jquery.ui.ru.js"></script>
        <?php }; ?>
    <script type="text/javascript" src="../js/jquery.vticker.js"></script>
    <script type="text/javascript" src="../js/msfc.shared.js"></script>
    <script type="text/javascript" id="js">
        $(document).ready(function() {
            $("#files").tablesorter({
                sortList: [[2, 0]],
                textExtraction: function(node) {
                    return $(node).find("span.hidden").text();
                }
            });
            $("#users").tablesorter();
            $("#multiclan_table").tablesorter();

            <?php if(!empty($adm_top_tanks)){ ?>
                $("#top_tanks").tablesorter({ sortList:[[6,0],[3,0]]});
            <?php } ?>
            <?php if(!empty($tanks_list)){ ?>
                $("#tanks_list").tablesorter({
                    sortList: [[2, 0]],
                    widgets : ['uitheme', 'zebra', 'filter'],
                    widgetOptions : {
                    filter_cssFilter : 'ui-datepicker-row-break'
                    },
                    textExtraction: function(node) {
                        return $(node).find("span.hidden").text();
                    }
                });
            <?php } ?>
            <?php if(isset($_GET['multi'])){ ?>
                $("#iserver").prop('disabled', true);
                $("#iclan").prop('disabled', true);
                $("#ccontrol").hide();
                $("#dccontrol").hide();
            <?php } ?>
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

            <?php $tablangs= array ('en', 'pl', 'ru', 'sk');
                  foreach ($tablangs as $val ) { ?>
                     $('#load<?=$val; ?>').click(function(){
                        <?php foreach($tabs_lang[$val] as $key => $val2){ ?>
                           $('#<?=$key; ?>php').val('<?=$val2; ?>');
                        <?php };?>
                     });
            <?php };?>
            <? if($config['company'] == 1 ) { ?>
              $( "ul.droptrue" ).sortable({
                  connectWith: "ul",
                  cursor: "move",
                  appendTo: ".pl_list",
                  dropOnEmpty: true,
                  receive: function(e, ui) {
                      var receiver = ui.item.closest('ul').attr('id');
                      $('#'+receiver+' li').sort(function(a, b){
                         return $(a).text().toLowerCase() > $(b).text().toLowerCase();
                      }).appendTo('ul#'+receiver);
                  },
                  create: function( event, ui ) {
                      $('#sortable0 li').sort(function(a, b){
                         return $(a).text().toLowerCase() > $(b).text().toLowerCase();
                      }).appendTo('ul#sortable0');
                  }
              });
              $( ".droptrue" ).disableSelection();
              $('#company_button').button().click( function() {
                  $.ajax({
                      cache: true,
                      type: "POST",
                      data: {
                          company : <?=$config['company_count']; ?>,
                          id : <?=$config['clan']; ?>,
                          <?php  for($index=1;$index<=$config['company_count'];$index++) { ?>
                              sort<?=$index;?> : $('#sortable<?=$index;?>').sortable('serialize'),
                          <?php } ?>
                      },
                      url: "../ajax/ajax_company.php"
                  });
                  return false;
              });
            <? } ?>
        });
        function magic(elem){
            $(".ui-menu-item").each(function(){
                if($(this).hasClass("ui-state-active")){
                     $(this).removeClass("ui-state-active");
                }
            });
            $(elem).parent('li').addClass("ui-state-active");
            if(elem.id == 'out'){ window.location = "../index.php?<?=$multi_get;?>"; }
            if(elem.id == 'out2'){ window.location = "index.php?logout=true<?=$multi_get;?>"; }
        };
        function magic3(){
          $(".admin_cdhide").toggle();
        };
        function magic4(){
          $(".admin_cdhide2").toggle();
        };
    </script>
    <?php if(isset($current_user)){ ?>
      <script type="text/javascript">
          $(document).ready(function()
            {
              <?php foreach($current_user as $val){?>
                  $('#dialog_<?=$val['user']?>').dialog({appendTo: "#adminalltabs", autoOpen: false});
                  $('.trigger_<?=$val['user']?>').click(function(){
                     $('#dialog_<?=$val['user']?>').dialog("open");
                     return false;
                  });
              <?php } ?>
        <?php if($config['cron'] == '0'){ ?>
          $(".admin_cdhide").hide();
        <?php } ?>
        <?php if($config['company'] == '0'){ ?>
          $(".admin_cdhide2").hide();
        <?php } ?>
            });
      </script>
    <?php } ?>
</head>
<body>