<?php

// print_r($this->item);die;

$a = array();
$a[] = array('label'=>'ชื่อโรงแรม', 'key' => 'per_hotel');
$a[] = array('label'=>'เบอร์โทรโรงแรม', 'key'=>'per_hotel_tel');
$a[] = array('label'=>'วันเดินทางถึง', 'key'=>'arrival_date');
?>
<section class="mbl">
	<header class="clearfix">
		<h2 class="title"><i class="icon-bed mrs"></i>ข้อมูลโรงแรม</h2>
		<a data-plugins="dialog" href="<?=URL?>products/edit_period_hotel/<?=$this->item['per_id']?>" class="btn-icon btn-edit"><i class="icon-pencil"></i></a>
	</header>
	
	<table cellspacing="0"><tbody><?php

	foreach ($a as $key => $value) {
		
		// if( empty($this->item[ $value['key'] ]) ) continue;
		$val = !empty($this->item[ $value['key'] ]) ? $this->item[ $value['key'] ] : "-";

		if( $value["key"] == "arrival_date" && !empty($this->item[ $value["key"] ]) ){
			$val = $this->fn->q('time')->DateTH( $this->item[ $value["key"] ] );
		}

		echo '<tr>'.
			'<td class="label">'.$value['label'].'</td>'.
			'<td class="data">'.$val.'</td>'.
		'</tr>';
	}
	?></tbody></table>
					
</section>