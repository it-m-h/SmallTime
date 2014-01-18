<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>
			<?php echo $_settings->_array[0][1] ?>
		</title>
		<meta name="author" content="<?php echo $_settings->_array[1][1] ?>">
		<meta name="editor" content="<?php echo $_settings->_array[2][1] ?>">
		<meta name="Content-language" content="<?php echo $_settings->_array[3][1] ?>">
		<meta http-equiv="Content-Type" content="<?php echo $_settings->_array[4][1] ?>">
		<meta http-equiv="Content-Script-Type" content="<?php echo $_settings->_array[5][1] ?>">
		<meta name="page-type" content="<?php echo $_settings->_array[6][1] ?>">
		<meta name="page-topic" content="<?php echo $_settings->_array[7][1] ?>">
		<meta name="description" content="<?php echo $_settings->_array[8][1] ?>">
		<meta name="keywords" content="<?php echo $_settings->_array[9][1] ?>">
		<meta name="copyright" content="<?php echo $_settings->_array[10][1] ?>">
		<meta http-equiv="expires" content="0">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">
		<meta name="revisit-after" content="2 days">
		<link rel="SHORTCUT ICON" href="<?php echo $_favicon ?>">
	</head>
	<script src="js/jquery.js?time=<?php echo time()?>" ></script>
	<script src="js/jquery.qrcode.js?time=<?php echo time()?>"></script>
	<script src="js/qrcode.js?time=<?php echo time()?>"></script>
	<script type="text/javascript" src="js/cssrefresh.js"></script>
	<link rel="stylesheet" href="<?php echo $_template->get_templatepfad() ?>css/smoothness/jquery-ui-1.10.3.custom.css?time=<?php echo time(); ?>" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_template->get_templatepfad() ?>css/black.css?time=<?php echo time(); ?>">
	<body>
		<center>
			<div id='div_body'>
				<div id='div_plugin'>
					<?php include($_template->get_plugin()); ?> 
				</div>
				<!-- Content / Monatsansicht / Eintragen, Edit usw. !-->
				<div id='div_user04'>
					<?php include($_template->get_user04()); ?>
				</div>
				<!-- Hauptmenue !-->
				<div id='div_user01'>
					<?php include($_template->get_user01()); ?>
				</div>
				<!-- Menue - für Content (Kalender) !-->
				<div id='div_user02'>
					<?php include($_template->get_user02()); ?>
				</div>
				<!-- Statistik / Bearbeitung / Infos !-->
				<div id='div_user03'>
					<?php include($_template->get_user03()); ?>
				</div>
			</div>
		</center>
	</body>
</html>
