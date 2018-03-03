<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.020
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<form name='login' action='?action=zip_user&admin_id=<?php echo $_SESSION['id']; ?>' method='post' target='_self'>
        <input type='submit' name='make' value='Neue Sicherung erstellen' >
</form>
<?php
echo "<hr>";
echo "Vorhandene Archive zum downloaden: <br>(pro Tag kann nur ein Archiv erstellt werden, bei erneutem Erstellen wird die vorhandene &uuml;berschrieben)<br><br>";
// zip - Pfad überprüfen
$_zippfad = "./Data/_zip/";
if(!file_exists($_zippfad))
{
        mkdir($_zippfad);
}
$_pfad = "./Data/_zip/".$_user->_ordnerpfad."/";
if(!file_exists ($_pfad) || !is_dir($_pfad))
{
        mkdir ($_pfad);
}
if(@$_POST['make'])
{
        $_jahr   = date("Y", time());
        $_monat  = date("n", time());
        $_tag    = date("j", time());
        $_stunde = date("H", time());
        $_minute = date("i", time());
        $_sekunde= date("s", time());;
        $_name   = $_jahr.".".$_monat.".".$_tag.".".$_stunde.".".$_minute.".".$_sekunde;
        $_jahr   = date("Y", time());
        $_monat  = date("n", time());
        $_tag    = date("j", time());
        $_name   = $_jahr.".".$_monat.".".$_tag;
        $_zippfad= $_pfad;
        $zipname = $_zippfad.$_name.".zip";
        $_ordner = "./Data/".$_user->_ordnerpfad."/";
        zipIt($_ordner, $zipname);
}

function zipIt($source, $destination, $include_dir = false, $additionalIgnoreFiles = array())
{
        $defaultIgnoreFiles = array('.','..');
        $ignoreFiles = array_merge($defaultIgnoreFiles, $additionalIgnoreFiles);
        if(!extension_loaded('zip') || !file_exists($source))
        {
                return false;
        }
        if(file_exists($destination))
        {
                unlink ($destination);
        }
        $zip = new ZipArchive();
        if(!$zip->open($destination, ZIPARCHIVE::CREATE))
        {
                return false;
        }
        $source = str_replace('\\', '/', realpath($source));
        if(is_dir($source) === true){

                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                if($include_dir)
                {
                        $arr     = explode("/",$source);
                        $maindir = $arr[count($arr) - 1];
                        $source  = "";
                        for($i = 0; $i < count($arr) - 1; $i++)
                        {
                                $source .= '/' . $arr[$i];
                        }
                        $source = substr($source, 1);
                        $zip->addEmptyDir($maindir);
                }
                foreach($files as $file){
                        $file = str_replace('\\', '/', $file);
                        // purposely ignore files that are irrelevant
                        if( in_array(substr($file, strrpos($file, '/') + 1), $ignoreFiles) )
                        continue;
                        //$file = realpath($file);
                        if(is_dir($file) === true){
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        }
                        else
                        if(is_file($file) === true){
                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        }
                }
        }
        else
        if(is_file($source) === true){
                $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
}


function Zip($source, $destination, $include_dir = false)
{
        if(!extension_loaded('zip') || !file_exists($source))
        {
                return false;
        }
        if(file_exists($destination))
        {
                unlink ($destination);
        }
        $zip = new ZipArchive();
        if(!$zip->open($destination, ZIPARCHIVE::CREATE))
        {
                return false;
        }
        $source = str_replace('\\', '/', realpath($source));
        if(is_dir($source) === true){
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                if($include_dir)
                {
                        $arr     = explode("/",$source);
                        $maindir = $arr[count($arr) - 1];
                        $source  = "";
                        for($i = 0; $i < count($arr) - 1; $i++)
                        {
                                $source .= '/' . $arr[$i];
                        }
                        $source = substr($source, 1);
                        $zip->addEmptyDir($maindir);
                }
                foreach($files as $file)
                {
                        $file = str_replace('\\', '/', $file);
                        // Ignore "." and ".." folders
                        if( in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')) )
                        continue;
                        $file = realpath($file);
                        if(is_dir($file) === true){
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        }
                        else
                        if(is_file($file) === true){
                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        }
                }
        }
        else
        if(is_file($source) === true){
                $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
}

function Zip2($source, $destination)
{
        if(!extension_loaded('zip') || !file_exists($source))
        {
                return false;
        }
        $zip = new ZipArchive();
        if(!$zip->open($destination, ZIPARCHIVE::CREATE))
        {
                return false;
        }
        $source = str_replace('\\', '/', realpath($source));
        if(is_dir($source) === true){
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                foreach($files as $file){
                        $file = str_replace('\\', '/', $file);
                        // Ignore "." and ".." folders
                        if( in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')) )
                        continue;
                        $file = realpath($file);
                        if(is_dir($file) === true){
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        }
                        else
                        if(is_file($file) === true){
                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        }
                }
        }
        else
        if(is_file($source) === true){
                $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
}
check_htaccess($_pfad.".htaccess",true,".htaccess - ZIP-Berechtigung gestzt in ". $_pfad);
$_pfad = "./Data/_zip/".$_user->_ordnerpfad."/";
echo "<div id='divpdf'>\n";
$folder1 = opendir($_pfad);
while($fA1 = readdir($folder1))
{
        if(!is_dir($fA1)) $afile[] = $fA1;
}
closedir($folder1);
if($afile) rsort($afile);
$anz     = count($afile);
$aktuell = date("Y", time());
for($i = 0;$i < $anz;$i++)
{
        if($afile[$i] != "." && $afile[$i] != ".." && $afile[$i] != ".htaccess")
        {
                $jahr = trim(substr($afile[$i],0,4));
                if($aktuell == $jahr)
                {
                        echo "";
                }
                else
                {
                        $aktuell = $jahr;
                        echo "</div><div id='divpdf'>";
                }
                // alte Variante, ohne Sicherheit
                //echo "<div id='pdf'><a href='./Data/_zip/".$_user->_ordnerpfad."/$afile[$i]' target='_new'><img id='zip' src='images/ico/zip.png' border=0 width=86><br><font size=-4>$afile[$i]</font></a>";
                // neu über Downlado.php
                 echo "<div id='pdf'><a href='download.php?datei=".$afile[$i]."&typ=zip&pfad=".$_user->_ordnerpfad."'' target='_new'><img id='zip' src='images/ico/zip.png' border=0 width=86><br><font size=-4>$afile[$i]</font></a>";
                
                echo "</div>";
        }
}
echo "</div>\n";
?>