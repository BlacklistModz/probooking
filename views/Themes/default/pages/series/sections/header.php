<div style="display: inline-block;width: 250px" class="mrl">
	<a href="<?=URL?>series/hotsale"><img src="<?=IMAGES?>icons/hotsale.png" style="width: 100%; border-radius: 5mm;"></a>
</div>
<?php foreach($this->country AS $i => $country) { ?>
<div style="display: inline-block;width: 250px;" class="mrl">
	<a href="<?=URL?>series/online/<?=$country["id"]?>"><img src="<?=IMAGES?>demo/title/<?=$country['id']?>.jpg" style="width:100%; border-radius: 5mm;"></a>
</div>
<?php } ?>