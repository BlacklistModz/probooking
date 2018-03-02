<div class="mts">
	<?php foreach ($this->booking["lists"] as $key => $value) { ?>
	<div class="uiBoxWhite pal clearfix mts">
		<h3><?=$value["user_fname"]?> <?=$value["user_lname"]?></h3>
		<ul class="lfloat mll mts">
			<li class="mt">
				<span class="fwb">INVOICE NO :</span> <?=$value["invoice_code"]?>
			</li>
			<li class="mt">
				<span class="fwb">Agent booking :</span> <?=$value["agen_com_name"]?>
			</li>
			<li class="mt">
				<span class="fwb">Sale Agent booking : </span> <?=$value["agen_fname"]?> <?=$value["agen_lname"]?>
			</li>
			<li class="mt">
				<span class="fwb">Customer Name :</span>
			</li>
			<li class="mt">
				<span class="fwb">Customer Tal :</span>
			</li>
		</ul>
		<div class="rfloat">
		</div>

		<div class="nfloat">
			<div class="table-responsive">
				<table class="table-bordered" width="100%">
					<thead>
						<tr>
							<th>asdf</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>
</div>