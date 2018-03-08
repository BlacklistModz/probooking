<?php 
$totalBalance = $this->item["book_amountgrandtotal"] - $this->item["payment"]["pay_total"];

$payment[] = array('text'=>'Deposit', 'price'=>'book_master_deposit', 'date'=>'book_due_date_deposit');
$payment[] = array('text'=>'Full payment', 'price'=>'book_master_full_payment', 'date'=>'book_due_date_full_payment');
?>
<style type="text/css">
hr {
	display: block;
	unicode-bidi: isolate;
	-webkit-margin-before: 0.5em;
	-webkit-margin-after: 0.5em;
	-webkit-margin-start: auto;
	-webkit-margin-end: auto;
	overflow: hidden;
	border-style: inset;
	border-width: 1px;
	margin-top: 21px;
    margin-bottom: 21px;
	border: 0;
	border-top: 1px solid #e4eaec;
}
</style>
<div class="clearfix">
	<div class="uiBoxWhite pas pam">
		<ul>
			<li class="mt"><span class="fwb fsxxl">รหัสการจอง</span> : <?=$this->item["book_code"]?></li>
			<li class="mt"><span class="fwb fsxxl">ชื่อลูกค้า</span> : <?=$this->item["book_cus_name"]?></li>
			<li class="mt"><span class="fwb fsxxl">เบอร์โทรลูกค้า</span> : <?=$this->item["book_cus_tel"]?></li>
		</ul>
		<hr class="mtm">
		<div class="clearfix">
			<table class="table-bordered" width="100%">
				<tr>
					<td width="33.33%" class="fsxxl pal">
						<span class="fwb mll mrl">TOTAL : </span>
						<span style="color:#8bb8f1;" class="fwb"><?=number_format($this->item["book_amountgrandtotal"])?></span>
					</td>
					<td width="33.33%" class="fsxxl pal">
						<span class="fwb mll mrl">Amount Receive : </span>
						<span style="color:#8bb8f1;" class="fwb"><?=number_format($this->item['payment']['pay_total'])?></span>
					</td>
					<td width="33.33%" class="fsxxl pal">
						<span class="fwb mll mrl">Total Balance : </span>
						<span style="color:#f05050;" class="fwb"><?=number_format($totalBalance)?></span>
					</td>
				</tr>
			</table>
			<table class="table-bordered mtl" width="100%">
				<thead>
					<tr>
						<th width="10%" class="pas">#</th>
						<th width="30%">Payment</th>
						<th width="30%">จำนวนเงิน</th>
						<th width="30%">วันที่ครบกำหนดชำระ</th>
					</tr>
				</thead>
				<tbody>
					<?php $num=0; foreach ($payment as $key => $value) { $num++;?>
					<tr>
						<td class="tac pas"><?=$num?></td>
						<td class="tac"><?=$value["text"]?></td>
						<td class="tac"><?= $this->item[$value["price"]] != 0.00 ? number_format($this->item[$value["price"]]) : "-" ?></td>
						<td class="tac"><?= $this->item[$value["date"]] != "0000-00-00 00:00:00" ? date("j/n/Y", strtotime($this->item[$value["date"]])) : "-" ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<hr class="mtm">
		<div class="clearfix">
			<div class="lfloat">
				<h3 class="fwb"><i class="icon-list mrs"></i> รายการชำระเงิน</h3>
			</div>
			<div class="rfloat">
				<span class="gbtn">
					<a href="<?=URL?>payment/add/<?=$this->item["book_id"]?>" data-plugins="dialog" class="btn btn-blue btn-small"><i class="icon-plus mrs"></i>เพิ่มการชำระเงิน</a>
				</span>
			</div>
			<table class="table-bordered mts" width="100%">
				<thead>
					<tr style="color:#fff; background-color: #003;">
						<th class="pas" width="2%">#</th>
						<th width="9%">สถานะ</th>
						<th width="5%">ไฟล์อ้างอิง</th>
						<th width="8%">ธนาคาร</th>
						<th width="8%">สาขา</th>
						<th width="8%">ชื่อบัญชี</th>
						<th width="7%">เลขที่บัญชี</th>
						<th width="7%">จำนวนเงิน</th>
						<th width="5%">วันที่โอน</th>
						<th width="5%">เวลาที่โอน</th>
						<th width="9%">ผู้ทำรายการ</th>
						<th width="8%">วันที่ทำรายการ</th>
						<th width="9%">สถานะการชำระเงิน</th>
						<th width="3%">แก้ไข</th>
						<th width="7%"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if( !empty($this->item['payment']["lists"]) ) { 
						$n = 0;
						foreach ($this->item["payment"]["lists"] as $key => $value) {
							// print_r($value);die;
							$n++;
							$cls = '';
							if( $value["status"]["id"] == 1 ){
								$cls = "bg-success";
							}
							elseif( $value["status"]["id"] == 9 ){
								$cls = "bg-danger-dark";
							}
							else{
								$cls = "bg-warning";
							}
							?>
							<tr>
								<td class="tac pam"><?=$n?></td>
								<td class="tac">
									<span class="status-label <?=$cls?>"><?=$value["status"]["name"]?></span>
								</td>
								<td class="tac">
									<span class="gbtn">
										<a class="btn btn-blue btn-no-padding"><i class="icon-eye"></i></a>
									</span>
								</td>
								<td class="tac"><?=$value["bank_name"]?></td>
								<td class="tac"><?=$value["bankbook_branch"]?></td>
								<td class="tac"><?=$value["bankbook_name"]?></td>
								<td class="tac"><?=$value["bankbook_code"]?></td>
								<td class="tac"><?= number_format($value["pay_received"],2) ?></td>
								<td class="tac"><?= date("j/n/Y", strtotime($value["pay_date"])) ?></td>
								<td class="tac"><?=$value["pay_time"]?></td>
								<td class="tac"><?=$value["user_action"]?></td>
								<td class="tac"><?= date("j/n/Y | H:i:s", strtotime($value["create_date"])) ?></td>
								<td class="tac">
									<span class="status-label status_<?=$value["book_status"]["id"]?>"><?=$value["book_status"]["name"]?></span>
								</td>
								<td class="tac">
									<span class="gbtn">
										<a href="<?=URL?>payment/edit/<?=$value["pay_id"]?>" class="btn btn-orange btn-no-padding" data-plugins="dialog"><i class="icon-pencil"></i></a>
									</span>
								</td>
								<td class="whitespace tac">
									<?php if( $value["status"]["id"] == 0 || $value["status"]["id"] == 9 ) { ?>
									<span class="gbtn">
										<a href="<?=URL?>payment/setStatus/<?=$value["pay_id"]?>/1" data-plugins="dialog" class="btn btn-green btn-small"><i class="icon-check-circle-o mrs"></i>อนุมัติ</a>
									</span>
									<?php } ?>
									<?php if( $value["status"]["id"] == 0 || $value["status"]["id"] == 1 ) { ?>
									<span class="gbtn">
										<a href="<?=URL?>payment/setStatus/<?=$value["pay_id"]?>/9" data-plugins="dialog" class="btn btn-red btn-small"><i class="icon-remove mrs"></i>ไม่อนุมัติ</a>
									</span>
									<?php } ?>
								</td>
							</tr>
							<?php 
						}
					}
					else{
						echo '<tr style="color:#fff; background-color: #ff902b;">
						<td colspan="15" class="pal tac"><span class="fsxxl">ไม่พบข้อมูล</span></td>
						</tr>';
					} ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="tac mtm">
		<a href="<?=URL?>office/booking" class="btn btn-red">กลับหน้าหลัก</a>
	</div>
</div>