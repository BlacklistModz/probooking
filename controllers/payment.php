<?php

class Payment extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function add($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $book = $this->model->query('booking')->get($id);
        if( empty($book) ) $this->error();

        $options = array();
        if( empty($this->me["group_id"]) ){
            $options["is_payment"] = true;
        }

        $bank = $this->model->query('bankbook')->lists( $options );

        $this->view->setData('book', $book);
        $this->view->setData('bank', $bank['lists']);
        $this->view->setData('status', $this->model->query('booking')->status());
        $this->view->render('forms/payment/add');
    }
    public function edit($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $book = $this->model->query('booking')->get($item["book_id"]);
        if( empty($book) ) $this->error();

        $options = array();
        if( empty($this->me["group_id"]) ){
            $options["is_payment"] = true;
        }
        $bank = $this->model->query('bankbook')->lists( $options );

        $this->view->setData('item', $item);
        $this->view->setData('book', $book);
        $this->view->setData('bank', $bank['lists']);
        $this->view->setData('status', $this->model->query('booking')->status());
        $this->view->render('forms/payment/add');
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
        	$form 	->post('bankbook_id')->val('is_empty')
        			->post('pay_date')
        			->post('pay_time')->val('is_empty')
        			->post('pay_received')->val('is_empty')
        			->post('remark')
        			->post('book_status')
        			->post('book_id');
        	$form->submit();
        	$postData = $form->fetch();

        	if( empty($arr['error']) ){
        		if( !empty($id) ){
        			$this->model->update($id, $postData);
        		}
        		else{
                    $postData["user_action"] = !empty($this->me["company_id"]) ? $this->me["company_id"] : $this->me["name"];
        			$this->model->insert($postData);
        			$id = $postData["id"];

                    $this->model->query('booking')->update($postData["book_id"], array("status"=>55));
        		}
        	}

        	if( !empty($id) && !empty($_FILES["pay_url_file"]) ){

        		if( !empty($item["pay_url_file"]) ){
        			$file = substr(strrchr($item['pay_url_file'],"/"),1);
        			if( file_exists(PATH_PAYMENT.$file) ){
        				@unlink(PATH_PAYMENT.$file);
        			}
        		}

        		$i = mt_rand(10, 99);
        		$type = strrchr($_FILES["pay_url_file"]['name'],".");
        		$name = 'img_'.$i.'_'.date('Y_m_d_H_i_s').$type;
        		if( move_uploaded_file($_FILES["pay_url_file"]["tmp_name"], PATH_PAYMENT.$name) ){
        			$this->model->update($id, array("pay_url_file"=>"../upload/payment/{$name}"));
        		}
        	}

        	$arr['message'] = 'แจ้งชำระเงินเรียบร้อยแล้ว';
        	$arr['url'] = 'refresh';

        } catch (Exception $e) {
        	$arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function setStatus($id=null, $status=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : $status;

        if( empty($id) || empty($status) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            if( $status == 9 && empty($_POST["remark_cancel"]) ){
                $arr['error']["remark_cancel"] = "กรุณาระบุเหตุผลในการไม่อนุมัติ";
            }

            if( empty($arr['error']) ){
                $postData = array(
                    'status'=>$status,
                    'remark_cancel'=> !empty($_POST["remark_cancel"]) ? $_POST["remark_cancel"] : '' 
                );
                $this->model->update($id, $postData);

                $postBook = array(
                    'status'=> $status == 1 ? $item["book_status"] : 55 
                );

                $this->model->query('booking')->update($item["book_id"], $postBook);

                $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                $arr['url'] = 'refresh';
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('booking', $this->model->query('booking')->get($item["book_id"]));
            $this->view->setData('item', $item);
            $this->view->setData('status', $status);
            $this->view->setPage('path', 'Themes/manage/forms/payment');
            $this->view->render('setStatus');
        }
    }
}