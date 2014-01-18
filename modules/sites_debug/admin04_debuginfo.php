<Form name='login' action='?action=debug_info' method='post' target='_self'>
<input type='submit' name='delete' value='alle Meldungen l&ouml;schen' >
</form>
<hr>
<?php
if($_POST['delete']){
	echo "Alle Meldungen wurden gel&ouml;scht!";
	$fp=fopen("./debug/time.txt", "w");
	fclose($fp);
}
//$_meldungen = new time_filehandle("./debug/","time.txt",";");
$_meldungen = file("./debug/time.txt");
//showClassVar($_meldungen);
$_meldungen = array_values(array_unique($_meldungen));
//print_r($_meldungen);
//$_bugs = explode(";", $_meldungen);
echo "<Table width='100%' border=0 cellpadding=3 cellspacing=1><tr>";
foreach($_meldungen as $_zeile){
	echo "<td align=left>";
	$_zeile = explode(";", $_zeile);
	echo $_zeile[5]. " in : ";
	//echo "<br>";
	//echo $_zeile[0]. " ";
	//echo $_zeile[1]. " ";
	//echo $_zeile[2]. " ";
	//echo $_zeile[3]. " ";
	//echo "<br>";
	echo $_zeile[4]. " ";
	//echo $_zeile[5]. " ";
	
	if (strstr($_zeile[5],"Leerzeile entdeckt")){
		echo "</td>";
		echo "<Form name='login' action='?action=debug_info' method='post' target='_self'>";
		echo "<td>";
		//echo "<input type='submit' name='fix' value='reparieren' >";
		echo "<input type='hidden' name='datei' value='".$_zeile[4]."' >";
		echo "</td>";
		echo "</form>";
	}else{
		echo "</td>";
	}

	/*
	foreach($_zeile as $_spalte){
		echo $_spalte. " / " ;
	}
	*/
	echo "</tr>";
	//echo "<hr>";
}
echo "</table>";
?>

