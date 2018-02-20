<?php

class Office extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	header("location:".URL."office/settings");
    }
    public function settings($section='my',$tap=''){

    	$this->view->setPage('on', 'settings' );
        $this->view->setPage('title', 'ตั้งค่า');
        $this->view->setData('section', $section);
        if( !empty($tap) ) $this->view->setData('tap', $tap);

        if( $section == "my" ){
        	if( empty($tap) ) $tap = 'basic';

            $this->view->setData('section', 'my');
            $this->view->setData('tap', 'display');
            $this->view->setData('_tap', $tap);

    		// if( $tap=='basic' ){

    		// 	$this->view
    		// 	->js(  VIEW .'Themes/'.$this->view->getPage('theme').'/assets/js/bootstrap-colorpicker.min.js', true)
    		// 	->css( VIEW .'Themes/'.$this->view->getPage('theme').'/assets/css/bootstrap-colorpicker.min.css', true);

    		// 	$this->view->setData('prefixName', $this->model->query('system')->prefixName());
    		// }
        }
        elseif( $section == "users" ){

            if( empty($tap) ) $tap = 'users';
            if( $tap == 'users' ){
                if( $this->format=='json' ){
                    $results = $this->model->query('user')->lists();
                    $this->view->setData('results', $results);
                    $render = "settings/sections/users/users/json";
                }
                else{
                    $this->view->setData('status', $this->model->query('user')->status());
                    $this->view->setData('group', $this->model->query('user')->group());
                }
            }
            elseif( $tap == 'group' ){
                $this->view->setData('data', $this->model->query('user')->group());
            }
        }
        // elseif( $section == 'agency' ){
        //     if( empty($tap) ) $tap = 'company';
        //     if( $tap == 'company' ){
        //         if( $this->format=='json' ){
        //             $results = $this->model->query('agency_company')->lists();
        //             $this->view->setData('results', $results);
        //             $render = "settings/sections/agency/company/json";
        //         }
        //         else{
        //             $this->view->setData('status', $this->model->query('agency_company')->status());
        //         }
        //     }
        //     else{
        //         $this->error();
        //     }
        // }
        elseif( $section == 'products' ){
            if( empty($tap) ) $tap = 'promotions';
            if( $tap == 'promotions' ){
                if( $this->format=='json' ){
                    $results = $this->model->query('promotions')->lists();
                    $this->view->setData('results', $results);
                    $render = "settings/sections/products/promotions/json";
                }
                else{
                    $this->view->setData('status', $this->model->query('promotions')->status());
                }
            }
        }
        else{
            $this->error();
        }
        $this->view->render( !empty($render) ? $render : "settings/display");
    }

    public function reports($section="booking", $tap=""){
        $this->view->setPage('on', 'reports');
        $this->view->setPage('title', 'Reports - '.ucfirst($section));
        $this->view->setData('section', $section);
        if( !empty($tap) ) $this->view->setData('tap', $tap);

        if( $section == "booking" ){
            if( empty($tap) ) $tap = "daily";
            $this->view->setData('tap', $tap);
            if( $tap == "daily" ){

                // $this->view->js('jquery/jquery-selector.min')
                //            ->css('jquery-selector');

                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('sales', $this->model->query('agency_company')->saleLists());
                $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true, 'status'=>1,'sort'=>'com_name') ));
                $this->view->setData('status', $this->model->query('booking')->status());
            }else if($tap =="monthy"){
               
                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('sales', $this->model->query('agency_company')->saleLists());
                $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true, 'status'=>1,'sort'=>'com_name') ));
                $this->view->setData('status', $this->model->query('booking')->status());
            }else{
                $this->error();
            }
        }
        elseif( $section == "recevied" ){
            if( empty($tap) ) $tap = "daily";
            $this->view->setData('tap', "recevied_".$tap);
            if( $tap == "daily" ){
                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('bank', $this->model->query("bankbook")->lists());
            }
        }
        elseif( $section == "period" ){
            if( empty($tap) ) $tap = "monthy";
            $this->view->setData('tap', "period_".$tap);
            if( $tap == "monthy" ){
                $this->view->setData('country', $this->model->query('products')->categoryList());
                $this->view->setData('sales', $this->model->query('agency_company')->saleLists());
                $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true, 'status'=>1,'sort'=>'com_name') ));
                $this->view->setData('status', $this->model->query('booking')->status());
            }
        }
        else{
            $this->error();
        }
        $this->view->render( !empty($render) ? $render : "reports/display" );
    }

    public function agency($id=null){

        $this->view->setPage('title', 'เอเจนซี่');
        $this->view->setPage('on', 'agency');

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( !empty($id) ){

        }
        else{
            if( $this->format=='json' ){
                $results = $this->model->query('agency')->lists();
                $this->view->setData('results', $results);
                $render = "agency/lists/json";
            }
            else{
                $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true, 'sort'=>'com_name') ));
                $this->view->setData('status', $this->model->query('agency')->status());
                $render = "agency/lists/display";
            }
        }
        $this->view->render( $render );
    }
    public function agency_company($id=null){
        $this->view->setPage('title', 'บริษัทเอเจนซี่');
        $this->view->setPage('on', 'agency_company');

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( !empty($id) ){

        }
        else{
            if( $this->format=='json' ){
                $results = $this->model->query('agency_company')->lists();
                $this->view->setData('results', $results);
                $render = "agency_company/lists/json";
            }
            else{
                $this->view->setData('status', $this->model->query('agency_company')->status());
                $this->view->setData('sales', $this->model->query('agency_company')->saleLists());
                $this->view->setData('province', $this->model->query('system')->province());
                $render = "agency_company/lists/display";
            }
        }
        $this->view->render($render);
    }
}