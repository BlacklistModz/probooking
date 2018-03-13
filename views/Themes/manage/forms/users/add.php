<?php 
$title = "ผู้ใช้งาน";
$arr['title'] = "เพิ่ม {$title}";
if( !empty($this->item) ){
	$arr['title'] = "แก้ไข {$title}";
	$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);
}

$form = new Form();
$form = $form->create()
			 ->elem('div')
			 ->addClass('form-insert');

$form 	->field('user_fname')
		->label("ชื่อ")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item["fname"]) ? $this->item["fname"] : "" );

$form 	->field("user_lname")
		->label("นามสกุล")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['lname']) ? $this->item['lname'] : "" );

$form 	->field("user_nickname")
		->label("ชื่อเล่น")
		->addClass("inputtext")
		->autocomplete('off')
		->value( !empty($this->item['nickname']) ? $this->item['nickname'] : "" );

$form 	->field("user_email")
		->label("อีเมล")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['email']) ? $this->item['email'] : "" );

$form 	->field("user_tel")
		->label("เบอร์โทรศัพท์มือถือ")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['tel']) ? $this->item['tel'] : "" );

$form 	->field("user_line_id")
		->label("Line ID")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['line_id']) ? $this->item['line_id'] : "" );

$form 	->field("user_address")
		->label("ที่อยู่")
		->addClass('inputtext')
		->autocomplete('off')
		->attr('data-plugins', 'autosize')
		->type('textarea')
		->value( !empty($this->item['address']) ? $this->item['address'] : "" );

$form 	->hr('<br/>');

$form 	->field("user_name")
		->label("Username")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['name']) ? $this->item['name'] : "" );

if( empty($this->item) ){
	$form 	->field("user_password")
			->label("Password")
			->addClass('inputtext')
			->type('password')
			->autocomplete('off')
			->value( '' );
}

$form 	->field("group_id")
		->label("กลุ่มผู้ใช้งาน")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->group )
		->value( !empty($this->item['group_id']) ? $this->item['group_id'] : '' );

$form 	->field("user_team_id")
		->label("ทีม")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->team['lists'] )
		->value( !empty($this->item['team_id']) ? $this->item['team_id'] : '' );

$status_01 = "";
$status_02 = "";
if( !empty($this->item['status']) ){
	if( $this->item['status'] == 1 ) $status_01 = 'checked="1"';
	if( $this->item['status'] == 2 ) $status_02 = 'checked="1"';
}
else{
	$status_01 = 'checked="1"';
}
$status = '<div>
				<label class="radio"><input type="radio" name="status" value="1" '.$status_01.'>ใช้งาน</label>
		   </div>';
$status .= '<div>
				<label class="radio"><input type="radio" name="status" value="2" '.$status_02.'>ระงับการใช้งาน</label>
		   </div>';
$form 	->field("status")
		->label("สถานะ")
		->text( $status );

#BODY
$arr["body"] = $form->html();

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL.'user/save"></form>';

# set button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

$arr['is_close_bg'] = true;

echo json_encode($arr);