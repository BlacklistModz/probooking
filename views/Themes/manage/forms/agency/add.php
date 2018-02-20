<?php 

$title = "เซลล์";
$arr["title"] = "เพิ่ม {$title}";

if( !empty($this->item) ){
	$arr['title'] = "แก้ไข {$title}";
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
if(!empty($this->item) && $this->item['id'] == $this->me['id']){
    $arr["title"] = "จัดการโปรไฟล์";
}
$form = new Form();
$form = $form ->create()
			  ->elem('div')
			  ->addClass('form-insert');

$form   ->field("agen_fname")
         ->label("ชื่อ*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ชื่อ')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['fname']) ? $this->item['fname'] : '' );

$form   ->field("agen_lname")
         ->label("นามสกุล*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('นามสกุล')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['lname']) ? $this->item['lname'] : '' );

$form   ->field("agen_nickname")
         ->label("ชื่อเล่น")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ชื่อเล่น')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['nickname']) ? $this->item['nickname'] : '' );

$form   ->field("agen_position")
         ->label("ตำแหน่ง*")   
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('ตำแหน่ง')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['position']) ? $this->item['position'] : '' );

$form   ->field("agen_email")
         ->label("อีเมล*")    
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('อีเมล')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['email']) ? $this->item['email'] : '' );

$form   ->field("agen_tel")
         ->label("มือถือ*")    
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('มือถือ')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['tel']) ? $this->item['tel'] : '' );

$form   ->field("agen_line_id")
         ->label("Line ID")  
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Line ID (ถ้ามี)')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['line_id']) ? $this->item['line_id'] : '' );

$form   ->field("agen_skype")
         ->label("Skype")   
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Skype (ถ้ามี)')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['skype']) ? $this->item['skype'] : '' );

$form    ->field("agency_company_id")
         ->label("บริษัท*")
         ->addClass('inputtext')
         ->autocomplete('off')
         ->select( $this->company['lists'], 'com_id', 'com_name' )
         ->value( !empty($this->item['company_id']) ? $this->item['company_id'] : '' );

$form    ->field("agen_user_name")
         ->label("ชื่อเข้าใช้งาน*")      
         ->addClass('inputtext')
         ->autocomplete('off')
         ->placeholder('Username')
         ->attr('style', 'color:black;')
         ->value( !empty($this->item['user_name']) ? $this->item['user_name'] : '' );

         if( empty($this->item) ) { 
         	$form   ->field("agen_password")
         			   ->label("รหัสผ่าน*")		
         			   ->addClass('inputtext')
         			   ->autocomplete('off')
         			   ->type('password')
         			   ->placeholder('Password')
         			   ->attr('style', 'color:black;')
         			   ->value('');
         	$form   ->hr('<h4 class="fwb">กรุณากรอกอย่างน้อย 6 ตัวอักษร</h4>');

         	$form   ->field("agen_password2")
         			   ->label("ยืนยันรหัสผ่าน*")        		
         			   ->addClass('inputtext')
         			   ->autocomplete('off')
         			   ->type('password')
         			   ->placeholder('ยืนยันรหัสผ่าน')
         			   ->attr('style', 'color:black;')
         			   ->value('');
         }

$ck_ad = '';
$ck_sl = '';
if( !empty($this->item['role']) ){
   if( $this->item['role'] == 'admin' ){
      $ck_ad = 'checked="1"';
   }
   elseif( $this->item['role'] == 'sales' ){
      $ck_sl = 'checked="1"';
   }
}
else{
   $ck_sl = 'checked="1"';
}

$role = '<div>
            <label class="radio"><input type="radio" '.$ck_ad.' name="agen_role" value="admin">ADMIN</label>
         </div>
         <div>
            <label class="radio"><input type="radio" '.$ck_sl.' name="agen_role" value="sales">SALE</label>
         </div>';
$form    ->field("agen_role")
         ->label("สิทธิ์")
         ->text( $role );

$status = '';
foreach ($this->status as $key => $value) {
   $ck = '';
   if( !empty($this->item) ){
      $ck = $this->item['status'] == $value['id'] ? 'checked="1"' : '';
   }
   else{
       if( $value['id'] == 1 ) $ck = 'checked="1"';
   }
   $status .= '<div>
                  <label class="radio"><input type="radio" '.$ck.' name="status" value="'.$value['id'].'"> '.$value['name'].'</label>
               </div>';
}

$form    ->field('status')
         ->label("สถานะ*")
         ->text( $status );
# set form
$arr['form'] = '<form class="js-submit-form" style="color:#000;" method="post" action="'.URL. 'agency/_save"></form>';

# set body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);




