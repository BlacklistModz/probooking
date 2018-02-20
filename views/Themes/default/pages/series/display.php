<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<header class="page-title">
		<!-- <h2><?=$this->item['name']?></h2> -->
		<?php include("sections/header.php"); ?>
		<!-- <h2>MYANMAR</h2> -->
	</header>
	<div class=" clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-book"></i> Series Online</h1>
				</header>
				<div class="clearfix">
					<?php 
					foreach ($this->results['lists'] as $key => $value) { 
						if( empty($value["period"]) ) continue;
						$row = count($value["period"]);
						if( $row <= 10 ) $row++;
					?>
					<table class="table-bordered mtl" width="100%" style="overflow-x: auto; display: block; width: 100%; -webkit-overflow-scrolling: touch;  -ms-overflow-style: -ms-autohiding-scrollbar;">
						<thead>
							<tr style="background-color: #003;color:#fff;">
								<th width="5%">CODE</th>
								<th width="30%">PROGRAM</th>
								<th width="10%">Period</th>
								<th width="5%">Adult</th>
								<th width="5%">Child</th>
								<th width="5%">Child NB</th>
								<th width="5%">Infant</th>
								<th width="5%">Joinland</th>
								<th width="5%">Sing Charge</th>
								<th width="5%">Com</th>
								<th width="3%">ที่นั่ง</th>
								<th width="3%">จอง</th>
								<th width="3%">รับได้</th>
								<th width="3%">Book</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($value['period'] as $i => $period) {
								// print_r($value);die;
								$dateStr = $this->fn->q('time')->str_event_date($period["date_start"], $period["date_end"]);
								?>
								<tr style="height: 0px;">
									<?php if( $i == 0 ) { ?>
									<td rowspan="<?=$row?>" valign="top" class="tac fwb"><?=$value['code']?></td>
									<td rowspan="<?=$row?>" valign="top">
										<ul>
											<li><span class="fwb"><?=$value["name"]?></span></li>
											<li><span>Airline : <?=$value["go_flight_code"]?></span></li>
											<li class="tac"><img target="_blank" src="<?=$value["image_cover_url"]?>" style="width: 250px;height: auto;"></li>
											<li class="tac mts mtm">
												<span><a href="<?=$value["image_cover_url"]?>" class="btn btn-green"><i class="icon-picture-o"></i> ดาวโหลดแบนเนอร์</a></span>
											</li>
											<?php if( !empty($value["url_word"]) ) { ?>
											<li class="tac mts mtm">
												<span><a href="<?=$value["url_word"]?>" class="btn btn-blue"><i class="icon-file-word-o"></i> ดาวโหลดโปรแกรม (WORD)</a></span>
											</li>
											<?php } ?>
											<?php if( !empty($value["url_pdf"]) ) { ?>
											<li class="tac mts mtm">
												<span><a href="<?=$value["url_pdf"]?>" class="btn btn-red"><i class="icon-file-pdf-o"></i> ดาวโหลดโปรแกรม (PDF)</a></span>
											</li>
											<?php } ?>
										</ul>
									</td>
									<?php } ?>
									<td class="tac"><?=$dateStr?></td>
									<td class="tar"><span class="mrs"><?=number_format($period["price_1"])?>.-</span></td>
									<td class="tar"><span class="mrs"><?=number_format($period["price_2"])?>.-</span></td>
									<td class="tar"><span class="mrs"><?=number_format($period["price_3"])?>.-</span></td>
									<td class="tar"><span class="mrs"><?=number_format($period["price_4"])?>.-</span></td>
									<td class="tar"><span class="mrs"><?=number_format($period["price_5"])?>.-</span></td>
									<td class="tar"><span class="mrs"><?=number_format($period["single_charge"])?>.-</span></td>
									<td class="tac">
										<?=number_format($period["com_company_agency"])?> + <?=number_format($period["com_agency"])?>
									</td>
									<td class="tac"><?=number_format($period["qty_seats"])?></td>
									<td class="tac"><?=number_format($period["booking"]["booking"])?></td>
									<td class="tac" style="background-color: #43d967;">
										<?php 
										if ($period['balance']<=0){ 
                                			if( $period['booking']['payed'] < $period['seats'] ){
                                				echo '<span class="fwb fcw">'.($period['balance']<=0  ? 'W/L': number_format($period['balance'])).'</span>';
                                			}
                                			else{
                                				echo '<span class="fcr fwb">เต็ม</span>';
                                			}
                                        } else {
                                    		if( $period['status'] == 1  ){
                                    			echo '<span class="fwb fcw">'.($period['balance']<=0  ? 'W/L': number_format($period['balance'])).'</span>';
                                    		}
                                    		else{
                                    			if( $period['status'] == 2 ){
                                    				echo '<span class="fcr fwb">เต็ม</span>';
                                    			}
                                    			else{
                                    				echo '<span class="fcr fwb">ปิดทัวร์</span>';
                                    			}
                                    			// elseif( $period['status'] == 3 ){
                                    			// 	echo '<span class="fcr">ปิดทัวร์</span>';
                                    			// }
                                    			// elseif( $period['status'] == 9 ){
                                    			// 	echo '<span class="fcr">ระงับ</span>';
                                    			// }
                                    			// elseif( $period['status'] == 10 ){
                                    			// 	echo '<span class="fcr">ตัดตั๋ว</span>';
                                    			// }
                                    		}
                                		}
										?>
									</td>
									<td class="tac">
										<?php if ($period['balance']<=0){ 

                                			if( $period['booking']['payed'] < $period['seats'] ){

                                				echo '<a href="'.URL.'booking/register/'.$period['id'].'" class="btn btn-orange btn-submit">W/L</a>';
                                			}
                                			else{
                                				echo '<span class="btn btn-danger disabled">เต็ม</span>';
                                			}
                                        

                                        } else {
                                    		
                                    		if( $period['status'] == 1  ){
                                    			echo '<a href="'.URL.'booking/register/'.$period['id'].'" class="btn btn-success btn-submit">จอง</a>';
                                    		}
                                    		else{
                                    			echo '<span class="btn btn-danger disabled"><i class="icon-lock"></i></span>';
                                    		}

                                		} ?>
									</td>
								</tr>
								<?php 
								if( ($row-1)<= 10 && $i==($row-2) ){
									echo '<tr>
												<td colspan="13"></td>
										  </tr>';
								}
							} 
							?>
						</tbody>
					</table>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>