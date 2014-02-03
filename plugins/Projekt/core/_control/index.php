<?php
class Controller{

        public $view;
        private $data;

        public function __construct(){
                $this->view = new View();
                $this->data = Model::getData();
        }

        public function display(){
                //$this->view->setTemplate();
                $this->view->setContent("user1", "Anzeige Menue Oben");
                $this->view->setContent("user2", "Anzeige Menue 2");
                $this->view->setContent("user3", "Anzeige links");
                //$this->view->setContent("user4", "Content");
                $this->view->setContent("user4", $this->data);

                //return $this->view->parseTemplate();
        }

}
?>