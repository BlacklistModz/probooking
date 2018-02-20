<?php 
$arr['title'] = "อัพโหลดไฟล์ การันตี";
$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['book_id']);
$form = new Form();
$form = $form ->create()
			  ->elem('div')
			  ->addClass('form-insert form-horizontal');

$form 	->field("book_guarantee_file")
		->label("ไฟล์อ้างอิง")
        ->addClass("inputtext")
        ->attr('accept', 'application/pdf, image/*')
        ->type('file');
$path = '';
if( !empty($this->item['book_guarantee_file']) ){
	$file = substr(strrchr($this->item['book_guarantee_file'],"/"),1);
	if( file_exists(PATH_GUARANTEE.$file) ){
		$path = 'http://admin.probookingcenter.com/admin/upload/guarantee/'.$file;
	}
	else{
		$path = '';
	}
}
$form 	->field("book_file")
		->label("File")
		->text( empty($path) ? '<span class="fwb" style="color:red;">ไม่มีการอัพโหลดไฟล์</span>': '<a href="'.$path.'" target="_blank" class="btn btn-blue"><i class="icon-download"></i> Download</a>' );
		

# set form
$arr['form'] = '<form class="js-submit-form" style="color:#000;" method="post" action="'.URL.'booking/guarantee" enctype="multipart/form-data"></form>';

# set body
$arr['body'] = $form->html();
 
# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);

