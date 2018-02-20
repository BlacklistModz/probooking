<?php

$f = new Form();
$form = $f->create()
	
	// set From
	->elem('div')
	->addClass('form-insert')

	->field("password_new")
		->label('รหัสผ่านใหม่')
		->type('password')
		->addClass('inputtext')
		->maxlength(30)
		->required(true)
		->autocomplete("off")

	->field("password_confirm")
		->label('รหัสผ่านใหม่อีกครั้ง')
		->type('password')
		->addClass('inputtext')
		->maxlength(30)
		->required(true)
		->autocomplete("off");

$arr['hiddenInput'][] = array('name'=>'id', 'value'=> $this->item['id']);
$arr['body'] = $form->html();
$arr['title'] = $this->item["fname"].' '.$this->item["lname"];	
$arr['form'] = '<form class="js-submit-form" action="'.URL.'user/change_password"></form>';
$arr['bottom_msg'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';
$arr['button'] = '<button type="submit" class="btn btn-blue btn-submit"><span class="btn-text">บันทึก</span></button>';

$arr['is_close_bg'] = true;

$arr['width'] = 330;
echo json_encode($arr);