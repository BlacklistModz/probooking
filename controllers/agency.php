<?php

class agency extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }
    public function add($company=null){
        // $company = isset($_REQUEST["company"]) ? $_REQUEST["company"] : $company;
        if( empty($this->me) || $this->format!='json' ) $this->error();
        // || empty($company) 

        $this->view->setData('status', $this->model->status());
        $this->view->render('forms/agency/add');
    }
    public function edit($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $this->view->setData('status', $this->model->status());
        $this->view->setData('item', $item);
        $this->view->render('forms/agency/add');
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
            $form   ->post('agen_fname')->val('is_empty')
                    ->post('agen_lname')
                    ->post('agen_nickname')
                    ->post('agen_position')
                    ->post('agen_email')->val('email')
                    ->post('agen_tel')
                    ->post('agen_line_id')
                    ->post('agen_skype');
                    // ->post('agen_user_name')->val('is_empty')
                    // ->post('status');
            $form->submit();
            $postData = $form->fetch();

            if( empty($item) || $this->me["role"] == "admin" ){
                $postData["agen_user_name"] = $_POST["agen_user_name"];
                if( empty($postData["agen_user_name"]) ){
                    $arr["error"]["user_name"] = "กรุณากรอกข้อมูล";
                }
            }

            if( !empty($_POST["status"]) ){
                $postData["status"] = $_POST["status"];
            }

            $has_user = true;
            $has_email = true;
            if( !empty($item) ){
                if( $item['user_name'] == $postData['agen_user_name'] ) $has_user = false;
                if( $item['email'] == $postData["agen_email"] ) $has_email = false;
            }
            if( $this->model->is_username($postData["agen_user_name"]) && $has_user ){
                $arr['error']['agen_user_name'] = 'มีชื่อผู้ใช้นี้ในระบบแล้ว';
            }
            if( $this->model->is_email($postData["agen_email"]) && $has_email ){
                $arr['error']['agen_email'] = 'มีอีเมลนี้ในระบบแล้ว';
            }

            if( empty($id) ){
                if( strlen($_POST["agen_password"]) < 6 ){
                    $arr['error']['agen_password'] = 'รหัสผ่านต้องมีความยาว 6 ตัวอักษรขึ้นไป';
                }
                elseif( $_POST["agen_password"] != $_POST["agen_password2"] ){
                    $arr['error']['agen_password2'] = 'รหัสยืนยันไม่ตรงกับรหัสผ่าน';
                }
                else{
                    $postData['agen_password'] = substr(md5(trim($_POST["agen_password"])),0,20);
                }
            }

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->update($id, $postData);
                }
                else{
                    // $postData['status'] = 1;
                    $postData['agen_role'] = 'sales';
                    $postData['agency_company_id'] = $this->me['company_id'];
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

    public function set(){
        if( empty($_POST) ) $this->error();

        try{
            $postCompany = array();
            $postData = array();

            if( $_POST["type"] == "company" ){
                if( empty($_POST["agency_company_id"]) ){
                    foreach ($_POST["company"] as $key => $value) {
                        if( $key != 'com_fax' && $key != 'com_address2' ){
                            if( empty($value) ) $arr['error']['agen_'.$key] = 'กรุณากรอกข้อมูล';
                        }
                    }
                }
            }

            if( $_POST["type"] == "agency" ){
                foreach ($_POST["agency"] as $key => $value) {
                    if( $key != 'lname' && $key != 'nickname' && $key != 'position' && $key != 'tel' && $key != 'line_id' && $key != 'skype' ){
                        if( empty($value) ) $arr['error']['agen_'.$key] = 'กรุณากรอกข้อมูล';
                    }
                }

                if( !empty($_POST["agency"]["fname"]) && !empty($_POST["agency"]["lname"]) ){
                    if( $this->model->is_fullname($_POST["agency"]["fname"], $_POST["agency"]["lname"]) ){
                        $arr['error']['agen_fname'] = 'ชื่อ-นามสกุล นี้มีอยู่ในระบบ';
                    }
                }

                if( !empty($_POST["agency"]["user_name"]) ){
                    $firstUser = substr($_POST["agency"]["user_name"], 0,1);
                    if( is_numeric($firstUser) ){
                        $arr['error']['agen_user_name'] = 'Username ต้องไม่ใช้ตัวเลขนำหน้า';
                    }
                    elseif( $this->model->is_username($_POST["agency"]["user_name"]) ){
                        $arr['error']['agen_user_name'] = 'มี Username นี้ในระบบ';
                    }
                }

                // if( !empty($_POST["agency"]["email"]) ){
                //     if( $this->model->is_email($_POST["agency"]["email"]) ){
                //         $arr['error']['agen_email'] = 'มี Email ซ้ำในระบบ';
                //     }
                // }

                if( strlen($_POST["agency"]["password"]) < 6 ){
                    $arr["error"]["agen_password"] = "กรุณากรอกรหัสผ่านให้มีความยาว 6 ตัวอักษรขึ้นไป";
                }
                elseif( $_POST["agency"]["password"] != $_POST["agency"]["password2"] ){
                    $arr["error"]["agen_password"] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
                    $arr["error"]["agen_password2"] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
                }
            }

            if( $_POST["type"] == "save" ){
                if( empty($_POST["agency_company_id"]) ){
                    foreach ($_POST["company"] as $key => $value) {
                        $postCompany["agen_".$key] = $value;
                    }
                    $postCompany["status"] = 0;
                    $postData["agency_company_id"] = $this->model->query('agency_company')->insert($postCompany);
                    $postData["agen_role"] = 'admin';
                }
                else{
                    $postData["agency_company_id"] = $_POST["agency_company_id"];
                    $postData['agen_role'] = 'sales';
                }

                foreach ($_POST["agency"] as $key => $value) {
                    if( $key == 'password2' ) continue;
                    if( $key == "password" ) $value = substr(md5(trim($value)),0,20);
                    $postData["agen_".$key] = $value;
                }

                $postData["status"] = 0;
                $this->model->insert($postData);
            }

            if( empty($arr['error']) ){
                if( $_POST["type"] != "save" ){
                    $arr['status'] = 1;
                }
                else{
                    $arr["message"] = "ขอบคุณที่เข้าร่วมกับเรา";
                    $arr["url"] = isset($_REQUEST["next"]) ? $_REQUEST["next"] : URL;
                }
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function listsCompany(){
        if( $this->format!='json' ) $this->error();
        echo json_encode( $this->model->query('agency_company')->lists() );
    }

    public function change_password($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->query("agency")->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            $form = new Form();
            $form   ->post("new_password")->val('is_empty')
                    ->post("new_password2")->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            if( strlen($postData["new_password"]) < 6 ){
                $arr['error']["new_password"] = "รหัสผ่านต้องมีความยาว 6 ตัวอักษรขึ้นไป";
            }
            elseif( $postData["new_password"] != $postData["new_password2"] ){
                $arr['error']["new_password2"] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
            }

            if( empty($arr['error']) ){
                $password = substr(trim(md5($postData["new_password"])), 0, 20);
                $this->model->update($id, array("agen_password"=>$password));

                $arr["message"] = "เปลี่ยนรหัสผ่านเรียบร้อย";
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->render("forms/agency/change_password");
        }
    }

    /* MANAGE OFFICE */
    public function _add($company=null){
        // $company = isset($_REQUEST["company"]) ? $_REQUEST["company"] : $company;
        if( $this->format!='json' ) $this->error();
        // || empty($company) 

        $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true) ));
        $this->view->setData('status', $this->model->status());
        $this->view->setPage('path','Themes/manage/forms/agency');
        $this->view->render('add');
    }
    public function _edit($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $this->view->setData('company', $this->model->query('agency_company')->lists( array('unlimit'=>true) ));
        $this->view->setData('status', $this->model->status());
        $this->view->setData('item', $item);
        $this->view->setPage('path','Themes/manage/forms/agency');
        $this->view->render('add');
    }
    public function _save(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('agen_fname')->val('is_empty')
                    ->post('agen_lname')
                    ->post('agen_nickname')
                    ->post('agen_position')
                    ->post('agen_email')->val('email')
                    ->post('agen_tel')
                    ->post('agen_line_id')
                    ->post('agen_skype')
                    ->post('agency_company_id')->val('is_empty')
                    ->post('agen_role')->val('is_empty')
                    ->post('status')
                    ->post('agen_user_name')->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            // if( empty($item) ){
            //     $postData["agen_user_name"] = $_POST["agen_user_name"];
            //     if( empty($postData["agen_user_name"]) ){
            //         $arr["error"]["user_name"] = "กรุณากรอกข้อมูล";
            //     }
            // }

            $has_user = true;
            $has_email = true;
            if( !empty($item) ){
                if( $item['user_name'] == $postData['agen_user_name'] ) $has_user = false;
                if( $item['email'] == $postData["agen_email"] ) $has_email = false;
            }
            if( $this->model->is_username($postData["agen_user_name"]) && $has_user ){
                $arr['error']['agen_user_name'] = 'มีชื่อผู้ใช้นี้ในระบบแล้ว';
            }
            if( $this->model->is_email($postData["agen_email"]) && $has_email ){
                $arr['error']['agen_email'] = 'มีอีเมลนี้ในระบบแล้ว';
            }

            if( empty($id) ){
                if( strlen($_POST["agen_password"]) < 6 ){
                    $arr['error']['agen_password'] = 'รหัสผ่านต้องมีความยาว 6 ตัวอักษรขึ้นไป';
                }
                elseif( $_POST["agen_password"] != $_POST["agen_password2"] ){
                    $arr['error']['agen_password2'] = 'รหัสยืนยันไม่ตรงกับรหัสผ่าน';
                }
                else{
                    $postData['agen_password'] = substr(md5(trim($_POST["agen_password"])),0,20);
                }
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
    public function _del($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if(empty($id)  || $this->format != 'json') $this->error();

        $item = $this->model->query('agency')->get($id);
        if(empty($item)) $this->error();

         if(!empty($_POST)){
            if( !empty($item['permit']['del']) ){
                $this->model->delete($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
                $arr['url'] = 'refresh';
            }
            else{
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }
            echo json_encode($arr);
         }else{    
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Themes/manage/forms/agency');
            $this->view->render('del');
         }
    }
    public function password($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->query("agency")->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            $form = new Form();
            $form   ->post("new_password")->val('is_empty')
                    ->post("new_password2")->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            if( strlen($postData["new_password"]) < 6 ){
                $arr['error']["new_password"] = "รหัสผ่านต้องมีความยาว 6 ตัวอักษรขึ้นไป";
            }
            elseif( $postData["new_password"] != $postData["new_password2"] ){
                $arr['error']["new_password2"] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
            }

            if( empty($arr['error']) ){
                $password = substr(trim(md5($postData["new_password"])), 0, 20);
                $this->model->update($id, array("agen_password"=>$password));

                $arr["message"] = "เปลี่ยนรหัสผ่านเรียบร้อย";
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Themes/manage/forms/agency');
            $this->view->render("change_password");
        }
    }
}