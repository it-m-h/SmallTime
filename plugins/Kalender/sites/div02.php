<?php
/********************************************************************************
* Small Time - Plugin : Kalender Absenzenansicht der Mitarbeiter
/*******************************************************************************
* Version 0.899
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
echo '<table cellpadding="2" cellspacing="1" border="0"><tr>';
for($i=1; $i<count($_monat->_MonatsArray); $i++){
        $tmp = explode(".", $_monat->_MonatsArray[$i][1]);
        echo '<td valign="middle" align="center" width="20" height="22" ';
        if($_monat->_MonatsArray[$i][2]==6 or $_monat->_MonatsArray[$i][2]==0 or $_monat->_MonatsArray[$i][5] >=0){
                echo ' class="td_background_wochenende"';
        }else{
                echo ' class="td_background_top"';
        }
        echo '><font size="-6">'.$tmp[0].'</font></td>';
}
echo '</tr>';
$_benutzer = file("./Data/users.txt");
unset($_benutzer[0]);
foreach($_benutzer as $string){
        echo '<tr>';
        $string = explode(";", $string);
        $_userdaten_tmp = file("./Data/".$string[0]."/userdaten.txt");

        //Absenzen laden
        $_user_absenzen = array();
        if (file_exists("./Data/".$string[0]."/Timetable/A". $_time->_jahr)){
                $_user_absenzen = file("./Data/".$string[0]."/Timetable/A". $_time->_jahr);
        }
        // Monatsanzeige
        for($i=1; $i<count($_monat->_MonatsArray); $i++){
                $tmp = explode(".", $_monat->_MonatsArray[$i][1]);
                if($i%2){
                    echo '<td valign="middle" align="center" width="20" height="22" ';
                }else{
                    echo '<td valign="middle" align="center" width="20" height="23" ';
                }
                // Absenzeintrag anzeigen
                $_text="";
                $z=0;
                if($_user_absenzen){
                        foreach($_user_absenzen as $_eintrag){
                                $_eintrag = explode(";", $_eintrag);
                                if ($_eintrag[0] == $_monat->_MonatsArray[$i][0]){
                                        $_text=$_eintrag[1];
                                }
                                $z++;
                        }
                }
                //Arbeitstag, falls nein Wochenende anzeigen
                $_arbeitstag = explode(";",$_userdaten_tmp[7]);
                $zeiten =  time_user::get_user_stempelzeiten($string[0], $_time->_jahr, $_time->_monat ,$i );
                if($_arbeitstag[$_monat->_MonatsArray[$i][2]]==0 or $_monat->_MonatsArray[$i][2]==6 or $_monat->_MonatsArray[$i][5] >=0){
                        echo ' class="td_background_wochenende"';
                }elseif($_text){
                        echo ' class="td_background_info"';
                }else{
                        $_prozent = $_arbeitstag[$_monat->_MonatsArray[$i][2]];
                        if($_prozent <= 0.5){
                                echo ' class="td_background_tag50"';
                        }elseif(count($zeiten)){
                                // Mitarbeiter war Anwesend, es hat Stempelzeiten
                                echo ' class="alert alert-success"';
                        }else{
                                 echo ' class="td_background_tag"';
                        }
                }
                echo '>';
                // Arbeitstag - in Prozent wenn nicht 0 oder 1
                if($_arbeitstag[$_monat->_MonatsArray[$i][2]]>0 && $_arbeitstag[$_monat->_MonatsArray[$i][2]]< 1 && !$_text){
                        echo '<font size="-6" color="#a6a6a6">'.$_arbeitstag[$_monat->_MonatsArray[$i][2]].'</font>';
                }else{
                        echo '<font size="-6">'.$_text.'</font>';
                }

                echo '</td>';
        }
        echo '</tr>';
}
echo '</table>';