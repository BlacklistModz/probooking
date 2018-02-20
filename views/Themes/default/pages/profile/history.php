<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">

	<div class=" container clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-book"></i> Booking History</h1>
					<h3 class="tac">บริษัท : <?=$this->me['company_name']?></h3>
				</header>
				<ul class="rfloat mbm" ref="control">
					<li>
						<label for="status" class="label fwb">เซลล์ : </label>
						<select ref="selector" class="inputtext" name="agency" style="display:inline;">
							<option value="">-- ทั้งหมด --</option>
							<?php foreach ($this->sales['lists'] as $key => $value) {
								$sel = '';
								if( $this->agen_id == $value["id"] ) $sel = ' selected="1"';
								echo '<option'.$sel.' value="'.$value["id"].'">'.$value["fullname"].'</option>';
							} ?>
						</select>		
					</li>
				</ul>
				<div class="clearfix">
					<table class="table-bordered"  style="color:#000; overflow-x:auto; display: block; width: 100%;-webkit-overflow-scrolling: touch;  -ms-overflow-style: -ms-autohiding-scrollbar;">
						<thead>
							<tr style="color:#fff; background-color: #003;">
								<th width="9%">Date</th>
								<th width="8%">Booking No.</th>
								<th width="5%">CODE</th>
								<th width="15%">Period</th>
								<th width="6%">Pax</th>
								<th width="7%">Total</th>
								<!-- <th width="10%">Deposit Date</th>
								<th width="10%">Full Payment Date</th> -->
								<th width="10%">Sales</th>
								<th width="10%">Status</th>
								<th width="5%">Quotation</th>
								<?php
								if( !empty($this->me['company_guarantee']) ){
									echo '<th width="5%">Guarantee</th>';
								}
								?>
								<th width="5%">Passport</th>
								<th width="5%">Payment</th>
								<th width="5%">ใบเตรียมตัว</th>
								<th width="5%">Cancel</th>
							</tr>
						</thead>
						<tbody>
							<?php if( !empty($this->results["lists"]) ) { 
								//print_r($this->results);die;
								foreach ($this->results["lists"] as $key => $value) {
									$dayStr = date("d", strtotime($value["book_date"]));
									$monthStr =  $this->fn->q('time')->month( date("n", strtotime($value["book_date"])) );
									$yearStr = date("Y", strtotime($value["book_date"])) + 543;

									$timeStr = date("H:i:s", strtotime($value["book_date"]));
									$dateTime = "{$dayStr} {$monthStr} {$yearStr}";
									//print_r($value); die;
									/* $fullPaymentStr = "-";
									$DepositStr = "-";
									$fullPaymenting ="";
									$deposited="";
									
									if( $value["book_due_date_full_payment"] != "0000-00-00 00:00:00" ){
										$fDaystr = date("d", strtotime($value['book_due_date_full_payment']));
										$fMonthStr = $this->fn->q('time')->month( date("n", strtotime($value["book_due_date_full_payment"])) );
										$fYearStr = date("Y", strtotime($value["book_due_date_full_payment"])) + 543;
										$fullPaymentStr = "{$fDaystr} {$fMonthStr} {$fYearStr}";
										
									}

									if( $value["book_due_date_deposit"] != "0000-00-00 00:00:00" ){
										$dDaystr = date("d", strtotime($value['book_due_date_deposit']));
										$dMonthStr = $this->fn->q('time')->month( date("n", strtotime($value["book_due_date_deposit"])) );
										$dYearStr = date("Y", strtotime($value["book_due_date_deposit"])) + 543;
										$DepositStr = "{$dDaystr} {$dMonthStr} {$dYearStr}";
									}
										$deposited = $value['book_master_deposit'];
										$fullPaymenting = $value["book_master_full_payment"]; */
									
									$guarantee = "";
									if( !empty($value["book_guarantee_file"]) ){
										$guarantee = '<span style="display:inline-block; color:#ff902b;" class="fwb"><i class="icon-thumbs-up"></i> Checking</span>';
									}
									if( !empty($value["book_is_guarantee"]) ){
										$guarantee = '<i class="icon-thumbs-up" style="color:blue;"></i>';
									}

									$inf = $value['book_inf'];
									$jl = $value['book_jl'];

									$ex_qty = '';
									if( !empty($inf) ){
										$ex_qty .= '<br/><span style="font-size: 12px;">(INF : '.$inf.')</span>';
									}
									if( !empty($jl) ){
										$ex_qty .= '<br/><span style="font-size: 12px;">(JL : '.$jl.')</span>';
									}
									?>
									<tr>
										<td class="tac"><?=$dateTime?><br/><?=$timeStr?></td>
										<td class="tac"><?=$guarantee?><a data-plugins="dialog" target="_blank" style="color:blue; text-decoration: none;" href="<?=URL?>booking/profile/<?=$value["book_id"]?>"><?=$value["book_code"]?></a></td>
										<th class="tac"><?=$value["ser_code"]?></th>
										<th class="tac"><?=$this->fn->q('time')->str_event_date($value["per_date_start"], $value["per_date_end"])?></th>
										<!-- <td class="tac">
											<a href="<?=URL?>tour/<?=$value["ser_id"]?>" style="color:blue; text-decoration: none;" target="_blank">
											<span class="fwb"><?=$value['ser_code']?></a>
										</td> -->
										<td class="tac"><?=$value["book_qty"]?> <?=$ex_qty?></td>
										<td class="tar" style="padding-right: 2mm;"><?=number_format($value['book_amountgrandtotal'])?></td>
										<td class="tac"><?= $value['agen_nickname'] != "-" ? $value['agen_nickname'] : $value['agen_fname'] ?></td>
										<td class="tac">
											<span class="fwb fz_11 status_<?=$value['status']?>"><?=$value["book_status"]['name']=="Full payment"?"FP":$value["book_status"]['name']?></span>
										</td>
		
										<td class="tac">
											<?php 
											
											if( ($value['status'] >5 && $value['status'] <40)  && (!empty($this->me) || $this->me['role']=="admin")  )  {
												echo '<a target="_blank" href="//admin.probookingcenter.com/admin/print/pdf_invoice.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'" class=" btn"><i class="icon-file-pdf-o"></i></a>';
											}
											else{
												echo '<a class="disabled btn"><i class="icon-file-pdf-o"></i></a>';
											}
											?>
										</td>
										<?php

										
										$p_upload = '';
											// echo '<span>N/A</span>'; 
										//print_r($value);die;
										if( !empty($this->me['company_guarantee'])  ){
											$cls = '';
											$bkst = '';
											if( $value["status"] == 40 || $value["status"] == 5 || $value["book_is_guarantee"] == 1 ){
												$cls = 'disabled';
											}if(!empty($value['book_guarantee_file'])){
												$bkst = 'btn-red';
											}
											
											echo '<td class="tac">
											<a href="'.URL.'booking/guarantee/'.$value['book_id'].'" data-plugins="dialog" class="btn btn '.$cls.' '.$bkst.'"><i class="icon-upload"></i></a>
											</td>'; 
										}				
										?>
										<td class="tac">
											<?php
											// passport ; 
											if( ($value['status'] == 00 || $value['status'] == 05 || $value['status'] == 10 || $value['status'] == 40 || $value['status'] == 50) || ($this->me['id'] != $value['agen_id'] && $this->me['role']!='admin') ) {
												echo '<p class="fca lock"><i class="icon-remove"></i></p>';
											}
											else{
												if($value['booking_passport']==0){
													$p_upload = '<a href="'.URL.'/booking/passport/'.$value['book_id'].'" role="dialog-close" data-plugins="dialog" class="fcb"><i class="icon-upload"></i></a>';
												}
												echo $p_upload;
												if( !empty($value["total_passport"]) ){
													echo '&nbsp;<a href="'.URL.'/booking/passport_view/'.$value['book_id'].'"class="fcb"><i class="icon-folder-open-o"></i></a>';
												}
											}
											?>
										</td>
										<td class="tac">
											<?php
											// echo '<span>N/A</span>'; 
											if( $value['status'] == 5 || $value['status'] == 35 || $value['status'] == 40 || $value['status'] == 50 ){
												echo '<a class="btn btn-blue disabled"><i class="icon-lock"></i></a>';
											}
											else{
												echo '<a href="'.URL.'booking/payment/'.$value['book_id'].'" class="btn btn-blue"><i class="icon-money"></i></a>';
											}
											?>
										</td>
										<td class="tac">
										<?=empty($value['per_url_pdf']) ? '<span> - </span>': '<a href="'.$value['per_url_pdf'].'" class="btn"  target="_blank"><i class="icon-file-pdf-o fcb" ></i></a>'?>		
										</td>
										<td class="tac">
											<?php 
											if( ($value['status'] == 0 || $value['status'] == 5 || $value['status'] == 10 ) && $value['agen_id'] == $this->me['id'] ) {
												echo '<a href="'.URL.'booking/booking_cancel/'.$value['book_id'].'" class="btn btn-red" data-plugins="dialog"><i class="icon-remove"></i></a>';
											}
											else{
												echo '<a class="disabled btn btn-red"><i class="icon-lock"></i></a>';
											}
											?>
										</td>
									</tr>
									<?php } 
								}
								else{
									echo '<tr><td colspan="11" style="color:red;" class="tac fwb">ไม่มีข้อมูลการจอง</td></tr>';
								} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript"> 
	$("[name=agency]").change(function(){
		// alert($(this).val());
		window.location.href = Event.URL + "profile/history/" + $(this).val();
	});
</script>