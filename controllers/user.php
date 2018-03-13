<?php

class User extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function add(){
    	//empty($this->me) ||
    	if( $this->format!='json' ) $this->error();

    	$this->view->setData('group', $this->model->group());
        $this->view->setData('team', $this->model->query('teams')->lists());
    	$this->view->setPage('path', 'Themes/manage/forms/users');
    	$this->view->render('add');
    }
    public function edit($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

    	$this->view->setData('item', $item);
    	$this->view->setData('group', $this->model->group());
        $this->view->setData('team', $this->model->query('teams')->lists());
    	$this->view->setPage('path', 'Themes/manage/forms/users');
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
            $form   ->post('user_fname')->val('is_empty')
                    ->post('user_lname')
                    ->post('user_nickname')
                    ->post('user_email')->val('email')
                    ->post('user_tel')
                    ->post('user_line_id')
                    ->post('user_address')
                    ->post('user_name')->val('username')
                    ->post('group_id')->val('is_empty')
                    ->post('user_team_id')
                    ->post('status');
            $form->submit();
            $postData = $form->fetch();

            if( empty($item) ){
                if( empty($_POST["user_password"]) ){
                    $arr['error']['user_password'] = "กรุณากรอกรหัสผ่าน";
                }
                elseif( strlen($_POST["user_password"]) < 4 ){
                    $arr['error']['user_password'] = 'รหัสผ่านต้องมีความยาว 4 ตัวอักษรขึ้นไป';
                }
                else{
                    $postData['user_password'] = substr(md5(trim($_POST["user_password"])),0,20);
                }
            }

            $has_user = true;
            $has_email = true;
            if( !empty($item) ){
                if( $item['name'] == $postData['user_name'] ) $has_user = false;
                if( $item['email'] == $postData['user_email'] ) $has_email = false;
            }
            if( $this->model->is_user($postData['user_name']) && $has_user ){
                $arr['error']['user_name'] = 'มีชื่อผู้ใช้นี้อยู่ในระบบแล้ว';
            }
            if( $this->model->is_email($postData['user_email']) && $has_email ){
                $arr['error']['user_email'] = 'มีอีเมลนี้อยู่ในระบบแล้ว';
            }

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->update($id, $postData);
                }
                else{
                    $postData["create_user_id"] = $this->me['id'];
                    $this->model->insert($postData);
                }
                $arr['message'] = 'บันทึกเรียบร้อย';
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
            $this->view->setPage('path', 'Themes/manage/forms/users');
            $this->view->render('del');
        }
    }
    public function change_password($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( strlen($_POST["password_new"]) < 6 ){
                $arr["error"]["password_new"] = "รหัสผ่านต้องเป็น 6 ตัวอักษรขึ้นไป";
            }
            elseif( $_POST["password_new"] != $_POST["password_confirm"] ){
                $arr["error"]["password_new"] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
                $arr["error"]["password_confirm"] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
            }

            if( empty($arr["error"]) ){
                $password = substr(md5(trim($_POST["password_new"])),0,20);
                $this->model->update($id, array("user_password"=>$password));
                $arr["message"] = "เปลี่ยนรหัสผ่านเรียบร้อย";
            }
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Themes/manage/forms/users');
            $this->view->render('change_password');
        }
    }

    #GROUP
    public function add_group(){
        if( $this->format!='json' ) $this->error();

        $this->view->setPage('path', 'Themes/manage/forms/users/group');
        $this->view->render('add');
    }
    public function edit_group($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->group($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Themes/manage/forms/users/group');
        $this->view->render('add');
    }
    public function save_group(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->group($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post("group_name")->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($item) ){
                if( $item["name"] == $postData["group_name"] ) $has_name = false;
            }
            if( $this->model->is_groupname($postData["group_name"]) && $has_name ){
                $arr["error"]["group_name"] = "มีชื่อกลุ่มนี้อยู่ในระบบแล้ว";
            }

            if( empty($arr["error"]) ){
                if( !empty($id) ){
                    $this->model->updateGroup($id, $postData);
                }
                else{
                    $this->model->insertGroup($postData);
                }

                $arr["message"] = "บันทึกเรียบร้อย";
                $arr["url"] = "refresh";
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_group($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->group($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( !empty($item['permit']['del']) ){
                $this->model->deleteGroup($id);
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
            $this->view->setPage('path', 'Themes/manage/forms/users/group');
            $this->view->render('del');
        }
    }
}