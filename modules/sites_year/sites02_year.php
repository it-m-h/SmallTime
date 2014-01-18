<?php
// ----------------------------------------------------------------------------
// Jahresanzeige und Wahl
// ---------------------------------------------------------------------------- 

echo "<table width='100%' hight='100%' border='0' cellpadding='2' cellspacing='0'>";
echo "<td>";
echo "<b>".$_infotext."</b>";
echo "</td>";
$_startjahr = date("Y",$_user->_BeginnDerZeitrechnung);		// User - Einstellungen
$_w_jahr	= $_time->_jahr;								// Gewähltes Jahr
$_nextjahr	= date("Y",time());								// nächstes Jahr
if($_startjahr<$_w_jahr){
	$_timestampv = mktime(0, 0, 0, 1, 1, $_w_jahr-1);
	echo "<td class='td_background_menue' valign='middle' align='center' width='20'><a title='zurück' href='?action=show_year2&admin_id=".$_SESSION['id']."&timestamp=$_timestampv'><img src='images/icons/control_rewind.png' border=0></a></td>";
}
echo "<td valign='middle' align='center' class='td_background_menue' width='45'>";
echo $_w_jahr;
echo "</td>";
if($_nextjahr >= $_w_jahr){ 
	$_timestampn = mktime(0, 0, 0, 1, 1, $_w_jahr+1);
	echo "<td valign='middle' align='center' class='td_background_menue' width='20'><a title='vorwärts' href='?action=show_year2&admin_id=".$_SESSION['id']."&timestamp=$_timestampn'><img src='images/icons/control_fastforward.png' border=0></a></td>";
}
echo "</tr></table>";
?>