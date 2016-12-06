<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.007
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$meldung = '';
if(isset($_GET['del'])){
	$id = $_GET['del'] -1;
	pausen::delete($id);
}
if(@$_POST['speichern']=="Speichern"){	
	$meldung = pausen::save(); 
}
$pausen = pausen::get();
?>
<script>
	$( document ).ready(function() {		

		});	
</script>
<style>
	td{
		padding: 5px;
	}
	.td1{
		width: 20px;
		text-align: center;
	}
	.td2{
		width: 70px;
	}
	.td3{
		width: 90px;
	}
	.img{
		width: initial;
	}
</style>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td colspan="9" class="alert alert-info" width="100" align="left">Automatische Pausenregelung</td>
	</tr>
</table>
<p>
	Pausen werden automatisch abgezogen ab erreichen der Arbeitszeit.<br>
	Eintrag: von 2 Stunden bis 3 Stunden 15 Minuten Pause abziehen.<br>
	Berechnet wird ein Abzug von 15 Minuten von 2 Stunden bis 2 Stunden 59 Min und 59 Sekunden Arbeitszeit.
</p><br>
<?php
if($meldung<>''){
	
	echo '
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
	<td colspan="9" class="alert alert-danger" width="100" align="left">' . $meldung . '</td>
	</tr>
	</table>
	';
}
?>



<form  enctype="multipart/form-data" method="POST" action="?action=settings&menue=pausen">
	<table width="100%">
		<tr>
			<td class="td2 alert-info">
				Action
			</td >

			<td  class="td1 alert-info">
				ID
			</td >

			<td  class="td3 alert-info">
				von Stunden
			</td >

			<td  class="td3 alert-info">
				bis Stunden
			</td >

			<td  class="alert-info">
				Abzug der Pause von der Arbeitszeit in Minuten
			</td >
		</tr>

		<?php 
		$p=1;
		if(strpos($pausen[0],'Daten') == false){

			foreach($pausen as $pause){
				$pause = explode(';',$pause);
				?>
				<!-- PAUSEN -->
				<tr>
					<td class="td2">
						<a title="delete" href="?action=settings&menue=pausen&del=<?php echo $p?>">
							<img class="img" src="images/icons/delete.png" border="0" width="5px"> delete</a>
					</td>
					<td class="td1">
						<?php echo $p?>
					</td>
					<td class="td3">
						<input type="text" size="2" name="time1_<?php echo $p?>" value="<?php echo $pause[0]?>" style="width:80px;">
					</td>
					<td class="td3">
						<input type="text" size="2" name="time2_<?php echo $p?>" value="<?php echo $pause[1]?>" style="width:80px;">
					</td>
					<td>
						<input type="text" size="2" name="pause_<?php echo $p?>" value="<?php echo $pause[2]?>" style="width:80px;">
					</td>
				</tr>
				<?php 
				$p++;
			}
		}
		?>


		<!-- INPUT FUER  NEUEN PAUSEN -->
		<tr>
			<td class="td2">
				<img class="img" src="images/icons/add.png" border="0" width="5px">
			</td>
			<td class="td1">
				#
			</td>
			<td class="td3">
				<input type="text" size="2" name="time1_x" value="" style="width:80px;">
			</td>
			<td class="td3">
				<input type="text" size="2" name="time2_x" value="" style="width:80px;">
			</td>
			<td>
				<input type="text" size="2" name="pause_x" value="" style="width:80px;">
			</td>
		</tr>
		<tr>
			<td colspan="5" class="alert-info" align="center">
				<input type="submit" name='speichern' value="Speichern">	
			</td>
		</tr>

	</table>
</form>
