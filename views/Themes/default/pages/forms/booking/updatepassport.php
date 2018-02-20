<?php
//print_r($_REQUEST);die;
$arr['title'] = 'ยืนยันการบันทึกข้อมูล';	
	$arr['form'] = '<form class="js-submit-form" action="'.URL.'booking/passport_update" style="color:#000;"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->id);
	$arr['body'] = "หากคุณบันทึกข้อมูลแล้วจะไม่สามารถแก้ไขไฟล์ได้อีกต่อไป <span class=\"fwb fcr\">\"คุณต้องการบันทึก\"</span> ใช่หรือไม่?";
	$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">ใช่</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ไม่</span></a>';

echo json_encode($arr);