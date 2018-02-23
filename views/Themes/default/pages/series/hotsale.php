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
					<h1 class="tac" style="color:red;"><i class="icon-fire"></i> โปรดันขาย <i class="icon-fire"></i></h1>
				</header>
				<div class="clearfix">
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
							<?php foreach ($this->results as $key => $value) { 
								$dateStr = $this->fn->q('time')->str_event_date($value["date_start"], $value["date_end"]);

								$price_1 = $value["price_1"];
								$price_2 = $value["price_2"];
								$price_3 = $value["price_3"];

								if( $value["discount"] != 0.00 ){
									$price_1 = $value["price_1"]!=0.00 
											   ? '<strike style="color:red;">'.number_format($value["price_1"]).'</strike> <label class="fwb">'.number_format($value["price_1"] - $value["discount"]).'.-</label>' 
											   : '-';
									$price_2 = $value["price_2"]!=0.00 
											   ? '<strike style="color:red;">'.number_format($value["price_2"]).'</strike> <label class="fwb">'.number_format($value["price_2"] - $value["discount"]).'.-</label>' 
											   : '-';
									$price_3 = $value["price_3"]!=0.00 
											   ? '<strike style="color:red;">'.number_format($value["price_3"]).'</strike> <label class="fwb">'.number_format($value["price_3"] - $value["discount"]).'.-</label>' 
											   : '-';
								}
								else{
									$price_1 = number_format($value["price_1"]).'.-';
									$price_2 = number_format($value["price_2"]).'.-';
									$price_3 = number_format($value["price_3"]).'.-';
								}
							?>
							<tr>
								<td class="tac fwb"><?=$value["ser_code"]?></td>
								<td><span class="mls"><?=$value["ser_name"]?></span></td>
								<td class="tac"><?=$dateStr?></td>
								<td class="tar"><span class="mrs"><?= $price_1 ?></span></td>
								<td class="tar"><span class="mrs"><?= $price_2 ?></span></td>
								<td class="tar"><span class="mrs"><?= $price_3 ?></span></td>
								<td class="tar"><span class="mrs"><?= $value["price_4"]!=0.00 ? number_format($value["price_4"]).'.-' : '-' ?></span></td>
								<td class="tar"><span class="mrs"><?= $value["price_5"]!=0.00 ? number_format($value["price_5"]).'.-' : '-' ?></span></td>
								<td class="tar"><span class="mrs"><?=number_format($value["single_charge"])?>.-</span></td>
								<td class="tac">
									<?=number_format($value["com_agency"])?> + <?=number_format($value["com_company_agency"])?>
								</td>
								<td class="tac"><?=number_format($value["bus_qty"])?></td>
								<td class="tac"><?=number_format($value["booking"]["booking"])?></td>
								<td class="tac" style="background-color: #43d967;">
										<?php 
										if ($value['balance']<=0){ 
                                			if( $value['booking']['payed'] < $value['seats'] ){
                                				echo '<span class="fwb fcw">'.($value['balance']<=0  ? 'W/L': number_format($value['balance'])).'</span>';
                                			}
                                			else{
                                				echo '<span class="fcr fwb">เต็ม</span>';
                                			}
                                        } else {
                                   		if( $value['status'] == 1  ){
                                   			echo '<span class="fwb fcw">'.($value['balance']<=0  ? 'W/L': number_format($value['balance'])).'</span>';
                                   		}
                                  		else{
                                   			if( $value['status'] == 2 ){
                                   				echo '<span class="fcr fwb">เต็ม</span>';
                                   			}
                                   			else{
                                   				echo '<span class="fcr fwb">ปิดทัวร์</span>';
                                   			}
                                   			// elseif( $value['status'] == 3 ){
                                   			// 	echo '<span class="fcr">ปิดทัวร์</span>';
                                   			// }
                                   			// elseif( $value['status'] == 9 ){
                                   			// 	echo '<span class="fcr">ระงับ</span>';
                                   			// }
                                    		// elseif( $value['status'] == 10 ){
                                    		// 	echo '<span class="fcr">ตัดตั๋ว</span>';
                                    		// }
                                    	}
                                	}
									?>
								</td>
								<td class="tac">
									<?php if ($value['balance']<=0){ 
                                		if( $value['booking']['payed'] < $value['seats'] ){
                                			echo '<a href="'.URL.'booking/register/'.$value['id'].'/'.$value["bus_no"].'" class="btn btn-orange btn-submit">W/L</a>';
                                		}
                                		else{
                                			echo '<span class="btn btn-danger disabled">เต็ม</span>';
                                		}
                                    } else {
                                    	if( $value['status'] == 1  ){
                                    		echo '<a href="'.URL.'booking/register/'.$value['id'].'/'.$value["bus_no"].'" class="btn btn-success btn-submit">จอง</a>';
                                    	}
                                    		else{
                                    		echo '<span class="btn btn-danger disabled"><i class="icon-lock"></i></span>';
                                    	}
                                	} ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include("sections/footer.php"); ?>