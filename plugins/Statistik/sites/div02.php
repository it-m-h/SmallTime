<?php
$_last = new time();
$_last->set_timestamp($_time->get_lastmonth());
$_last->set_monatsname($_settings->_array[11][1]);
$_next = new time();
$_next->set_timestamp($_time->get_nextmonth());
$_next->set_monatsname($_settings->_array[11][1]);
?>
<table width='390' height='100%' border='0' cellpadding='2' cellspacing='0'><tr><td valign='middle'>
		</td><td valign='middle' align='right'>
			<img src='images/icons/calendar_view_month.png' border=0>
		</td><td valign='middle' align='left'>
			<a title='Monat <?php echo $_last->_monatname ?>' href='?action=plugins&timestamp=<?php echo $_last->_timestamp ?>'>
				<?php echo $_last->_monatname ?> <?php echo $_last->_jahr ?>	
			</a>&nbsp;&nbsp;
		</td><td valign='middle' class='td_background_info' align='right'>
			<img src='images/icons/calendar_edit.png' border=0>
		</td><td valign='middle' class='td_background_info' align='left'>
			<a title='Monat <?php echo $_time->_monatname ?>' href='?action=plugins&timestamp=<?php echo $_time->_timestamp ?>'>
				<u><?php echo $_time->_monatname ?> <?php echo $_time->_jahr ?></u>
			</a>&nbsp;&nbsp;
		</td><td valign='middle' align='right'>
			<img src='images/icons/calendar_view_month.png' border=0>
		</td><td valign='middle' align='left'>
			<a title='Monat <?php echo $_next->_monatname ?>' href='?action=plugins&timestamp=<?php echo $_next->_timestamp ?>'>
				<?php echo $_next->_monatname ?> <?php echo $_next->_jahr ?>
			</a>&nbsp;&nbsp;
		</td></tr>
</table>