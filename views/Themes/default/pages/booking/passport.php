

<style>
._table tr{
    height:30px;
    border-bottom:1px solid #c6c5c5 ;
  
}
._table tbody tr:hover{
    background:#F1F8FE;
}
._table tbody tr td a:hover{
    text-decoration:none;
}
._table tbody tr td:last-child i:hover{
    color:red;
}
._table tbody tr td:last-child i{
    color:#000;
}

._table tbody tr td:nth-child(3) i:hover{
    color:orange;
}
</style>

<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<div class="container clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-address-book "></i>หนังสือเดินทาง</h1>
					<h3 class="tac">CODE : <?=$this->item['book_code']?>&nbsp; Customer name :<?=$this->item['book_cus_name'] ?> &nbsp; Tel no. <?=$this->item['book_cus_tel']?></h3>  
                    
				</header>
				<div class="clearfix">
                <div class="clearfix" style="margin-right:70px;"><?= $this->item['booking_passport']==0 ? ' <a href="/booking/passport_insert/'.$this->item['book_id'].'>" data-plugins="dialog" class="btn btn-blue rfloat"> <i class="icon-plus"></i> เพิ่ม</a> ': ''?></div>
                    <table class="_table" style="width: 88%;margin: 0 auto; border: 1px solid #c6c5c5;padding: 10px; box-shadow: 5px 10px 10px 0px;  margin-top: 10px;z-index: 222;">
                   <thead>
							<tr style="color:#fff; background-color: #003;">
				
                                <th class="tac" width="5%">ลำดับ</th>
								<th class="tac" width="85%">รายการ</th>
								<th class="tac" width="10%"><?=$this->item['booking_passport']==0? 'ลบ': 'ตรวจสอบไฟล์แล้ว'?></th>			
							</tr>
						</thead>
                  
                   <tbody>
                   <?php foreach($this->item['passport'] as $key => $value){ ?>
                        <tr>
                            <td class="tac"><?=$key+1?></td>
                            <td class="tac"><a target="_blank" class="fcb" href="http://admin.probookingcenter.com/admin/upload/passport/<?=$value['pass_file_url']?>"><i class="icon-file-pdf-o"></i> กดเพื่อดูไฟล์</td>
                            <td class="tac"><?=$this->item['booking_passport'] ==0 ? '<a href="'.URL.'booking/delete_passport/'.$value['pass_id'].'" data-plugins="dialog"><i class="icon-remove"></i></a>' : '<p><i class="icon-check-square"></i></p>'?></td>
                        </tr>
                    <?php }  ?>
                    </tbody>
                    </table>
                    <?=$this->item['booking_passport'] ==0 ? '<a style="margin:1rem; margin-right:65px;" href="/booking/passport_update/'.$this->item['book_id'].'?>" data-plugins="dialog" class="btn btn-blue rfloat"> <i class="icon-send-o"></i> บันทึก</a>' :'' ?>
                </div>
            </div>
         </div>
     </div>
   </section>   




