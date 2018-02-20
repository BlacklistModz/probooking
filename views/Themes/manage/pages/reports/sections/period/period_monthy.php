<div id="mainContainer" class="clearfix" data-plugins="main">
	<div role="main">
		<div class="pal">
			<h3 class="fwb"><i class="icon-calendar mrs"></i>Monthy Period Report</h3>
			<div class="uiBoxWhite mts pam" data-plugins="reportDaily">
				<div class="clearfix">
					<ul class="lfloat">
						<li style="display:inline-block;">
                        <label for="month" class="label fwb">Period</label>
							<select name="month" class="inputtext">
								<?php 
                                    for($i=1;$i<=12;$i++){
                                        $sel = '';
                                        if( $i == date("n") ) $sel = 'selected="1"';
                                        echo'<option '.$sel.' value="'.sprintf("%02d", $i).'">'.$this->fn->q('time')->month($i, true).'</option>';
                                    }
								?>
							</select>
						</li>
                        <li style="display:inline-block;">
                        <label for="year" class="label fwb"></label>
							<select name="year" class="inputtext">
								<?php 
                                    for($i=0;$i<5;$i++){
                                        $sel = '';
                                        $year = date("Y")-$i;
                                        if( date("Y") == $year ) $sel = 'selected="1"';
                                        echo '<option '.$sel.' value="'.$year.'">'.($year + 543).'</option>';
                                    }
								?>
							</select>
							
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
							</select>
						</li>
						<li style="display:inline-block;">
							<label for="user_id" class="label fwb">เซลล์</label>
							<select name="user_id" class="inputtext">
								<option value="">- ทั้งหมด -</option>
								<?php 
								foreach ($this->sales as $key => $value) {
									echo '<option value="'.$value["id"].'">'.$value["name"].'</option>';
								}
								?>
							</select>
						</li>
					</ul>
				</div>
				<div class="clearfix mtm">
					<ul>
						<li style="display:inline-block;">
							<label for="agency_company_id" class="label fwb">Agent</label>
							<select name="agency_company_id" class="inputtext">
								<option value="">- ทั้งหมด -</option>
								<?php 
								foreach ($this->company['lists'] as $key => $value) {
									echo '<option value="'.$value["com_id"].'">'.$value["com_name"].'</option>';
								}
								?>
							</select>
						</li>
						<li style="display:inline-block;">
							<label for="agen_id" class="label fwb">Sale Agent</label>
							<select name="agen_id" class="inputtext">
								<option value="">- เลือกบริษัท -</option>
							</select>
						</li>
					</ul>
				</div>
				<div class="clearfix mtm">
					<ul>
						<li style="display:inline-block;">
							<label for="status" class="label fwb">สถานะ</label>
							<?php foreach ($this->status as $key => $value) { 
								$chk = '';
								if( $value["id"] == 20 || $value["id"] == 25 || $value["id"] == 30 || $value["id"] == 35 ) $chk = 'checked="1"';
								?>
								<label class="checkbox mrs mrm">
									<input type="checkbox" <?=$chk?> name="status[]" value="<?=$value["id"]?>"> <?=$value["name"]?>
								</label>
							<?php } ?>
						</li>
						<li style="display:inline-block;">
							<button class="btn btn-green js-search" style="margin-top: -1.5mm;"><i class="icon-search"></i></button>
						</li>
					</ul>
				</div>
			</div>
			<div class="mtm">
				<h3><i class="icon-list mrs"></i>รายงาน</h3>
			</div>
			<div class="uiBoxWhite mts pam">
				<div id="reportPeriodMonthy"><h3 class="tac fcr">-- กรุณาทำรายการ --</h3></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.js-search').click(function(){

		// var date = $('[name=date]').val();
        var month = $('[name=month]').val();
        var year = $('[name=year]').val();
		var country = $('[name=country_id]').val();
		var series = $('[name=ser_id]').val();
		var sale = $('[name=user_id]').val();
		var company = $('[name=agency_company_id]').val();
		var agency = $('[name=agen_id]').val();
		var status = [];
		$('input[name^="status"]').each(function() {
			if( this.checked ){
				status.push($(this).val());
			}
		});

		$.ajax({
			type: "POST",
			url: Event.URL + 'reports/period_monthy/',
			data: { month:month, year:year, country:country, series: series, sale:sale, company:company, agency:agency, status:status }
		}).done(function( html ) {
            
			$("#reportPeriodMonthy").html( html );
		});
	});
</script>