<?php
class View{

        private $path       = './templates/';
        private $template;
        public $content    = array();

        public function setContent($key, $value){               
                if($key == "user1"){
                	$this->content[$key] = $value;
                }
                if($key == "user2"){
                	$this->content[$key] = $value;
                }
                if($key == "user3"){
                	$this->content[$key] = $value;
                }
                if($key == "user4"){
                	$this->content[$key] = $value;
                }           
        }
        
        public function parseTemplate(){
        	$strhtml = '';
        	$strhtml .= '<br>' . $this->content['usee01'];
        	$strhtml .= '<br>' . $this->content['usee02'];
        	$strhtml .= '<br>' . $this->content['usee03'];
        	$strhtml .= '<br>' . $this->content['usee04'];
        	
        	return $strhtml;
        }
}
?>