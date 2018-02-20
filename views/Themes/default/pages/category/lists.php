<section id="product" class="module parallax product" style="background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg);padding-top: 0;padding-bottom: 0">
	<div class="article-single" style="padding-top: 180px; ">


<!-- menu tour -->
<?php require WWW_VIEW .'Themes/'.$this->getPage('theme').'/layouts/tour/menu.php'; ?>
<!-- end: menu tour -->

	<div class="container post">
		<header class="page-title">
			<!-- <h2><?=$this->item['name']?></h2> -->
			<div style="display: inline-block;width: 300px;"><img src="<?=IMAGES?>demo/title/<?=$this->item['id']?>.jpg" style="width:100%;border-radius: 2mm;"></div>
			<!-- <h2>MYANMAR</h2> -->
		</header>
	
		<?php foreach ($this->results['lists'] as $item) { 
			//print_r($item['url_word']);
			if( empty($item['period']) )  continue;
			
			$item_url_word = '';
            if(!empty($item['url_word'])){
                $item_url_word = ' href="'.$item['url_word'].'"';
            }

            $item_url_pdf = '';
            if(!empty($item['url_pdf'])){
                $item_url_pdf = ' href="'.$item['url_pdf'].'"';
            } 

		?>
		<div class="clearfix product-item product-period-item" style="background-color: #fff;padding: 12px;border-radius: 10px;margin-top: 40px;margin-bottom: 40px;color: #000">
			<div class="product-header clearfix">
				
				<h2 class="product-name fwb"><a href="<?=$item['url']?>" title="<?=$item['name']?>" style="color:#822d2d;"><?=$item['name']?></a></h2>

				<ul class="product-meta fwb">
						<li class="">
							<strong>Code</strong>
							<span><?=$item['code']?></span>
						</li>
						<?php 
						// $midDay = $this->fn->q('time')->DateDiff($item['first_start_date'], $item['first_end_date'])+1;
						// $nightDay = $midDay - 2;
						// if( empty($nightDay) ){
						// 	$nightDay = "-";
						// }
						?>
						<!-- <li class="">
							<strong>ระยะเวลา</strong>
							<span><?=$midDay?>วัน <?=$nightDay?>คืน</span>
						</li> -->

						<li class="">
							<strong>ราคาเริ่มต้น</strong>
							<span><?=$item['price_str']?></span>
						</li>
						<li class="">
							<strong>สายการบิน</strong>
							<span><?=$item['air_name']?></span>
						</li>
					</ul>

			</div>
			
			<div class="clearfix">
				<div class="product-image-w clearfix">
					<div class="product-image"><?php

						if( !empty($item['image_cover_url']) ){ ?>
						<a href="<?=$item['url']?>" class="pic">
							<img class="img" src="<?=$item['image_cover_url']?>" alt="<?=$item['name']?>">
						</a>
						<?php } ?>
					</div>

					<?php if( !empty($item['remark']) ){ ?>
					<div class=" pam" style="background-color: #fff;color: #000">
						<h4 class="fwb">* ไฮไลท์</h4>
						<?=$item['remark']?>
					</div>
					<?php } ?>

					<?php if( !empty($item_url_word) || !empty($item_url_pdf) ) { ?>
					<div class="pam" style="background-color: #fff;color: #000">
						<h4 class="fwb">* ดาวโหลด</h4>
						<?php if( !empty($this->me) && !empty($item_url_word) ) { ?>
						<a<?=$item_url_word?> target="_blank" class="btn btn-blue btn-jumbo btn-block"><i class="icon-file-word-o"></i> WORD</a>
						<?php } ?>
						
						<?php 
						if( !empty($item_url_pdf) ) { ?>
						<a<?=$item_url_pdf?> target="_blank" class="btn btn-blue btn-jumbo btn-block"><i class="icon-file-pdf-o"></i> PDF</a>
						<?php } ?>
					</div>
					<?php } ?>

					<div class="product-plan">
						<h3>แผนการเดินทาง</h3>
						<ul class="travel-plan">
							<li>
								
						        <ul class="travel-plan-content">
						        	<li><div class="label"><span class="time">วันที่ 1</span></div><div class="data editor-text">กรุงเทพ-โฮจิมินห์-ฟานเทียต-ทะเลทรายมุยเน่</div></li>

						        	<li><div class="label"><span class="time">วันที่ 2</span></div><div class="data editor-text">มุยเน่-โฮจิมินห์-ทำเนียบอิสรภาพ-ไชน่าทาวน์-วัดเทียนห่าว-ตลาดเบนถัน-ล่องเรือแม่น้ำไซ่ง่อน</div></li>

						        	<li><div class="label"><span class="time">วันที่ 3</span></div><div class="data editor-text">โฮจิมินห์-กรุงเทพฯ</div></li>

						        </ul>

						    </li>
								
						</ul>
					</div>
				</div>

				<div class="product-content">

					<div class="product-program">
						<table class="product-program-tabel">
							<thead>
								<tr>
									<th class="name">ช่วงเวลาเดินทาง</th>
									<th class="price">ราคา</th>

									<?php if ( !empty($this->me) ) { ?>
                                    <th class="qty">ที่นั่ง</th>
                                    <?php } ?>
                                    <th class="qty">รับได้</th>
                                    <?php if ( !empty($this->me) ) { ?>
                                    <th class="actions"></th>
									<?php } ?>
									<?php if (!empty($this->me)) {?> 
										<th class="status">ใบเตรียมตัว</th>
									<?php } ?>
                                    
								</tr>
							</thead>

							<tbody>
								<?php foreach ($item['period'] as $key => $value) {
										
									// $url_pdf = ' disabled';
									// if(!empty($value['url_pdf'])){
									// 	$url_pdf = ' href="'.$value['url_pdf'].'"';
									// }
									//print_r($value);
								?>
								<tr>
									<td class="name<?=$value['booking']['payed'] == $value['seats'] ? " fcr": "" ?>"><?=$this->fn->q('time')->str_event_date($value['date_start'], $value['date_end'])?></td>
									<td class="price"><?=number_format($value['price_1'])?>.-</td>
				
									<?php if ( !empty($this->me) ) { ?>
									<td class="qty"><?=number_format($value['seats'])?></td>
									<?php } ?>
                                    <td class="qty fwb">
                                    	<?php

                                    	if ($value['balance']<=0){ 

                                			if( $value['booking']['payed'] < $value['seats'] ){

                                				echo $value['balance']<=0  ? '<span class="">W/L</span>': number_format($value['balance']);
                                			}
                                			else{
                                				echo '<span class="fcr">เต็ม</span>';
                                			}
                                        

                                        } else {
                                    		
                                    		if( $value['status'] == 1  ){
                                    			echo $value['balance']<=0  ? '<span class="">W/L</span>': number_format($value['balance']);
                                    		}
                                    		else{
                                    			echo '<span class="fcr">เต็ม</span>';
                                    		}

                                		}
                                    	?>
                                    </td> 
									<!-- <td class="actions"><a>ดาวน์โหลด</a></td> -->
                                   <?php if ( !empty($this->me) ) { ?>
                                    <td style="white-space: nowrap;">

                                		<?php if ($value['balance']<=0){ 

                                			if( $value['booking']['payed'] < $value['seats'] ){

                                				echo '<a href="'.URL.'booking/register/'.$value['id'].'" class="btn btn-orange btn-submit">W/L</a>';
                                			}
                                			else{
                                				echo '<span class="btn btn-danger disabled">เต็ม</span>';
                                			}
                                        

                                        } else {
                                    		
                                    		if( $value['status'] == 1  ){
                                    			echo '<a href="'.URL.'booking/register/'.$value['id'].'" class="btn btn-success btn-submit">จอง</a>';
                                    		}
                                    		else{
                                    			echo '<span class="btn btn-danger disabled">เต็ม</span>';
                                    		}

                                		} ?>
                                  		<!-- <a<?=$url_word?> class="btn-icon" target="_blank"><i class="icon-file-word-o"></i></a> -->
                                	</td>
                                	<td class="tac">
                                		<?php if( $value['url_word'] != "http://admin.probookingcenter.com/admin/upload/travel/" ) { ?>
                                		<a href="<?=$value['url_word']?>" class="btn-icon" target="_blank"><i class="icon-file-word-o"></i></a>
                                		<?php }
                                		else if($value['url_pdf']!="http://admin.probookingcenter.com/admin/upload/travel/"){ ?>
                                			<a href="<?=$value['url_pdf']?>" target="_blank" class="btn-icon"><i class="icon-file-pdf-o"></i></a>
                                		<?php }else{
											echo('-');
										} ?> 
                                	</td>
                                    <?php }else{
                                    	//echo '<td class="tac" style="white-space: nowrap;"><a'.$url_pdf.' class="btn-icon" target="_blank"><i class="icon-file-pdf-o"></i></a></td>';
                                    } // end: if login ?>
								</tr>
								<?php } // end: for period ?>
							</tbody>
						</table>
					</div>
		
				</div>

			</div>
			
			<footer class="clearfix mtm hidden_elem">
				<div class="lfloat" style="max-width: 780px">
					<?php if( !empty($item['remark']) ){ ?>
					<div class="uiBoxYellow pam"><?=$item['remark']?></div>
					<?php } ?>
				</div>
				<div class="rfloat tar">
					<span class="btn btn-blue"><i class="icon-arrow-right mrs"></i>ดาวน์โหลดโปรแกรมทัวร์</span>
					<div class="mtm"></div>
					<a href="<?=$item['url']?>" style="color: #000">ดูรายละเอียดเพิ่มเติม<i class="icon-arrow-right mls"></i></a>
				</div>
			</footer>
			
		</div>

		<?php } ?>
	</div>
	</div>
</section>
						