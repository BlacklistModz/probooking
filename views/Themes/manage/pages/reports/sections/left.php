<div class="profile-left" role="left" data-width="260">
	<div class="profile-left-header" role="leftHeader">

		<div class="profile-left-title">
			<h2><i class="icon-line-chart"></i> Reports</h2>
			<!-- <div class="fsm">แก้ไขข้อมูลล่าสุด: <abbr title="วันพฤหัสษบดีที่ 22 ก.พ. 2561  เวลา 02:52 น." data-utime="1519267944" class="timestamp livetimestamp">24 นาทีที่แล้ว</abbr></div> -->
		</div>
	</div>
	<!-- menu -->
	<div class="profile-left-details form-insert-people" style="padding-top: 10px;">
	<?php foreach ($menu as $key => $value) { ?>
		<div>
			<a class="settings-nav-header-link<?php 
						if(!empty($value['key']) && !empty($this->section)){
							if( $value['key']==$this->section ){
								echo ' active';
							}
						} ?>"<?php if(!empty($value['url'])){
							echo '  href="'.$value['url'].'"';
						} ?>><?=$value['text']?></a>
		<?php if( !empty($value['sub']) ){ ?>
			<ul class="settings-nav-list">
				<?php foreach ($value['sub'] as $i => $item) { ?>
					<li><a class="settings-nav-page-link<?php 

						if( !empty($item['key']) && !empty($this->_tap) ){
							if( $item['key']==$this->section ){
								echo ' active';
							}

						}elseif( !empty($item['key']) && !empty($this->tap) ){
							if( $item['key']==$this->tap ){
								echo ' active';
							}
						}
						elseif(!empty($item['key']) && !empty($this->section)){
							if( $item['key']==$this->section ){
								echo ' active';
							}
						} ?>"<?php if(!empty($item['url'])){
							echo '  href="'.$item['url'].'"';
						} ?>><?=$item['text']?></a></li>
				<?php } ?> 
			</ul>
		<?php } ?>

		</div>
	<?php } ?>
	</div>
	<!-- /menu -->

</div>