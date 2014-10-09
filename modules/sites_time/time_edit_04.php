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
			$('#datetimepicker2').datetimepicker({
					language: 'de-DE',
					pickSeconds: false,
					pickDate: false
				});
			var now = new Date();
			var seldate = new Date(<?php echo$_GET['timestamp'];?>*1000);
			$('#datetimepicker2').data('datetimepicker').setLocalDate(seldate);
			$('#datetimepicker2').on('changeDate', function(e) {
					$('input[name=_w_stunde]').val(e.localDate.getHours());
					$('input[name=_w_minute]').val(e.localDate.getMinutes());
				});
			$('.bootstrap-datetimepicker-widget').css("margin-left", "-219px");
		});
</script>
<?php } ?>
<form name="insert" action="?action=update_time&timestamp=<?php echo $_time->_timestamp ?>&token=<?php echo $token ?>" method="post" target="_self">
	<table width="100%" border="0" cellpadding="5" cellspacing="2">
		<tr>
			<td class=td_background_wochenende width="200" align=left>Datum : (Tag / Monat / Jahr)</td>
			<td class=td_background_tag align=left>
				<?php echo $_time->_tag."."; ?><input type="hidden" type="text" name="_w_tag" value="<?php echo $_time->_tag; ?>" size="4">
				<?php echo $_time->_monat."."; ?><input type="hidden" type="text" name="_w_monat" value="<?php echo $_time->_monat; ?>" size="4">
				<?php echo $_time->_jahr; ?><input type="hidden" type="text" name="_w_jahr" value="<?php echo $_time->_jahr; ?>" size="4">
			</td>
		</tr>
<?php if (strstr($_template->_jquery,'true')){ ?>
		<tr style="display: none">
			<td class=td_background_wochenende align=left>Zeit : (Stunde:Minute)</td>
			<td class=td_background_tag align=left>
				<input type="text" name="_w_stunde" value="<?php echo $_time->_stunde; ?>" size="4">
				<input type="text" name="_w_minute" value="<?php echo $_time->_minute; ?>" size="4"></td>
		</tr>
		<tr>
			<td class=td_background_top>
				<div id="datetimepicker2" class="input-append" style="margin-top: 6px;">
					<input id="newtimestamp" data-format="hh:mm" type="text" />
					<span class="add-on">
						<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
					</span>
				</div>
			</td>
			<td class=td_background_top align=left>  
				<input type="submit" name="absenden" value="UPDATE" class="btn">
				<!-- <input type="submit" name="absenden" value="CANCEL" class="btn"> !-->
			</td>
		</tr>	
<?php }else{ ?>
		<tr>
			<td class=td_background_wochenende align=left>Zeit : (Stunde:Minute)</td>
			<td class=td_background_tag align=left>
				<input type="text" name="_w_stunde" value="<?php echo $_time->_stunde; ?>" size="4">
				<input type="text" name="_w_minute" value="<?php echo $_time->_minute; ?>" size="4"></td>
		</tr>
		<tr>
			<td class=td_background_top>		
			</td>
			<td class=td_background_top align=left>  
				<input type="submit" name="absenden" value="UPDATE" class="btn">	
				<input type="submit" name="absenden" value="CANCEL" class="btn">
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
		<tr>
			<td class=td_background_heute align=right colspan="2">  
				<input type='submit'  name='absenden' value='DELETE' class="btn">
			</td>
		</tr>
	</table>
</form>