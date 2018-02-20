<section class="front-shop-wrap">
	<div class="screen-dot"></div>
	<div class="curtain">

		<div class="curtain-image video active">
			<video autoplay loop muted>
				<source src="<?=IMAGES?>demo/background-video-1.webm" type="video/webm">
				<source src="<?=IMAGES?>demo/background-video-1.mp4" type="video/mp4">
				<source src="<?=IMAGES?>demo/background-video-1.ogv" type="video/ogv">
			</video>
		</div>
	</div>
	
	<div class="front-shop">

		<!-- slogan -->
		<div class="slogan">
			<h1>Pro Booking Center</h1>
			<br>
			<h2>We makes <span>good tour</span> happen</h2>
		</div>
		<!-- end: slogan -->

		<!-- slider -->

		<div class="container">
			
			<div id="products-showcase" class="products-showcase" style="position: relative;">

				<div class="showcase-wrapper">
					<div class="showcase-items clearfix">
						<?php foreach($this->slideList as $key => $value ){ ?>
						<div class="showcase-item" data-id="">
							<div class="showcase-inner">
								<a href="<?=$value['url']?>" class="pic">
									<img class="img" src="<?=$value['image_cover_url']?>">
									<!-- <div style="position: absolute;font-size: 50px;top: 0"><?=$i?></div> -->
								</a>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>

				<!-- <div class="showcase-wrapper">
					<div class="showcase-items clearfix">
						<?php $i=0; foreach ($this->recommendList as $key => $value) { $i++; ?>
						<div class="showcase-item" data-id="<?=$value['id']?>">
							<div class="showcase-inner">
								<a href="<?=$value['url']?>" class="pic">
									<img class="img" src="<?=$value['image_cover_url']?>">
								</a>
							</div>
						</div>
						<?php } ?>
					</div>
				</div> -->
				
				<a class="control prev" data-action="prev"><i class="icon-arrow-left"></i></a>
				<a class="control next" data-action="next"><i class="icon-arrow-right"></i></a>
			</div>
		</div>

	</div>

</section>