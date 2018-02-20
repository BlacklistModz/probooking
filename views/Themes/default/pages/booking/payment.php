<?php 
$totalBalance = $this->item["book_amountgrandtotal"] - $this->item["payment"]["pay_total"];
?>
<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<div class="container clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-money"></i> แจ้งชำระเงิน</h1>
					<h3 class="tac">CODE : <?=$this->item['book_code']?></h3>
					<h4 class="tac">(<?=$this->item['ser_code']?>) <?=$this->item['ser_name']?></h4>
				</header>
				<div class="clearfix">
					<div class="mts">
						<a href="<?=URL?>payment/add/<?=$this->item["book_id"]?>" data-plugins="dialog" class="rfloat btn btn-blue"><i class="icon-upload"></i> แจ้งชำระ</a>
					</div>
				</div>
				<table class="table-bordered mtl">
					<tr>
						<td width="33.33%" class="pal" style="font-size: 24px;">
							<span class="fwb">TOTAL :</span> 
							<span style="color:#8bb8f1;"><?= number_format($this->item['book_amountgrandtotal']) ?></span>
						</td>
						<td width="33.33%" class="pal" style="font-size: 24px;">
							<span class="fwb">Amount Receive :</span>
							<span style="color:#8bb8f1;"><?= number_format($this->item["payment"]["pay_total"]) ?></span>
						</td>
						<td width="33.33%" class="pal" style="font-size: 24px;">
							<span class="fwb">Total balance :</span>
							<span style="color:#f05050;"><?= number_format($totalBalance) ?></span>
						</td>
					</tr>
				</table>
				<table class="table-bordered mtl">
					<thead>
						<tr>
							<th width="10%">#</th>
							<th width="30%">Payment</th>
							<th width="30%">จำนวนเงิน</th>
							<th width="30%">วันที่ครบกำหนดชำระ</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="tac">1</td>
							<td class="tac">Deposit</td>
							<td class="tac"><?=number_format($this->item['book_master_deposit'])?></td>
							<td class="tac">
								<?php if( $this->item['book_due_date_deposit'] != "0000-00-00 00:00:00" ){
									echo date("d/m/Y", strtotime($this->item['book_due_date_deposit']));
								}?>
							</td>
						</tr>
						<tr>
							<td class="tac">2</td>
							<td class="tac">Full payment</td>
							<td class="tac"><?=number_format($this->item['book_master_full_payment'])?></td>
							<td class="tac">
								<?php if( $this->item['book_due_date_full_payment'] != "0000-00-00 00:00:00" ) {
									echo date("d/m/Y", strtotime($this->item['book_due_date_full_payment']));
								} ?>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table-bordered mtl">
					<thead>
						<tr>
							<th width="2%">#</th>
							<th width="10%">สถานะ</th>
							<th width="5%">ไฟล์</th>
							<th width="10%">ธนาคาร</th>
							<th width="10%">สาขา</th>
							<th width="10%">ชื่อบัญชี</th>
							<th width="10%">เลขที่บัญชี</th>
							<th width="10%">จำนวนเงิน</th>
							<th width="7%">วันที่โอน</th>
							<th width="6%">เวลา</th>
							<th width="10%">วันทำรายการ</th>
							<th width="10%">สถานะการชำระเงิน</th>
						</tr>
					</thead>
					<tbody>
						<?php if( !empty($this->item['payment']['lists']) ) { 
							$no=0;
							foreach ($this->item["payment"]['lists'] as $key => $value) {
								$no++;
								?>
								<tr>
									<td class="tac fwb"><?=$no?></td>
									<td class="tac"><span class="fwb fz_11 status_<?=$value["book_status"]["id"]?>"><?=$value["book_status"]["name"]?></span></td>
									<td class="tac">
										<a href="<?=$value["pay_url_file"]?>" target="_blank" class="btn" style="color:#fff; background-color: #003;"><i class="icon-download"></i></a>
									</td>
									<td class="tac"><?=$value["bank_name"]?></td>
									<td class="tac"><?=$value["bankbook_branch"]?></td>
									<td class="tac"><?=$value["bankbook_name"]?></td>
									<td class="tac"><?=$value["bankbook_code"]?></td>
									<td class="tac"><?= number_format($value["pay_received"]) ?></td>
									<td class="tac"><?= date("d/m/Y", strtotime($value["pay_date"])) ?></td>
									<td class="tac"><?=$value["pay_time"]?></td>
									<td class="tac"><?= date("d/m/Y", strtotime($value["create_date"])) ?></td>
									<td class="tac">
									<?php 
										if($value['status']['id']==9){ ?>
											<a style="text-decoration:none;" href="/booking/payRejected/<?=$value['pay_id']?>" data-plugins="dialog"><span class="pay_status_<?=$value["status"]["id"]?>"><?=$value["status"]["name"]?></span></a>
									<?php }else{ ?>
												<span class="pay_status_<?=$value["status"]["id"]?>"><?=$value["status"]["name"]?></span>
									<?php }?>
									</td>
								</tr>
								<?php 
							}
						}
						else{
							echo '<tr><td colspan="12" style="color:red;" class="fwb tac">ไม่พบข้อมูล</td></tr>';
						} ?>
					</tbody>
				</table>
				<!-- <div class="clearfix" style="margin-top:10px;">	
					<span class="fcr">หมายเหตุ สถานะปฏิเสธการชำระเงิน ไม่ใช่การตัดสิทธิ์การจอง กรุณาตรวจสอบสาเหตุการปฏิเสธการชำระของท่านอีกครั้ง โดยท่านสามารถ คลิกที่สถานะการชำระเงิน</span>
				</div> -->
			</div>
		</div>
	</div>
</section>