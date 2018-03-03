<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.020
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// Jahresanzeige und Wahl (Beginn bis Heute)
// ---------------------------------------------------------------------------- 
$_startjahr	= @date("Y",$_user->_BeginnDerZeitrechnung);
$_aktuell		= date("Y",time());
$_w_jahr        = $_time->_jahr;
echo '<div class="btn-group">';
for($y=$_startjahr; $y<=$_aktuell; $y++){
        $_timestamp = mktime(0, 0, 0, 1, 1, $y);
        $str = "";
        if ($y==$_w_jahr) $str = " active";
        $strnj = "";
        $strnj .= "<span class='btn".$str."'>";
        $strnj .= "<a title='Jahr' href='?action=show_year2&admin_id=".$_SESSION['id']."&timestamp=".$_timestamp."'>$y</a>";
        $strnj .= "</span>";
        echo $strnj;
}
echo '</div>';