<?php
$a = array();
$a[] = array('label'=>'วันเดินทางไป', 'key' => 'per_date_start');
$a[] = array('label'=>'วันเดินทางกลับ', 'key' => 'per_date_end');
$a[] = array('label'=>'Bus', 'key'=>'bus_no');
$a[] = array('label'=>'จำนวนที่นั่ง', 'key'=>'bus_qty');
$a[] = array('label'=>'ราคา', 'key'=>'per_price_1');
$a[] = array('label'=>'คอมมิชชั่น', 'key'=>'per_com_agency');
$a[] = array('label'=>'สถานะ', 'key'=>'status');
?>
<section class="mbl">
	<header class="clearfix">
		<h2 class="title"><i class="icon-plane mrs"></i>ข้อมูลพีเรียด</h2>
	</header>
	
	<table cellspacing="0"><tbody><?php

	foreach ($a as $key => $value) {
		
		if( empty($this->item[ $value['key'] ]) ) continue;

		if( $value['key'] == 'per_date_start' || $value['key'] == 'per_date_end' ){
			$date = date("d", strtotime( $this->item[ $value['key'] ] ));
			$month = $this->fn->q('time')->month( date("n", strtotime($this->item[ $value['key'] ])) );
			$year = date("Y", strtotime($this->item[ $value['key'] ])) + 543;

			$val = "{$date} {$month} {$year}";
		}
		elseif( $value['key'] == 'bus_qty' ){
			$val = $this->item["bus_qty"]."/".$this->item["booking"]['booking'];
		}
		elseif( $value['key'] == 'per_com_agency' ){
			$val = number_format($this->item['per_com_agency']).'+'.number_format($this->item['per_com_company_agency']);
		}
		elseif( $value['key'] == 'status' ){
			$val = '<div class="status-label '.$this->item['status_arr']['cls'].'">'.$this->item['status_arr']['name'].'</div>';
		}
		else{
			if( is_numeric($this->item[ $value['key'] ]) ){
				$val = number_format($this->item[ $value['key'] ]);
			}
			else{
				$val = $this->item[ $value['key'] ];
			}
		}
		echo '<tr>'.
			'<td class="label">'.$value['label'].'</td>'.
			'<td class="data">'.$val.'</td>'.
		'</tr>';
	}
	?></tbody></table>
					
</section>