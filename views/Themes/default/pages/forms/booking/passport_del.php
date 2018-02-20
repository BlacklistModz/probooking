<?php

$arr['title'] = 'ยืนยันการลบ';	
	$arr['form'] = '<form class="js-submit-form" action="'.URL.'booking/delete_passport" style="color:#000;"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['pass_id']);
	$arr['body'] = "คุณต้องการลบ <span class=\"fwb\">\"ไฟล์นี้\"</span> หรือไม่?";
	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">ใช่</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ไม่</span></a>';



echo json_encode($arr);