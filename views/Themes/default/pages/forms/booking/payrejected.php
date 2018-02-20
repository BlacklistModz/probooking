<?php

    if($this->item['status'] ==9){
        $arr['title'] = '<span class="fwb fcb">การชำระถูกปฏิเสธ</span>';
    }
    if($this->item['status'] ==1){
        $arr['title'] = '<span class="fwb fcb">อนุมัติการชำระ</span>';
    }
    if($this->item['status']==0){
        $arr['title'] = '<span class="fwb fcb">แจ้งชำระเงิน</span>';
    }
	
	//$arr['form'] = '<form class="js-submit-form" action="'.URL.'booking/delete_passport" style="color:#000;"></form>';
	$arr['body'] = "<span class=\"fcr fwb\">".$this->item['remark_cancel']."</span>";
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ปิด</span></a>';



echo json_encode($arr);