<?php 
$a = array();
$a[] = array('label'=>'ยอดเงินรวม', 'key'=>'booking');
$a[] = array('label'=>'ยอดเงินที่ได้รับ', 'key'=>'received');
$a[] = array('label'=>'คงเหลือ', 'key'=>'balance');
?>
<section class="mbl">
	<header class="clearfix">
		<h2 class="title"><i class="icon-money mrs"></i>การชำระเงิน</h2>
	</header>
	
	<table cellspacing="0"><tbody>
		<?php 
		foreach ($a as $key => $value) {

			if( empty($this->item['payment'][ $value['key'] ]) ) continue;

			$val = number_format($this->item['payment'][ $value['key'] ]);

			echo '<tr>'.
			'<td class="label">'.$value['label'].'</td>'.
			'<td class="data">'.$val.'</td>'.
			'</tr>';
		}
		?>
	</tbody></table>
</section>