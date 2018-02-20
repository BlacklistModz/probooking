<?php

class agency_company extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function add(){
    	if( $this->format!='json' ) $this->error();

    	$this->view->setData('status', $this->model->status());
        $this->view->setData('geo', $this->model->query('system')->geo());
        $this->view->setData('sales', $this->model->saleLists());
    	$this->view->setPage('path','Themes/manage/forms/agency/company');
    	$this->view->render('add');
    }
    public function edit($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

    	$this->view->setData('item', $item);
    	$this->view->setData('status', $this->model->status());
        $this->view->setData('geo', $this->model->query('system')->geo());
        $this->view->setData('sales', $this->model->saleLists());
    	$this->view->setPage('path','Themes/manage/forms/agency/company');
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
    		$form 	->post('agen_com_name')->val('is_empty')
    				->post('agen_com_address1')
    				->post('agen_com_address2')
                    ->post('agen_com_geo')
                    ->post('agen_com_province')
                    ->post('agen_com_amphur')
    				->post('agen_com_tel')
    				->post('agen_com_fax')
    				->post('agen_com_email')
    				->post('agen_com_ttt_on')
    				->post('remark')
                    ->post('agen_com_user_id')
    				->post('status');
    		$form->submit();
    		$postData = $form->fetch();

    		$has_name = true;
    		if( !empty($item) ){
    			if( $item['com_name'] == $postData['agen_com_name'] ) $has_name = false;
    		}
    		if( $this->model->is_name($postData['agen_com_name']) && $has_name ){
    			$arr['error']['agen_com_name'] = 'มีชื่อนี้ซ้ำในระบบ';
    		}

    		$postData['agen_com_guarantee'] = !empty($_POST["agen_com_guarantee"]) ? 1 : 0;

    		if( empty($arr['error']) ){
    			if( !empty($id) ){
    				$this->model->update($id, $postData);
    			}
    			else{
    				$id = $this->model->insert($postData);
    			}

    			if( !empty($id) && !empty($_FILES) ){
    				if( !empty($_FILES["file_ttt"]) ){
    					
    				}
    				if( !empty($_FILES["file_logo"]) ){

    				}
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
    		if( !empty($item['permit']['del']) ){
    			$this->model->delete($id);
    			$arr['message'] = 'ลบช้อมูลเรียบร้อย';
    			$arr['url'] = 'refresh';
    		}
    		else{
    			$arr['message'] = 'ไม่สามารถลบข้อมูลได้';
    		}
    	}
    	else{
    		$this->view->setData('item', $item);
    		$this->view->setPage('path', 'Themes/manage/forms/agency/company');
    		$this->view->render('del');
    	}
    }

    /* JSON DATA */
    public function listsProvince($id=null){
        if( empty($id) ) $this->error();
        echo json_encode($this->model->query('system')->getGeoProvince( $id ));
    }
    public function listsAmphur($id=null){
        if( empty($id) ) $this->error();
        echo json_encode($this->model->query('system')->getProvinceAmphur( $id ));
    }
}