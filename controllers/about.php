<?php

class About extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->view->setPage('title', 'ABOUT US');
    	$this->view->render('about/display');
    }
}