<?php
/********************************************************************************
* Small Time - Beispiel für ein einfaches Touch - Screen - Stempelterminal
* bei nichtgebrauch Datei löschen
/*******************************************************************************
* Version 0.9.015
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_grpwahl = '2';
$gruppe = 2;
if(isset($_GET['gruppe'])){
	$_grpwahl =  $_GET['gruppe'];
	$gruppe = $_GET['gruppe'];
}
//error_reporting(E_ALL); 
//ini_set("display_errors", 1); 
include_once ('./include/class_absenz.php');
include_once ('./include/class_user.php');
include_once ('./include/class_group.php');
include_once ('./include/class_login.php');   
include_once ('./include/class_template.php');
include_once ('./include/class_time.php');
include_once ('./include/class_month.php');
include_once ('./include/class_jahr.php');
include_once ('./include/class_feiertage.php');
include_once ('./include/class_filehandle.php');
include_once ('./include/class_rapport.php');
include_once ('./include/class_show.php');
include_once ('./include/class_settings.php');
include ("./include/time_funktionen.php");

$_grpwahl = $_grpwahl-1;
$_group = new time_group($_grpwahl);
if(isset($id)) $_grpwahl = $_group->get_usergroup($id);
$anzMA = count($_group->_array[1][$_grpwahl]);	

foreach($_group->_array[0] as $gruppen){
	//echo $gruppen[0];
}			
						
if(isset($_GET['json'])){

	//-------------------------------------------------------------------------------------------------------------
	// Anwesenheitsliste in ein JSON laden
	//-------------------------------------------------------------------------------------------------------------
	
	$tmparr = array();
	for($x=0; $x<$anzMA ;$x++){	
		$tmparr[$x]['gruppe'] = trim($_group->_array[0][$_grpwahl][$x]);		
		$tmparr[$x]['mitarbeiterid'] = trim($_group->_array[1][$_grpwahl][$x]);	
		$tmparr[$x]['loginname'] = trim($_group->_array[2][$_grpwahl][$x]);	
		$tmparr[$x]['pfad'] = trim($_group->_array[3][$_grpwahl][$x]);
		// Mitarbeiter - Name
		$tmparr[$x]['username'] = trim($_group->_array[4][$_grpwahl][$x]);	
		//Anwesend oder nicht
		$tmparr[$x]['anwesend'] = (count($_group->_array[5][$_grpwahl][$x]))%2;	
		if($tmparr[$x]['anwesend']){
			$tmparr[$x]['status'] = 'Anwesend';
		}else{
			$tmparr[$x]['status'] = 'Abwesend';
		}
						
		// Mitarbeiter - Bild anzeigen
		if(file_exists("./Data/".$_group->_array[2][$_grpwahl][$x]."/img/bild.jpg")){		
			$tmparr[$x]['bild'] = "./Data/".$_group->_array[2][$_grpwahl][$x]."/img/bild.jpg";	
		}else{
			$tmparr[$x]['bild'] = "./images/ico/user-icon.png";	
		}	
		if(isset($_group->_array[5][$_grpwahl][$x][count($_group->_array[5][$_grpwahl][$x])-1])){
			$tmparr[$x]['lasttime'] =$_group->_array[5][$_grpwahl][$x][count($_group->_array[5][$_grpwahl][$x])-1];	
			$tmparr[$x]['alltime'] = implode(" - ", $_group->_array[5][$_grpwahl][$x]);	
		}else{
			$tmparr[$x]['lasttime'] = '';
			$tmparr[$x]['alltime'] = '';
		}	
		$tmparr[$x]['passwort'] = trim($_group->_array[7][$_grpwahl][$x]);	
		$idtime_secret = 'CHANGEME';
		// stempeln über idtime
		//http://localhost:88/Kunden/time.repmo.ch/idtime.php?id=1864f9f71f65975b
		$hash = sha1($tmparr[$x]['pfad'].$tmparr[$x]['passwort'].crypt($tmparr[$x]['pfad'], '$2y$04$'.substr($idtime_secret.$tmparr[$x]['passwort'], 0, 22)));
		$tmparr[$x]['idtime'] = substr($hash, 0, 16);
	}	
	echo json_encode($tmparr);
}else{
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
			<!--<meta http-equiv="refresh" content="10">-->
			<title>SmallTime - Touch - Screen - Stempelterminal</title>
			<link href='https://fonts.googleapis.com/css?family=Rambla:400,700' rel='stylesheet' type='text/css'>
			<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
			<link href='https://fonts.googleapis.com/css?family=Ubuntu+Mono:400,400italic,700italic,700' rel='stylesheet' type='text/css'>		
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.js"></script>		
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
			<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>		
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">	
			<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.js"></script>		
			<style>
				body{
					background-color: #70858f;
					font-size: 0.8em;
				}
				.alert-danger, .alert-error {
					background-color: #e9c7c7;
					border-color: #da9e9e;
				}
				.alert-success {
					background-color: #d1e9c7;
					border-color: #A6C48D;
				}
				.alert {
					padding: 6px 6px 6px 6px;
					margin-bottom: 20px;
					text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
					/*background-color: #fcf8e3;*/
					border: 1px solid #fbeed5;
					-webkit-border-radius: 0px;
					-moz-border-radius: 0px;
					border-radius: 0px;
					color: #000000;
				}
				table{
					margin-top: 2em;
				}
				.mitarbeiter{
					display: -ms-flex; 
					display: -webkit-flex; 
					display: flex;
					margin: 10px;
				}
				.bild{
					-webkit-flex: 1;
					flex: 1;
					-webkit-order: 1;
					order: 1;
				}
				.bild img{
					        width: 100%;
				}
				.name{
					-webkit-flex: 2;
					flex: 2;
					-webkit-order: 2;
					order: 2;
				}

				.row{
				}
				nav ul li.active {
					background-color: rgba(0, 0, 0, 0.6);
				}
				.container .row {
					margin-left: 0;
					margin-right: 0;
				}
				@media only screen and (min-width: 1200px){
					.container{
						width: 80%;
						max-width: 1600px;
					}

				}
				@media only screen and (min-width: 993px){
					.container{
						width: 95%;
					}	

				}
				@media only screen and (min-width: 601px){
					.container{
						width: 95%;
					}	
		
				}

			</style>
			<script>
				(function($)
					{
						$(function()
							{	
								$(".button-collapse").sideNav();
									
							
							}); // End Document Ready
					})(jQuery); // End of jQuery name space 	
				function start(){
					uebersicht('?gruppe=<?php echo $gruppe; ?>&json');
					//uebersicht('repmo_json.php?group=<?php echo $_grpwahl; ?>&json');
				}
				function mastempeln(str){
					console.log(str);
					idtime(str);
				}
				function idtime(id)
				{
					$.ajax(
						{
							url: 'idtime.php?id=' + id + '&w=no',
							//url: url,
							type: 'get',
							dataType: 'text',
							async: true,
							success: function(response)
							{
								console.log(response);
								uebersicht('?gruppe=<?php echo $gruppe; ?>&json');
							}
						});
				}		
				function uebersicht(url)
				{
					$.ajax(
						{
							//url: 'idtime.php?id=' + id + '&w=no',
							url: url,
							type: 'get',
							dataType: 'json',
							async: true,
							success: function(response)
							{
								console.log(response);
								$('#maanzeige').html('');
								//$('#matemplate').show();
								var panel = $('#matemplate').clone();
								$('#matemplate').hide();
								for (i = 0; i < response.length; i++) {
									var new_panel = panel.clone();
									// Tabelle farblich unterscheiden
									if (response[i].anwesend == 1) {
										new_panel.find('.mitarbeiter').addClass('green lighten-1');
									} else {
										new_panel.find('.mitarbeiter').addClass('deep-orange accent-2');
									}
									//<img src="{{bild}}" alt="{{username}}" />
									new_panel.find('#img').html('<img src="'+ response[i].bild+'" alt="'+ response[i].username+'" />');
									var html_for_mustache = new_panel.html();
									var html = Mustache.to_html(html_for_mustache, response[i]);
									$('#maanzeige').append(html);
								};
							}
						});
				}
			</script>
		</head>	
		<body onload="start();">
			<!--  NAVIGATION !-->
			<nav class="navbar blue-grey darken-3" role="navigation">
				<div class="nav-wrapper container">
					<a id="logo-container" href="?" class="brand-logo"><span class="fa fa-fw fa-home fa-1x"></span>Home</a>
					<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
					<ul class="right hide-on-med-and-down">
						<?php 
						
						$i=2;
						foreach($_group->_array[0] as $gruppen){
							echo '<li ';
							if(intval($gruppe)==$i)  echo 'class="active" '; 
							echo 'id="menue'.$i.'" ><a href="?gruppe='.$i.'" id="seite'.$i.'"><i class="fa fa-users fa-2"></i> '. $gruppen[0]. '</a></li>';
							$i++;
						}
						echo '<li ><a href="index.php" target="_new"><i class="fa fa-home fa-2"></i> Index.php</a></li>';
						echo '<li ><a href="admin.php" target="_new"><i class="fa fa-lock fa-2"></i> Admin.php</a></li>';
						echo '</ul><ul class="side-nav" id="mobile-demo">';
						$i=2;
						foreach($_group->_array[0] as $gruppen){
							echo '<li ';
							if(intval($gruppe)==$i) echo 'class="active" '; 
							echo 'id="mobmenue'.$i.'"><a href="?gruppe='.$i.'" id="mobseite'.$i.'"><i class="fa fa-users fa-2"></i> '. $gruppen[0].'</a></li>';
							$i++;
						}
						echo '<li ><a href="index.php" target="_new"><i class="fa fa-home fa-2"></i> Index.php</a></li>';
						echo '<li ><a href="admin.php" target="_new"><i class="fa fa-lock fa-2"></i> Admin.php</a></li>';
						
						
						?>
					
						<!--<li <?php if($_grpwahl=='2') echo 'class="active" '; ?>id="menue1"><a href="?gruppe=2" id="seite1"><i class="fa fa-firefox mr5"></i> Seite 1</a></li>
						<li <?php if($_grpwahl=='3') echo 'class="active" '; ?>id="menue2"><a href="?gruppe=3" id="seite2"><i class="fa fa-firefox mr5"></i> Seite 2</a></li>
						</ul>
						<ul class="side-nav" id="mobile-demo">
						<li <?php if($_grpwahl=='2') echo 'class="active" '; ?>id="mobmenue1"><a href="?gruppe=2" id="mobseite1"><i class="fa fa-firefox mr5"></i> Seite 1</a></li>
						<li <?php if($_grpwahl=='3') echo 'class="active" '; ?>id="mobmenue2"><a href="?gruppe=3" id="mobseite2"><i class="fa fa-firefox mr5"></i> Seite 2</a></li>-->
					</ul>
				</div>
			</nav>
			<!--  CONTENT  --------------------------------------------------------------------------------------------------------------------------------->
			<div class="container" id="ContentHTML">
				<div class="container">
					<div id="maanzeige" class="row"></div>
					<div id="matemplate"  style="visibility: hidden">
						
						<div class="col s12 m6 l4">
							<div class=" mitarbeiter " onclick="mastempeln('{{idtime}}')" >
								<div class="bild" id="img"><img src="{{bild}}" alt="{{username}}"  /></div>
								<div class="name"><h5>{{username}}</h5><p>
									{{alltime}}
									<hr>
									 {{status}} seit: {{lasttime}}</p>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</body>
	</html> 
	<?php
}
?>