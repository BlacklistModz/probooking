<?php

$arr['title'] = 'ยืนยันการยกเลิก';
if( $this->item['permit']['cancel'] ){
	
	$arr['form'] = '<form class="js-submit-form" action="'.URL.'booking/booking_cancel" style="color:#000;"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['book_id']);
	$arr['body'] = "คุณต้องการยกเลิก <span class=\"fwb\">\"{$this->item['book_code']}\"</span> หรือไม่?";
	
	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">ใช่</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ไม่</span></a>';
}
else{

	$arr['body'] = "คุณไม่สามารถ <span class=\"fwb\">\"{$this->item['book_id']}\"</span> ได้?";	
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">ปิด</span></a>';
}


echo json_encode($arr);