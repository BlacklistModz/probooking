<?php include ("init.php"); ?>
<div id="mainContainer" class="profile clearfix" data-plugins="main"><div id="customer-profile">

	<div role="content" class="<?=!empty($this->menu)? 'has-toolbar':'';?>" data-plugins="tab">
		
		<?php if( !empty($this->menu) ) {?>
		<div role="toolbar"><?php include "toolbar/display.php"; ?></div>
		<!-- End: toolbar -->
		<?php } ?>

		<div role="main"><div class="profile-content"><?php include "sections/{$this->sections}.php"; ?></div></div>
		<!-- end: main -->

	</div>
	<!-- end: content -->

</div></div>

<script type="text/javascript">
	$(".tab-action").find("a").click(function(){
		if( $(this).hasClass("active") ) return false;
		window.location.href = $(this).attr("href");
	});
</script>