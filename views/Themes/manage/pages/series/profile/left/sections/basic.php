<?php

// print_r($this->item);die;

$a = array();
$a[] = array('label'=>'ชื่อซีรีย์', 'key' => 'name');
$a[] = array('label'=>'Code', 'key'=>'code');
$a[] = array('label'=>'ประเทศ', 'key'=>'country_name');
$a[] = array('label'=>'สายการบิน', 'key'=>'air_name');
?>
<section class="mbl">
	<header class="clearfix">
		<h2 class="title"><i class="icon-calendar mrs"></i>ข้อมูลซีรีย์</h2>
		<!-- <a data-plugins="dialog" href="<?=URL?>customers/edit_basic/<?=$this->item['id']?>" class="btn-icon btn-edit"><i class="icon-pencil"></i></a> -->
	</header>
	
	<table cellspacing="0"><tbody><?php

	foreach ($a as $key => $value) {
		
		if( empty($this->item[ $value['key'] ]) ) continue;
		$val = $this->item[ $value['key'] ];
		echo '<tr>'.
			'<td class="label">'.$value['label'].'</td>'.
			'<td class="data">'.$val.'</td>'.
		'</tr>';
	}
	?></tbody></table>
					
</section>