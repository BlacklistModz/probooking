<?php

class Register extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
    	$this->view->setPage('title', 'สมัครตัวแทนจำหน่าย');
    	$this->view->render("register/display");
    }

}