<div id="mainContainer" class="clearfix" data-plugins="main">
	<div role="main">
		<div class="pal">
			<h3 class="fwb"><i class="icon-desktop mrs"></i>Recevied Monitor</h3>
			<div class="uiBoxWhite mts pam" data-plugins="reportDaily">
				<div class="clearfix">
					<ul class="lfloat">
						<li style="display:inline-block;">
							<label for="year" class="label fwb">Year</label>
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
							<label for="country_id" class="label fwb">Country</label>
							<select name="country_id" class="inputtext">
								<?php 
								foreach ($this->country as $key => $value) {
									echo '<option value="'.$value["id"].'">'.$value["name"].'</option>';
								}
								?>
							</select>
						</li>
						<li style="display:inline-block;">
							<label for="ser_id" class="label fwb">Series</label>
							<select name="ser_id" class="inputtext">
							</select>
						</li>
					</ul>
				</div>
				<div class="clearfix mtm">
					<ul>
						<li style="display:inline-block;">
							<label for="status" class="label fwb">Period Status</label><br/>
							<?php foreach ($this->status as $key => $value) { 
								$chk = '';
								if( $value["id"] == 1 || $value["id"] == 2 ) $chk = 'checked="1"';
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
				<div id="reportMonitor"><h3 class="tac fcr">-- กรุณาทำรายการ --</h3></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.js-search').click(function(){

		$("#reportMonitor").html( '<div class="tac"><div class="loader-spin-wrap" style="display:inline-block;"><div class="loader-spin"></div></div></div>' );

		var year = $('[name=year]').val();
		var country = $('[name=country_id]').val();
		var series = $('[name=ser_id]').val();
		var status = [];
		$('input[name^="status"]').each(function() {
			if( this.checked ){
				status.push($(this).val());
			}
		});

		$.ajax({
			type: "POST",
			url: Event.URL + 'reports/monitor/',
			data: { year:year, country:country, series:series, status:status }
		}).done(function( html ) {
			$("#reportMonitor").html( html );
		});
	});
</script>