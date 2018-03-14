<?php

class partner extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($geo=null){
    	$geo = isset($_REQUEST["geo"]) ? $_REQUEST["geo"] : $geo;
    	if( empty($geo) ) $geo = 7;
    	
        $this->view->setPage('title', 'Partner');

        $results = $this->model->listsCompany( array('status'=>1, 'unlimit'=>true, 'geo'=>$geo) );
       	
       	$this->view->setData('geo', $this->model->query('system')->geo());
       	$this->view->setData('currGeo', $geo);
        $this->view->setData('results', $results);
        $this->view->render('partner/display');
    }
}