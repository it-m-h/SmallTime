<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9
* Author: IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// Spaltenreite vergrössern, wenn Benutzer keine Berechtigungen haben
// ----------------------------------------------------------------------------
$t = 1;
if($_settings->_array[15][1]||$_settings->_array[26][1]) $t++;
if($_settings->_array[16][1]||$_settings->_array[26][1]) $t++;
$a = 1;
if($_settings->_array[17][1]||$_settings->_array[26][1]) $a++;
if(!$_settings->_array[18][1||$_settings->_array[26][1]]) $a++;
$modal = "";
if(strstr($_template->_modal,'true')) $modal = "&modal";
?>
<table width=100% border=0 cellpadding=3 cellspacing=1>
        <tr>
                <td COLSPAN="3" class="td_background_top" align="center">Datum</td>
                <td COLSPAN="<?php echo $t; ?>" class="td_background_top" align="left">Zeiten</td>
                <td class=td_background_top width="40" align="center">Std.</td>
                <td class=td_background_top width="40" align="center">Saldo</td>
                <td COLSPAN="<?php echo $a; ?>" class="td_background_top" width="50" align="center">Absenzen</td>
                <?php if($_settings->_array[18][1]) echo "<td class=td_background_top width=16 align=center>Do</td>"; ?>
        </tr>
        <?php
        for($z=1; $z< count($_monat->_MonatsArray); $z++){
                //-------------------------------------------------------------------------
                //Feiertag - Textanzeige
                //-------------------------------------------------------------------------
                if($_monat->_MonatsArray[$z][6]<>""){
                        $_txt0 = "<img title='".$_monat->_MonatsArray[$z][6]."' src='images/icons/bullet_star.png' border=0>";
                }else{
                        $_txt0 = "";
                }
                //-------------------------------------------------------------------------
                echo " <tr>\n";
                echo " <td ". $_monat->_MonatsArray[$z][30]." width=16 align=center>". $_monat->_MonatsArray[$z][1]."</td>\n";
                echo " <td ". $_monat->_MonatsArray[$z][30]." width=16 align=center>". $_monat->_MonatsArray[$z][3]."</td>\n";
                echo " <td ". $_monat->_MonatsArray[$z][30]." width=16 align=center>$_txt0</td>\n";
                //-------------------------------------------------------------------------
                // Falls User die Zeit eintragen darf - anzeigen
                //-------------------------------------------------------------------------

                if($_settings->_array[15][1]==1||$_settings->_array[26][1]) echo " <td ". $_monat->_MonatsArray[$z][30]." width=16 align=center><a href='?action=add_time&timestamp=". $_monat->_MonatsArray[$z][0].$modal."' title='Zeit hinzuf&uuml;gen'><img border='0' src='images/icons/time_add.png'></a></td>\n";
                //-------------------------------------------------------------------------
                // Falls User mehrere zeiten eintragen darf - anzeigen
                //-------------------------------------------------------------------------
                if($_settings->_array[16][1]==1||$_settings->_array[26][1]) echo " <td ". $_monat->_MonatsArray[$z][30]." width=16 align=center><a href='?action=add_time_list&timestamp=". $_monat->_MonatsArray[$z][0].$modal."' title='mehrere Zeiten hinzuf&uuml;gen'><img border='0' src='images/icons/time_go.png'></a></td>\n";
                //-------------------------------------------------------------------------
                // Stempelzeiten anzeigen mit Link zum editieren falls in den Settings true
                //-------------------------------------------------------------------------
                echo " <td ". $_monat->_MonatsArray[$z][30]." align = left>";
                $tmp = "";
                for($x=0; $x< count($_monat->_MonatsArray[$z][12]) and count($_monat->_MonatsArray[$z][12])>0; $x++){
                        // Trennzeichen bei Stempelzeiten als $trenn
                        if($x==0){$trenn = "";}elseif($x%2 and $x<>0){$trenn = "-";}else{$trenn = " / ";}
                        $tmp = $tmp . $trenn;
                        if($_settings->_array[14][1]||$_settings->_array[26][1]){
                                $tmp = $tmp ."<a href='?action=edit_time&timestamp=".$_monat->_MonatsArray[$z][10][$x].$modal."' title='Zeit editieren' class='time'>".$_monat->_MonatsArray[$z][12][$x]."</a>";
                        }else{
                                $tmp = $tmp . ' '.$_monat->_MonatsArray[$z][12][$x].' ';
                        }
                }
                $tmp = $tmp . " ". $_monat->_MonatsArray[$z][35] . " " . $_monat->_MonatsArray[$z][36];
                //-------------------------------------------------------------------------
                // Anzeige von Zeit fehlt, wenn ungerade Stempelzeiten
                //-------------------------------------------------------------------------
                if($_monat->_MonatsArray[$z][11]%2==1) {
                        $tmp = $tmp ."<a href='?action=add_time&timestamp=". $_monat->_MonatsArray[$z][0].$modal."' title='Zeit hinzuf&uuml;gen'>";
                        $tmp = $tmp . " - <font class=timefehlt>Zeit fehlt!</font>";
                        $tmp = $tmp . "</a>";
                }
                echo $tmp;
                echo "</td>\n";
                //-------------------------------------------------------------------------
                // Arbeitsstunden anzeigen
                //-------------------------------------------------------------------------
                echo " <td ". $_monat->_MonatsArray[$z][30]." align=center>";
                if($_monat->_MonatsArray[$z][13]>0){
                        echo number_format($_monat->_MonatsArray[$z][13], 2, '.', '');
                }
                echo "</td>\n";
                //-------------------------------------------------------------------------
                // Saldo anzeigen
                //-------------------------------------------------------------------------
                echo " <td ". $_monat->_MonatsArray[$z][30]." align=center>";
                if($_monat->_MonatsArray[$z][20]>0){
                        echo number_format($_monat->_MonatsArray[$z][20], 2, '.', '');
                }elseif($_monat->_MonatsArray[$z][20]<0){
                        echo "<font class=minus>". number_format($_monat->_MonatsArray[$z][20], 2, '.', ''). "</font>";
                }
                echo "</td>\n";
                //-------------------------------------------------------------------------
                // Absenzen enzeigen
                //-------------------------------------------------------------------------
                if($_settings->_array[17][1]||$_settings->_array[26][1]){
                        echo " <td ". $_monat->_MonatsArray[$z][30]." width=16 align=center>".$_monat->_MonatsArray[$z][31]."</td>\n";
                }
                echo " <td ". $_monat->_MonatsArray[$z][30]." width=62 align=center>";
                //-------------------------------------------------------------------------
                // Falls eine Absenz vorhanden ist, Infos anzeigen
                //-------------------------------------------------------------------------
                if($_monat->_MonatsArray[$z][14]<>""){
                        echo "\n        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                        <tr>
                        <td>".trim($_monat->_MonatsArray[$z][14]).":".trim($_monat->_MonatsArray[$z][15])."</td>
                        <td width='16'><img title='".$_monat->_MonatsArray[$z][32]."' src='images/icons/information.png' border=0></td>
                        </tr>
                        </table>\n";
                }
                //-------------------------------------------------------------------------
                echo " </td>\n";
                //-------------------------------------------------------------------------
                // Rapport editieren oder erstellen
                //-------------------------------------------------------------------------
                if($_settings->_array[18][1]||$_settings->_array[26][1]){
                        echo " <td ". $_monat->_MonatsArray[$z][30]." width=16 align=center>".$_monat->_MonatsArray[$z][33]."</td>\n";
                }
                echo " </tr>\n";
        }
        //-------------------------------------------------------------------------
        // Fusszeile mit den Summen
        //-------------------------------------------------------------------------
        ?>
        <tr>
                <td COLSPAN=3 class=td_background_top width=70 align=center></td>
                <td COLSPAN=<?php echo $t; ?> class=td_background_top width=550 align = left>Sollstunden :
                        <?php
                        echo $_monat->_SummeSollProMonat; ?> Std.</td>
                <td class=td_background_top width=40 align=center><?php if($_monat->_SummeWorkProMonat>0) echo $_monat->_SummeWorkProMonat ?></td>
                <td class=td_background_top width=40 align=center><?php
                        if($_monat->_SummeSaldoProMonat>0){
                                echo number_format($_monat->_SummeSaldoProMonat, 2, '.', '');
                        }elseif($_monat->_SummeSaldoProMonat<0){
                                echo "<font class=minus>". number_format($_monat->_SummeSaldoProMonat, 2, '.', ''). "</font>";
                        }
                        ?>
                </td>
                <td COLSPAN=<?php echo $a; ?> class=td_background_top width=50 align=center><?php echo $_monat->_SummeAbsenzProMonat?></td>
                <?php
                if($_settings->_array[18][1]) echo "<td class=td_background_top width=16 align=center> </td>";
                ?>
        </tr>
</table>
<?php
//TODO : Template ohne Bootstrap -> lÃ¶schen
if(strstr($_template->_modal,'true')){ ?>
        <script type="text/javascript">
                $('#div_user04 a').click(function(e){
                                e.preventDefault();
                                $("#modalBody").html("");
                                $("#modalBody").load(this.href + '');
                                $('#myModalLabel').html($(this).attr('title'));
                                $("#mainModal").modal('show');
                                console.log(this.href);
                                e.preventDefault();
                        });
                $('a[title="delete Absenz"]').unbind();
        </script>
<?php } ?>