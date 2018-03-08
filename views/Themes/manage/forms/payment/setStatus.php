<?php

$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);
$arr['hiddenInput'][] = array('name'=>'status', 'value'=>$this->status);

if( $this->status == 1 ){
	$arr['title'] = 'อนุมัติการจ่ายเงิน';
	$arr['body'] = "คุณต้องการอุมัติการจ่ายเงิน <span class=\"fwb\">\"{$this->booking['book_code']}\"</span> หรือไม่?";

	$cls = "btn-green";
}
elseif( $this->status == 9 ){
	$arr['title'] = 'ไม่อนุมัติการจ่ายเงิน';

	$form = new Form();
	$form = $form 	->create()
					->elem('div')
					->addClass('form-insert');

	$form 	->field("remark_cancel")
			->label("ไม่ผ่านการอนุมัติ*")
			->type('textarea')
			->addClass('inputtext')
			->attr('data-plugins', 'autosize')
			->value('');

	$arr['body'] = $form->html();

	$cls = "btn-red";
}

$arr['form'] = '<form class="js-submit-form" action="'.URL. 'payment/setStatus/"></form>';

$arr['button'] = '<button type="submit" class="btn '.$cls.' btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);