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
<script>
	$( document ).ready(function() {		
			var my_jetzt 	= new Date();
			var my_monat  	= my_jetzt.getMonth();
			my_monat = my_monat +1;
			var my__jahr 	= my_jetzt.getFullYear();			
			$('#monat').text(my_monat);
			$('#jahr').text(my__jahr);
			$('#jahr0').text(my__jahr);
			$('#jahr1').text((my__jahr-1));
			$('#jahr2').text((my__jahr-2));
			$('#jahr3').text((my__jahr-3));
			$('#jahr4').text((my__jahr-4));
			$('#jahr5').text((my__jahr-5));
			$('#jahr6').text((my__jahr-6));
			$.ajax(
				{
					url: '?action=pdfgenerate&function=getpdf',
					type: 'post',
					dataType: 'text',
					async: true,
					data: {
						monat: $('#monat').text(),
						jahr: $('#jahr').text()
					},
					success: function(response)
					{
						//document.getElementById("ajaxoutput").innerHTML = response;
						jPut.pdfliste.data=response;
					}
				});
		});
		
	function anzeigen(){
		$.ajax(
			{
				url: '?action=pdfgenerate&function=getpdf',
				type: 'post',
				dataType: 'text',
				async: true,
				data: {
					monat: $('#monat').text(),
					jahr: $('#jahr').text()
				},
				success: function(response)
				{
					//document.getElementById("ajaxoutput").innerHTML = response;
					jPut.pdfliste.data=response;		
				}
			});
	
	}
	
	function create(mylink, mytime){
		console.log('Erstellen von PDF' + mylink+' : '+mytime);
		console.log($('#jahr').text());
		console.log($('#monat').text());
		var templink = '?admin_id='+mylink+'&action=print_month&timestamp='+mytime+'&print=0&calc=1';
		$.ajax(
			{
				url: templink,
				type: 'get',
				dataType: 'text',
				async: true,
				success: function(response)
				{
					anzeigen();	
				}
			});	
	}

	function jahr(jahr)
	{
		var my_jetzt 	= new Date();
		var my__jahr 	= my_jetzt.getFullYear();	
		console.log('Jahr wurde gewählt: ' + (my__jahr-jahr));
		$('#jahr').text((my__jahr-jahr));
		anzeigen();
	}
  	
	function monat(monat)
	{
		console.log('Monat wurde gewählt: ' + monat);
		$('#monat').text(monat);
		anzeigen();
	}
	
</script>
<style>
	.float div, .float p{
		float: left;
		margin-right: 5px;
		margin-bottom: 15px;
		display: block;
	}
	.anz{
		margin-left: 15px;
	}
	#monat, #jahr{
		margin-right: 15px;
	}
	.floatright{
		float: right;
		margin-bottom: 15px;
		display: block;
	}
	.floatright p{
		margin-left: 25px;
	}
	
	.menue{
		display: block;
		float: none;
		clear: both;
	}
	.left, .left div{
		float: left;
		margin-right: 5px;
		margin-bottom: 15px;
	}
	.right{
		float: right;
	}
	.clear{
		float: none;
		display: block;
		clear: both;
	}
	.pdfcreate {
		margin-left: 25px;
		float: right;
	}
	#mypdf{
		    width: 100%;
		    background-color: #f9f9f9;    
	} 
	#mypdf table{
		width: 100%;
		background-color: #e7e7e7;
	}
    	#mypdf table thead{
		font-weight: bold;
		background-color: #e7e7e7;
	}
	#mypdf table thead tr td{
		height: 30px;
		padding: 5px;
	}
	#mypdf table tbody tr td{
		height: 30px;
		background-color: #ffffff;
		padding: 5px;
	}
</style>
<table height="100%" width="100%" align="center">
	<tr>
		<td>
			<?php echo "<center>".@$_infotext04."</cernter>"; ?>
		</td>
	</tr>
</table>
<div class="menue">
	<div class="left">
		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Monat
				<span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a href="#" onclick="monat('01')">01</a></li>
				<li><a href="#" onclick="monat('02')">02</a></li>
				<li><a href="#" onclick="monat('03')">03</a></li>
				<li><a href="#" onclick="monat('04')">04</a></li>
				<li><a href="#" onclick="monat('05')">05</a></li>
				<li><a href="#" onclick="monat('06')">06</a></li>
				<li><a href="#" onclick="monat('07')">07</a></li>
				<li><a href="#" onclick="monat('08')">08</a></li>
				<li><a href="#" onclick="monat('09')">09</a></li>
				<li><a href="#" onclick="monat('10')">10</a></li>
				<li><a href="#" onclick="monat('11')">11</a></li>
				<li><a href="#" onclick="monat('12')">12</a></li>
			</ul>
		</div>
		<div>
			<p id="monat" class="btn">0</p>
		</div>

		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" >Jahr
				<span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a href="#" onclick="jahr('0')" id="jahr0">2000</a></li>
				<li><a href="#" onclick="jahr('1')" id="jahr1">2000</a></li>
				<li><a href="#" onclick="jahr('2')" id="jahr2">2000</a></li>
				<li><a href="#" onclick="jahr('3')" id="jahr3">2000</a></li>
				<li><a href="#" onclick="jahr('4')" id="jahr4">2000</a></li>
				<li><a href="#" onclick="jahr('5')" id="jahr5">2000</a></li>
				<li><a href="#" onclick="jahr('6')" id="jahr6">2000</a></li>

			</ul>
		</div>
		<div>
			<p id="jahr" class="btn">0</p>
		</div>

	</div>
</div>
<div class="clear"><hr></div>
<div id="mypdf">
	<table border="0" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td width="20px"># </td>
				<td width="240px">Name</td>
				<td width="calc (100% - 260px)">PDF</td>
			</tr> 
		</thead>
		<tbody jput="pdfliste" jput-jsondata='[]'>
			<tr>
				<td>{{index}}</td>
				<td>{{json.username}}</td>
				<td>{{json.pdflink}}{{json.pdflinkcreate}}</td>
			</tr>
		</tbody>
	</table>  
</div>

