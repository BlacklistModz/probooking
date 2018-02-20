<?php 

$title = "แจ้งโอนเงิน";
$arr['title'] = "เพิ่ม {$title}";
if( !empty($this->item) ){
	$arr["title"] = "แก้ไข {$title}";
	$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);
}
$arr['hiddenInput'][] = array('name'=>'book_id', 'value'=>$this->book['book_id']);

$form = new Form();
$form = $form ->create()
			  ->elem('div')
			  ->addClass('form-insert form-horizontal');

$form 	->field("bankbook_id")
		->label("ธนาคาร*")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->bank, 'bankbook_id', 'bank_name' )
		->value( !empty($this->item["bankbook_id"]) ? $this->item["bankbook_id"] : '' );

$form 	->field("branch")
		->label("สาขา")
		->addClass('inputtext disabled')
		->attr('disabled', 1)
		->autocomplete('off')
		->value('');

$form 	->field("name")
		->label("ชื่อบัญชี")
		->addClass('inputtext disabled')
		->attr('disabled', 1)
		->autocomplete('off')
		->value('');

$form 	->field("number")
		->label("เลขที่บัญชี")
		->addClass('inputtext disabled')
		->attr('disabled', 1)
		->autocomplete('off')
		->value('');

$form 	->field("pay_date")
		->label("วันที่ชำระเงิน*")
		->addClass('inputtext')
		->attr('data-plugins', 'datepicker')
		->type('date')
		->autocomplete('off')
		->value( !empty($this->item['pay_date']) ? $this->item['pay_date'] : '' );

$form 	->field("pay_time")
		->label("เวลา*")
		->addClass("inputtext")
		->autocomplete("off")
		->value( !empty($this->item["pay_time"]) ? $this->item["pay_time"] : "" );

$form 	->field("pay_received")
		->label("จำนวนเงิน*")
		->addClass("inputtext")
		->type('number')
		->autocomplete("off")
		->value( !empty($this->item["pay_received"]) ? $this->item["pay_received"] : 0 );

$form 	->field("pay_url_file")
		->label("ไฟล์อ้างอิง")
		->addClass("inputtext")
		->type('file');

$form 	->field("remark")
		->label("หมายเหตุ*")
		->addClass("inputtext")
		->autocomplete("off")
		->attr("rows", 5)
		->attr("style", "resize:none;")
		// ->attr("data-plugins", "autosize")
		->type("textarea")
		->value( !empty($this->item["remark"]) ? $this->item["remark"] : "" );

$s_arr = array(20,25,30,35);
if( $this->book["book_due_date_deposit"] == "0000-00-00 00:00:00" ){
	$s_arr = array(30,35);
}
$status = '';
$i = 0;
foreach ($this->status as $key => $value) {
	if( in_array($value["id"], $s_arr) ){
		$i++;
		$ck = '';
		if( !empty($this->item) ){
			if( $this->item['book_status'] == $value["id"] ) $ck = ' checked="1"';
		}
		else{
			if( $i == 1 ) $ck = ' checked="1"';
		}
		$status .= '<div><label class="radio"><input'.$ck.' type="radio" name="book_status" value="'.$value["id"].'">'.$value["name"].'</label></div>';
	}
}

$form 	->field("book_status")
		->label("เงื่อนไขการชำระเงิน")
		->text( $status );

$options = $this->fn->stringify( array(
	'bank_id' => !empty($this->item['bankbook_id']) ? $this->item['bankbook_id'] : '',
) );

# set form
$arr['form'] = '<form class="js-submit-form" style="color:#000;" method="post" action="'.URL.'payment/save" data-plugins="formPayment" data-options="'.$options.'" enctype="multipart/form-data"></form>';

# set body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);

