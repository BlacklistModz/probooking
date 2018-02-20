<?php 

#SET DATE START & END
$startDate = '';
if( !empty($this->item['start_date']) ){
	$startDate = $this->item['start_date'];
}
elseif( isset($_REQUEST['date']) ){
	$startDate = $_REQUEST['date'];
}
$endDate = '';
if( !empty($this->item['end_date']) ){
	if( $this->item['end_date'] != '0000-00-00' ){
		$endDate = $this->item['end_date'];
	}
}

$title = "โปรโมชั่น";
if( !empty($this->item) ){
	$arr['title'] = "แก้ไข {$title}";
	$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);
}
else{
	$arr['title'] = "เพิ่ม {$title}";
}

$form = new Form();
$form = $form->create()
			 ->elem('div')
			 ->addClass('form-insert');

$form 	->field("pro_name")
		->label("ชื่อโปรโมชั่น")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['name']) ? $this->item['name'] : '' );

$form 	->field("pro_discount")
		->label("ส่วนลด (บาท/หัว)")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item["discount"]) ? round($this->item["discount"]) : '' );

$options = $this->fn->stringify( array(
			'startDate' => $startDate,
			'endDate' => $endDate,

			'allday' => 'disabled',
			'endtime' => 'disabled',
			'time' => 'disabled',

			'str' => array(
				'เริ่ม',
				'สิ้นสุด',
				// $this->lang->translate('All day'),
				// $this->lang->translate('End Time'),
			),

			'lang' => $this->lang->getCode(),
			'name' => array('pro_start_date', 'pro_end_date'),
		) );
$form 	->field("pro_date")
		->label('ระยะเวลา')
		->text( '<div data-plugins="setdate" data-options="'.$options.'"></div>' );

$form 	->field("pro_status")
		->label("สถานะ")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->status )
		->value( !empty($this->item['status']) ? $this->item['status'] : '' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'promotions/save"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn btn-red" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);