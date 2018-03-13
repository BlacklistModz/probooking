<style>
.ReportSummary_numberItem>div:first-child {
	font-size: 24px;
	line-height: 45px;
}
</style>
<div class="uiBoxOverlay pal">
	<div class="clearfix">
		<h3 class="fwb"><i class="icon-money mrs"></i>สรุปยอดรวม</span></h3>
		<?php if( empty($_REQUEST["team"]) ){ ?>
		<div class="ReportSummary_numberList mtm">
			<?php foreach ($this->team['lists'] as $key => $value) { ?>
			<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div class="uiBoxGray pas pam">
					<div>
						<?=$value["name"]?>
					</div>
					<div>
						ยอดรวม : <span class="value bg-success" style="padding: 1mm; border-radius: 1mm;">
							<?= !empty($this->results["total_team"][$value["id"]]) 
							? number_format($this->results["total_team"][$value["id"]]) 
							: "0" 
							?>฿
						</span>
					</div>
					<div>
						ยอดที่นั่ง : <span class="value bg-primary" style="padding: 1mm; border-radius: 1mm;">
							<?= !empty($this->results["total_book"][$value["id"]]) 
							? number_format($this->results["total_book"][$value["id"]]) 
							: "0" 
							?>
						</span>
					</div>
				</div>
			</div>
			<?php } 
		?>
		</div>
		<?php }elseif( empty($_REQUEST["sale"]) ){ ?>
		<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดรวมของทีม : 
					<span class="value" style="background-color:blue; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->results['total_team'][$_REQUEST["team"]])?>฿
					</span>
				</div>
			</div>
		</div>
		<?php }else{ ?>
		<div class="ReportSummary_numberItem subtotal-text">
				<!-- <div>รวมเป็นเงิน</div> -->
				<div>ยอดรวมของเซลล์ : 
					<span class="value" style="background-color:blue; border-radius: 1mm; color:#fff; padding: 1mm;">
						<?=number_format($this->results['total_sale'][$_REQUEST["sale"]])?>฿
					</span>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<div class="clearfix mtm">
	<h3 class="fwb"><i class="icon-list mrs"></i> ยอดขาย (บาท)</h3>
	<table class="table-bordered" width="100%">
		<thead>
			<tr style="background-color: #003; color:#fff;">
				<th width="14%">Sale</th>
				<th width="6%">Team</th>
				<?php 
				for($i=1;$i<=12;$i++){
					echo '<th width="6%" class="tac">'.$this->fn->q('time')->month($i,true,'en').'</th>';
				}
				?>
				<th width="8%" class="bg-success">Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->results["lists"] as $key => $value) { ?>
			<tr>
				<td class="tac pas"><?=$value["user_nickname"]?></td>
				<td class="tac"><?=$value["team_name"]?></td>
				<?php 
				for($i=1;$i<=12;$i++){
					$i = sprintf("%02d", $i);
					$total = !empty($value[$i]) ? number_format($value[$i]) : "-";
					echo '<td class="tac">'.$total.'</td>';
				}
				?>
				<td class="tac bg-success fwb"><?= number_format($this->results['total_sale'][$value["user_id"]]) ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2">รวม</th>
				<?php 
				for ($i=1; $i <= 12 ; $i++) { 
					$i = sprintf("%02d", $i);
					$amount = !empty($this->results["total_month"][$i]) ? number_format($this->results["total_month"][$i]) : "-";
					echo '<th class="fwb fcr">'.$amount.'</th>';
				}
				?>
				<th class="fwb fcr"><?= !empty($this->results["total"]) ? number_format($this->results["total"]) : "-" ?></th>
			</tr>
		</tfoot>
	</table>
</div>

<div class="clearfix mtm">
	<h3 class="fwb"><i class="icon-list mrs"></i> ยอดขาย (ที่นั่ง)</h3>
	<table class="table-bordered" width="100%">
		<thead>
			<tr style="background-color: #003; color:#fff;">
				<th width="14%">Sale</th>
				<th width="6%">Team</th>
				<?php 
				for($i=1;$i<=12;$i++){
					echo '<th width="6%" class="tac">'.$this->fn->q('time')->month($i,true,'en').'</th>';
				}
				?>
				<th width="8%" class="bg-primary">Seat</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->results["seats"] as $key => $value) { ?>
			<tr>
				<td class="tac pas"><?=$value["user_nickname"]?></td>
				<td class="tac"><?=$value["team_name"]?></td>
				<?php 
				for($i=1;$i<=12;$i++){
					$i = sprintf("%02d", $i);
					$total = !empty($value[$i]) ? number_format($value[$i]) : "-";
					echo '<td class="tac">'.$total.'</td>';
				}
				?>
				<td class="tac bg-primary fwb"><?= number_format($this->results['total_seat'][$value["user_id"]]) ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2">รวม</th>
				<?php 
				for ($i=1; $i <= 12 ; $i++) { 
					$i = sprintf("%02d", $i);
					$amount = !empty($this->results["total_month_seat"][$i]) ? number_format($this->results["total_month_seat"][$i]) : "-";
					echo '<th class="fwb fcr">'.$amount.'</th>';
				}
				?>
				<th class="fwb fcr"><?= !empty($this->results["seat"]) ? number_format($this->results["seat"]) : "-" ?></th>
			</tr>
		</tfoot>
	</table>
</div>