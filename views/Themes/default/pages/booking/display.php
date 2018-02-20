<?php

// ที่นั้งที่ยังว่างอยู่
$availableSeat = $this->item['per_qty_seats']-$this->seatBooked['booking'];

$numberArray = array();
for ($i=1; $i <= $availableSeat; $i++) { 
    $numberArray[] = array('id'=>$i, 'name' => $i);
}

#contactFrom
$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');
$form->field("Period")->label('Period:')->addClass('inputtext disabled')->autocomplete('off')->attr('disabled', 1)->value( $this->fn->q('time')->str_event_date($this->item['per_date_start'], $this->item['per_date_end']) );
$form->field("sale_id")->label('Sales Contact:')->autocomplete('off')->addClass('inputtext')->select( $this->salesList )->value( $this->book['user_id'] );
$form->field("seat")->label('Available Seat:')->autocomplete('off')->attr('disabled', 1)->addClass('inputtext disabled')->value( number_format($availableSeat) ) ;
$form->field("sgent")->label('Agent Company:')->autocomplete('off')->attr('disabled', 1)->addClass('inputtext disabled')->value( $this->me['company_name'] );
$form->field("sale")->label('Agent Name:')->autocomplete('off')->attr('disabled', 1)->addClass('inputtext disabled')->value( $this->me['fullname'] );
$form->field("comment")->label('Remark:')->autocomplete('off')->addClass('inputtext')->type('textarea');

$contactFrom = $form->html();

#travelerFrom
$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form   ->field("seat_adult")->name('seat[adult]')->label('Adult:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray )->value( !empty($this->book['items'][1]['book_list_qty']) ? $this->book['items'][1]['book_list_qty']: '' );
$form   ->field("seat_child")->name('seat[child]')->label('Child:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray );
$form   ->field("seat_child_bed")->name('seat[child_bed]')->label('Child No bed:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray );
$form   ->field("seat_infant")->name('seat[infant]')->label('Infant:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray );
$form   ->field("seat_joinland")->name('seat[joinland]')->label('Joinland:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray );

$travelerFrom = $form->html();



#roomtypeFrom
$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form   ->field("room_twin")->name('room[twin]')->label('Twin:')->addClass('inputtext')->select( $numberArray );
$form   ->field("room_double")->name('room[double]')->label('Double:')->addClass('inputtext')->select( $numberArray );
$form   ->field("room_triple")->name('room[triple]')->label('Triple:')->addClass('inputtext')->select( $numberArray );
$form   ->field("room_single")->name('room[single]')->label('Single:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray );
$roomtypeFrom = $form->html();


#priceFrom
$tablePrice = array();
$tablePrice[] = array('id'=>'seat_adult','name'=>'Audit','price'=>$this->item['per_price_1']);
$tablePrice[] = array('id'=>'seat_child','name'=>'Child','price'=>$this->item['per_price_2']);
$tablePrice[] = array('id'=>'seat_child_bed','name'=>'Child No bed','price'=>$this->item['per_price_3']);
$tablePrice[] = array('id'=>'seat_infant','name'=>'Infant','price'=>$this->item['per_price_4']);
$tablePrice[] = array('id'=>'seat_joinland','name'=>'Joinland','price'=> $this->item['per_price_5'] );
$tablePrice[] = array('id'=>'room_single','name'=>'Sing Charge','price'=> $this->item['single_charge'] );

if( $this->item['per_com_company_agency']>0 ){
	$tablePrice[] = array('id'=>'agency_com','name'=>'Discount Agent('.$this->me['company_name'].')','price'=>$this->item['per_com_company_agency'], 'qty'=>1, 'type'=> 'discount');
}

if( $this->item['per_com_agency']>0 ){
	$tablePrice[] = array('id'=>'sales_com','name'=>'Discount Sale Agent('.$this->me['fullname'].')','price'=>$this->item['per_com_agency'], 'qty'=>1, 'type'=> 'discount');
}


?><section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
    <div class="container clearfix">
        <div class="primary-content post">

            <!-- form -->
            <form class="card js-submit-form" method="POST" action="<?=URL?>booking/save/">
                <input type="hidden" name="period" value="<?=$this->item['per_id']?>" autocomplete="off">

                <header class="mbl clearfix">
                    
                </header>

                <div class="">
                    
                </div>

                <div class="clearfix">
                    <div class=""></div>
                </div>

                
                <div id="info" class="" style="">

                    <?php 
                    $fullname = $this->book['agen_fname'];
                    $this->book['agen_lname'] = str_replace("-", "", $this->book['agen_lname']);
                    if( !empty($this->book['agen_lname']) ){
                        $fullname .= ' '.$this->book['agen_lname'];
                    }
                    ?>
                    
                    <div class="tac mvm">
                        <h1>ขอบคุณ การจองทัวร์ของคุณเสร็จเรียบร้อย</h1>
                        <h1>รหัสการจอง <?=$this->book['book_code']?></h1>
                    </div>
                    <div class="row-fluid clearfix">
                    <!--  -->
                    <div class="span4" >
                        <div class="uiBoxWhite pam">
                        <table><tbody>
                            <tr><td class="clearfix fwb pbm"><i class="icon-address-book-o mrs"></i>ข้อมูลผู้จอง</td></tr>
                            <tr><td>ชื่อเซลล์ : <?=$fullname?></td></tr>
                            <tr><td>อีเมลล์ : <?=$this->book['agen_email']?></td></tr>
                            <tr><td>เบอร์โทร : <?=$this->book['agen_tel']?></td></tr>
                            <tr><td>บริษัท : <?=$this->me['company_name']?></td></tr>
                        </tbody></table>
                        </div>
                    </div>

                    <div class="span4">
                        <div class="uiBoxWhite pam">
                        <table><tbody>
                            <tr><td class="clearfix fwb pbm"><i class="icon-plane mrs"></i>ข้อมูลการเดินทาง</td></tr>
                            <tr><td>Code : <span class="text-blue"><?=$this->item['code']?></span></td></tr>
                            <tr><td>ชื่อโปรแกรม : <?=$this->item['name']?></td></tr>
                            <tr><td>วันเดือนทาง : <?=$this->fn->q('time')->str_event_date($this->item['per_date_start'], $this->item['per_date_end'])?></td></tr>
                            <tr><td>สายการบิน : <span class="text-red"><?=$this->item['air_name']?></span> เส้นทาง <?=$this->item['ser_route'];?></td></tr>
                        </tbody></table>
                        </div>
                    </div>
                

                    <div class="span4" >
                        <div class="uiBoxWhite pam">
                        <table><tbody>
                            <tr>
                                <td colspan="2" class="clearfix fwb pbm"><i class="icon-money mrs"></i>QUOTATION</td>
                            </tr>
                            <tr>
                                <td>Quotation No.:</td>
                                <td><?=$this->book['invoice_code']?></td>
                            </tr>

                            <tr>
                                <td>Deposit Date:</td>
                                <td class="fwb"> <?= $this->book['book_due_date_deposit']=='0000-00-00 00:00:00' ? '-': date('Y-m-d | H:i:s', strtotime($this->book['book_due_date_deposit'])) ?></td>
                            </tr>

                            <tr>
                                <td>Deposit Price:</td>
                                <td><?= $this->book['book_master_deposit']==0 ? '-': number_format($this->book['book_master_deposit'])?></td>
                            </tr>
                            <tr>
                                <td>Full Payment Date:</td>
                                <td style="color: #9e0000;" class="fwb"><?= date('Y-m-d | H:i:s', strtotime($this->book['book_due_date_full_payment']))?></td>
                            </tr>
                            <tr>
                                <td>Full Payment Price:</td>
                                <td style="color: #0a0b92;"><?= number_format($this->book['book_master_full_payment'])?>.-</td>
                            </tr>

                        </tbody></table>
                        </div>
                    </div>    

                    </div>               
                </div>
                <!-- end: info -->

                <div id="booking" class="mtl hidden_elem">
                    
                    <div class="row-fluid clearfix ">
                        <div class="span8">
                            
                            <div class="row-fluid clearfix ">

                                <div class="span6">
                                    <h3>CONTACT</h3>
                                    <?=$contactFrom?>
                                </div>

                                <div class="span3">
                                    <h3>TRAVELER INFO</h3>
                                    <?=$travelerFrom?>
                                </div>

                                <div class="span3">
                                    <h3>ROOM TYPE</h3>
                                    <?=$roomtypeFrom?>
                                </div>

                            </div>

                            
                        </div>
                        
                        <div class="span4">
                            <h3>PRICE</h3>
                            
                            <table class="tablePrice"><tbody>
                                <?php 

                                $discountFrist = false;
                                foreach ($tablePrice as $key => $value) {

                                    if( empty($value['qty']) ){
                                        $value['qty'] = 0;
                                    }

                                    $type = !empty($value['type']) ?$value['type']: 'income';

                                    $cls = !empty($value['cls']) ?$value['cls']: '';
                                    if( $type=='discount' ){

                                    	if( !$discountFrist ){
                                    		$cls.=!empty($cls) ? " ":"";
                                        	$cls.='first';
                                        	$discountFrist = true;
                                    	}


                                        $cls.=!empty($cls) ? " ":"";
                                        $cls.='discount';
                                    }
                                    else{
                                        $cls.=!empty($cls) ? " ":"";
                                        $cls.='income';
                                    }
                                ?>
                                <tr class="<?=$cls?>" data-summary="item" data-summary-id="<?=$value['id']?>" data-type="<?=$type?>">
                                    <td class="name"><?=$value['name']?></td>
                                    <td class="price" data-price="<?=$value['price']?>"><?=number_format( $value['price'] )?></td>
                                    <td class="unit">x</td>
                                    <td class="qty" data-qty="<?=$value['qty']?>"><?=$value['qty']?></td>
                                    <td class="unit">=</td>
                                    <td class="total"><?=$type=='discount' ? '-':''?><span data-total><?=number_format($value['qty']*$value['price'])?></span></td>
                                </tr>
                                <?php } ?>
                            </tbody></table>
                        </div>
                    </div>
                    
                    <div class="mvm" style="background-color: #f2f2f2;padding: 20px;font-size: 20px;font-weight: bold;text-align: right;">
                        Total: <span data-text="total" data-summary="subtotal">0</span>
                    </div>

                    <div class="" style="text-align: right;">
                        <button type="submit" class="btn btn-success btn-booking">Save</button>
                    </div>
                </div>
                <!-- End: booking -->
            </form>
             <!-- End: form -->
        </div>
    </div>

</section>
<style type="text/css">
    .tablePrice td{
        padding: 5px 4px;
        border-bottom: 1px solid #ddd
    }
    .tablePrice .price{
        width: 20px;
        text-align: right;
    }
    .tablePrice .unit{
        width: 10px;
        color: #777;
        padding-left: 0;
        padding-right: 0;
    }
    .tablePrice .qty{
        width: 10px;
        text-align: left;
    }
    .tablePrice .total{
        width: 20px;
        text-align: right;
        color: #168add
    }
    .tablePrice .discount td{
        border-bottom: none;
        color: #666;
        font-size: 80%;
        padding-top: 2px;
        padding-bottom: 2px;
    }
    .tablePrice .discount.first td{
        padding-top: 12px;
    }
    .btn-booking{
        height: 50px;
        line-height: 50px;
        padding-left: 30px;
        padding-right: 30px;
    }
    
</style>

<script type="text/javascript">
    

    function _sum() {
        
        var income = 0;
        var discount = 0;
        $.each( $('[data-summary=item]'), function(index, el) {
            
            var type = $(this).attr('data-type');
            var price = parseInt( $(this).find('[data-price]').attr('data-price') ) || 0;
            var qty = parseInt( $(this).find('[data-qty]').attr('data-qty') ) || 0;
            var total = price*qty;
            
            if( type=='discount' ){
                discount+=total;
            }
            else{
                income += total;
            }

            $(this).find('[data-total]').text( number_format(total) );
        });

        var subtotal = income-discount;
        if(subtotal < 0) subtotal = 0;
        $('[data-summary=subtotal]').text( number_format(subtotal) );
    }

    function number_format (number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };

        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    _sum();


    $('[data-action=selector]').change(function() {
        var key = $(this).attr('id'),
            val = $(this).val() || 0;
        $('[data-summary-id='+ key +']').find('[data-qty]').attr('data-qty', val).text( val );

        _sum();
    });

</script>