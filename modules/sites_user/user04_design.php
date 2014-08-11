<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.872
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<?php if ($_GET["designname"]=="mobile"){ ?>
<script type="text/javascript">
$(document).ready(function()
{
	$("#designwahl a").click(function()
	{

		alert("Eventuell die Seite nach der Auswahl des Designs mit F5 neu laden.");
		//window.location.reload();

	});
}); 
</script>
<?php } ?>
<?php
	
//echo "<div id=debug>";
//echo "Design = " . $_COOKIE["designname"];
echo getinfotext( "Eventuell die Seite nach der Auswahl des Designs mit F5 neu laden." ,"td_background_top");
echo "<hr>";
//echo "design = ".$_template->get_template()."<hr>";
//echo "pfad = ". $_template->get_templatepfad()."<hr>";
//echo "</div>";
				
				
echo '<div id="designwahl">';
$ordner = "./templates";
$handle = opendir($ordner);
while ($file = readdir ($handle)) {
    //if($file != "." && $file != ".." && $file != "mobile") {
	if($file != "." && $file != "..") {
        //echo $file. "&nbsp;";
		//<hr><a href="javascript:location.reload()">Reload</a><hr>
		//echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'" onclick="load2(\'?action=setdesign&designname='.$file.'\')">';
		//echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'" onclick="load(\'?action=setdesign&designname='.$file.'\')">';
		//echo '<a title="'.$file.'" href="#" onclick="load(\'?action=setdesign&designname='.$file.'\')">';
		if ($_COOKIE["designname"]=="mobile"){
			//echo '<a title="'.$file.'" href="#" onclick="alert(\'?action=setdesign&designname='.$file.'\')">';
			echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'">';
		}else{
			echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'">';
		}
		
	
        //echo "<a title='$file' href='?action=setdesign&designname=$file'>";
		//echo "<a title='$file' href='?action=setdesign&designname=$file'>";

        echo "<img src='./templates/$file/images/background.jpg' width='100'>";
        echo "</a>&nbsp;";
    }
}
closedir($handle);
echo "</div>";



$ja=false;
if($ja){

echo "Akutelles Design : ". $_template->get_template() . "<hr>";
/*
echo "design zum auswählen:<br>";
echo "<a title='standard' href='?action=setdesign&name=standard'>standard</a><br>";
echo "<a title='standard' href='?action=setdesign&name=steel'>steel</a><br>";
echo "<hr>";
*/
$ordner = "./templates";
$handle = opendir($ordner);
while ($file = readdir ($handle)) {
    //if($file != "." && $file != ".." && $file != "mobile") {
	if($file != "." && $file != "..") {
        //echo $file. "&nbsp;";
		//<hr><a href="javascript:location.reload()">Reload</a><hr>
		echo "<a title='$file' href='?action=setdesign&designname=$file&time=".time()."'>";
        //echo "<a title='$file' href='?action=setdesign&designname=$file'>";
		//echo "<a title='$file' href='?action=setdesign&designname=$file'>";
		
		
        echo "<img src='./templates/$file/images/background.jpg' width='100'>";
        echo "</a>&nbsp;";
        /*
        if(is_dir($ordner."/".$file)) {
            //echo "/".$file."<br/>";
        } else {
            // kompletter Pfad
            $compl = $ordner."/".$file;
            //echo "<a href=\"".$compl."\">".$file."</a><br/>";
        }
        */
    }
}
closedir($handle);
	
}
?>