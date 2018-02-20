<style>
.ReportSummary_numberItem>div:first-child {
	font-size: 24px;
	line-height: 45px;
}
</style>
<div class="uiBoxOverlay pal">
	<div class="clearfix">
		<h3 class="fwb">พบ <?=$this->book["total"]?> รายการ | <span class="fcr">ยกเลิก <?=$this->book['total_cancel']?> รายการ (รวมเป็น <?=$this->book["total_qty_cancel"]?> ที่นั่ง)</span></h3>
		<div class="ReportSummary_numberList mtm">
			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดเงินรวม : 
					<span class="value" style="background-color:blue; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->book["total_master"])?>฿
					</span>
				</div>
			</div>

			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดยกเลิก : 
					<span class="value" style="background-color:red; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->book["total_master_cancel"])?>฿
					</span>
				</div>
			</div>

			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดเงินรวม (หักยกเลิก) : 
					<span class="value" style="background-color:#1b1b4a; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->book['total_master'] - $this->book["total_master_cancel"])?>฿
					</span>
				</div>
			</div>
			<div class="clearfix">
				<!-- <h5 class="fcr rfloat">** ยอดรวมจะไม่คำนวนสถานะ <span class="fwb">จอง, แจ้ง Invoice, W/L, จอง/WL, ยกเลิก</span></h5> -->
			</div>
		</div>
		<div class="ReportSummary_numberList mtm">
			<div class="ReportSummary_numberItem total-text">
				<!-- <div>จ่ายแล้ว</div> -->
				<div>จ่ายแล้ว : 
					<span class="value" style="background-color:green; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->book["total_receipt"])?>฿
					</span>
				</div>
			</div>

			<div class="ReportSummary_numberItem total-text">
				<!-- <div>ค้างชำระ</div> -->
				<div>ค้างชำระ : 
					<span class="value" style="background-color:orange; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->book["total_balance"] - $this->book["total_master_cancel"])?>฿
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix mtm">
	<table class="table-bordered" width="100%">
		<thead>
			<tr style="background-color:#003; color:#fff;">
				<th width="10%">วันที่จอง</th>
				<th width="10%">รหัส</th>
				<th width="5%">CODE</th>
				<th width="5%">จำนวน</th>
				<th width="15%">Agency</th>
				<th width="10%">รวม</th>
				<th width="10%">จ่ายแล้ว</th>
				<th width="10%">คงเหลือ</th>
				<th width="15%">เซลล์</th>
				<th width="10%">สถานะ</th>
			</tr>
		</thead>
		<tbody>
			<?php if( !empty($this->book['lists']) ) { 
				foreach ($this->book['lists'] as $key => $value) {
					$date = date("d", strtotime($value["book_date"]));
					$month = $this->fn->q('time')->month( date("n", strtotime($value["book_date"])) );
					$year = date("Y", strtotime($value["book_date"]))+543;

					$dateStr = "{$date} {$month} {$year}";

					?>
					<tr>
						<td class="tac pam"><?=$dateStr?></td>
						<td class="tac"><?=$value["book_code"]?></td>
						<td class="tac"><?=$value["ser_code"]?></td>
						<td class="tac"><?=$value["qty"]?></td>
						<td class="tac"><?=$value["agen_com_name"]?> (<?=$value["agen_fname"].' '.$value["agen_lname"]?>)</td>
						<td class="tac"><?=number_format($value["book_master"])?></td>
						<td class="tac"><?=number_format($value["book_receipt"])?></td>
						<td class="tac"><?=number_format($value["book_balance"])?></td>
						<td class="tac"><?=$value["user_fname"].' '.$value["user_lname"]?></td>
						<td class="tac"><span class="status_<?=$value["status"]?>"><?=$value["status_arr"]["name"]?></span></td>
					</tr>
					<?php 
				}
			}
			else{
				echo '<tr><td colspan="10" class="fcr fwb tac">ไม่มีข้อมูล</td></tr>';
			}?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="3">ยอดรวม</th>
				<th><?=number_format($this->book["total_qty"])?></th>
				<th>-</th>
				<th><?=number_format($this->book["total_master"])?></th>
				<th><?=number_format($this->book["total_receipt"])?></th>
				<th class="fcr"><?=number_format($this->book["total_balance"])?></th>
				<th>-</th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>