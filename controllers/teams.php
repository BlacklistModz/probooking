<?php

class Teams extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function add(){
    	if( empty($this->me) || $this->format!='json' ) $this->error();

    	$this->view->setPage('path','Themes/manage/forms/users/teams');
    	$this->view->render('add');
    }
    public function edit($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

    	$this->view->setData('item', $item);
    	$this->view->setPage('path','Themes/manage/forms/users/teams');
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
    		$form 	->post('team_name')->val('is_empty');
    		$form->submit();
    		$postData = $form->fetch();

    		$has_name = true;
    		if( !empty($item) ){
    			if( $item["name"] == $postData["team_name"] ) $has_name = false;
    		}
    		if( $this->model->is_name($postData["team_name"]) && $has_name ){
    			$arr['error']['team_name'] = 'ตรวจพบชื่อซ้ำในระบบ';
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

    	} catch (Expcetion $e) {
    		$arr['error'] = $this->_getError($e->getMessage());
    	}
    	echo json_encode($arr);
    }
    public function del($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

    	if( !empty($_POST) ){
    		if( !empty($item["permit"]["del"]) ){
    			$this->model->delete($id);
    			$arr['message'] = 'ลบข้อมูลเรียบร้อย';
    			$arr['url'] = 'refresh';
    		}
    		else{
    			$arr['message'] = 'ไม่สามารถลบข้อมูลได้';
    		}
    		echo json_encode($arr);
    	}
    	else{
    		$this->view->setData('item', $item);
    		$this->view->setPage('path','Themes/manage/forms/users/teams');
    		$this->view->render('del');
    	}
    }
}