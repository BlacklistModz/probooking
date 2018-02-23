<?php 

$this->tab = isset($this->tab)? $this->tab: '';

$this->tabs = array();
$this->tabs[] = array('id'=>'booking','name'=>'รายละเอียด', 'icon'=>'clock-o');
$this->tabs[] = array('id'=>'customers','name'=>'ข้อมูลผู้เดินทาง', 'icon'=>'users');

// $this->tabs_right = array();
// $this->tabs_right[] = array('id'=>'files','name'=>'Files', 'icon'=>'file-o');
// $this->tabs_right[] = array('id'=>'plans','name'=>'นัดหมาย', 'icon'=>'history', 'active'=>1);
// $this->tabs_right[] = array('id'=>'notes','name'=>'Notes', 'icon'=>'comments-o');