<?php

$url = URL .'user/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_group"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a>

</div>

<div class="setting-title">กลุ่มผู้ใช้งาน</div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="name">ชื่อกลุ่มผู้ใช้งาน</th>
			<th class="actions">จัดการ</th>

		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<td class="name"><?=$item['name']?></td>
			<td class="actions whitespace">
				<span class="gbtn">
					<a href="<?=URL?>user/edit_group/<?=$item["id"]?>" data-plugins="dialog" class="btn btn-warning btn-no-padding">
						<i class="icon-pencil"></i>
					</a>
				</span>
				<span class="gbtn">
					<a href="<?=URL?>user/del_group/<?=$item["id"]?>" data-plugins="dialog" class="btn btn-red btn-no-padding">
						<i class="icon-trash"></i>
					</a>
				</span>
			</td>
		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>