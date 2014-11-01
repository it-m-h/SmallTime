<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<script>
        $(document).ready(function() {
                $('.qrcode').each(function() {
                        var url = document.URL.substring(0, document.URL.lastIndexOf('/')) + '/idtime.php?id=' + this.getAttribute('data-qrcode');
                        $(this).attr('href', url);
                        $(this).qrcode({
                                text: url,
                                width: 100,
                                height: 100
                        });
                });
                $('.idtime').on('click', function(e) {
                        e.preventDefault();
                        $(this).hide();
                });
        });
</script>
<style>
        @media print {
          	#div_body > * { display:none; }
          	#div_body > #home { display:block; }
        }
        .idtime { 
			float:left; 
			margin:1em;
			padding:5px;
			background-color:#ffffff; 
		}
</style>
<b>Tipps:</b> 
<ul>
	<li>Klick zum Ausblenden von Elementen, dann mit Strg+P ausdrucken.</li>
	<li>Link (rot) mit rechtsklick -> Link-Adresse kopieren, f&uuml;r die Weitergabe.</li>
</ul>
<?php
        $idtime_secret = 'CHANGEME'; // gleichzeitig in idtime.php ändern

        $fp = @fopen('./Data/users.txt', 'r');
        @fgets($fp); // erste Zeile überspringen
        while (($logindata = fgetcsv($fp, 0, ';')) != false) {
                $hash = sha1($logindata[1].$logindata[2].crypt($logindata[1], '$2y$04$'.substr($idtime_secret.$logindata[2], 0, 22)));
                $ID = substr($hash, 0, 16);
                echo '<div class="idtime">';
                echo '<h2>'.$logindata[1].'</h2>';
                echo '<a class="qrcode" data-qrcode="'.$ID.'"></a><br/>'.$ID;
				echo '<br><a href=idtime.php?id='. $ID.'><font style="color:red">Link->IDTime</font></a>';
				echo '</div>';  
        }
        fclose($fp);
?>