<?php if( !empty($this->results) ) { ?>
<label class="fwb mbs"><i class="icon-list mrs"></i> รายการค้างชำระ</label>
<table class="table-bordered" width="100%">
	<thead>
		<tr style="color:#fff; background-color: #003;">
			<th width="15%">Status</th>
			<th width="10%">Date Due</th>
			<th width="10%">Booking No.</th>
			<th width="15%">CODE & PERIOD</th>
			<th width="5%">Pax</th>
			<th width="10%">Total</th>
			<th width="15%">Sale</th>
			<th width="15%">Due Status</th>
			<th width="5%">Payments</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->results as $key => $value) { ?>
		<tr>
			<td class="tac"><span class="fz_11 status_<?=$value["status"]?>"><?=$value["status_arr"]["name"]?></span></td>
			<td class="tac">
				<?php if( $value["status"] > 25 && $value['book_due_date_deposit'] != "0000-00-00 00:00:00" ){
					echo $this->fn->q('time')->DateTH( $value["book_due_date_deposit"] );
				}else{
					echo $this->fn->q('time')->DateTH( $value["book_due_date_full_payment"] );
				} ?>
			</td>
			<td class="tac"><?=$value["book_code"]?></td>
			<td class="tac">
				<?=$value["ser_code"]?> <br/>
				<?= $this->fn->q('time')->str_event_date($value["per_date_start"], $value["per_date_end"]) ?>
			</td>
			<td class="tac"><?=$value["qty"]?></td>
			<td class="tac"><?= number_format($value["book_amountgrandtotal"]) ?></td>
			<td class="tac"><?= $value["agen_nickname"] ?></td>
			<td class="tac">
				<?php 
				if( $value["status"] > 25 && $value['book_due_date_deposit'] != "0000-00-00 00:00:00" ){
					echo '<span class="fz_11 status_25">มัดจำ</span>';
				}else{
					echo '<span class="fz_11 status_35">ชำระเต็มจำนวน</span>';
				} 
				?>
			</td>
			<td class="tac">
				<?php 
				if( $value['status'] == 5 || $value['status'] == 35 || $value['status'] == 40 || $value['status'] == 50 ){
					echo '<a class="btn btn-blue disabled"><i class="icon-lock"></i></a>';
				}
				else{
					echo '<a href="'.URL.'booking/payment/'.$value['book_id'].'" class="btn btn-blue"><i class="icon-money"></i></a>';
				}
				?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php }
else{
	echo '<h2 class="tac fwb fcr"><i class="icon-exclamation-triangle mrs"></i>ไม่พบข้อมูล</h2>';
} ?>