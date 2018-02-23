<div style="display: inline-block;width: 250px" class="mrl">
	<a href="<?=URL?>series/hotsale"><img src="<?=IMAGES?>icons/hotsale.png" style="width: 100%; border-radius: 5mm;"></a>
</div>
<?php foreach($this->country AS $i => $country) { ?>
<div style="display: inline-block;width: 250px;" class="mrl">
	<a href="<?=URL?>series/online/<?=$country["id"]?>"><img src="<?=IMAGES?>demo/title/<?=$country['id']?>.jpg" style="width:100%; border-radius: 5mm;"></a>
</div>
<?php } ?>

<div class="container clearfix mtl">
	<div class="card fwb">
		<form class="form-insert">
			<table width="100%">
				<tr>
					<td width="33.33%" valign="top">
						ค้นหาตามคีย์เวิร์ด
						<input type="text" name="q" class="inputtext" value="">
					</td>
					<td width="33.33%" valign="top">
						ค้นหาตามวันเดินทาง
					</td>
					<td width="33.33%" valign="top">
						ค้นหาตามงบประมาณ
					</td>
				</tr>
				<tr>
					<td colspan="3" align="center" class="pal" valign="center">
						<button class="btn btn-blue"><i class="icon-search"></i> ค้นหา</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>