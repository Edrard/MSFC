$.extend($.tablesorter.defaults, {
    headerTemplate : '<div style="padding: 0px; padding-right:12px;">{content}</div>{icon}',
    widgets : ['uitheme', 'zebra'],
    widthFixed : false,
    sortList : [[0,0]],
    theme : 'jui'
  });

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
    odd        : ''   // even row zebra striping
});
$.extend($.tablesorter.themes.bootstrap, {
    table      : 'ui-widget ui-widget-content ui-corner-all table-borders',
    header     : 'ui-widget-header ui-corner-all ui-state-default',
    footerRow  : 'ui-corner-all',
    footerCells: 'ui-widget-header ui-corner-all ui-state-default',
    icons      : '',
    sortNone   : '',
    sortAsc    : '',
    sortDesc   : '',
    active     : '',
    hover      : '',
    filterRow  : '',
    even       : 'ui-widget-content', // even row zebra striping
    odd        : ''   // even row zebra striping
});

$('.bb[title]').tooltip({
    track: false,
    delay: 0,
    fade: 250,
    items: "[title]",
    content: function() {
        var element = $( this );
        if ( element.is( "[title]" ) ) {
             return element.attr( "title" );
        }
    }
});
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
      } else {
        altRow = "dark-row";
      }
   }
  $.tablesorter.defaults.widgetOptions.zebra = [ '' , altRow ];
});