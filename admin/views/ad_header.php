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
    <?php
    include_once(ROOT_DIR.'/js/msfc.js');

    if(isset($current_user)){ ?>
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
    <?php }  ?>

    <script type="text/javascript" id="js">
        $(function() {
            <?php if(isset($_GET['multi'])){ ?>
                $("#iserver").prop('disabled', true);
                $("#iclan").prop('disabled', true);
                $("#ccontrol").hide();
                $("#dccontrol").hide();
                <?php } ?>
        });
        function magic(elem)
        {
            $(".ui-menu-item").each(function(){
                if($(this).hasClass("ui-state-active")){
                    $(this).removeClass("ui-state-active");
                }
            });
            $(elem).parent('li').addClass("ui-state-active");
            if(elem.id == 'out'){ window.location = "index.php?logout=true<?=$multi_get;?>"; }
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
                    widgetOptions: {uitheme : 'jui'},
                    textExtraction: function(node) {
                        return $(node).find("span.hidden").text();
                    }
                });
                $("#users").tablesorter({
                    sortList: [[1, 0]],
                    widgetOptions: {uitheme : 'jui'}
                });
                $("#multiclan_table").tablesorter({
                    sortList: [[1, 0]],
                    widgetOptions: {uitheme : 'jui'}
                });
                <?php if(!empty($adm_top_tanks)){ ?>
                    $("#top_tanks").tablesorter({
                        sortList:[[6,0],[3,0]],
                        widgetOptions: {uitheme : 'jui'}
                    });
                    <?php } ?>
                <?php if(!empty($tanks_list)){ ?>
                    $("#tanks_list").tablesorter({
                        sortList: [[2, 0]],
                        widgetOptions: {uitheme : 'jui'},
                        textExtraction: function(node) {
                            return $(node).find("span.hidden").text();
                        }
                    });
                    <?php } ?>
        });
    </script>
</head>
<body>