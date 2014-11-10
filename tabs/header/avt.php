
$("#avt1").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}}, widgetOptions: {uitheme : 'bootstrap'}});
$("#avt2").add("#avt3").add("#avt4").add("#avt5").add("#avt6").add("#avt7").add("#avt8").add("#avt9").tablesorter({headers:{ 0: { sorter: false}, 1: {sorter: false} }, sortList:[[1,0]], widgetOptions: {uitheme : 'bootstrap'}});
$("#avt7").add("#avt8").add("#avt9").trigger("sorton",[[[0,0]]]);