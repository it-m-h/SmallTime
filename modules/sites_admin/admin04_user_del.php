<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$x = $_GET['delete_user_id'];

echo  "<form action='?action=delete_user&delete_user_id=".$_GET['delete_user_id']."' method='post' target='_self'>";
echo   "<table width=100% border=0 cellpadding=3 cellspacing=1>";
echo   "<tr>";
echo   "<td align=left COLSPAN=3 class=td_background_info width=60>Userdaten L&ouml;schen!</td>";
echo   "</tr>";
echo   "<tr>";
echo   "<td align=left class=td_background_tag width=300>Pfad</td>";
echo   "<td align=left class=td_background_tag >".$_users->_array[$x][0]."</td>";
echo   "</tr>";
echo   "<tr>";
echo   "<td align=left class=td_background_tag>Loginname</td>";
echo   "<td align=left class=td_background_tag >".$_users->_array[$x][1]."</td>";
echo   "</tr>";
echo   "<tr>";
echo   "<td align=left class=td_background_tag>Passwort</td>";
echo   "<td align=left class=td_background_tag >".$_users->_array[$x][2]."</td>";
echo   "</tr>";
echo   "<tr>";
echo   "<td COLSPAN=3 align=center class=td_background_info width=60>";
echo   "<input type='submit' name='absenden' value='OK' >";
echo   "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ";
echo   "<input type='submit'  name='absenden' value='CANCEL' > ";
echo   "</td>";
echo   "</tr>";
echo   "</table>";
echo   "</form>";