<?php

?>
<div class="profile-toolbar profile-toolbar-mg">
		
	<nav class="profile-actions-toolbar clearfix tab-action"><?php

		foreach ($this->menu as $key => $value) {

			$icon = !empty($value['icon']) ? '<i class="icon-'.$value['icon'].' mrs"></i>':'';
			$active = $value['key']==$this->sections ? ' class="active" ':'';
			$href = URL."office/booking/{$value["key"]}";
			if( !empty($this->item) ) $href .= "/{$this->item["book_id"]}";
			echo '<a'.$active.' data-action="'.$value['key'].'" href="'.$href.'">'.$icon.'<span>'.$value['text'].'</span></a>';
		}

	?>
	</nav>
</div>