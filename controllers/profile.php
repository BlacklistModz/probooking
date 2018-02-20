<?php

class Profile extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	header("location:".URL."profile/history");
    }
    public function history($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($this->me) || empty($this->me['company_id']) ) $this->error();
        $this->view->setPage('title', "Booking History");

         /* GET BOOKING */
        $options = array(
            "company"=>$this->me["company_id"],
            "unlimit"=>true,
            "agency"=>$id
        );
        $booking = $this->model->query("booking")->lists( $options );
        /* GET SALES */
        $s_options = array(
            "company"=>$this->me["company_id"],
            "unlimit"=>true
        );
        $agency = $this->model->query("agency")->lists( $s_options );

        $this->view->setData('sales', $agency);
        $this->view->setData('results', $booking);
        $this->view->setData('agen_id', $id);
        $this->view->render('profile/history');
    }
    public function sales(){
        if( empty($this->me) || empty($this->me['company_id']) ) $this->error();
        $this->view->setPage('title', "Manage Sales");

        $options = array(
            "company"=>$this->me["company_id"],
            "unlimit"=>true
        );
        $agency = $this->model->query("agency")->lists( $options );
        $this->view->setData("results", $agency);
        $this->view->render('profile/manage');
    }
}