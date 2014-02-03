<?php


$meins = drei::show();

echo "<hr>";
$new = new eins();



class drei{
	static function show(){
		echo "<br>jaja";
	}
	public function temp(){
		echo "<br>geht";
	}
}

class zwei{
	
}

class eins{
	public $_zwei;
	public $_drei;
	
	public function __construct(){ 
	 	$this->_zwei = new zwei();
	 	$this->_drei = drei::show();
	 	$this->_drei = new drei();
	 	$this->_drei->temp();
	}
	
}
?>