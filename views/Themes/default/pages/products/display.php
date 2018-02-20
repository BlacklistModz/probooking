<section id="product" class="module parallax product" style="background-image: url(<?=IMAGES?>/demo/curtain/curtain-3.jpg);padding-top: 0;padding-bottom: 0">
	<div class="article-single" style="padding-top: 180px; ">

<!-- menu tour -->
<?php require WWW_VIEW .'Themes/'.$this->getPage('theme').'/layouts/tour/menu.php'; ?>
<!-- end: menu tour -->

	<div class="container clearfix">
	<div class="primary-content post" id="post-8952">

		<header class="page-title">
			<h2><?=$this->item['name']?></h2>
			
			<?php require_once 'sections/meta.php'; ?>
		</header>

		
		<div class="card">
			<?php require_once 'sections/content.php';?>
		</div>

		<?php /* require_once 'sections/booking.php'; */ ?>
		
		<?php /* require_once 'sections/condition.php'; */ ?>
		
	</div></div>
	
	</div>
</section>