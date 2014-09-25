 $( document ).ready(function() {
		console.log( "document loaded" );
	});


$( window ).load(function() {
		console.log( "window loaded" );
	});
        
var offsetx=20
var offsety=0

function RapportMouseover(e,Inhalte){
	console.log( "Rapport onmouseover" );
	offsetX = -180;
	offsetY = 0;
	if (offsetX) {offsetx=offsetX;} else {offsetx=0;}
	if (offsetY) {offsety=offsetY;} else {offsety=0;}
	var PositionX = 0;
	var PositionY = 0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY)
	{
		PositionX = e.pageX;
		PositionY = e.pageY;
	}
	else if (e.clientX || e.clientY)
	{
		PositionX = e.clientX + document.body.scrollLeft;
		PositionY = e.clientY + document.body.scrollTop;
	}
	document.getElementById("BoxInhalte").innerHTML = Inhalte;
	document.getElementById('InfoBox').style.left = (PositionX+offsetx)+"px";
	document.getElementById('InfoBox').style.top = (PositionY+offsety)+"px";
	document.getElementById('InfoBox').style.visibility = "visible";
}

function RapportMouseout(){
	console.log( "Rapport onmouseout" );
	document.getElementById('InfoBox').style.visibility = "hidden";
}