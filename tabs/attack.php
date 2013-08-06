<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2012
    * Date:        $Date: 2012-12-01 $
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2011-2012 SHW
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<script type="text/javascript">
    $(document).ready(function(){
        x = new Date();
        y = - x.getTimezoneOffset()/60;
        $.ajax({
            cache: true,
            type: "POST",
            data: {
                  diff    : y,
                  db_pref : '<?php echo $db->prefix; ?>',
                  key     : '<?=$key;?>'
            },
            url: "./ajax/ajax_attack.php",
            success: function(msg){
                $("#attack_result").html(msg).show();
            }
        });
});
</script>
<div align="center">

    <div id="attack_result"></div>
</div>