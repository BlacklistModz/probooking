<!-- front-shop -->
<?php include 'sections/front-shop.php'; ?>
<!-- end: front-shop -->


<section class="module parallax" style="background-image: url('<?=IMAGES?>demo/curtain/curtain-3.jpg')">

<!-- menu tour -->
<?php require WWW_VIEW .'Themes/'.$this->getPage('theme').'/layouts/tour/menu.php'; ?>
<!-- end: menu tour -->


	<div class="container">
	<header class="page-title">
		<h2>โปรแกรมแนะนำ</h2>
		<h2>RECOMMEND PROGRAM</h2>
	</header>
	

	<?php

	echo $this->fn->q('tour')->item( $this->popularList );

	?>
</div></section>

<!-- lists tour -->
<?php require WWW_VIEW .'Themes/'.$this->getPage('theme').'/layouts/tour/lists.php'; ?>
<!-- end: menu lists -->