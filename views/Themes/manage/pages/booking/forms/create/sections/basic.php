<?php
// print_r($this->item);die;

// ที่นั่งที่ยังว่างอยู่
$availableSeat = $this->period['bus_qty']-$this->seatBooked['booking'];

$selectSeat = $this->period["bus_qty"];
if( empty($this->item) ){
	$selectSeat = $availableSeat <= 0 ? $this->period['bus_qty']: $availableSeat;
}
$numberArray = array();
for ($i=1; $i <= $selectSeat; $i++) { 
	$numberArray[] = array('id'=>$i, 'name' => $i);
}

$seat = array();
$other = array();
if( !empty($this->item["items"]) ){
	foreach ($this->item["items"] as $key => $value) {
		if( $value["book_list_code"] < 6 ){
			$seat[$value["book_list_code"]] = $value["book_list_qty"];
		}
		else{
			$other[] = $value;
		}
	}
}

// Deposit Date
$depositDate = date('Y-m-d');
$depositDate = date('Y-m-d', strtotime("+2 day", strtotime($depositDate)));

$depositPrice = $this->period['ser_deposit'];

// Full Pay
$fullpayDate = $depositDate;
$fullpayDate = date('Y-m-d', strtotime("+21 day", strtotime($fullpayDate)));
$fullpayPrice = 0;

// 
#contactFrom
$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');
$form->field("Period")->label('Period:')->addClass('inputtext disabled')->autocomplete('off')->attr('disabled', 1)->value( $this->fn->q('time')->str_event_date($this->period['per_date_start'], $this->period['per_date_end']) );
$form->field("sale_id")->label('Sales Contact:')->autocomplete('off')->addClass('inputtext custom-select')->select( $this->salesList )->value( !empty($this->item["user_id"]) ? $this->item["user_id"] : "" );
$form->field("seat")->text('<div class="clearfix"><label for="seat" class="control-label lfloat" style="width:auto">Available Seat:</label><div class="rfloat" style="font-size: 40px;color: #3F51B5;">'.($availableSeat <= 0 ? 'เต็ม': number_format($availableSeat)).'</div></div>' .

	''
);
$form->field("sgent")->label('Agent Company:')->autocomplete('off')->addClass('inputtext custom-select')->select( $this->company )->value( !empty($this->item["agen_com_id"]) ? $this->item["agen_com_id"] : "" );
$form->field("sale")->label('Agent Name:')->autocomplete('off')->addClass('inputtext custom-select')->select( array() );
$form->field("comment")->label('คำขอพิเศษ:')->autocomplete('off')->placeholder('สำหรับคำขอพิเศษต่างๆ หรือชื่อลูกค้า')->addClass('inputtext')->attr('data-plugins','autosize')->type('textarea')->value( !empty($this->item["remark"]) ? $this->item["remark"] : "" );
$form->field("customername")->label('ชื่อลูกค้า')->addClass('inputtext')->type('text')->placeholder('สำหรับกรอกชื่อลูกค้า')->value( !empty($this->item["book_cus_name"]) ? $this->item["book_cus_name"] : "" );
$form->field("customertel")->label('เบอร์โทรลูกค้า')->addClass('inputtext')->type('text')->placeholder('สำหรับกรอกเบอร์โทรของลูกค้า')->value( !empty($this->item["book_cus_tel"]) ? $this->item["book_cus_tel"] : "" );

$contactFrom = $form->html();

#travelerFrom
$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form   ->field("seat_adult")->name('seat[adult]')->label('Adult:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray )->value( !empty($seat[1]) ? $seat[1] : "" );
$form   ->field("seat_child")->name('seat[child]')->label('Child:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray )->value( !empty($seat[2]) ? $seat[2] : "" );
$form   ->field("seat_child_bed")->name('seat[child_bed]')->label('Child No bed:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray )->value( !empty($seat[3]) ? $seat[3] : "" );
$form   ->field("seat_infant")->name('seat[infant]')->label('Infant:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray )->value( !empty($seat[4]) ? $seat[4] : "" );
$form   ->field("seat_joinland")->name('seat[joinland]')->label('Joinland:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray )->value( !empty($seat[5]) ? $seat[5] : "" );

$travelerFrom = $form->html();



#roomtypeFrom
$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form   ->field("room_twin")->name('room[twin]')->label('Twin:')->addClass('inputtext')->select( $numberArray )->value( !empty($this->item["book_room_twin"]) ? $this->item["book_room_twin"] : "" );
$form   ->field("room_double")->name('room[double]')->label('Double:')->addClass('inputtext')->select( $numberArray )->value( !empty($this->item["book_room_double"]) ? $this->item["book_room_double"] : "" );
$form   ->field("room_triple")->name('room[triple]')->label('Triple:')->addClass('inputtext')->select( $numberArray )->value( !empty($this->item["book_room_triple"]) ? $this->item["book_room_triple"]: "" );
$form   ->field("room_single")->name('room[single]')->label('Single:')->addClass('inputtext')->attr('data-action','selector')->select( $numberArray )->value( !empty($this->item["book_room_single"]) ? $this->item["book_room_single"] : "" );
$roomtypeFrom = $form->html();


#priceFrom
$tablePrice = array();
$tablePrice[] = array('id'=>'seat_adult','name'=>'Audit','price'=>$this->period['per_price_1'], 'cls'=>'sum-qty sum-dis');
$tablePrice[] = array('id'=>'seat_child','name'=>'Child','price'=>$this->period['per_price_2'], 'cls'=>'sum-qty sum-dis');
$tablePrice[] = array('id'=>'seat_child_bed','name'=>'Child No bed','price'=>$this->period['per_price_3'], 'cls'=>'sum-qty sum-dis');
$tablePrice[] = array('id'=>'seat_infant','name'=>'Infant','price'=>$this->period['per_price_4']);
$tablePrice[] = array('id'=>'seat_joinland','name'=>'Joinland','price'=> $this->period['per_price_5'], 'cls'=>'sum-qty');
$tablePrice[] = array('id'=>'room_single','name'=>'Sing Charge','price'=> $this->period['single_charge'] );

if( $this->period['per_com_company_agency']>0 ){
	$tablePrice[] = array('id'=>'agency_com','name'=>'Com Office','price'=>$this->period['per_com_company_agency'], 'type'=> 'discount');
}

if( $this->period['per_com_agency']>0 ){
	$tablePrice[] = array('id'=>'sales_com','name'=>'Com Sale','price'=>$this->period['per_com_agency'], 'type'=> 'discount');
}

if( $this->period['per_discount'] > 0 ){
	$tablePrice[] = array('id'=>'discount','name'=>'Discount on Period','price'=>$this->period['per_discount'], 'type'=> 'discount_extra');
}

// if( $this->promotion > 0 ){
// 	$tablePrice[] = array('id'=>'promotion', 'name'=>'Promotion','price'=>$this->promotion, 'type'=>'discount_extra');
// }

$options = $this->fn->stringify( 
	array(
		"deposit" => !empty($this->settings["deposit"]["price"]) ? $this->settings["deposit"]["price"] : 0,
		'agen_id' => !empty($this->item["agen_id"]) ? $this->item["agen_id"] : 0,
		'items' => !empty($other) ? $other : array(),
		'discount' => !empty($this->item["book_discount"]) ? $this->item["book_discount"] : 0
	) 
);

?>
<div class="clearfix">
	<div class="primary-content post pas pam">

		<!-- form -->
		<form class="card js-submit-form" method="POST" action="<?=URL?>office/booking/basic" data-plugins="bookingForm" data-options="<?=$options?>">
			<input type="hidden" name="period" value="<?=$this->period['per_id']?>" autocomplete="off">
			<input type="hidden" name="bus" value="<?=$this->period['bus_no']?>" autocomplete="off">

			<header class="mbl clearfix">
				<h1>จัดการจองทัวร์</h1>
			</header>

			<div id="info" class="" style="">

				<div class="row-fluid clearfix">
					<!--  -->
					<div class="span4" >
						<div class="uiBoxGray pam">
							<table><tbody>
								<tr><td class="clearfix fwb pbm"><i class="icon-address-book-o mrs"></i>ข้อมูลผู้จอง</td></tr>
								<tr><td>ชื่อเซลล์ : <span style="color:#3F51B5;"> <?= !empty($this->item["agen_fname"]) ? $this->item["agen_fname"]." ".$this->item["agen_lname"] : "-" ?> </span></td></tr>
								<tr><td>อีเมลล์ : <span style="color:#3F51B5;"><?= !empty($this->item["agen_email"]) ? $this->item["agen_email"] : "-" ?></span></td></tr>
								<tr><td>เบอร์โทร : <span style="color:#3F51B5;"><?= !empty($this->item["agen_tel"]) ? $this->item["agen_tel"] : "-" ?></span></td></tr>
								<tr><td>บริษัท : <span style="color:#3F51B5;"><?= !empty($this->item["agen_com_name"]) ? $this->item["agen_com_name"] : "-" ?></span></td></tr>
							</tbody></table>
						</div>
					</div>

					<div class="span4">
						<div class="uiBoxGray pam">
							<table><tbody>
								<tr><td class="clearfix fwb pbm"><i class="icon-plane mrs"></i>ข้อมูลการเดินทาง</td></tr>
								<tr><td>Code : <span class="text-blue" style="color:red;"><?=$this->period['code']?></span></td></tr>
								<tr><td>หัวข้อ : <span style="color:red;"><?=$this->period['name']?></span></td></tr>
								<tr><td>วันเดินทาง : <span style="color:red;"><?=$this->fn->q('time')->str_event_date($this->period['per_date_start'], $this->period['per_date_end'])?></span></td></tr>
								<tr><td>สายการบิน : <span class="text-red" style="color:red;"><?=$this->period['air_name']?></span> เส้นทาง : <span class="text-red" style="color:red;"><?=$this->period['ser_route'];?></span></td></tr>
                            <tr><td>
                                <label>Bus:</label> <select name="bus" id="bus" class="inputtext" style="display: inline-block;background-color: #fff;border-width: 1px"><?php


                                        // echo '<option value="">-</option>';
                                    foreach ($this->busList as $key => $value) {
                                        
                                        echo '<option value="'.$value['bus_no'].'" data-qty="'.$value['bus_qty'].'">'.$value['bus_no'].'</option>';
                                    }
                                     // echo $option_bus;
                                ?>
                                </select>
                            </td></tr>
                        </tbody></table>
                    </div>
                </div>
                

                    <div class="span4" >
                        <div class="uiBoxGray pam">
                        <table><tbody>
                            <tr>
                            	<td class="clearfix fwb pbm"><i class="icon-money mrs"></i>INVOICE</td>
                            </tr>
                            <tr>
                            	<td><span class="fwb">Invoice No.</span> <?= !empty($this->item["invoice_code"]) ? $this->item["invoice_code"] : "" ?></td>
                            </tr>
                        </tbody></table>
                        </div>
                    </div>    

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

            				<div class="span6">
            					<div class="span6">
            						<h3>TRAVELER INFO</h3>
            						<?=$travelerFrom?>
            					</div>
            					<div class="span6">
            						<h3>ROOM TYPE</h3>
            						<?=$roomtypeFrom?>
            					</div>
            					<div class="span12 mtl" style="margin-left: -0.5mm;">
            						<div class="uiBoxGray pas">
            							<h3 class="fwb"><i class="icon-list mrs"></i>รายการเพิ่มเติม</h3>
            							<table class="table-bordered" style="background-color: #fff;">
            								<thead>
            									<tr>
            										<th width="10%">#</th>
            										<th width="30%">รายการ</th>
            										<th width="15%">ราคา</th>
            										<th width="15%">จำนวน</th>
            										<th width="20%">รวม</th>
            										<th width="10%"></th>
            									</tr>
            								</thead>
            								<tbody role="listsItem"></tbody>
            							</table>

            							<div class="clearfix mts tac">
            								<span class="gbtn">
            									<a class="btn btn-blue btn-no-padding js-add-item" style="color:#fff;"><i class="icon-plus"></i></a>
            								</span>
            							</div>
            						</div>
            					</div>
            				</div>

            			</div>


            		</div>

            		<div class="span4">
            			<h3>PRICE</h3>

            			<table class="tablePrice" width="100%"><tbody>
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
            					<tr class="first discount" data-type="discount_extra">
            						<td class="name"><span class="fwb">ส่วนลด</span></td>
            						<td class="price" colspan="5"><input type="text" name="book_discount" value="<?= !empty($this->item["book_discount"]) ? round($this->item["book_discount"]) : "" ?>" class="inputtext tar"></td>
            					</tr>
            				</tbody></table>
            			</div>
            		</div>

            		<div class="mvm" style="background-color: #f2f2f2;padding:10px 20px;font-size: 20px;font-weight: 500;text-align: right;">
            			Total: <span data-text="total" data-summary="subtotal">0</span>
            		</div>

            		<table width="100%">
            			<tr>
            				<td class="prl" width="65%" valign="top">
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
            				</td>
            			</tr>
            		</table>
            		<div class="mtl" style="text-align: right;">
            			<button type="submit" class="btn btn-primary btn-booking">Booking</button>
            		</div>
            	</div>
            	<?php 
            	if( !empty($this->item) ){
            		echo '<input type="hidden" name="id" value="'.$this->item["book_id"].'">';
            	}
            	?>
            	<!-- End: booking -->
            </form>
            <!-- End: form -->
        </div>
    </div>
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
    $("#sale_id, #sgent").customselect();
</script>