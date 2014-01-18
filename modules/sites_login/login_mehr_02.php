<?php
echo "        <table width='100%' hight='100%' border='0' cellpadding='3' cellspacing='1'  ><tr>";
$y=1;
foreach($_groups->_array as $_group){
	// Administratorengruppe nicht anzeigen, dann sit $y ==1
	if($y>1){
		//$_group = explode(";", $_group);
		if($_grpwahl==-1)$_grpwahl = 1;
		
		if($_grpwahl==$_group[0]-1){
			echo "        <td class='td_background_info' align ='center' valign='middle'>";
		}else{
			echo "        <td class='td_background_wochenende' align ='center' valign='middle'>";
		}
		echo "                <a href='?action=$_action&group=$y'>";
		echo "                <img src='./images/icons/group_go.png'> ";
		//echo $_group[0];
		echo $_group[1];
		//echo $_grpwahl;
		//echo $_group[2];
		echo "                </a>    ";
		echo "                </td>";
	}
	$y++;
}
echo "        </tr></table>";
?>