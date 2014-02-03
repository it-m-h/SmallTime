<?php
/*******************************************************************************
* Projekt - Plugin - Core - Controller
/*******************************************************************************
* Version 0.1
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c) , IT-Master GmbH, All rights reserved
*******************************************************************************/
// -------------------------------------------------------------------------------------------------
// Controller vom GET - Wert laden und Klasse laden
// -------------------------------------------------------------------------------------------------
include_once('./plugins/Projekt/core/_control/user01.php');
include_once('./plugins/Projekt/core/_model/index.php');
include_once('./plugins/Projekt/core/_view/index.php');
// -------------------------------------------------------------------------------------------------
// neue Instanz vom Controller und Anzeigen
// -------------------------------------------------------------------------------------------------
$controller = new Controller();
echo $controller->display();
?>