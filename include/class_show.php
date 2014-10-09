<?php
/*******************************************************************************
* Anzeige eines Arrays für Debug
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_show{
	function __construct($array){
		if($array){
			echo "<table border=1 width=100% cellspacing=0 cellpadding=2>";
			$i=0;
			foreach($array as $zeile){
				echo "<tr>";
				echo "<td bgcolor=yellow width=20>".$i."</td>";
				if(count($zeile) > 1){
					foreach($zeile as $spalte){
						if(is_array($spalte)){
							echo "<td>";
							print_r($spalte);
							echo "</td>";
						}else{
							echo "<td>".htmlspecialchars($spalte)."</td>";
						}	
					}				
				}elseif(count($zeile) == 0){
					echo "<td>FEHLER : keine Daten</td>";
				}else{
					echo "<td>".$zeile."</td>";
				}
				echo "</tr>";
				$i++;
			}
			echo "</table>";
		}else{
			echo "Keine Daten zum Anzeigen vorhanden.";
		}
	}
}