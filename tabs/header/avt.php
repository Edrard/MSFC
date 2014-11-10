
$("#avt1").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}}, widgetOptions: {uitheme : 'bootstrap'}});
<? for ($i=2; $i<=6; $i++) { ?>
$("#avt<?=$i;?>").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[1,0]], widgetOptions: {uitheme : 'bootstrap'}});
<? }
   for ($i=7; $i<=9; $i++) { ?>
$("#avt<?=$i;?>").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[0,0]], widgetOptions: {uitheme : 'bootstrap'}});
<? } ?>