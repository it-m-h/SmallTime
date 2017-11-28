<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.017
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
//--------------------------------------------------------------
//img- Pfad überprüfen und erstellen falls nicht vorhanden
//--------------------------------------------------------------
$_tmppfad = "./Data/".$_user->_ordnerpfad."/img/";
if(!file_exists($_tmppfad))
{
	mkdir($_tmppfad);
}
//--------------------------------------------------------------
//Berechtigung überprüfen und setzten falls nötig
//--------------------------------------------------------------
check_htaccess($_tmppfad.".htaccess", true, ".htaccess im Personalbildpfad gesetzt");
//--------------------------------------------------------------
//Bild upload Überprüfung
//--------------------------------------------------------------
if($_POST['submit'])
{
	$dateityp = GetImageSize($_FILES['datei']['tmp_name']);
	if($dateityp[2] == 2)
	{
		move_uploaded_file($_FILES['datei']['tmp_name'], "./Data/".$_user->_ordnerpfad."/img/bild.jpg");
		include_once ('./include/class_foto.php');
		$foto = new foto("./Data/".$_user->_ordnerpfad."/img/bild.jpg", 250, 300);
	}
	else
	{
		echo "Bitte nur Bilder im jpg Format hochladen.<hr>";
	}
}

$_count = count($_personaldaten->_personaldaten) + 1;
if($_POST['update'])
{
	for($i = 0; $i < $_count ;$i++)
	$array[] = $_POST["feld_$i"];
	$_personaldaten->save_data($array);
	$_personaldaten->load_data();
}


$_bild = "./Data/".$_user->_ordnerpfad."/img/bild.jpg";
if(!file_exists($_bild))
{
	$_bild = "./images/ico/jpg.png";
}
?>
<form action="?action=user_personalkarte&admin_id=<?php echo $_SESSION['id'] ?>" method="post" enctype="multipart/form-data">
	<table border=0 height="100%" width="100%" cellpadding=3 cellspacing=1>
		<tr>
			<td valign="top" width="250" align="left" rowspan="<?php echo $_count ?>">
			<img src="<?php echo $_bild ."?".  time()   ?>" alt="" id="foto" width="250"/><br/><br/><hr	>
			<input type="file" name="datei"><br/>
			<input type="submit" name='submit' value="Hochladen">
			<br/><hr	>
			<br/>
			<u>
				Bild - Empfehlung:
			</u><br/>
			250x300 Pixel,
			<b>
				JPG
			</b><br/>
			Bild wird automatisch verkleinert.	<br/>
			<br/>
			Aktuelles Bild:<br/>
			<?php echo $_bild ?>
			<br/>
		</tr>
		<?php
		$x = 0;
		foreach($_personaldaten->_personaldaten as $_zeilen)
		{
			echo "
			<tr>
			<td class=td_background_tag width=120 align=left>$_zeilen[0]</td>
			<td class=td_background_tag align=left><input type='text' name='feld_$x' value='$_zeilen[1]' size='40'></td>
			</tr>";
			$x++;
		}
		?>
		<tr>
			<td >
			</td>
			<td class="alert alert-error" align="center" colspan="2">
				<input type="submit" name='update' value="update">
			</td>
		</tr>

	</table>
</form>
<br>