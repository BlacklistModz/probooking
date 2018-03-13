<div id="mainContainer" class="clearfix" data-plugins="main">
	<div role="main">
		<div class="pal">
			<h3 class="fwb"><i class="icon-users mrs"></i>Team Sale Monitor</h3>
			<div class="uiBoxWhite mts pam" data-plugins="reportTeamSale">
				<div class="clearfix">
					<ul class="lfloat">
						<li style="display:inline-block;">
							<label for="month" class="label fwb">Period</label>
							<select name="month" class="inputtext">
								<option value="">- ทั้งหมด -</option>
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
							<label for="team" class="label fwb">Team</label>
							<select name="team" class="inputtext">
								<option value="">- ทั้งหมด -</option>
								<?php 
								foreach ($this->teams['lists'] as $key => $value) {
									echo '<option value="'.$value["id"].'">'.$value["name"].'</option>';
								}
								?>
							</select>
						</li>
						<li style="display:inline-block;">
							<label for="sale" class="label fwb">เซลล์</label>
							<select name="sale" class="inputtext">
								<option value="">- ทั้งหมด -</option>
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
							<a class="btn btn-green js-search" style="margin-top: -1.5mm;"><i class="icon-search"></i></a>
						</li>
					</ul>
				</div>
				<div class="mtm">
					<h3><i class="icon-list mrs"></i>รายงาน</h3>
				</div>
				<div class="uiBoxWhite mts pam">
					<div id="reportTeamSale"><h3 class="tac fcr">-- กรุณาทำรายการ --</h3></div>
				</div>
			</div>
		</div>
	</div>
</div>