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
<div align="center"style="min-height: 100%; width:100%; padding: 0; margin: 0; border: 0px inset black !important; "
class="ui-accordion-content ui-helper-reset ui-widget-content ui-accordion-content-active">
   <div align="center" class="ui-state-highlight ui-widget-content">
      Old version detected. Let's try to update.
   </div>
   <?php
   if ($oldmodule) {
       //add clan to multiclan
       $sql = 'SELECT * FROM config';
       $q = $db->prepare($sql);
       if ($q->execute() == TRUE) {
           $params = $q->fetchAll(PDO :: FETCH_ASSOC);
           foreach ($params as $val) {
               $configg[$val['name']] = $val['value'];
           }
       } else {
          die(show_message($q->errorInfo(),__line__,__file__,$sql));
       }
       //common db update
       insert_file(LOCAL_DIR.'/sql/update.sql');

       //update multiclan table
       insert_multicaln($configg['clan'],$configg['server'],'');

       //medals update
       $int11 = array('sinai', 'evileye', 'medalDeLanglade', 'medalTamadaYoshio',
                      'medalNikolas', 'medalLehvaslaiho', 'medalDumitru',
                      'medalPascucci', 'medalLafayettePool', 'medalRadleyWalters',
                      'medalTarczay', 'medalBrunoPietro', 'medalCrucialContribution',
                      'medalBrothersInArms', 'huntsman', 'luckyDevil', 'ironMan',
                      'sturdy', 'pattonValley', 'heroesOfRassenay', 'bombardier',
                      'mechanicEngineer');
       $tinyint1 = array('tankExperts_usa', 'tankExperts_france', 'tankExperts_ussr',
                         'tankExperts_china', 'tankExperts_uk', 'tankExperts_germany',
                         'mechanicEngineers_usa', 'mechanicEngineers_france',
                         'mechanicEngineers_ussr', 'mechanicEngineers_china',
                         'mechanicEngineers_uk', 'mechanicEngineers_germany');

       $alter = array('tankExpertUSA', 'tankExpertFrance', 'tankExpertUSSR',
                      'tankExpertChina', 'tankExpertBrit', 'tankExpertGermany',
                      'mechanicEngineerUSA', 'mechanicEngineerFrance',
                      'mechanicEngineerUSSR', 'mechanicEngineerChina',
                      'mechanicEngineerBrit',  'mechanicEngineerGermany');

       $alter2 = array();

       $sql = 'SHOW COLUMNS FROM `col_medals`';
       $q = $db->prepare($sql);
       if ($q->execute() == TRUE) {
           $medss = $q->fetchAll();
           foreach ($medss as $val) {
              foreach ($int11 as $key => $val2) {
                if ($val[0]== $val2) unset($int11[$key]);
              }
              foreach ($alter as $key2 => $val22) {
                if ($val[0]== $val22) {
                    $alter2[$key2] = $val22;
                    unset($alter[$key2]);
                }
              }
           }
           if (!empty($alter2)) {
                //update fields from medals mod
                $tsql = "ALTER TABLE `col_medals` ";
                foreach ($alter2 as $key2 => $val2) {
                   if ($tsql[strlen($tsql)-1] <> ' ') $tsql .= ', ';
                   $tsql .= "CHANGE `".$val2."` `".$tinyint1[$key2]."` TINYINT(1) NOT NULL";
                   unset($tinyint1[$key2]);
                }
                $tsql .= ";";
                $q = $db->prepare($tsql);
                if ($q->execute() <> TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$tsql));
                }
           }
           if (!empty($int11)) {
               //add new fields int (11)
               $tsql = "ALTER TABLE `col_medals` ";
               foreach ($int11 as $key11 => $val11) {
                  if ($tsql[strlen($tsql)-1] <> ' ') $tsql .= ', ';
                  $tsql .= "ADD `".$val11."` INT(11) NOT NULL";
               }
               $tsql .= ";";
               $q = $db->prepare($tsql);
               if ($q->execute() <> TRUE) {
                   die(show_message($q->errorInfo(),__line__,__file__,$tsql));
               }
           }
           if (!empty($tinyint1)) {
                //add new fields tinyint (1)
                $tsql = "ALTER TABLE `col_medals` ";
                foreach ($tinyint1 as $key1 => $val1) {
                   if ($tsql[strlen($tsql)-1] <> ' ') $tsql .= ', ';
                   $tsql .= "ADD `".$val1."` TINYINT(1) NOT NULL";
                }
                $tsql .= ";";
                $q = $db->prepare($tsql);
                if ($q->execute() <> TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$tsql));
                }
           }
           // redirecting available tanks to new tab
           $tsql = "UPDATE `tabs` SET `file`='available_tanks.php' WHERE `file`='aval_top_tank.php';";
           $q = $db->prepare($tsql);
           if ($q->execute() <> TRUE) {
               die(show_message($q->errorInfo(),__line__,__file__,$tsql));
           }
           //some movements for avt tab
           $avtexist = false;
           $tsql = 'SELECT id, file FROM `tabs` ORDER BY ID';
           $q = $db->prepare($tsql);
           if ($q->execute() == TRUE) {
               $temps = $q->fetchAll(PDO :: FETCH_ASSOC);
               foreach ($temps as $val) {
                   $tabss[$val['id']] = $val['file'];
                   if ($val['file'] == 'avt.php') { $avtexist = true;} ;
               }
           } else {
              die(show_message($q->errorInfo(),__line__,__file__,$tsql));
           }
           if (!($avtexist)) {
              $i = 1; $sql = '';
              if (isset($tabss[$i])) { $tempfile = $tabss[$i];} ;
              for ($i = 1; $i <= 999; $i++) {
                  if (!isset($tabss[$i])) {
                       $tsql = "UPDATE `tabs` SET `id`='".$i."' WHERE `file`='".$tabss[$i]."';";
                       break;
                  }
              }
              $tsql .= "INSERT INTO `tabs` (`id`, `name`, `file`, `type`, `status`, `auth`) VALUES
                       (1, 'Приветственное', 'avt.php', 0, 1, 'all');";
              $q = $db->prepare($tsql);
              if ($q->execute() <> TRUE) {
                  die(show_message($q->errorInfo(),__line__,__file__,$tsql));
              }
           }
           unset($params, $configg, $medss, $tabss, $temps);
           $cache->clear_all(array(), ROOT_DIR.'/cache/');
       } else {
          die(show_message($q->errorInfo(),__line__,__file__,$sql));
       }
   }
?>
   <div align="center" class="ui-state-highlight ui-widget-content">
      Update complete. Redirecting to main page.
   </div>
<?php
   if (!headers_sent()) {
        header ( 'Location: '.ROOT_DIR.'index.php');
        exit;
   } else { print_R('<script type="text/javascript">
        location.replace("./index.php");
        </script>');
   }
?>
</div>