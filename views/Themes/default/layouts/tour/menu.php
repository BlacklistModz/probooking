<nav id="menu-tour" class="menu-tour">

	<div class="menu-tour-outer container">
	<div class="menu-tour-wrap clearfix">
		<div class="container">
			
			<div class="menu-tour-inner">
				<a class="menu-tour-logo"><img src="<?=IMAGES?>logo/logo-1.svg" alt=""></a>
				<nav class="menu-tour-nav"><ul>
					<?php foreach ($this->categoryList as $value) { ?>
					<li><a href="<?=URL?>tour/category/<?=$value['id']?>"><?=$value['name']?></a></li>
					<?php } ?>
				</ul></nav>
			</div>
		</div>
	</div></div>
</nav>