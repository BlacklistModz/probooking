<?php 
$form = new Form();
$form = $form->create()
		->addClass('form-insert')
		->elem('div');
//
//$form 	->field("mem_first_name")
//		->label("ชื่อ*")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('ชื่อ')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_last_name")
//		->label("นามสกุล*")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('นามสกุล')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_nickname")
//		->label("ชื่อเล่น")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('ชื่อเล่น')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_position")
//		->label("ตำแหน่ง*")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('ตำแหน่ง')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_email")
//		->label("อีเมล*")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('อีเมล')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_mobile_phone")
//		->label("มือถือ*")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('มือถือ')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_social_line")
//		->label("Line ID")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('Line ID (ถ้ามี)')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_social_skype")
//		->label("Skype")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('Skype (ถ้ามี)')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_username")
//		->label("ชื่อเข้าใช้งาน*")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->placeholder('Username')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->field("mem_password")
//		->label("รหัสผ่าน*")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->type('password')
//		->placeholder('Password')
//		->attr('style', 'color:black;')
//		->value('');
//
//$form 	->hr('<h4 class="fwb">กรุณากรอกอย่างน้อย 6 ตัวอักษร</h4>');
//
//$form 	->field("mem_password2")
//		->label("ยืนยันรหัสผ่าน*")
//		->addClass('inputtext')
//		->autocomplete('off')
//		->type('password')
//		->placeholder('ยืนยันรหัสผ่าน')
//		->attr('style', 'color:black;')
//		->value('');


$form 	->field("agen_fname")
         ->label("ชื่อ*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ชื่อ')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_lname")
         ->label("นามสกุล*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('นามสกุล')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_nickname")
         ->label("ชื่อเล่น")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ชื่อเล่น')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_position")
         ->label("ตำแหน่ง*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ตำแหน่ง')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_email")
         ->label("อีเมล*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('อีเมล')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_tel")
         ->label("มือถือ*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('มือถือ')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_line_id")
         ->label("Line ID")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Line ID (ถ้ามี)')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_skype")
         ->label("Skype")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Skype (ถ้ามี)')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_user_name")
         ->label("ชื่อเข้าใช้งาน*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Username')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->field("agen_password")
         ->label("รหัสผ่าน*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->type('password')
         ->placeholder('Password')
         ->attr('style', 'color:black;')
         ->value('');

$form 	->hr('<h4 class="fwb">กรุณากรอกอย่างน้อย 6 ตัวอักษร</h4>');

$form 	->field("agen_password2")
         ->label("ยืนยันรหัสผ่าน*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->type('password')
         ->placeholder('ยืนยันรหัสผ่าน')
         ->attr('style', 'color:black;')
         ->value('');

$formCom = new Form();
$formCom = $formCom ->create()
				->addClass('form-insert')
				->elem('div');

$formCom 	->field("agen_note_com_name")
            ->label("บริษัท*")
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('บริษัท')
            ->attr('style', 'color:black;')
            ->value('');

$formCom 	->field("agen_note_com_address1")
            ->label("ที่อยู่บริษัท")
            ->addClass('inputtext')
            ->autocomplete('off')
            ->type('textarea')
            ->attr('data-plugins', 'autosize')
            ->placeholder('ที่อยู่บริษัท')
            ->attr('style', 'color:black;')
            ->value('');

$formCom 	->field("agen_note_com_tel")
            ->label("เบอร์โทร")
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('เบอร์โทร')
            ->attr('style', 'color:black;')
            ->value('');

$formCom 	->field("agen_note_com_fax")
            ->label("แฟ็กซ์")
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('แฟ็กซ์')
            ->attr('style', 'color:black;')
            ->value('');

$formCom 	->field("agen_note_com_ttt_on")
            ->label("เลข ททท")
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('เลข ททท.')
            ->attr('style', 'color:black;')
            ->value('');


//$formCom 	->field("mem_co_name")
//			->label("บริษัท*")
//			->addClass('inputtext')
//			->autocomplete('off')
//			->placeholder('บริษัท')
//			->attr('style', 'color:black;')
//			->value('');
//
//$formCom 	->field("mem_co_address")
//			->label("ที่อยู่บริษัท")
//			->addClass('inputtext')
//			->autocomplete('off')
//			->type('textarea')
//			->attr('data-plugins', 'autosize')
//			->placeholder('ที่อยู่บริษัท')
//			->attr('style', 'color:black;')
//			->value('');
//
//$formCom 	->field("mem_co_phone")
//			->label("เบอร์โทร")
//			->addClass('inputtext')
//			->autocomplete('off')
//			->placeholder('เบอร์โทร')
//			->attr('style', 'color:black;')
//			->value('');
//
//$formCom 	->field("mem_co_fax")
//			->label("แฟ็กซ์")
//			->addClass('inputtext')
//			->autocomplete('off')
//			->placeholder('แฟ็กซ์')
//			->attr('style', 'color:black;')
//			->value('');
//
//$formCom 	->field("mem_co_license")
//			->label("เลข ททท")
//			->addClass('inputtext')
//			->autocomplete('off')
//			->placeholder('เลข ททท.')
//			->attr('style', 'color:black;')
//			->value('');
?>
<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<div class=" container clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-pencil-square-o"></i> สมัครเป็นตัวแทนจำหน่าย</h1>
				</header>

				<div class="clearfix">
					<form class="js-submit-form" action="<?=URL?>agency/save" method="POST">
						<div class="span6 mbl" style="max-width: 520px;">
							<h3 class="fwb">ข้อมูลผู้สมัคร</h3>
							<?=$form->html()?>
						</div>
						<div class="span6" style="max-width: 520px;">
							<h3 class="fwb">ข้อมูลบริษัท</h3>
							<?=$formCom->html()?>
						</div>
						<div class="span11 mtm tac">
							<button id="submit" type="submit" class="btn btn-blue btn-submit"> สมัครเป็นตัวแทนจำหน่าย</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
    // $(function(){

    //     console.log(555);
    // });
</script>