<style type="text/css">
	.discount {background-color: #51c6ea; color:#fff;}
	.fulltotal{background-color: #43d967; color:#fff;}
	.expired{background-color: #f47f7f; color:#fff;}
	.amount{background-color: #2b957a; color:#fff;}
</style>
<div class="clearfix mtl">
	<div class="lfloat">
		<h3><i class="icon-list mrs"></i>รายการจอง</h3>
	</div>
	<form class="rfloat form-search" method="GET">
		<input class="inputtext" type="text" id="search-query" placeholder="ค้นหา" name="q" autocomplete="off" value="<?= isset($_REQUEST["q"]) ? $_REQUEST["q"] : "" ?>">
		<span class="search-icon">
			<a class="icon-search"></a>
		</span>
	</form>
</div>
<?php if( !empty($this->booking['lists']) ) { ?>
<table class="mtm table-bordered" width="100%" style="background-color: #fff;">
	<thead>
		<tr>
			<th width="2%" class="pam">#</th>
			<th width="5%">สถานะ</th>
			<th width="8%">Booking No.</th>
			<th width="5%">จำนวน</th>
			<th width="6%">ยอดรวม</th>
			<th width="6%" class="fwb discount">ส่วนลด</th>
			<th width="6%" class="fulltotal">ยอดสุทธิ</th>
			<th width="7%">วันที่จอง</th>
			<th width="7%" class="expired">วันหมดอายุ</th>
			<th width="16%">ชื่อบริษัท</th>
			<th width="10%">Booking By</th>
			<th width="10%">Sale Contact</th>
			<th width="7%" class="amount">Amount Receive</th>
			<th width="5%">จัดการ</th>
		</tr>
	</thead>
	<tbody>
		<?php $num=0; foreach ($this->booking['lists'] as $key => $value) { $num++; ?>
			<tr>
				<td class="tac pas pam"><?=$num;?></td>
				<td class="tac"><span class="status-label status_<?=$value["status"]?>"><?=$value["book_status"]["name"]?></span></td>
				<td class="tac"><?=$value["book_code"]?></td>
				<td class="tac"><?=$value["book_qty"]?></td>
				<td class="tar"><span class="mrs"><?=number_format($value["book_total"])?></span></td>
				<td class="tar discount"><span class="mrs"><?= $value["book_discount"] != 0.00 ? number_format($value["book_discount"]) : "-" ?></span></td>
				<td class="tar fulltotal"><span class="mrs"><?=number_format($value["book_amountgrandtotal"])?></span></td>
				<td class="tac"><?=$this->fn->q('time')->DateTH($value["book_date"])?></td>
				<td class="tac expired">
					<?php 
					if( $value["book_due_date_deposit"] != "0000-00-00" && ($value["status"] == 20 || $value["status"] == 25)){
						echo $this->fn->q('time')->DateTH( $value["book_due_date_deposit"] );
					}
					else{
						echo $this->fn->q('time')->DateTH( $value["book_due_date_full_payment"] );
					}
					?>
				</td>
				<td class="tac"><?=$value["agen_com_name"]?></td>
				<td class="tac"><?=$value["agen_fname"]?> <?=$value["agen_lname"]?></td>
				<td class="tac"><?=$value["user_nickname"]?> (<?=$value["user_name"]?>)</td>
				<td class="tar amount"><span class="mrs"><?= number_format($value["book_receipt"]) ?></span></td>
				<td class="tac">
					<span class="gbtn">
						<a href="<?=URL?>" class="btn btn-blue btn-no-padding"><i class="icon-pencil"></i></a>
					</span>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php }
else{
	$label = 'ไม่พบข้อมูลการจอง';
	$icon = "remove";
	if( !empty($_REQUEST["q"]) ){
		$label = 'ไม่พบข้อมูลการค้นหา';
		$icon = "search";
	}
	echo '<div class="uiBoxWhite pal mtl"><h2 class="tac fcr"><i class="icon-'.$icon.' mrs"></i>'.$label.'</h2></div>';
} ?>