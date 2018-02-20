<?php
// ที่นั่งที่ยังว่างอยู่
$availableSeat = $this->item['per_qty_seats']-$this->seatBooked['booking'];


$selectSeat = $availableSeat <= 0 ? $this->item['per_qty_seats']: $availableSeat;
$numberArray = array();
for ($i=1; $i <= $selectSeat; $i++) { 
    $numberArray[] = array('id'=>$i, 'name' => $i);
}


// Deposit Date
$depositDate = date('Y-m-d');
$depositDate = date('Y-m-d', strtotime("+2 day", strtotime($depositDate)));

$depositPrice = $this->item['ser_deposit'];

// Full Pay
$fullpayDate = $depositDate;
$fullpayDate = date('Y-m-d', strtotime("+21 day", strtotime($fullpayDate)));
$fullpayPrice = 0;


// 
#contactFrom
$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');
$form->field("Period")->label('Period:')->addClass('inputtext disabled')->autocomplete('off')->attr('disabled', 1)->value( $this->fn->q('time')->str_event_date($this->item['per_date_start'], $this->item['per_date_end']) );
$form->field("sale_id")->label('Sales Contact:')->autocomplete('off')->addClass('inputtext')->select( $this->salesList );
$form->field("seat")->text('<div class="clearfix"><label for="seat" class="control-label lfloat" style="width:auto">Available Seat:</label><div class="rfloat" style="font-size: 40px;color: #3F51B5;">'.($availableSeat <= 0 ? 'เต็ม': number_format($availableSeat)).'</div></div>' .

    ''
);
$form->field("sgent")->label('Agent Company:')->autocomplete('off')->attr('disabled', 1)->addClass('inputtext disabled')->value( $this->me['company_name'] );
$form->field("sale")->label('Agent Name:')->autocomplete('off')->attr('disabled', 1)->addClass('inputtext disabled')->value( $this->me['fullname'] );
$form->field("comment")->label('คำขอพิเศษ:')->autocomplete('off')->placeholder('สำหรับคำขอพิเศษต่างๆ หรือชื่อลูกค้า')->addClass('inputtext')->attr('data-plugins','autosize')->type('textarea');
$form->field("customername")->label('ชื่อลูกค้า')->addClass('inputtext')->type('text')->placeholder('สำหรับกรอกชื่อลูกค้า');
$form->field("customertel")->label('เบอร์โทรลูกค้า')->addClass('inputtext')->type('text')->placeholder('สำหรับกรอกเบอร์โทรของลูกค้า');

$contactFrom = $form->html();

#travelerFrom
$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form   ->field("seat_adult")->name('seat[adult]')->label('Adult:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray );
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
$tablePrice[] = array('id'=>'seat_adult','name'=>'Audit','price'=>$this->item['per_price_1'], 'cls'=>'sum-qty sum-dis');
$tablePrice[] = array('id'=>'seat_child','name'=>'Child','price'=>$this->item['per_price_2'], 'cls'=>'sum-qty sum-dis');
$tablePrice[] = array('id'=>'seat_child_bed','name'=>'Child No bed','price'=>$this->item['per_price_3'], 'cls'=>'sum-qty sum-dis');
$tablePrice[] = array('id'=>'seat_infant','name'=>'Infant','price'=>$this->item['per_price_4']);
$tablePrice[] = array('id'=>'seat_joinland','name'=>'Joinland','price'=> $this->item['per_price_5'], 'cls'=>'sum-qty');
$tablePrice[] = array('id'=>'room_single','name'=>'Sing Charge','price'=> $this->item['single_charge'] );

if( $this->item['per_com_company_agency']>0 ){
    $tablePrice[] = array('id'=>'agency_com','name'=>'Com Office','price'=>$this->item['per_com_company_agency'], 'type'=> 'discount');
}

if( $this->item['per_com_agency']>0 ){
    $tablePrice[] = array('id'=>'sales_com','name'=>'Com Sale','price'=>$this->item['per_com_agency'], 'type'=> 'discount');
}

if( $this->item['per_discount'] > 0 ){
    $tablePrice[] = array('id'=>'discount','name'=>'Discount','price'=>$this->item['per_discount'], 'type'=> 'discount_extra');
}

if( $this->promotion > 0 ){
    $tablePrice[] = array('id'=>'promotion', 'name'=>'Promotion','price'=>$this->promotion, 'type'=>'discount');
}

?>
<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
    <div class="container clearfix">
        <div class="primary-content post">

            <!-- form -->
            <form class="card js-submit-form" method="POST" action="<?=URL?>booking/register/">
                <input type="hidden" name="period" value="<?=$this->item['per_id']?>" autocomplete="off">

                <header class="mbl clearfix">
                    <h1><a style="color: #000" href="<?=URL?>tour/<?=$this->item['ser_id']?>"><?=$this->item['name']?></a> จองทัวร์</h1>
                </header>

                <div id="info" class="" style="">
                    
                    <div class="row-fluid clearfix">
                    <!--  -->
                    <div class="span6" >
                        <div class="uiBoxGray pam">
                        <table><tbody>
                            <tr><td class="clearfix fwb pbm"><i class="icon-address-book-o mrs"></i>ข้อมูลผู้จอง</td></tr>
                            <tr><td>ชื่อเซลล์ : <span style="color:#3F51B5;"><?=$this->me['fullname']?></span></td></tr>
                            <tr><td>อีเมลล์ : <span style="color:#3F51B5;"><?=$this->me['email']?></span></td></tr>
                            <tr><td>เบอร์โทร : <span style="color:#3F51B5;"><?=$this->me['tel']?></span></td></tr>
                            <tr><td>บริษัท : <span style="color:#3F51B5;"><?=$this->me['company_name']?></span></td></tr>
                        </tbody></table>
                        </div>
                    </div>

                    <div class="span6">
                        <div class="uiBoxGray pam">
                        <table><tbody>
                            <tr><td class="clearfix fwb pbm"><i class="icon-plane mrs"></i>ข้อมูลการเดินทาง</td></tr>
                            <tr><td>Code : <span class="text-blue" style="color:red;"><?=$this->item['code']?></span></td></tr>
                            <tr><td>หัวข้อ : <span style="color:red;"><?=$this->item['name']?></span></td></tr>
                            <tr><td>วันเดินทาง : <span style="color:red;"><?=$this->fn->q('time')->str_event_date($this->item['per_date_start'], $this->item['per_date_end'])?></span></td></tr>
                            <tr><td>สายการบิน : <span class="text-red" style="color:red;"><?=$this->item['air_name']?></span> เส้นทาง : <span class="text-red" style="color:red;"><?=$this->item['ser_route'];?></span></td></tr>
                            <!-- <tr><td>
                                <label>Bus:</label> <select name="bus" id="bus" class="inputtext" style="display: inline-block;background-color: #fff;border-width: 1px"><?php


                                        // echo '<option value="">-</option>';
                                    foreach ($this->busList as $key => $value) {
                                        
                                        echo '<option value="'.$value['bus_no'].'" data-qty="'.$value['bus_qty'].'">'.$value['bus_no'].'</option>';
                                    }
                                     // echo $option_bus;
                                ?>
                                </select>
                            </td></tr> -->
                        </tbody></table>
                        </div>
                    </div>
                

                    <!-- <div class="span4" >
                        <div class="uiBoxGray pam">
                        <table><tbody>
                            <tr><td class="clearfix fwb pbm"><i class="icon-money mrs"></i>INVOICE</td></tr>
                        </tbody></table>
                        </div>
                    </div> -->    

                    </div>               
                </div>
                <!-- end: info -->

                <div id="booking" class="mtl">
                    
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
                                    if( $type=='discount' || $type=='discount_extra' ){

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
                                    <td class="total"><?=$type=='discount' || $type=='discount_extra' ? '-':''?><span data-total><?=number_format($value['qty']*$value['price'])?></span></td>
                                </tr>
                                <?php } ?>
                            </tbody></table>
                        </div>
                    </div>
                    
                    <div class="mvm" style="background-color: #f2f2f2;padding:10px 20px;font-size: 20px;font-weight: 500;text-align: right;">
                        Total: <span data-text="total" data-summary="subtotal">0</span>
                    </div>
                    
                    <table >
                        <tr>
                            <td class="prl" width="50%" valign="top">
                                <?php if( $availableSeat<=0 ){ ?>
                            <div class="uiBoxYellow pam fwb">*เนื่องจากมีจำนวนการจองที่นั่งเต็มจำนวนแล้ว<br>คุณจะสามาจองทัวร์นี้ได้ในสถานะ Waiting List เท่านั้น</div>
                            <?php }?>
                            </td>
                            <td valign="top">
                                <table class="table-deposit">
                                    <tr class="first">
                                        <td>Deposit Date:</td>
                                        <td class="date"><?=$this->settings['deposit']['date']?></td>
                                        <td>Deposit:</td>
                                        <td class="price" data-deposit="<?=$this->settings['deposit']['price']?>"></td>
                                    </tr>

                                    <tr>
                                        <td>Full Payment Date:</td>
                                        <td class="date"><?=$this->settings['fullPayment']['date']?></td>
                                        <td>Full Payment:</td>
                                        <td class="price" data-fullpay="0"></td>
                                    </tr>
                                    
                                </table>

                                <div class="mtl" style="text-align: right;">
                                    <button type="submit" class="btn btn-primary btn-booking">Booking</button>
                                </div>

                            </td>
                        </tr>
                    </table>
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
        
        padding-top: 2px;
        padding-bottom: 2px;
    }
     .tablePrice .discount td.total{
        color: #ce0046
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

    .table-deposit td{
        padding-left: 10px;
        padding-top: 5px;
        padding-bottom: 5px;
        font-weight: 500;
        font-size: 20px;
        border-top: 1px dotted #ccc;
        /*border-style: ;*/
    }
    .table-deposit tr.first td{
        border-top: none;
    }
    .table-deposit .date{
        color: #566d00
    }
    .table-deposit .price{
        color: #3F51B5
    }

    .text-blue{
        color: #3F51B5
    }
    .text-red{
        color: #566d00
    }

</style>

<script type="text/javascript">
    
    var deposit = parseInt(<?=$this->settings['deposit']['price']?>) || 0;
    function _sum() {

        var comQty = 0;
        $.each( $('.tablePrice .sum-qty'), function () {
            comQty += parseInt( $(this).find('[data-qty]').attr('data-qty') ) || 0;
        } );

        var disQty = 0;
        $.each( $('.tablePrice .sum-dis'), function() {
            disQty += parseInt( $(this).find('[data-qty]').attr('data-qty') ) || 0;
        } );

        $('[data-summary-id=agency_com], [data-summary-id=sales_com]').find('[data-qty]').attr('data-qty', comQty).text( comQty );
        $('[data-summary-id=discount], [data-summary-id=promotion]').find('[data-qty]').attr('data-qty', disQty).text( disQty );

        var _deposit = deposit*comQty;
        $('[data-deposit]').text( number_format(_deposit) );
        
            
        var income = 0;
        var discount = 0;
        $.each( $('[data-summary=item]'), function(index, el) {
            
            var type = $(this).attr('data-type');
            var price = parseInt( $(this).find('[data-price]').attr('data-price') ) || 0;
            var qty = parseInt( $(this).find('[data-qty]').attr('data-qty') ) || 0;
            var total = price*qty;
            
            if( type=='discount' || type=='discount_extra' ){
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


        $('[data-fullpay]').text( number_format(subtotal-_deposit) );
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