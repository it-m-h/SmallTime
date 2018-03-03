<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.020
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
if(@$_POST['delete'])
{
	echo "Alle Meldungen wurden gel&ouml;scht!";
	$fp = fopen("./debug/time.txt", "w");
	fclose($fp);
}

function show($array)
{
	if($array[0] <> "keine Daten vorhanden!")
	{
		echo '
		<table width="100%">';
		echo '
		<thead>
		<tr>
		<th class="td_background_top">ID</th>
		<th class="td_background_top">Time</th>
		<th class="td_background_top">User</th>
		<th class="td_background_top">Pass</th>
		<th class="td_background_top">Info</th>
		<th class="td_background_top">Link</th>
		</tr>
		</thead>
		<tbody> ';

		$i = 0;
		foreach($array as $zeile)
		{
			echo "<tr>";
			echo "<td  class='td_background_wochenende' width=20>".$i."</td>";
			foreach($zeile as $spalte)
			{
				if($zeile[3] == "Fehler")
				{
					echo "<td class='td_background_info' >".htmlspecialchars($spalte)."</td>";
				}
				else
				{
					echo "<td class='td_background_tag' >".htmlspecialchars($spalte)."</td>";
				}
			}
			echo "</tr>";
			$i++;
		}
		echo '</tbody></table>';
	}
}
?>
<div id="kn">
	<ul id="myTab" class="nav nav-tabs">
		<li  class="active"  >
			<a data-toggle="tab" href="#s1">
				<img src="./images/icons/bug_go.png" alt="" /> Userlogin
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="#s2">
				<img src="./images/icons/bug_go.png" alt="" /> Adminlogin
			</a>
		</li>
		<li >
			<a data-toggle="tab" href="#s3">
				<img src="./images/icons/bug_go.png" alt="" /> Bugs
			</a>
		</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div id="s1" class="tab-pane fade active in">
			<p id="content">
				<?php
				$_log       = new time_filehandle("./debug/login/","userlogin.txt",";");
				show($_log->_array);
				?>
			</p>
		</div>
		<div id="s2" class="tab-pane fade">
			<p id="content">
				<?php
				$_log       = new time_filehandle("./debug/login/","adminlogin.txt",";");
				show($_log->_array);
				?>
			</p>
		</div>
		<div id="s3" class="tab-pane fade">
			<p id="content">
				<form name='login' action='?action=debug_info' method='post' target='_self'>
					<input type='submit' name='delete' value='alle Meldungen l&ouml;schen' >
				</form>
				<hr>
				<?php
				$_meldungen = file("./debug/time.txt");
				$_meldungen = array_values(array_unique($_meldungen));
				echo "<Table width='100%' border=0 cellpadding=3 cellspacing=1>";
				foreach($_meldungen as $_zeile)
				{
					echo "<tr>";
					if(strstr($_zeile,"Memory"))
					{
							echo "<td align='left' colspan='2'>";
							echo $_zeile;
							echo "</td>";
							
					}else{
						$_zeile = explode(";", $_zeile);
						echo "<td align=left>";
						echo $_zeile[5]. " in : ";
						echo $_zeile[4]. " ";
						if(strstr($_zeile[5],"Leerzeile entdeckt"))
						{
							echo "</td>";
							echo "<form name='login' action='?action=debug_info' method='post' target='_self'>";
							echo "<td>";
							echo "<input type='hidden' name='datei' value='".$_zeile[4]."' >";
							echo "</td>";
							echo "</form>";
						}
						else
						{
							echo "</td>";
						}
					}
					echo "</tr>";
				}
				echo "</table>";
				?>
			</p>
		</div>
	</div>
</div>



