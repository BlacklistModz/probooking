<?php

class Index extends Controller {

    public function __construct() {
        parent::__construct();
    }

    // home
    public function index() {

        
        $this->view->setData('recommendList', $this->model->query('products')->recommendList( 9 ) );

        $this->view->setData('popularList', $this->model->query('products')->popularList() );

        $this->view->setData('slideList', $this->model->query('products')->slideList( 9 ) );


        $categoryList = $this->model->query('products')->categoryList();
        foreach ($categoryList as $key => $value) {
            
            $results = $this->model->query('products')->lists( array('status'=>1, 'country'=>$value['id'], 'limit'=>4) );
            $categoryList[$key]['items'] = $results['lists'];
        }
        $this->view->setData('categoryList', $categoryList );

        if( isset($_GET['chong_debug']) ){ if( $_GET['chong_debug']=='results' ){ print_r($categoryList); die; } }

        // $this->view->css( VIEW. 'Themes/default/assets/css/slider.css', true );
        $this->view->render("home/display");
    }

    public function search($param=null) {

        $categoryList = $this->model->query('products')->categoryList();
        foreach ($categoryList as $key => $value) {
            
            $results = $this->model->query('products')->lists( array('status'=>1, 'country'=>$value['id'], 'limit'=>4) );
            $categoryList[$key]['items'] = $results['lists'];
        }
        $this->view->setData('categoryList', $categoryList );

        // $this->view->render("products/display"); 
        /*$this->view->render("products/lists"); 
        exit;*/

        switch ( count($param) ) {


            case 3: // category
                $this->getCategory( $param[2] );
                // $this->getPost( $param[1] );
                break;

            case 2: // category
                $this->getProduct( $param[1] );
                // $this->getPost( $param[1] );
                break;

            case 1:
                $this->getPage( $param[0] );
                break;

            default:
                $this->error();
                break;
        }
        
    }

    public function getPage( $q ) {
        
        $default = array('search');
        $keyword = '';
        foreach ($this->system['navigation'] as $nav) {
            
            if( $nav['id'] == 'index' || empty($nav['url']) ) continue;

            if( in_array($q,  $default) ){
                $keyword = $q; break;
            }

            if( Translate::Menu($nav['id']) == $q ){
                $keyword = $nav['id'];
                break;
            }
        }

        /*if( in_array($keyword, $default) ){
            $this->searchList();
        }*/
        
        $this->view->setPage('on', $keyword );

        $theme = isset($this->system['theme']) ?$this->system['theme']: 'default';
        $path = WWW_VIEW."Themes/{$theme}/pages/{$keyword}/display.php";

        // echo $path; die;
        if( !file_exists($path) ) $this->error();
        // echo $keyword; die;

        /*if( $keyword=='contact' ){
            $this->view->js('https://www.google.com/recaptcha/api.js', true);
        }*/

        /*if( $keyword=='service' ){
            $agency = $this->model->query('agency')->lists();
            $this->view->setData('agencyList', $agency['lists'] ); 
        }*/


        $this->view->render( "{$keyword}/display" );
    }

    // category
    public function getCategory( $keyword )
    {
        $item = $this->model->query('products')->category( $keyword );
        if( empty($item) ) $this->error();
        $this->view->setPage('on', 'category' );
        // if( isset($_GET['chong_debug']) ){ print_r($item); die; }

        $options = array(
            // 'limit'=>9,
            'unlimited' => true,
            'country' => $item['id'],

            'period' => true,

            'status' => 1,
            // 'code' => 
        );

        $results = $this->model->query('products')->lists( $options );
        if( isset($_GET['chong_debug']) ){ print_r($results); die; }
        // print_r($results); die;
        
        //print_r($results);die;
        $this->view->setData('results', $results );
       
        $this->view->setData('item', $item);
        $this->view->render("category/lists");
    }

    public function getProduct($keyword)
    {
        // echo 'this getPostOfYear'; die;
        $item = $this->model->query('products')->get( $keyword, array('period' => true) );
        if( empty($item) ) $this->error();
        // print_r($item); die;

        $this->view->setPage('on', 'products' );
        $this->view->setPage('title', $item['name'] );


        $this->view->setData('item', $item);
        $this->view->render("products/display");
    }
    
}
