<?php 
$formCom = new Form();
$formCom = $formCom ->create()
                ->addClass('form-insert')
				->elem('div');

$formCom 	->field("agen_com_name")
            ->label("บริษัท <span style='color:red'>*</span>")
            ->name('company[com_name]')
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('บริษัท')
            ->attr('style', 'color:black;')
            ->value('');

// $formCom 	->field("agen_com_address1")
//             ->label("ที่อยู่บริษัท <span style='color:red'>*</span>")
//             ->name('company[com_address1]')
//             ->addClass('inputtext')
//             ->autocomplete('off')
//             ->type('textarea')
//             ->attr('data-plugins', 'autosize')
//             ->placeholder('ที่อยู่บริษัท')
//             ->attr('style', 'color:black;')
//             ->value('');

$formCom    ->field("agen_com_address1")
            ->label("ที่อยู่บริษัท <span style='color:red'>*</span>")
            ->name("company[com_address1]")
            ->addClass("inputtext")
            ->autocomplete("off")
            ->placeholder("ที่อยู่1")
            ->attr("style", "color:black;")
            ->value("");

$formCom    ->field("agen_com_address2")
            ->label("ที่อยู่บริษัท(ต่อ) <span style='color:red'>*</span>")
            ->name("company[com_address2]")
            ->addClass("inputtext")
            ->autocomplete("off")
            ->placeholder("ที่อยู่2")
            ->attr("style", "color:black;")
            ->value("");

$formCom 	->field("agen_com_tel")
            ->label("เบอร์โทร <span style='color:red'>*</span>")
            ->name('company[com_tel]')
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('เบอร์โทร')
            ->attr('style', 'color:black;')
            ->value('');

$formCom 	->field("agen_com_fax")
            ->label("แฟ็กซ์")
            ->name('company[com_fax]')
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('แฟ็กซ์')
            ->attr('style', 'color:black;')
            ->value('');

$formCom 	->field("agen_com_ttt_on")
            ->label("เลข ททท <span style='color:red'>*</span>")
            ->name('company[com_ttt_on]')
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('เลข ททท.')
            ->attr('style', 'color:black;')
            ->value('');

$formCom    ->field("agen_com_email")
            ->label("อีเมลบริษัท <span style='color:red'>*</span>")
            ->name('company[com_email]')
            ->addClass('inputtext')
            ->autocomplete('off')
            ->placeholder('อีเมลบริษัท')
            ->attr('style', 'color:black;')
            ->value('');

$formAgen = new Form();
$formAgen = $formAgen ->create()
                ->addClass('form-insert')
                ->elem('div');
            
$formAgen   ->field("agen_fname")
         ->label("ชื่อ*")
         ->name('agency[fname]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ชื่อ')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_lname")
         ->label("นามสกุล*")
         ->name('agency[lname]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('นามสกุล')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_nickname")
         ->label("ชื่อเล่น")
         ->name('agency[nickname]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ชื่อเล่น')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_position")
         ->label("ตำแหน่ง*")
         ->name('agency[position]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ตำแหน่ง')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_email")
         ->label("อีเมล*")
         ->name('agency[email]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('อีเมล')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_tel")
         ->label("มือถือ*")
         ->name('agency[tel]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('มือถือ')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_line_id")
         ->label("Line ID")
         ->name('agency[line_id]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Line ID (ถ้ามี)')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_skype")
         ->label("Skype")
         ->name('agency[skype]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Skype (ถ้ามี)')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_user_name")
         ->label("ชื่อเข้าใช้งาน*")
         ->name('agency[user_name]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Username')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->field("agen_password")
         ->label("รหัสผ่าน*")
         ->name('agency[password]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->type('password')
         ->placeholder('Password')
         ->attr('style', 'color:black;')
         ->value('');

$formAgen   ->hr('<h4 class="fwb">กรุณากรอกอย่างน้อย 6 ตัวอักษร</h4>');

$formAgen   ->field("agen_password2")
         ->label("ยืนยันรหัสผ่าน*")
         ->name('agency[password2]')
         ->addClass('inputtext')
         ->autocomplete('off')
         ->type('password')
         ->placeholder('ยืนยันรหัสผ่าน')
         ->attr('style', 'color:black;')
         ->value('');
   
?>
<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<div class=" container clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-pencil-square-o"></i> สมัครเป็นตัวแทนจำหน่าย</h1>
				</header>

				<div class="clearfix">
					<form data-plugins="formRegister">
                  <input type="hidden" name="type" value="company">
                  <div class="beadcrumbs">
                   <ul class="breadcrumb js-current-step">
                     <li class="part active">
                        <div class="part back">
                           <span class="arrowBorder"></span>
                           <span class="arrow"></span>
                        </div>
                        <div class="part middle">
                           <div class="text"> 
                              <a class="js-change-step" data-target="#form1"> ข้อมูลบริษัท</a>
                           </div>
                        </div>
                        <div class="part point">
                           <span class="arrowBorder"></span>
                           <span class="arrow"></span>
                        </div>
                     </li>
                     <li class="part">
                        <div class="part back">
                           <span class="arrowBorder"></span>
                           <span class="arrow"></span>
                        </div>
                        <div class="part middle">
                           <div class="text">  
                              <a class="js-change-step" data-target="#form2"> ข้อมูลเซลล์</a>
                           </div>
                        </div>
                        <div class="part point">
                           <span class="arrowBorder"></span>
                           <span class="arrow"></span>
                        </div>
                     </li>
                     <li class="part">
                        <div class="part back">
                           <span class="arrowBorder"></span>
                           <span class="arrow"></span>
                        </div>
                        <div class="part middle">
                           <div class="text">  
                              <a class="js-change-step" data-target="#form3">ยืนยันการสมัคร</a>
                           </div>
                        </div>
                        <div class="part point">
                           <span class="arrowBorder"></span>
                           <span class="arrow"></span>
                        </div>
                     </li>
                  </ul>
                  </div>
                        <div id="form1" class="js-hidden-form">
                            <?=$formCom->html();?>
                        </div>
                        <div class="js-hidden-form" id="form2">
                            <?=$formAgen->html();?>
                        </div>
                        <div class="js-hidden-form preview" id="form3">
                            
                        </div>
                        <div class="mtl clearfix">
                            <a class="btn btn-red hidden_elem" id="btn-back"><i class="icon-arrow-left"></i> กลับ</a>
                            <button type="submit" class="btn btn-green rfloat" id="btn-next">ต่อไป <i class="icon-arrow-right"></i></button>
                            <button type="submit" class="btn btn-blue btn-submit rfloat">บันทึก</button>
                        </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>