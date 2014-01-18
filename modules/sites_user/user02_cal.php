<?php
$i=1;
$monate = explode(";",$_settings->_array[11][1]);
echo "<table width='100%' height='100%' border='0' cellpadding='0' cellspacing='0'><tr>";
foreach($monate as $_month){
        $_timestamp = mktime(0, 0, 0, $i, 1, $_time->_jahr);
		$month = htmlspecialchars($month);
        if ($i==$_time->_monat) {
                echo "<td valign='middle'>";
                echo "<img src='images/icons/calendar_edit.png' border=0>";
                echo "</td><td valign='middle'>";
                echo "<a title='Monat $_month' href='?action=show_time&timestamp=$_timestamp'><u>$_month</u></a>&nbsp;&nbsp;";
				echo "</td>";
        }else{
                echo "<td valign='middle'>";
                echo "<img src='images/icons/calendar_view_month.png' border=0>";
                echo "</td><td valign='middle'>";
                echo "<a title='Monat $_month' href='?action=show_time&timestamp=$_timestamp'>$_month</a>&nbsp;&nbsp;";
				echo "</td>";
        }
        $i++;
}
echo "</tr></table>";
?>