<div class="product-program">
	<table>
		<thead>
			<tr>
				<th class="name">ช่วงเวลาเดินทาง</th>
				<th class="price">ราคา</th>

				<?php if ( !empty($this->me) ) { ?>
				<th class="qty">ที่นั้ง</th>
				<?php } ?>
				<th class="qty">รับได้</th>
				<?php if( !empty($this->me) ) { ?>
				<th class="status">ใบเตรียมตัว</th>
                <th class="actions"></th>
                <?php } ?>
                
			</tr>
		</thead>
		
		<!-- lists period -->
		<tbody>
			<?php foreach ($this->item['period'] as $key => $value) { ?>
			<tr>
				<td class="name"><?=$this->fn->q('time')->str_event_date($value['date_start'], $value['date_end'])?></td>
				<td class="price"><?=number_format($value['price_1'])?>.-</td>

				<?php if ( !empty($this->me) ) { ?>
				<td class="qty"><?=number_format($value['seats'])?></td>
				<?php } ?>
                <td class="qty fwb">
                <?php
									
                // if( $value['booking']['payed'] < $value['seats'] ){
                // 	echo $value['balance']<=0  ? '<span class="">W/L</span>': number_format($value['balance']);
                // }
                // else{
                // 	echo '<span class="fcr">เต็ม</span>';
                // }
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
										
                ?></td> 
				<!-- <td class="actions"><a>ดาวน์โหลด</a></td> -->
				<?php if ( !empty($this->me) ) { ?>
				<td class="tac">
                	<a<?=$url_pdf?> class="btn-icon" target="_blank"><i class="icon-file-pdf-o"></i></a>
            	</td>
                <td style="white-space: nowrap;">

            		<?php if ($value['balance']<=0){ 

            			if( $value['booking']['payed'] < $value['seats'] ){

            				echo '<a href="'.URL.'booking/register/'.$value['id'].'" class="btn btn-orange btn-submit">W/L</a>';
						}
							
            			else{
            				echo '<span class="btn btn-danger disabled">เต็ม</span>';
            			}
                    

                    } else {
                		
                		// echo '<a href="'.URL.'booking/register/'.$value['id'].'" class="btn btn-success btn-submit">จอง</a>';
                    	if( $value['status'] == 1  ){
                    		echo '<a href="'.URL.'booking/register/'.$value['id'].'" class="btn btn-success btn-submit">จอง</a>';
                    	}
                    	else{
                    		echo '<span class="btn btn-danger disabled">เต็ม</span>';
                    	}

            		} ?>

				</td>
				
                <?php } // end: if login ?>
			</tr>
			<?php } // end: for period ?>

		</tbody>
		<!-- end: lists period-->
	</table>
</div>