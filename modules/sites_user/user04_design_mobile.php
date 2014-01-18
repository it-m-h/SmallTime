<script type="text/javascript">
function load(seite) {
	
	$.ajax({
			url: seite,
			type: 'get',
			dataType: 'text',
			async: true,
			success: function(response) {
				document.getElementById("div_body").innerHTML = response;
			}
		});
	//window.location.reload(); 
	alert("Seite bitte nach der Auswahl des Designs mit F5 aktualisieren, oder erneut ein Design anklicken.");
	//javascript:location.reload();
}

function reload() {
	alert("Seite bitte nach der Auswahl des Designs mit F5 aktualisieren, oder erneut ein Design anklicken.");
	window.location.reload();	
}


$(document).ready(function()
{
	$("#navigation2 a").click(function()
	{
		var pageToLoad = $(this).attr("href");
		var pageContent;

		
		//alert("Reload Page.");
		alert("Seite bitte nach der Auswahl des Designs mit F5 neu laden.");
		//window.location.reload();
		
		$.get(pageToLoad, function(data)
		{
			pageContent=data;
			
			$("#div_body").fadeOut("slow", function()
			{
				$("#div_body").html(pageContent);
				$("#div_body").fadeIn("slow");
			});
		});
		
		//javascript:location.reload();
		window.location.reload();
		//return false;
	});
	
});   
 




</script>
<div id="navigation" data-role="navbar"> 
<a href="http://www.google.ch" target="self" >google</a><hr>
<?php
//echo "Akutelles Design : ". $_template->get_template() . "<hr>";
/*
Design mit AJAX href
*/
$ordner = "./templates";
$handle = opendir($ordner);
while ($file = readdir ($handle)) {
    //if($file != "." && $file != ".." && $file != "mobile") {
	if($file != "." && $file != "..") {
        //echo $file. "&nbsp;";
		//<hr><a href="javascript:location.reload()">Reload</a><hr>
		//echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'" onclick="load2(\'?action=setdesign&designname='.$file.'\')">';
		//echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'" onclick="load(\'?action=setdesign&designname='.$file.'\')">';
		echo '<a title="'.$file.'" href="#" onclick="load(\'?action=setdesign&designname='.$file.'\')">';
		//echo '<a title="'.$file.'" href="?action=setdesign&designname='.$file.'">';
		
		
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
echo "</div>";
?>

