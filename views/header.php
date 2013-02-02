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
    <script type="text/javascript" id="js">
        $(document).ready(function()
            {
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

                $( "#acc_medals" ).accordion({collapsible: true, active: false, autoHeight: false});

                $( "#login_dialog" ).dialog({
                    title: "<?php echo $lang['login']; ?>",
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
            $('#allcontainer ul li a').click(function () {window.location.hash = $(this).attr('href');window.scrollTo(0, 0);});
        });
    </script>
</head>
<body>
<noscript>
    <?php show_message($lang['js_off']); ?>
</noscript>