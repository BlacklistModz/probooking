<div id="footer-2" class="container clearfix">

	<ul class="nav lfloat"><?php

		foreach ($this->system['navigation'] as $val) {
			if( empty($val['has_footer']) ) continue;

			echo '<li><h3><a href="'.URL.$val['name'].'" title="'.$val['name'].'">'.$val['name'].'</a></h3></li>';
			
		}
	?></ul>

	
	<button type="button" class="rfloat back-to-top"><span>กลับด้านบน</span><i class="icon-chevron-up mls"></i></button>
</div>