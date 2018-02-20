<?php

$li = '';
foreach ($this->system['navigation'] as $val) {

	if( empty($val['has_topbar']) ) continue;

	$sub = '';
	if( !empty($val['items']) ){
		foreach ($val['items'] as $item) {


			if( !empty($val['item_url']) ){
				$url = !empty($item['primarylink']) ? ' href="'.$val['item_url'].$item['primarylink'].'"':'';
			}
			else{
				$url = !empty($item['primarylink']) ? ' href="'.URL.$val['id'].'/'.$item['primarylink'].'"':'';
			}

			$sub.='<li><a'.$url.'>'.$item['name'].'</a></li>';
		}

		$sub = '<ul class="sub-menu">'.$sub.'</ul>';
	}

	$active = $this->getPage('on')==$val['id'] ? ' class="active"':'';
	$url = !empty($val['url']) ? ' href="'.$val['url'].'"':'';
	$li .= '<li'.$active.'><a'.$url.'>'.$val['name'].'</a>'.$sub.'</li>';
}

echo '<div id="page-nav">';
	echo '<nav class="container clearfix"><ul clsas="nav" id="global-actions">'.$li.'</ul></nav>';
echo '</div>';
# <!-- end: page-nav -->