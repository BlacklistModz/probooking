<?php foreach ($this->categoryList as $key => $country) { ?>
<section id="section-category" class="section-category module parallax " style="background-image: url('<?=IMAGES?>demo/country/<?=$key?>.jpg')">

	<div class="container">
	<header class="page-header">
		<div class="page-title">
			<h2><?=$country['name']?></h2>
			<!-- <h2>MYANMAR</h2> -->
		</div>
	</header>
	

	<?php

	echo $this->fn->q('tour')->item( $country['items'] );

	?>
</div></section>

<?php } ?>