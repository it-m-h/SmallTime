<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.020
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
if(@$_GET["designname"] == "mobile")
{
?>
	<script type="text/javascript">
		$(document).ready(function()
			{
				$("#designwahl a").click(function()
					{
						alert("Eventuell die Seite nach der Auswahl des Designs mit F5 neu laden.");
					});
			});
	</script>
<?php
}
echo getinfotext( "Eventuell die Seite nach der Auswahl des Designs mit F5 neu laden.","td_background_top");
echo "<hr>";
echo '<div id="designwahl">';
$ordner = "./templates";
$handle = opendir($ordner);
while($file = readdir ($handle))
{
	if($file != "." && $file != "..")
	{
		if(@$_COOKIE["designname"] == "mobile")
		{
			echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'">';
		}
		else
		{
			echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'">';
		}
		echo "<img src='./templates/$file/images/background.jpg' width='100'>";
		echo "</a>&nbsp;";
	}
}
closedir($handle);
echo "</div>";
$ja = false;
if($ja)
{
	echo "Akutelles Design : ". $_template->get_template() . "<hr>";
	$ordner = "./templates";
	$handle = opendir($ordner);
	while($file = readdir ($handle))
	{
		if($file != "." && $file != "..")
		{
			echo "<a title='$file' href='?action=setdesign&designname=$file&time=".time()."'>";
			echo "<img src='./templates/$file/images/background.jpg' width='100'>";
			echo "</a>&nbsp;";
		}
	}
	closedir($handle);
}
?>