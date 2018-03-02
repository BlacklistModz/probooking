<?php

class Register extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->view->setData("geo", $this->model->query("system")->geo());
    	$this->view->setPage('title', 'สมัครตัวแทนจำหน่าย');
    	$this->view->render("register/display");
    }

}