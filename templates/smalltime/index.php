<?php
/********************************************************************************
* Small Time - Template
/*******************************************************************************
* Version 0.9.021
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<?php
		include('include/defaultheader.php');
		echo "\n";
		?>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_template->get_templatepfad() ?>css/smalltime.css">
	</head>
	<?php //if($_modal == false)
	if($_modal == false){
		?>
		<body>
			<!--Anfang DIV für die InfoBox -->
			<div id="InfoBox" style="z-index: 1; visibility: hidden; left: 0px; top: 0px;">
				<div id="BoxInnen">
					<span id="BoxInhalte"></span>
				</div>
			</div>
			<!--Ende DIV für die InfoBox 	-->
			<div class="container">
				<div class="row">
					<div class="span12">
						<img style="width:100%" src="<?php echo $_template->get_templatepfad() ?>images/smalltime.jpg">
					</div>
				</div>
				<div class="row">
					<div class="span3">
						<div id='div_plugin'>
							<?php include($_template->get_plugin()); ?>
						</div>
					</div>
					<div class="span9">
						<div id="div_user01" class="pull-right">
							<!-- Hauptmenue !-->
							<?php //echo " < pre > ".$_template->get_user01()."</pre > "; ?>
							<?php include($_template->get_user01()); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span3">
						<!-- Statistik / Bearbeitung / Infos !-->
						<div id='div_user03'>
							<?php  //echo " < pre > ".$_template->get_user03()."</pre > "; ?>
							<?php include($_template->get_user03()); ?>
							<?php echo $_copyright; ?>
						</div>
					</div>
					<div class="span9">
						<!-- Menue - für Content (Kalender) !-->
						<div id='div_user02'>
							<?php //echo " < pre > ".$_template->get_user02()."</pre > "; ?>
							<?php include($_template->get_user02()); ?>
						</div>
						<!-- Content / Monatsansicht / Eintragen, Edit usw. !-->
						<div id='div_user04'>

							<?php //echo " < pre > ".$_template->get_user04()."</pre > "; ?>
							<?php include($_template->get_user04()); ?>
						</div>
					</div>
				</div>
			</div>
			<div id="mainModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 id="myModalLabel">Edit:</h3>
				</div>
				<div id="modalBody" class="modal-body">
				</div>
				<div class="modal-footer">
					<div id="scheduleModalMsg" class="alert alert-block alert-error hide">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h3>Warning!</h3>
						<p></p>
					</div>
				</div>
			</div>
		</body>
		<?php
	}
	elseif($_modal == true){
		?>
		<body>
			<div id='div_user04'>
				<?php include($_template->get_user04()); ?>
			</div>
		</body>
		<?php
	} ?>
</html>