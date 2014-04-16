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
    * @version     $Rev: 3.0.4 $
    *
    */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Mysql Install</title>
        <?php if (!isset($config['theme'])) {
            $config['theme'] = 'ui-lightness'; } ?>
        <link rel="stylesheet" href="../theme/<?=$config['theme']; ?>/jquery-ui.css" type="text/css" media="print, projection, screen" />
        <link rel="stylesheet" href="../theme/style.css" type="text/css" media="print, projection, screen" />
        <script type="text/javascript" src="../js/jquery.js"></script>
        <style>
            .red{
                color:red;
                font-weight: bold;
            }
            .green{
                color:green;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div align="center"style="min-height: 100%; width:100%; padding: 0; margin: 0; border: 0px inset black !important; "
        class="ui-accordion-content ui-helper-reset ui-widget-content ui-accordion-content-active">
            <div style="height: 25%; "></div>
            <div class="adinsider">
                <form action="./index.php" method="post">
                    <table width="340px" cellspacing="4" cellpadding="0" class="ui-widget-content">
                        <tr>
                            <td colspan="2" align="center">
                                <h3>Install mysql connection</h3>
                            </td>
                        </tr>
                        <tr>
                            <td width="120" align="left">&nbsp;&nbsp;Host: </td>
                            <td align="left">
                                <input id="host" type="text" name="host" value="<?=$dbhost ? $dbhost : ''?>" size="18" />
                            </td>
                        </tr>
                        <tr>
                            <td width="120" align="left">&nbsp;&nbsp;Username: </td>
                            <td align="left">
                                <input id="user" type="text" name="user" value="<?=$dbuser ? $dbuser : ''?>" size="18" />
                            </td>
                        </tr>
                        <tr>
                            <td width="120" align="left">&nbsp;&nbsp;Password: </td>
                            <td align="left">
                                <input id="pass" type="text" name="pass" value="<?=$dbpass ? $dbpass : ''?>" size="18" />
                            </td>
                        </tr>
                        <tr>
                            <td width="120" align="left">&nbsp;&nbsp;Database name: </td>
                            <td align="left">
                                <input id="dbname" type="text" name="dbname" value="<?=$dbname ? $dbname : ''?>" size="18" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center" id="result"><br></td>
                        </tr>
                        <tr>
                            <td width="120" align="center">
                                <input type="submit" value="Check Connection" id="check">
                            </td>
                            <td  align="center">
                                <input type="submit" value="Submit" name="mysql" id="mysql">
                            </td>
                        </tr>
                    </table>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#mysql').attr('disabled','disabled');
                            $("#check").click( function() {
                                $('#mysql').attr('disabled','disabled');
                                $("#result").removeClass();
                                $.ajax({
                                    cache: true,
                                    type: "POST",
                                    data: {
                                        host    :  $('#host').val(),
                                        user  :  $('#user').val(),
                                        pass     :  $('#pass').val(),
                                        dbname : $('#dbname').val(),
                                    },
                                    url: "../ajax/mysql_check.php",
                                    success: function(msg){
                                        if(msg == 'done'){
                                            $("#result").html('Connected to Database, please press Submit button').show().addClass('green');
                                            $('#mysql').removeAttr('disabled');
                                        }else{
                                            $("#result").html(msg).show().addClass('red');
                                        }
                                    }
                                });
                                return false;
                            });
                        });

                    </script>
                </form>
            </div>
        </div>
    </body>
</html>