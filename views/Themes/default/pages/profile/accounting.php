<section id="product" class="module parallax product" style="padding-top: 180px; background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg)">
	<div class=" container clearfix">
		<div class="primary-content post">
			<div class="card">
				<header class="header clearfix">
					<h1 class="tac"><i class="icon-credit-card"></i> Accounting Management System</h1>
					<h3 class="tac">บริษัท : <?=$this->me['company_name']?></h3>
				</header>
				<table class="table-bordered" width="100%">
					<thead>
						<tr>
							<th>Date Due</th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="card">
				<div class="clearfix">
					<ul class="rfloat mbm" ref="control">
						<li>
							<label for="date" class="label fwb">วันที่ : </label>
							<input type="date" class="inputtext" name="date" data-plugins="datepicker" value="" style="display:inline;">
						</li>
						<li>
							<label for="status" class="label fwb">เซลล์ : </label>
							<select ref="selector" class="inputtext" name="agency" style="display:inline;">
								<option value="">-- ทั้งหมด --</option>
								<?php foreach ($this->sales['lists'] as $key => $value) {
									$sel = '';
									if( $this->agen_id == $value["id"] ) $sel = ' selected="1"';
									echo '<option'.$sel.' value="'.$value["id"].'">'.$value["fullname"].'</option>';
								} ?>
							</select>		
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>