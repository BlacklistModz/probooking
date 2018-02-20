<?php

$this->pageURL = URL.'office/';

$image = '';
if( !empty($this->me['image_url']) ){
	$image = '<div class="avatar lfloat mrm"><img class="img" src="'.$this->me['image_url'].'" alt="'.$this->me['fullname'].'"></div>';
}
else{
	$image = '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>';
}

echo '<div class="navigation-main-bg navigation-trigger"></div>';
echo '<nav class="navigation-main" role="navigation"><a class="navigation-btn-trigger navigation-trigger"><span></span></a>';

echo '<div class="navigation-main-header"><div class="anchor clearfix">'.$image.'<div class="content"><div class="spacer"></div><div class="massages"><div class="fullname">'.$this->me['fullname'].'</div><span class="subname">'.$this->me['group_name'].'</span></div></div></div></div>';

echo '<div class="navigation-main-content">';

$agency[] = array('key'=>'agency_company', 'text'=>'Agent Profile', 'link'=>$this->pageURL.'agency_company', 'icon'=>'building-o');
$agency[] = array('key'=>'agency', 'text'=>'Agent Account', 'link'=>$this->pageURL.'agency', 'icon'=>'users');
if( !empty($agency) ){
	echo $this->fn->manage_nav($agency, $this->getPage('on'));
}

#reports
$reports[] = array('key'=>'reports','text'=>'Reports','link'=>$this->pageURL.'reports','icon'=>'line-chart');
// $reports[] = array('key'=>'report_payment','text'=>'Report Payment','link'=>$this->pageURL.'reports/payment','icon'=>'book');
// foreach ($reports as $key => $value) {
// 	if( empty($this->permit[$value['key']]['view']) ) unset($reports[$key]);
// }
if( !empty($reports) ){
	echo $this->fn->manage_nav($reports, $this->getPage('on'));
}

$web[] = array('key'=>'website', 'text'=>'View Website', 'link'=>URL, 'icon'=>'eye', 'target'=>'_cms' );
echo $this->fn->manage_nav($web, $this->getPage('on'));

$cog[] = array('key'=>'settings','text'=>'Settings', 'link'=>$this->pageURL.'settings','icon'=>'cog');
echo $this->fn->manage_nav($cog, $this->getPage('on'));

echo '</div>';

	echo '<div class="navigation-main-footer">';


echo '<ul class="navigation-list">'.

	'<li class="clearfix">'.
		'<div class="navigation-main-footer-cogs">'.
			'<a data-plugins="dialog" href="'.URL.'logout/admin?next='.URL.'office"><i class="icon-power-off"></i><span class="visuallyhidden">Log Out</span></a>'.
			// '<a href="'.URL.'logout/admin"><i class="icon-cog"></i><span class="visuallyhidden">Settings</span></a>'.
		'</div>'.
		'<div class="navigation-brand-logo clearfix"><img class="lfloat mrm" src="'.IMAGES.'logo/128x128.png">'.( !empty( $this->system['title'] ) ? $this->system['title']:'' ).'</div>'.
	'</li>'.
'</ul>';

echo '</div>';


echo '</nav>';