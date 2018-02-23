<?php

class Series extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
        header("location:".URL."series/hotsale");
    }
    public function online($id=null){
        if( empty($this->me) ) $this->error();

        $this->view->setPage('title', 'ซีรีย์ทัวร์ ออนไลน์');
        $options = array(
            'unlimited' => true,
            'period' => true,
            'status' => 1,
            'country' => !empty($id) ? $id : 1
        );

        $results = $this->model->query('products')->lists( $options );
        $this->view->setData('country', $this->model->query('products')->categoryList());
        $this->view->setData('results', $results);
        $this->view->render("series/display");
    }
    public function hotsale(){
        if( empty($this->me) ) $this->error();

        $this->view->js('jquery/jquery-ui.min');

        $this->view->setPage('title', 'โปรดันขาย');
        $this->view->setData('country', $this->model->query('products')->categoryList());
        $this->view->setData('results', $this->model->query('products')->hotsaleList());
        $this->view->render("series/hotsale");
    }
    public function tester(){
        $this->view->css('jquery-ui');
        $this->view->js('jquery/jquery-ui.min');

        $this->view->render('series/tester');
    }
}