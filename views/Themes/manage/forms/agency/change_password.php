<?php 

$arr['title'] = 'เปลี่ยนรหัสผ่าน ('.$this->item['user_name'].')';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);

$form = new Form();
$form = $form->create()
			 ->elem('div')
			 ->addClass("form-insert");
$form 	->field("new_password")
		->label("รหัสผ่านใหม่")
		->addClass("inputtext")
		->autocomplete('off')
		->type("password")
		->placeholder('รหัสผ่านใหม่');

$form 	->field("new_password2")
		->label("ยืนยันรหัสผ่านใหม่")
		->addClass("inputtext")
		->autocomplete("off")
		->type("password")
		->placeholder("ยืนยันรหัสผ่านใหม่");

# set form
$arr['form'] = '<form class="js-submit-form" style="color:#000;" method="post" action="'.URL. 'agency/password"></form>';

/* SET BODY */
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

$arr['is_close_bg'] = true;

echo json_encode($arr);