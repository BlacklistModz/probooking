<?php

class Tour extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index($id=null){
    	if( empty($id) ) $this->error();

    	$item = $this->model->query('products')->get( $id, array('period' => true) );
        if( empty($item) ) $this->error();
        if( isset($_GET['chong_debug']) ){ if( $_GET['chong_debug']=='results' ){ print_r($item); die; } }

        $this->view->setPage('on', 'products' );
        $this->view->setPage('title', $item['name'] );

        $this->view->setData('item', $item);
        $this->view->render("products/display");
    }

    public function country($id=null) {
    	if( empty($id) ) $this->error();


    	$item = $this->model->query('products')->category( $id );
        if( empty($item) ) $this->error();
        $this->view->setPage('on', 'category' );
        // if( isset($_GET['chong_debug']) ){ print_r($item); die; }

        $options = array(
            'unlimited' => true,
            'country' => $item['id'],

            'period' => true,

            'status' => 1,
        );

        $results = $this->model->query('products')->lists( $options );
        
        if( isset($_GET['chong_debug']) ){ if( $_GET['chong_debug']=='results' ){ print_r($results); die; } }
        

        $this->view->setData('results', $results );

        $this->view->setData('item', $item);
        $this->view->render("category/lists");
    }
}