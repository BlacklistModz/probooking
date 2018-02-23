<?php

require 'init.php';

?><div id="mainContainer" class="profile clearfix" data-plugins="main"><div id="customer-profile">
	<?php require 'left/display.php'; ?>

	<div role="content" class="<?=!empty($this->tabs)? 'has-toolbar':'';?>" data-plugins="tab">
		
		<?php if( !empty($this->tabs) ) {?>
		<div role="toolbar"><?php include "toolbar/display.php"; ?></div>
		<!-- End: toolbar -->
		<?php } ?>

		<div role="main"><div class="profile-content"><?php include "main/display.php"; ?></div></div>
		<!-- end: main -->

	</div>
	<!-- end: content -->

</div></div>