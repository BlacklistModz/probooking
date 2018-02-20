<?php

class Contact extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->view->setPage('title', 'CONTACT US');
    	$this->view->render('contact/display');
    }
}