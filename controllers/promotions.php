<?php

class Promotions extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function add(){
    	if( $this->format!='json' ) $this->error();

    	$this->view->setData('status', $this->model->status());
    	$this->view->setPage('path','Themes/manage/forms/promotions');
    	$this->view->render('add');
    }
    public function edit($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

    	$this->view->setData('item', $item);
    	$this->view->setData('status', $this->model->status());
    	$this->view->setPage('path','Themes/manage/forms/promotions');
    	$this->view->render('add');
    }
    public function save(){
    	if( empty($_POST) ) $this->error();

    	$id = isset($_POST["id"]) ? $_POST["id"] : null;
    	if( !empty($id) ){
    		$item = $this->model->get($id);
    		if( empty($item) ) $this->error();
    	}

    	try{
    		$form = new Form();
    		$form 	->post('pro_name')->val('is_empty')
    				->post('pro_discount')->val('is_empty')
    				->post('pro_start_date')
    				->post('pro_end_date')
    				->post('pro_status')->val('is_empty');
    		$form->submit();
    		$postData = $form->fetch();

    		$has_name = true;
    		if( !empty($item) ){
    			if( $item["name"] == $postData["pro_name"] ) $has_name = false;
    		}
    		if( $this->model->is_name($postData["pro_name"]) && $has_name ){
    			$arr['error']['pro_name'] = 'มีชื่อนี้ซ้ำในระบบ';
    		}

    		if( empty($arr['error']) ){
    			if( !empty($id) ){
    				$this->model->update($id, $postData);
    			}
    			else{
    				$this->model->insert($postData);
    			}

    			$arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
    			$arr['url'] = 'refresh';
    		}

    	} catch (Exception $e) {
    		$arr['error'] = $this->_getError($e->getMessage());
    	}
    	echo json_encode($arr);
    }
    public function del($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

    	if( !empty($_POST) ){
    		if( !empty($item["permit"]["del"]) ){
    			$arr["message"] = "ลบข้อมูลเรียบร้อยแล้ว";
    			$arr["url"] = "refresh";
    		}
    		else{
    			$arr["message"] = "ไม่สามารถลบข้อมูลได้";
    		}
    		echo json_encode($arr);
    	}
    	else{
    		$this->view->setData('item', $item);
    		$this->view->setPage('path','Themes/manage/forms/promotions');
    		$this->view->render('del');
    	}
    }
}