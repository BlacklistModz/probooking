<?php


$image = $this->getPage('logo');
$site_name = $this->getPage('site_name');

echo '<div id="header-2" class="header-2"><div class="container clearfix">';
	

	/*logo*/
	echo '<h1 id="page-logo" class="page-logo"><a href="'.URL.'" title="'.$site_name.'"><img src="'.IMAGES.'logo/logo.svg" alt="'.$site_name.'"></a></h1>';
	

	/* nav */
	echo '<nav class="main-menu"><ul class="nav clearfix">';

	foreach ($this->system['navigation'] as $key => $value) {
		$div = '';
		$cls = '';
		if( $value['id'] == 'tour' ){
			$li = '';
			foreach ($this->categoryList as $val) {
				$li .= '<li><a href="'.URL.'tour/category/'.$val['id'].'">'.$val['name'].'</a></li>';
			}
			$div .= '<div class="dropdown-content"><ul>'.$li.'</ul></div>';
  			$cls = 'class="dropdown"';
		}
		if( $value['id'] == 'contact-us' && empty($this->me) ) continue;
		echo '<li '.$cls.'><a href="'.$value['url'].'">'.$value['name'].'</a>'.$div.'</li>';
	}

	if( !empty($this->me) ) {
		echo '<li style="background-color:yellow;"><a href="'.URL.'series" style="color:#000;">Series Online</a></li>';
	}
	
	echo '</ul><nav>';



echo '</div></div>';
# <!-- end: header-2 -->