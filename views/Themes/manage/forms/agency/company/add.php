<?php 

$title = "บริษัทเอเจนซี่";
$arr["title"] = "เพิ่ม {$title}";

if( !empty($this->item) ){
	$arr['title'] = "แก้ไข {$title}";
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['com_id']);
}

$form = new Form();
$form = $form->create()
			 ->elem('div')
			 ->addClass('form-insert form-horizontal');

$form 	->field('agen_com_name')
		->label("ชื่อบริษัท*")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['com_name']) ? $this->item['com_name'] : '' );

$form 	->field('agen_com_address1')
		->label("ที่อยู่1")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['com_address1']) ? $this->item['com_address1'] : '' );

$form 	->field('agen_com_address2')
		->label("ที่อยู่2")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['com_address2']) ? $this->item['com_address2'] : '' );

$form 	->field('agen_com_geo')
		->label("ภาค/โซน*")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->geo, 'id', 'name', '--- กรุณาเลือกภาค/โซน ---' )
		->value( !empty($this->item["com_geo"]) ? $this->item["com_geo"] : '' );

$form 	->field('agen_com_province')
		->label("จังหวัด*")
		->addClass('inputtext')
		->autocomplete('off')
		->select( array() );

$form 	->field('agen_com_amphur')
		->label("เขต/อำเภอ")
		->addClass('inputtext')
		->autocomplete("off")
		->select( array() );

$form 	->field('agen_com_tel')
		->label("เบอร์โทรศัพท์")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['com_tel']) ? $this->item['com_tel'] : '' );

$form 	->field('agen_com_fax')
		->label("FAX")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['com_fax']) ? $this->item['com_fax'] : '' );

$form 	->field('agen_com_email')
		->label("Email")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['com_email']) ? $this->item['com_email'] : '' );

$form 	->field('agen_com_ttt_on')
		->label("เลข ททท")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['com_ttt_on']) ? $this->item['com_ttt_on'] : '' );

$form 	->field('file_ttt')
		->label('รูป ททท')
		->addClass('inputtext')
		->autocomplete('off')
		->type('file')
		->value( '' );

$form 	->field('file_logo')
		->label('รูป LOGO บริษัท')
		->addClass('inputtext')
		->autocomplete('off')
		->type('file')
		->value( '' );

$form 	->field('remark')
		->label('หมายเหตุ')
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['remark']) ? $this->item['remark'] : '' );

$form 	->field("agen_com_user_id")
		->label("เซลล์ผู้ดูแล")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->sales )
		->value( !empty($this->item['com_user_id']) ? $this->item['com_user_id'] : '' );

$status = '';
foreach ($this->status as $key => $value) {
	$ck = '';
	if( !empty($this->item) ){
		if( $this->item['status'] == $value['id'] ) $ck = 'checked="1"';
	}
	else{
		if( $value["id"] == 0 ) $ck = 'checked="1"';
	}
	$status .= '<div><label class="radio"><input type="radio" '.$ck.' value="'.$value["id"].'" name="status">'.$value["name"].'</label></div>';
}
$form 	->field("status")
		->label("สถานะ")
		->text( $status );

$ck_gua = '';
if( !empty($this->item['com_guarantee']) ){
	$ck_gua = 'checked="1"';
}
$guarantee = '<div>
				<label class="checkbox"><input type="checkbox" '.$ck_gua.' value="1" name="agen_com_guarantee">การันตี</label>
			 </div>';

$form 	->field("agen_com_guarantee")
		->label('<i class="icon-thumbs-o-up"></i>')
		->text( $guarantee );

#body
$arr['body'] = $form->html();

$options = $this->fn->stringify( array(
	'province' => !empty($this->item["com_province"]) ? $this->item["com_province"] : '',
	'amphur' => !empty($this->item["com_amphur"]) ? $this->item["com_amphur"] : ''
) );

#form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL.'agency_company/save" enctype="multipart/form-data" data-plugins="formAgencyCompany" data-options="'.$options.'"></form>';

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);