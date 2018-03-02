<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<div class=" container clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-credit-card"></i> Accounting Management System</h1>
					<h3 class="tac">บริษัท : <?=$this->me['company_name']?></h3>
				</header>
				<label class="fwb mbs"><i class="icon-list mrs"></i> รายการเลยกำหนดชำระ</label>
				<table class="table-bordered" width="100%">
					<thead>
						<tr style="color:#fff; background-color: red;">
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
			</div>
			<div class="card">
				<div class="clearfix">
					<ul class="rfloat mbm" ref="control">
						<li>
							<label for="date" class="label fwb">วันที่ : </label>
							<input type="date" class="inputtext" name="date" data-plugins="datepicker" value="" style="display:inline;">
							<label for="status" class="label fwb">เซลล์ : </label>
							<select ref="selector" class="inputtext" name="agency" style="display:inline;">
								<option value="">-- ทั้งหมด --</option>
								<?php foreach ($this->sales['lists'] as $key => $value) {
									$sel = '';
									if( $this->agen_id == $value["id"] ) $sel = ' selected="1"';
									echo '<option'.$sel.' value="'.$value["id"].'">'.$value["fullname"].'</option>';
								} ?>
							</select>
							<a class="btn btn-blue js-search" style="margin-top: -1.5mm;"><i class="icon-search"></i></a> 
						</li>
					</ul>
				</div>
				<div class="clearfix" id="tableAccount">
					<h2 class="tac fwb fcr">กรุณาทำรายการ<i class="icon-exclamation mls"></i></h2>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(".js-search").click(function(){

		var date = $("input[name=date]").val();
		var agency = $("select[name=agency]").val();

		$("#tableAccount").html( '<div class="tac"><div class="loader-spin-wrap" style="display:inline-block;"><div class="loader-spin"></div></div></div>' );
		$.get( Event.URL + 'profile/accounting', {date:date, agency:agency}, function( html ){
			$("#tableAccount").html( html );
		});
	});
</script>