<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_w2_jahr = date("Y", $_fehlzeit);
$_w2_monat = date("n", $_fehlzeit);
$_w2_tag = date("j", $_fehlzeit);
$_w2_stunde= date("H", $_fehlzeit);
$_w2_minute = date("i", $_fehlzeit);
$_w2_sekunde=0;
$_txt = $_w2_tag.'.'.$_w2_monat.'.'.$_w2_jahr;		
?>		
<script>
	$(function() {
			$('#datetimepicker2').datetimepicker({
					language: 'de-DE',
					pickSeconds: false,
					pickDate: false,
				});
			var now2 = new Date();
			var seldate2 = new Date(<?php echo $_fehlzeit;?>*1000);
			seldate2.setHours(now2.getHours());
			seldate2.setMinutes(now2.getMinutes());
			$('#datetimepicker2').data('datetimepicker').setLocalDate(seldate2);
			$('#datetimepicker2').on('changeDate', function(e) {
					$('input[name=_w2_stunde]').val(e.localDate.getHours());
					$('input[name=_w2_minute]').val(e.localDate.getMinutes());
				});		
		});
	function oldshow(){
		wert = 0;
		wert = document.getElementById("oldtime").value;
		if (wert==1){
			$('#datetimepicker2').css("visibility", "visible") ;  	
		}else{
			$('#datetimepicker2').css("visibility", "hidden") ; 
		}
	}	
</script>	
<tr style="display: none">
	<td class=td_background_info width="200" align=left>Datum:</td>
	<td class=td_background_info align=left>
		<input type="text" name="_w2_tag" value="<?php echo $_w2_tag; ?>" size="4">
		<input type="text" name="_w2_monat" value="<?php echo $_w2_monat; ?>" size="4">
		<input type="text" name="_w2_jahr" value="<?php echo $_w2_jahr; ?>" size="4">
		<input type="text" name="_w2_stunde" value="<?php echo $_w2_stunde; ?>" size="4">
		<input type="text" name="_w2_minute" value="<?php echo $_w2_minute; ?>" size="4">
	</td>
</tr>
<tr>
	<td class=td_background_info colspan="2">
		<label for="oldtime">Fehlender Zeitstempel vom : <?php echo $_txt; ?></label>
		<select id = "oldtime" name='oldtime' size='1' onchange="oldshow()">
			<option value='0' selected>keine Eingabe</option>
			<option value='1'>Zeit eintragen</option>
			<option value='2'>&uuml;ber Mitternacht gearbeitet</option>
		</select>
		<div id="datetimepicker2" class="input-append" style="visibility: hidden">
			<input id="newtimestamp2" data-format="hh:mm" type="text"></input>
			<span class="add-on">
				<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			</span>
		</div>
	</td>
</tr>

