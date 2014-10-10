<?php 
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
// Falls Template jquery benutzt (im XML - File), dann kann datetimepicker verwendet werden
if (strstr($_template->_jquery,'true')){ ?>
<script>
$(function() {
        $('#datetimepicker1').datetimepicker({
            language: 'de-DE',
            pickSeconds: false,
            pickDate: false
        });
        var now = new Date();
        var seldate = new Date(<?php echo $_GET['timestamp'];?>*1000);
        seldate.setHours(now.getHours());
        seldate.setMinutes(now.getMinutes());
        $('#datetimepicker1').data('datetimepicker').setLocalDate(seldate);
        $('#datetimepicker1').on('changeDate', function(e) {
          $('input[name=_w_tag]').val(e.localDate.getDate());
          $('input[name=_w_monat]').val(e.localDate.getMonth()+1);
          $('input[name=_w_jahr]').val(e.localDate.getFullYear());
          $('input[name=_w_stunde]').val(e.localDate.getHours());
          $('input[name=_w_minute]').val(e.localDate.getMinutes());
        });
});
</script>
<?php }  ?>
<form name="insert" action="?action=insert_time&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>" target="_self" method="post">
        <table width="100%" border="0" cellpadding="5" cellspacing="2">
                <tr style="display: none">
                        <td class=td_background_wochenende width="200" align=left>Datum : (Tag / Monat / Jahr)</td>
                        <td class=td_background_tag align=left>
                                <input type="text" name="_w_tag" value="<?php echo $_time->_tag; ?>" size="4">
                                <input type="text" name="_w_monat" value="<?php echo $_time->_monat; ?>" size="4">
                                <input type="text" name="_w_jahr" value="<?php echo $_time->_jahr; ?>" size="4">
                        </td>
                </tr>
<?php if (strstr($_template->_jquery,'true')){ ?>            
<?php
	//Falls eine Zeit fehlt - Auswahlmöglichkeiten anzeigen
	$_fehlzeit =  $_time->lasttime($_time->_timestamp, $_user->_ordnerpfad);
	if($_fehlzeit  && $_fehlzeit <$_time->_timestamp && strstr($_template->_jquery,'true')){	
		include("time_last_add.php");
	}
?>   
                <tr style="display: none">
                        <td class=td_background_wochenende align=left>Zeit : (Stunde:Minute)</td>
                        <td class=td_background_tag align=left>
                                <input type="text" name="_w_stunde" value="<?php echo $_time->get_stunde_now(); ?>" size="4">
                                <input type="text" name="_w_minute" value="<?php echo $_time->get_minute_now(); ?>" size="4"></td>
                </tr>
                <tr>
                        <td class=td_background_top colspan="2">
                                <div id="datetimepicker1" class="input-append">
                                        <label for="newtimestamp">Neuer Zeitstempel: </label>
                                    <input id="newtimestamp" data-format="dd.MM.yyyy hh:mm" type="text" />
                                    <span class="add-on">
                                      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                      </i>
                                    </span>
                                </div>
                        </td>
                </tr>
                <tr>
                        <td class=td_background_heute align=left width="50%" align="center">
                        </td>
                        <td class=td_background_heute align=left width="50%" align="center">
                            	<input type="submit" name="absenden" value="OK" >
                        </td>
                </tr>
<?php }else{ ?>
                <tr>
                        <td class=td_background_wochenende align=left>Zeit : (Stunde:Minute)</td>
                        <td class=td_background_tag align=left>
                                <input type="text" name="_w_stunde" value="<?php echo $_time->get_stunde_now(); ?>" size="4">
                                <input type="text" name="_w_minute" value="<?php echo $_time->get_minute_now(); ?>" size="4"></td>
                </tr>
                <tr>
                        <td class=td_background_heute align=left width="50%" align="center">
                        	<input type='submit'  name='absenden' value='CANCEL' >
                        </td>
                        <td class=td_background_heute align=left width="50%" align="center">
                            	<input type="submit" name="absenden" value="OK" >
                        </td>
                </tr>
                <tr >
                        <td ><br><br><br><br></td>
                        <td ></td>
                </tr>
                <tr >
                        <td class=td_background_wochenende valign="middle">Stunde :</td>
                        <td class=td_background_wochenende valign="middle" align=left>
                                <?php
                                for($z=5;$z<20;$z++){
                                        echo "<input type='button' value='$z' onclick='this.form._w_stunde.value = $z'>";
                                }?></td>
                </tr>
                <tr >
                        <td class=td_background_wochenende valign="middle">Minute :</td>
                        <td class=td_background_wochenende valign="middle" align=left>
                                <input type="button" value="0" onclick="this.form._w_minute.value = 0">
                                <input type="button" value="15" onclick="this.form._w_minute.value = 15">
                                <input type="button" value="30" onclick="this.form._w_minute.value = 30">
                                <input type="button" value="45" onclick="this.form._w_minute.value = 45">
                        </td>
                </tr>                
<?php } ?>	
        </table>     
</form>