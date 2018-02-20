<style type="text/css">
	.uiPopover{
		display: block;
	}
</style>
<div id="mainContainer" class="clearfix" data-plugins="main">
	<div role="main">
		<div class="pal" data-plugins="reportRecevied">
			<h3 class="fwb"><i class="icon-money mrs"></i>Daily Recevied Report</h3>
			<div class="uiBoxWhite mts pam">
				<div class="clearfix">
					<ul class="lfloat">
						<li style="display:inline-block;">
							<label for="date" class="label fwb">วันที่รับเงิน</label>
							<input type="date" name="date" value="<?=date("Y-m-d")?>" data-plugins="datepicker">
						</li>
						<li style="display:inline-block;">
							<label for="country_id" class="label fwb">ประเทศ</label>
							<select name="country_id" class="inputtext">
								<option value="">- ทั้งหมด -</option>
								<?php 
								foreach ($this->country as $key => $value) {
									echo '<option value="'.$value["id"].'">'.$value["name"].'</option>';
								}
								?>
							</select>
						</li>
						<li style="display:inline-block;">
							<label for="ser_id" class="label fwb">ซีรีย์</label>
							<select name="ser_id" class="inputtext">
								<option value="">- ทั้งหมด -</option>
							</select>
						</li>
						<li style="display:inline-block;">
							<label for="bankbook_id" class="label fwb">บัญชีธนาคาร</label>
							<select name="bankbook_id" class="inputtext">
								<option value="">- ทั้งหมด -</option>
								<?php 
								foreach ($this->bank['lists'] as $key => $value) {
									echo '<option value="'.$value["bankbook_id"].'">'.$value["bankbook_code"].' - '.$value["bankbook_name"].'('.$value["bank_name"].')</option>';
								}
								?>
							</select>
						</li>
						<li style="display: inline-block;">
							<button class="btn btn-green js-search" style="margin-top: -1.5mm;"><i class="icon-search"></i></button>
						</li>
					</ul>
				</div>
			</div>
			<div class="mtm">
				<h3><i class="icon-list mrs"></i>รายงาน</h3>
			</div>
			<div class="uiBoxWhite mts pam">
				<div id="reportDaily"><h3 class="tac fcr">-- กรุณาทำรายการ --</h3></div>
			</div>
		</div>
	</div>
</div>