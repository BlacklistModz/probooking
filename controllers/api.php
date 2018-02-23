<?php

class Api extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function loadPayments($id=null){
        header('Access-Control-Allow-Origin:http://admin.probookingcenter.com'); 
      
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($_POST)) $this->error();
        $options = array(
           'status' => isset($_REQUEST["status"]) ? $_REQUEST["status"] : null
        );

        $item = $this->model->query('payment')->getbyUser($id, $options);
        echo json_encode($item);
    }
}