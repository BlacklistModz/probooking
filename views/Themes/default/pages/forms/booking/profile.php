<?php 

$fullname = $this->book['agen_fname'];
$this->book['agen_lname'] = str_replace("-", "", $this->book['agen_lname']);
if( !empty($this->book['agen_lname']) ){
	$fullname .= ' '.$this->book['agen_lname'];
}


$html = '<div class="clearfix">
 
        		<div id="" class="" style="">
        			<div class="span4" style="margin-left: 25px;">
                        <div class="uiBoxWhite pam">
                        <table><tbody>
                            <tr><td class="clearfix fwb pbm"><i class="icon-address-book-o mrs"></i>ข้อมูลผู้จอง</td></tr>
                            <tr><td>'.$fullname.'</td></tr>
                            <tr><td>'.$this->book['agen_email'].'</td></tr>
                            <tr><td>'.$this->book['agen_tel'].'</td></tr>
                            <tr><td>'.$this->me['company_name'].'</td></tr>
                        </tbody></table>
                        </div>
                    </div>

                    <div class="span4" style="margin-left: 25px;">
                        <div class="uiBoxWhite pam">
                        <table><tbody>
                            <tr><td class="clearfix fwb pbm"><i class="icon-plane mrs"></i>ข้อมูลการเดินทาง</td></tr>
                            <tr><td>Code: <span class="text-blue">'.$this->item['code'].'</span></td></tr>
                            <tr><td>'.$this->item['name'].'</td></tr>
                            <tr><td>'.$this->fn->q('time')->str_event_date($this->item['per_date_start'], $this->item['per_date_end']).'</td></tr>
                            <tr><td><span class="text-red">'.$this->item['air_name'].'</span> เส้นทาง '.$this->item['ser_route'].'</td></tr>
                        </tbody></table>
                        </div>
                    </div>

                    <div class="span4" style="margin-left: 25px;">
                        <div class="uiBoxWhite pam">
                        <table><tbody>
                            <tr>
                                <td colspan="2" class="clearfix fwb pbm"><i class="icon-money mrs"></i>INVOICE</td>
                            </tr>
                            <tr>
                                <td>Code:</td>
                                <td>'.$this->book['invoice_code'].'</td>
                            </tr>

                            <tr>
                                <td>Deposit Date:</td>
                                <td  class="fwb"> '.( $this->book['book_due_date_deposit']=='0000-00-00 00:00:00' ? '-': date('Y-m-d H:i:s', strtotime($this->book['book_due_date_deposit'])) ).'</td>
                            </tr>

                            <tr>
                                <td>Deposit Price:</td>
                                <td>'.( $this->book['book_master_deposit']==0 ? '-': number_format($this->book['book_master_deposit']) ).'</td>
                            </tr>
                            <tr>
                                <td>Full Payment Date:</td>
                                <td class="fwb">'.( date('Y-m-d H:i:s', strtotime($this->book['book_due_date_full_payment'])) ).'</td>
                            </tr>
                            <tr>
                                <td>Full Payment Price:</td>
                                <td>'.( number_format($this->book['book_master_full_payment']) ).'.-</td>
                            </tr>

                        </tbody></table>
                        </div>
                    </div>

                    <div class="span4" style="margin-left: 25px;">
                        <div class="uiBoxWhite pam">
                        <table><tbody>
                            <tr>
                                <td colspan="2" class="clearfix fwb pbm"><i class="icon-info mrs"></i>ข้อมูลลูกค้า</td>
                            </tr>
                            <tr>
                                <td width="50%">ชื่อลูกค้า</div>
                                <td width="50%">'.(!empty($this->book['book_cus_name']) ? $this->book['book_cus_name'] : '<span class="tac">-</span>').'</td>
                            </tr>
                            <tr>
                                <td width="50%">เบอร์โทรลูกค้า</div>
                                <td width="50%">'.(!empty($this->book['book_cus_tel']) ? $this->book['book_cus_tel'] : '<span class="tac">-</span>').'</td>
                            </tr>
                        </tbody></table>
                        </div>
                    </div>

                    <div class="span4" style="margin-left: 25px;">
                        <div class="uiBoxWhite pam">
                        <table><tbody>
                            <tr>
                                <td colspan="2" class="clearfix fwb pbm"><i class="icon-info mrs"></i>หมายเหตุ</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="clearfix">'.(!empty($this->book['book_comment']) ? $this->book['book_comment'] : "-").'</td>
                            </tr>
                        </tbody></table>
                        </div>
                    </div>
        		</div>

        </div>';

$arr['form'] = '<div style="color:#000;"></div>';
$arr['title'] = $this->book['book_code'];
$arr['body'] = $html;
// $arr['width'] = 1270;

$arr['is_close_bg'] = true;

echo json_encode($arr);