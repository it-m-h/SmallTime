<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_last = new time();
$_last->set_timestamp($_time->get_lastmonth());
$_last->set_monatsname($_settings->_array[11][1]);
$_next = new time();
$_next->set_timestamp($_time->get_nextmonth());
$_next->set_monatsname($_settings->_array[11][1]);
?>
<?php  
// Settings des Templates mit Bootstrap
if (strstr($_template->_bootstrap,'true')){
?>
<div class="btn-group">
        <span class="btn">
                <a title='Monat <?php echo $_last->_monatname ?>' href='?action=show_time&timestamp=<?php echo $_last->_timestamp ?>'>
                        <img src='images/icons/calendar_view_month.png'>
                        <?php echo $_last->_monatname ?> <?php echo $_last->_jahr ?>
                </a>
        </span>
        <span class="btn">
                <a title='Monats&uuml;bersicht drucken (PDF)' href='?action=print_month&timestamp=<?php echo $_time->_timestamp ?>&print=0&calc=1'><img src='images/icons/printer.png' border=0></a>
                | 
                <a title='Excel - Export' href='?action=show_time&timestamp=<?php echo $_time->_timestamp ?>&excel=true'><img src='images/icons/page_excel.png' border=0></a>
                 | 
                <a title='Monat <?php echo $_time->_monatname ?>' href='?action=show_time&timestamp=<?php echo $_time->_timestamp ?>'>
                        <img src='images/icons/calendar_edit.png' border=0>
                        <u><?php echo $_time->_monatname ?> <?php echo $_time->_jahr ?></u>
                </a>
        </span>
        <span class="btn">
                <a title='Monat <?php echo $_next->_monatname ?>' href='?action=show_time&timestamp=<?php echo $_next->_timestamp ?>'>
                        <img src='images/icons/calendar_view_month.png' border=0>
                        <?php echo $_next->_monatname ?> <?php echo $_next->_jahr ?>
                </a>
        </span>
</div>
<div class="clearfix"></div> 

<?php 
//TODO : Template ohne Bootstrap -> löschen
}else{ ?>
<table width='390' height='100%' border='0' cellpadding='2' cellspacing='0'><tr><td valign='middle'>
		</td><td valign='middle' align='right'>
			<img src='images/icons/calendar_view_month.png' border=0>
		</td><td valign='middle' align='left'>
			<a title='Monat <?php echo $_last->_monatname ?>' href='?action=show_time&timestamp=<?php echo $_last->_timestamp ?>'>
				<?php echo $_last->_monatname ?> <?php echo $_last->_jahr ?>	
			</a>&nbsp;&nbsp;
		</td><td valign='middle' class='td_background_info' align='right'>	
			<a title='Monats&uuml;bersicht drucken' href='?action=print_month&timestamp=<?php echo $_time->_timestamp ?>&print=0'><img src='images/icons/printer.png' border=0></a>&nbsp;&nbsp;		
			<img src='images/icons/calendar_edit.png' border=0>
		</td><td valign='middle' class='td_background_info' align='left'>
			<a title='Monat <?php echo $_time->_monatname ?>' href='?action=show_time&timestamp=<?php echo $_time->_timestamp ?>'>
				<u><?php echo $_time->_monatname ?> <?php echo $_time->_jahr ?></u>
			</a>&nbsp;&nbsp;
		</td><td valign='middle' align='right'>
			<img src='images/icons/calendar_view_month.png' border=0>
		</td><td valign='middle' align='left'>
			<a title='Monat <?php echo $_next->_monatname ?>' href='?action=show_time&timestamp=<?php echo $_next->_timestamp ?>'>
				<?php echo $_next->_monatname ?> <?php echo $_next->_jahr ?>
			</a>&nbsp;&nbsp;
		</td></tr>
</table>
<?php } ?>



