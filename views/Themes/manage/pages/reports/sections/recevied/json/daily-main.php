<style>
.ReportSummary_numberItem>div:first-child {
	font-size: 24px;
	line-height: 45px;
}
</style>
<div class="uiBoxOverlay pal">
	<div class="clearfix">
		<h3 class="fwb">พบ <?=$this->results["total"]?> รายการ</span></h3>
		<div class="ReportSummary_numberList mtm">
			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดมัดจำ(บางส่วน) : 
					<span class="value" style="background-color:#8bb8f1; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->results["total_dep_pt"])?>฿
					</span>
				</div>
			</div>
			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดมัดจำ : 
					<span class="value" style="background-color:#2f80e7; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->results["total_dep"])?>฿
					</span>
				</div>
			</div>
			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดชำระเต็มจำนวน(บางส่วน) : 
					<span class="value" style="background-color:#58ceb1; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->results["total_full_pt"])?>฿
					</span>
				</div>
			</div>
			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดชำระเต็มจำนวน : 
					<span class="value" style="background-color:#43d967; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->results["total_full"])?>฿
					</span>
				</div>
			</div>
		</div>
		<div class="ReportSummary_numberList mtm">
			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดรวมทั้งหมด : 
					<span class="value" style="background-color:#833098; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->results["total_receipt"])?>฿
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if( empty($this->bank) ) { ?>
<div class="uiBoxOverlay pal mtm">
	<h3>สรุปตามธนาคาร</h3>
	<table class="table-bordered mts" width="100%">
		<thead>
			<tr style="background-color:#003; color:#fff;">
				<th width="5%">#</th>
				<th width="20%">ธนาคาร</th>
				<th width="20%">เลขบัญชี</th>
				<th width="25%">ชื่อบัญชี</th>
				<th width="20%">สาขา</th>
				<th width="10%">ยอดรวม</th>
			</tr>
		</thead>
		<tbody>
			<?php $num=1; foreach ($this->bankbook['lists'] as $key => $value) { ?>
				<tr>
					<td class="tac"><?=$num?></td>
					<td class="tac"><?=$value["bank_name"]?></td>
					<td class="tac"><?=$value["bankbook_code"]?></td>
					<td class="tac"><?=$value["bankbook_name"]?></td>
					<td class="tac"><?=$value["bankbook_branch"]?></td>
					<td class="tac">
						<?= !empty($this->results["bankbook"][$value["bankbook_id"]]) ? number_format($this->results["bankbook"][$value["bankbook_id"]]) : "-" ?>
					</td>
				</tr>
			<?php $num++; } ?>
		</tbody>
	</table>
</div>
<?php } ?>
<div class="clearfix mtm">
	<table class="table-bordered" width="100%">
		<thead>
			<tr style="background-color:#003; color:#fff;">
				<th width="5%">#</th>
				<th width="10%">Invoice no</th>
				<th width="10%">รหัส</th>
				<th width="15%">Period</th>
				<th width="10%">เลขบัญชี</th>
				<th width="10%">จำนวนเงิน</th>
				<th width="5%">เวลาที่โอน</th>
				<th width="15%">ผู้ทำรายการ</th>
				<!-- <th width="10%">วันทำรายการ</th> -->
				<th width="10%">สถานะการชำระเงิน</th>
			</tr>
		</thead>
		<tbody>
			<?php if( !empty($this->results["lists"]) ) { 
				$num = 1;
				foreach ($this->results["lists"] as $key => $value) {
					?>
					<tr>
						<td class="tac fwb pam"><?=$num?></td>
						<td class="tac"><?=$value["invoice_code"]?></td>
						<td class="tac"><?=$value["ser_code"]?></td>
						<td class="tac"><?= $this->fn->q('time')->str_event_date($value["per_date_start"], $value["per_date_end"]) ?></td>
						<td class="tac"><?=$value["bankbook_code"]?></td>
						<td class="tac"><?=number_format($value["received"])?></td>
						<td class="tac"><?=$value["time"]?></td>
						<td class="tac"><?=$value["action"]?></td>
						<!-- <td></td> -->
						<td class="tac">
							<span class="status_<?=$value["book_status"]?>"><?=$value["book_status_arr"]["name"]?></span>
						</td>
					</tr>
					<?php
					$num++;
				}
			?>
			<?php }
			else{
				echo '<tr><td colspan="10" class="tac fwb" style="color:red;">ไม่พบข้อมูล</td></tr>';
			} ?>
		</tbody>
	</table>
</div>