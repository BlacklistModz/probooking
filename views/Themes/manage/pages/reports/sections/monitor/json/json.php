<style>
.ReportSummary_numberItem>div:first-child {
	font-size: 24px;
	line-height: 45px;
}
</style>
<div class="uiBoxOverlay pal">
	<div class="clearfix">
		<h3 class="fwb">พบ <?=$this->results["total"]?> พีเรียส</h3>
		<div class="ReportSummary_numberItem subtotal-text">
			<!-- <div>รวมเป็นเงิน</div> -->
			<div>ที่นั่งทั้งหมด : 
				<span class="value" style="background-color:blue; border-radius: 1mm; color:#fff; padding: 1mm;">
					<?=number_format($this->results["total_seat"])?>
				</span>
			</div>
		</div>

		<div class="ReportSummary_numberItem subtotal-text">
			<!-- <div>รวมเป็นเงิน</div> -->
			<div>จองแล้วทั้งหมด : 
				<span class="value" style="background-color:green; border-radius: 1mm; color:#fff; padding: 1mm;">
					<?=number_format($this->results["total_payment"])?>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="clearfix mtm">
	<table class="table-bordered" width="100%">
		<thead>
			<th width="12%">Period</th>
			<th width="10%">Code</th>
			<th width="2%">Bus</th>
			<th width="2%">Seats</th>
			<th width="2%">Book</th>
			<?php 
			for($i=1;$i<=12;$i++){
				echo '<th width="6%" class="tac">'.$this->fn->q('time')->month($i,true,'en').'</th>';
			}
			?>
		</thead>
		<tbody>
			<?php foreach ($this->results["lists"] as $key => $value) {

				$sty = '';
				if( empty($value["book_qty"]) ){
					$sty = 'background-color: red; color: white;';	
				}
				elseif( ($value["bus_qty"] > $value["book_qty"]) && ($value["book_qty"] > 0) ){
					$sty = 'background-color: blue; color: white;';	
				}
				else{
					$sty = 'background-color: green; color: white;';
				}
			?>
			<tr>
				<td class="tac"><?= $this->fn->q('time')->str_event_date( $value["per_date_start"], $value["per_date_end"] ) ?></td>
				<td class="tac"><?=$value["ser_code"]?></td>
				<td class="tac"><?=$value["bus_no"]?></td>
				<td class="tac" style="<?=$sty?>"><?=$value["bus_qty"]?></td>
				<td class="tac" style="<?=$sty?>"><?= !empty($value["book_qty"]) ? $value["book_qty"] : "-" ?></td>
				<?php 
				for($i=1;$i<=12;$i++){
					$month = date("Y", strtotime($this->results["options"]["start"]))."-".$i;
					echo '<td class="tac">'.(!empty($value[$month]["total"]) ? number_format($value[$month]["total"]) : "-").'</td>';

					if( empty($total[$i]) ){
						$total[$i] = 0;
					}
					$total[$i] += !empty($value[$month]["total"]) ? $value[$month]["total"] : 0;
				}
				?>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5">รวม</th>
				<?php 
				for($i=1;$i<=12;$i++){
					echo '<th>'.(!empty($total[$i]) ? number_format($total[$i]) : "-").'</th>';
				}
				?>
			</tr>
		</tfoot>
	</table>
</div>