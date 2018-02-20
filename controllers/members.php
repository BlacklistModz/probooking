<?php

class Members extends Controller  {

    public function __construct() {
        parent::__construct();        
    }

    public function index(){
        $this->error();
    }

    public function save(){

//    	echo '<pre>';
//    	print_r($_POST);
//    	echo '</pre>';
//    	exit();


    	if( empty($_POST) ) $this->error();

        $id = !empty($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }

    	try{
    		$form = new Form();
    		$form 	->post('mem_first_name')
    				->post('mem_last_name')
    				->post('mem_nickname')
    				->post('mem_position')
    				->post('mem_email')
    				->post('mem_mobile_phone')
    				->post('mem_social_line')
    				->post('mem_social_skype')
    				->post('mem_username')
    				->post('mem_password')
    				->post('mem_co_name')
    				->post('mem_co_address')
    				->post('mem_co_phone')
    				->post('mem_co_fax')
    				->post('mem_co_license');
    		$form->submit();
    		$postData = $form->fetch();

		    $has_name = true;
		    $has_email = true;

    		if($postData['mem_first_name'] == '') {
    			$arr['error']['mem_first_name'] = 'กรุณากรอกชื่อ';
			    $has_name = false;
		    }
    		if($postData['mem_last_name'] == '') {
    			$arr['error']['mem_last_name'] = 'กรุณากรอกนามสกุล';
		    }
    		if($postData['mem_position'] == '') {
    			$arr['error']['mem_position'] = 'กรุณากรอกตำแหน่ง';
		    }
    		if($postData['mem_email'] == '') {
    			$arr['error']['mem_email'] = 'กรุณากรอกอีเมล';
			    $has_email = false;
		    }
    		if($postData['mem_mobile_phone'] == '') {
    			$arr['error']['mem_mobile_phone'] = 'กรุณากรอกมือถือ';
		    }
    		if($postData['mem_username'] == '') {
    			$arr['error']['mem_username'] = 'กรุณากรอกชื่อเข้าใช้งาน';
		    }
    		if($postData['mem_co_name'] == '') {
    			$arr['error']['mem_co_name'] = 'กรุณากรอกบริษัท';
		    }


    		if( $this->model->is_username($postData['mem_username']) && $has_name ){
    			$arr['error']['mem_username'] = 'มีชื่อผู้ใช้นี้ในระบบแล้ว';
    		}

            if( $this->model->is_email($postData['mem_email']) && $has_email ){
                $arr['error']['mem_email'] = 'มีอีเมลนี้ในระบบแล้ว';
            }

		    if( strlen($postData['mem_password']) < 6 ){
                $arr['error']['mem_password'] = 'กรุณากรอกรหัสผ่าน 6 ตัวอักษรขึ้นไป';
            }

            if( $_POST["mem_password2"] != $postData['mem_password'] ){
                $arr['error']['mem_password2'] = 'รหัสยืนยันไม่ตรงกับรหัสผ่าน';
            }

    		if( empty($arr['error']) ){

                if( !empty($id) ){
                    $this->model->update($id, $postData);
                }
                else{
                    if( !empty($postData['mem_password']) ){
                        $postData['mem_password'] = substr(md5(trim($postData['mem_password'])),0,20);
                    }
                    $this->model->insert($postData);
                }

                $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
//                $arr['post'] = $_POST;
//                $arr['url'] = isset($_REQUEST["next"]) ? $_REQUEST["next"] : URL;
    		}

    	} catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
}