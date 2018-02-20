<?php 
$arr['title'] = "อัพโหลดไฟล์ หนังสือเดินทาง";
$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['book_id']);
$form = new Form();
$form = $form   ->create()
	        ->elem('div')
	        ->addClass('form-insert form-horizontal');

$form 	->field("book_passport_file")
        ->label("ไฟล์อ้างอิง")
        ->name('book_passport_file[]')
        ->addClass("inputtext")
        ->attr('accept', 'application/pdf, image/*')
        ->attr('multiple',1)
        ->type('file');

// $form 	->field("book_file")
// 		->label("File")
// 		->text( empty($this->item['passport_file']) ? '<span class="fwb" style="color:red;">ไม่มีการอัพโหลดไฟล์</span>': '<a href="'.$this->item['book_guarantee_file'].'" target="_blank" class="btn btn-blue"><i class="icon-download"></i> Download</a>' );
		

# set form
$arr['form'] = '<form class="js-submit-form" style="color:#000;" method="post" action="'.URL.'booking/passport"  enctype="multipart/form-data"></form>';

# set body
$arr['body'] = $form->html();
 

# fotter: button

$dissabledElem = '';
        if($this->me['id']  != $this->item['agen_id'] && $this->me['role']!= 'admin'){
                $dissabledElem = 'disabled';
        }
$arr['button'] = '<button type="submit" '.$dissabledElem.' class="btn btn-primary btn-submit "><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn " role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);

