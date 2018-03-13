<?php

$this->count_nav = 0;

/* System */
$sub = array();
$sub[] = array('text' => 'Daily Booking Report','key' => 'daily','url' => $this->pageURL.'reports/booking/daily');
$sub[] = array('text' => 'Monthy Booking Report','key' => 'monthy','url' => $this->pageURL.'reports/booking/monthy');

// foreach ($sub as $key => $value) {
// 	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
// }
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => 'Booking Reports', 'url' => $this->pageURL.'reports/daily', 'sub' => $sub);
}

$sub = array();
$sub[] = array('text' => 'Monthy Period Report', 'key'=>'period_monthy', 'url'=>$this->pageURL.'reports/period/monthy');
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=>'Period Reports', 'url'=>$this->pageURL.'reports/period/', 'sub'=>$sub);
}

$sub = array();
$sub[] = array('text'=>'Daily Recevied Report', 'key'=>'recevied_daily', 'url'=> $this->pageURL.'reports/recevied/daily');
// $sub[] = array('text'=>'Monthy Recevied Report', 'key'=>'recevied_monthy', 'url'=> $this->pageURL.'reports/recevied/monthy');
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => 'Recevied Reports', 'url' => $this->pageURL.'reports/recevied', 'sub'=>$sub);
}

$sub = array();
$sub[] = array('text'=>'Monitor', 'key'=>'monitor', 'url'=>$this->pageURL.'reports/monitor');
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=>'Recevied Monitor', 'url'=>$this->pageURL.'reports/monitor', 'sub'=>$sub);
}

$sub = array();
$sub[] = array('text'=>'Team Sale Monitor', 'key'=>'teams', 'url'=>$this->pageURL.'reports/sales/teams');
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=>'Team Sales', 'url'=>$this->pageURL.'reports/sales', 'sub'=>$sub);
}