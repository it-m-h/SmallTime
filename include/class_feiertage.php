<?php
/*******************************************************************************
* Feiertage für das gewählte Jahr
/*******************************************************************************
* Version 0.8
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_feiertage{
        public         $_w_jahr                = NULL;
        public         $_country                = NULL;
        public         $_easter                = NULL;
        public         $_feiertageUSER        = NULL;
        public         $_feiertage                = array();                 //Feiertage die gültig sind
        private $_file                        = "./include/Settings/feiertage.txt";
        function __construct($_w_jahr, $_country, $_feiertageUSER){
                $this->_w_jahr                         = $_w_jahr;
                $this->_country                 = $_country;
                $this->_easter                         = $this->easter2($_w_jahr);
                $this->_feiertageUSER         = $_feiertageUSER;
                $this->_feiertage                 = $this->getFeiertage($_w_jahr,$_country);
        }
        function __destruct(){
        }
        function getFeiertage($year = NULL, $_country){
                //Feiertage All werden mit den Einstellungen beim User verglichen, ob true oder false

                //if(is_null($year)) $year = $this->getYear();
                $_FT = $this->_feiertageUSER;
                //print_r($_FT);
                if(!$easter = $this->easter($year)) return false;
                else{
                        if(trim($_FT[0])) $holidays['Neujahr']                = mktime(0,0,0,1,1,$year);
                        if(trim($_FT[1])) $holidays['Rosenmontag']            = strtotime("-48 days", $easter);
                        if(trim($_FT[2])) $holidays['Aschermittwoch']         = strtotime("-46 days", $easter);
                        if(trim($_FT[3])) $holidays['TagDerArbeit']           = mktime(0,0,0,5,1,$year);
                        if(trim($_FT[4])) $holidays['Karfreitag']             = strtotime("-2 days", $easter);
                        if(trim($_FT[5])) $holidays['Ostersonntag']           = $easter;
                        if(trim($_FT[6])) $holidays['Ostermontag']            = strtotime("+1 day", $easter);
                        if(trim($_FT[7])) $holidays['Himmelfahrt']            = strtotime("+39 days", $easter);
                        if(trim($_FT[8])) $holidays['Pfingsten']              = strtotime("+49 days", $easter);
                        if(trim($_FT[9])) $holidays['Pfingstsonntag']         = strtotime("+49 days", $easter);
                        if(trim($_FT[10])) $holidays['Pfingstmontag']         = strtotime("+50 days", $easter);
                        if(trim($_FT[11])) $holidays['Fronleichnam']          = strtotime("+60 days", $easter);
                        //Landesfeiertag - aus den Settings------------------------------------------------------
                        if($_country==1) $holidays['Bundesfeier'] = mktime(0,0,0,8,1,$year);
                        if($_country==2) $holidays['Tag der deutschen Einheit'] = mktime(0,0,0,10,3,$year);
                        if($_country==3) $holidays['österreichische Nationalfeiertag'] = mktime(0,0,0,10,26,$year);
                        if($_country==4) $holidays['Staatsfeiertag in Liechtenstein'] = mktime(0,0,0,8,15,$year);
                        //Landesfeiertag - aus den Settings------------------------------------------------------
                        if(trim($_FT[12])) $holidays['Allerheiligen']          = mktime(0,0,0,11,1,$year);
                        if(trim($_FT[13])) $holidays['Bussbettag']             = strtotime("-11 days", strtotime("1 sunday", mktime(0,0,0,11,26,$year)));
                        if(trim($_FT[14])) $holidays['Advent1']                = strtotime("1 sunday", mktime(0,0,0,11,26,$year));
                        if(trim($_FT[15])) $holidays['Advent2']                = strtotime("2 sunday", mktime(0,0,0,11,26,$year));
                        if(trim($_FT[16])) $holidays['Advent3']                = strtotime("3 sunday", mktime(0,0,0,11,26,$year));
                        if(trim($_FT[17])) $holidays['Advent4']                = strtotime("4 sunday", mktime(0,0,0,11,26,$year));
                        if(trim($_FT[18])) $holidays['Heiligabend']            = mktime(0,0,0,12,24,$year);
                        if(trim($_FT[19])) $holidays['Weihnachtsfeiertag1']    = mktime(0,0,0,12,25,$year);
                        if(trim($_FT[20])) $holidays['Weihnachtsfeiertag2']    = mktime(0,0,0,12,26,$year);
                        if(trim($_FT[21])) $holidays['Sylvester']              = mktime(0,0,0,12,31,$year);

                        //echo "-".trim($_FT[22])."-";
                        //if(trim($_FT[22])) echo "hhhh";


                        //Individuelle Feiertage laden
                        $_userfeiertage = file($this->_file);
                        foreach($_userfeiertage as $_eintrag){
                                $_eintrag         = explode(";", $_eintrag);
                                $_datum         = date('d.n', $_eintrag[1]);
                                $_datum         = explode(".", $_datum);
                                $_datum         = mktime(0,0,0,$_datum[1],$_datum[0],$year) ;
                                $holidays[$_eintrag[0]] = $_datum;
                        }
                        return $holidays;
                }
        }
        function easter($year = null){
                //if(is_null($year)) $year = $this->getYear();
                if(strlen(strval($year)) == 2){
                        if($year < 70) $year += 2000;
                        else $year += 1900;
                }
                if($year > 2038 || $year < 1901) return false;  // limitations of date()/mktime(), if OS == Win change 1901 to 1970!
                $d = (((255 - 11 * ($year % 19)) - 21) % 30) + 21;
                $delta = $d + ($d > 48) + 6 - (($year + $year / 4 + $d + ($d > 48) + 1) % 7);
                $easter = strtotime("+$delta days", mktime(0,0,0,3,1,$year));
                return $easter;
        }
        function easter2($year){
                //if(is_null($year)) $year = $this->getYear();
                if(strlen(strval($year)) == 2){
                        if($year < 70) $year += 2000;
                        else $year += 1900;
                }
                if($year > 2038 || $year < 1901) return false;
                //limitations of date()/mktime(), if OS == Win change 1901 to 1970!
                $a = $year % 19;
                $b = $year % 4;
                $c = $year % 7;
                $m = ((8 * ($year / 100) + 13) / 25) - 2;
                $s = ($year / 100) - ($year / 400) - 2;
                $M = (15 + $s - $m) % 30;
                $N = (6 + $s) % 7;
                $d = ($M + 19 * $a) % 30;
                if($d == 29) $D = 28;
                elseif($d == 28 && $a >= 11) $D = 27;
                else $D = $d;
                $e = (2 * $b + 4 * $c + 6 * $D + $N) % 7;
                $delta = $D + $e + 1;
                return strtotime("+$delta days", mktime(0,0,0,3,21,$year));
        }
        function getFeiertageALL($year = NULL, $_country){
                if(!$easter = $this->easter($year)) return false;
                else{
                        $holidays['Neujahr']                = mktime(0,0,0,1,1,$year);
                        $holidays['Rosenmontag']            = strtotime("-48 days", $easter);
                        $holidays['Aschermittwoch']         = strtotime("-46 days", $easter);
                        $holidays['TagDerArbeit']           = mktime(0,0,0,5,1,$year);
                        $holidays['Karfreitag']             = strtotime("-2 days", $easter);
                        $holidays['Ostersonntag']           = $easter;
                        $holidays['Ostermontag']            = strtotime("+1 day", $easter);
                        $holidays['Himmelfahrt']            = strtotime("+39 days", $easter);
                        $holidays['Pfingsten']              = strtotime("+49 days", $easter);
                        $holidays['Pfingstsonntag']         = strtotime("+49 days", $easter);
                        $holidays['Pfingstmontag']         = strtotime("+50 days", $easter);
                        $holidays['Fronleichnam']          = strtotime("+60 days", $easter);
                        //Landesfeiertag - aus den Settings------------------------------------------------------
                        if($_country==1) $holidays['Bundesfeier'] = mktime(0,0,0,8,1,$year);
                        if($_country==2) $holidays['Tag der deutschen Einheit'] = mktime(0,0,0,10,3,$year);
                        if($_country==3) $holidays['österreichische Nationalfeiertag'] = mktime(0,0,0,10,26,$year);
                        if($_country==4) $holidays['Staatsfeiertag in Liechtenstein'] = mktime(0,0,0,8,15,$year);
                        //Landesfeiertag - aus den Settings------------------------------------------------------
                        $holidays['Allerheiligen']          = mktime(0,0,0,11,1,$year);
                        $holidays['Bussbettag']             = strtotime("-11 days", strtotime("1 sunday", mktime(0,0,0,11,26,$year)));
                        $holidays['Advent1']                = strtotime("1 sunday", mktime(0,0,0,11,26,$year));
                        $holidays['Advent2']                = strtotime("2 sunday", mktime(0,0,0,11,26,$year));
                        $holidays['Advent3']                = strtotime("3 sunday", mktime(0,0,0,11,26,$year));
                        $holidays['Advent4']                = strtotime("4 sunday", mktime(0,0,0,11,26,$year));
                        $holidays['Heiligabend']            = mktime(0,0,0,12,24,$year);
                        $holidays['Weihnachtsfeiertag1']    = mktime(0,0,0,12,25,$year);
                        $holidays['Weihnachtsfeiertag2']    = mktime(0,0,0,12,26,$year);
                        $holidays['Sylvester']              = mktime(0,0,0,12,31,$year);
                        return $holidays;
                }
        }
        function getFeiertageUserEdit($year = NULL, $_country){
                if(!$easter = $this->easter($year)) return false;
                else{
                        $holidays['Neujahr']                = mktime(0,0,0,1,1,$year);
                        $holidays['Rosenmontag']            = strtotime("-48 days", $easter);
                        $holidays['Aschermittwoch']         = strtotime("-46 days", $easter);
                        $holidays['TagDerArbeit']           = mktime(0,0,0,5,1,$year);
                        $holidays['Karfreitag']             = strtotime("-2 days", $easter);
                        $holidays['Ostersonntag']           = $easter;
                        $holidays['Ostermontag']            = strtotime("+1 day", $easter);
                        $holidays['Himmelfahrt']            = strtotime("+39 days", $easter);
                        $holidays['Pfingsten']              = strtotime("+49 days", $easter);
                        $holidays['Pfingstsonntag']         = strtotime("+49 days", $easter);
                        $holidays['Pfingstmontag']         = strtotime("+50 days", $easter);
                        $holidays['Fronleichnam']          = strtotime("+60 days", $easter);
                        $holidays['Allerheiligen']          = mktime(0,0,0,11,1,$year);
                        $holidays['Bussbettag']             = strtotime("-11 days", strtotime("1 sunday", mktime(0,0,0,11,26,$year)));
                        $holidays['Advent1']                = strtotime("1 sunday", mktime(0,0,0,11,26,$year));
                        $holidays['Advent2']                = strtotime("2 sunday", mktime(0,0,0,11,26,$year));
                        $holidays['Advent3']                = strtotime("3 sunday", mktime(0,0,0,11,26,$year));
                        $holidays['Advent4']                = strtotime("4 sunday", mktime(0,0,0,11,26,$year));
                        $holidays['Heiligabend']            = mktime(0,0,0,12,24,$year);
                        $holidays['Weihnachtsfeiertag1']    = mktime(0,0,0,12,25,$year);
                        $holidays['Weihnachtsfeiertag2']    = mktime(0,0,0,12,26,$year);
                        $holidays['Sylvester']              = mktime(0,0,0,12,31,$year);
                        return $holidays;
                }
        }
        function save_feiertage(){
                $_anzahl = $_POST['anzahl'];
                $_tmparr = array();
                for($x=0; $x<=$_anzahl; $x++){
                        $_name = $_POST['e'.$x];
                        $_datum= $_POST['v'.$x];
                        if($_name<>"" && $_datum<>""){
                                $_datum = explode(".", $_datum);
                                $_datum2 = mktime(0,0,0,$_datum[1],$_datum[0],0);
                                $_tmparr[$x] =         $_name.";".$_datum2;
                        }
                }
                $neu = implode( "\r\n", $_tmparr);
        $open = fopen($this->_file,"w+");
        fwrite ($open, $neu);
        fclose($open);
        }
        function delete_feiertag($id){
                $_temp = file($this->_file);
                unset($_temp[$id]);
                $neu = implode( "", $_temp);
                $open = fopen($this->_file,"w+");
                fwrite ($open, $neu);
            fclose($open);
        }
        function get_firmenfeiertage(){
                $_tmparr = file($this->_file);
                $x = 0;
                foreach($_tmparr as $_zeile){
                        $_tmparr[$x] = explode(";", $_tmparr[$x]);
                        $_tmparr[$x][1] = date('d.n', $_tmparr[$x][1]);
                        $x++;
                }
                return $_tmparr;
        }
}
?>