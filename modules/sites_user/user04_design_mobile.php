<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<script type="text/javascript">
	function load(seite)
	{
		$.ajax(
			{
				url: seite,
				type: 'get',
				dataType: 'text',
				async: true,
				success: function(response)
				{
					document.getElementById("div_body").innerHTML = response;
				}
			});
		alert("Seite bitte nach der Auswahl des Designs mit F5 aktualisieren, oder erneut ein Design anklicken.");
	}

	function reload()
	{
		alert("Seite bitte nach der Auswahl des Designs mit F5 aktualisieren, oder erneut ein Design anklicken.");
		window.location.reload();
	}
	$(document).ready(function()
		{
			$("#navigation2 a").click(function()
				{
					var pageToLoad = $(this).attr("href");
					var pageContent;
					alert("Seite bitte nach der Auswahl des Designs mit F5 neu laden.");
					$.get(pageToLoad, function(data)
						{
							pageContent=data;
							$("#div_body").fadeOut("slow", function()
								{
									$("#div_body").html(pageContent);
									$("#div_body").fadeIn("slow");
								});
						});
					window.location.reload();
				});

		});
</script>
<div id="navigation" data-role="navbar">
<a href="http://www.google.ch" target="self" >
	google
</a><hr>
<?php
$ordner = "./templates";
$handle = opendir($ordner);
while($file = readdir ($handle))
{
	if($file != "." && $file != "..")
	{
		echo '<a title="'.$file.'" href="#" onclick="load(\'?action=setdesign&designname='.$file.'\')">';
		echo "<img src='./templates/$file/images/background.jpg' width='100'>";
		echo "</a>&nbsp;";
	}
}
closedir($handle);
echo "</div>";
?>