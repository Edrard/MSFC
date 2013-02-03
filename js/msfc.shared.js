/** Код выполняемый после загрузки страницы **/
$(document).ready(function() {
  $('#rotate').vTicker({
      speed: 500,
      pause: 5000,
      showItems: 1,
      animation: 'fade',
      mousePause: false,
      height: 0,
      direction: 'down'
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
  var altRow = '';
  if (exist == true) {
       if ((rgbColors[0] > 224)&&(rgbColors[1] > 224)&&(rgbColors[2] > 224)) {
        altRow = "light-row";
      }
      if ((rgbColors[0] < 17)&&(rgbColors[1] < 17)&&(rgbColors[2] < 17)) {
        altRow = "dark-row";
      }
   }
  $.tablesorter.defaults.widgetOptions.zebra = [ '' , altRow ];
});
/** Общие функции и код **/
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
$.tablesorter.defaults.headerTemplate = '<div style="padding: 0px; padding-right:12px;">{content}</div>{icon}';
$.tablesorter.defaults.widgets = ['uitheme', 'zebra'];
$.tablesorter.defaults.widthFixed = false;
$.tablesorter.defaults.sortList = [[0,0]];
$.tablesorter.defaults.widgetOptions.uitheme = 'jui';

$.extend($.tablesorter.themes.jui, {
    table      : 'ui-widget ui-widget-content ui-corner-all table-borders', // table classes
    header     : 'ui-widget-header ui-corner-all ui-state-default', // header classes
    footerRow  : 'ui-corner-all',
    footerCells: 'ui-widget-header ui-corner-all ui-state-default',
    icons      : 'ui-icon', // icon class added to the <i> in the header
    sortNone   : 'ui-icon-carat-2-n-s',
    sortAsc    : 'ui-icon-carat-1-n',
    sortDesc   : 'ui-icon-carat-1-s',
    active     : 'ui-state-active', // applied when column is sorted
    hover      : 'ui-state-hover',  // hover class
    filterRow  : '',
    even       : 'ui-widget-content', // even row zebra striping
    odd        : 'ui-priority-secondary'   // even row zebra striping
});
$.extend($.tablesorter.themes.bootstrap, {
    table      : 'ui-widget ui-widget-content table-borders', // table classes
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