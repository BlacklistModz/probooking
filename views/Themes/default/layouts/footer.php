<footer id="footer-primary">
	
	<!-- footer-1 -->
	<?php include_once 'footer/footer-1.php'; ?>
	<!-- end: footer-1 -->
	
	<!-- footer-2 -->
	<?php // include_once 'footer/footer-2.php'; ?>
	<!-- end: footer-2 -->

	<!-- footer-3 -->
	<?php include_once 'footer/footer-3.php'; ?>
	<!-- end: footer-3 -->

</footer>


<?php 
echo '<div class="elevator-wrapper" data-action="backtotop"><div class="elevator icon-arrow-up"></div></div>';

echo '<div class="elevator-wrapper contact" data-action="getcontact"><div class="elevator icon-commenting-o"></div></div>';


echo '<div class="elevator-contact">'.
	'<div class="elevator-contact-header clearfix" data-action="getcontact"><h3 class="lfloat">ติดต่อ</h3><a class="rfloat"><i class="icon-remove"></i></a></div>'.
	'<ul class="footer-contact">'.
		'<li class="phone"><a><i class="icon-phone"></i><span>02-9358550</span></a></li>'.
		'<li class="line"><a><i class="icon-line"></i><span>jitwilai-wholesale</span></a></li>'.
		'<li class="email"><a><i class="icon-envelope-o"></i><span>saleprobooking@gmail.com</span></a></li>'.
		'<li class="facebook"><a><i class="icon-facebook"></i><span>probookingcenter</span></a></li>'.
		'<li class="map"><a><i class="icon-map"></i><span>Google Map</span></a></li>'.
	'</ul>'.
'</div>';

?>
<script>
	$(function(){
		 const _bar =  $('<i>',{class:"icon-bars"});
		 const  _times = $('<i>',{class:"icon-times"});
		$('.navbar-toggler').click(function(){
			$("i", this).toggleClass("icon-bars icon-times");
		const root =$(this).data('toggle');
		const data = $('div').hasClass(root);
		
		$("."+root+"").toggle(function(){
				$(this).addClass("active")
			})
		})
		
	})
	function toggleractive (e){
		 const target = e.data('toggle');
		const div = $('div').hasClass(target);
		div.toggle(function(){
				$(this).addClass('active');
		});
		$(this).html($(this).html() == 'Menu' ? 'Close': 'Menu');
	
	}
	function Isactive(){
		$(this).addClass('active');
	}


</script>
<script type="text/javascript" src="<?=VIEW?>Themes/<?=$this->getPage('theme')?>/assets/js/modernizr.custom.53451.js"></script>
<script type="text/javascript" src="<?=VIEW?>Themes/<?=$this->getPage('theme')?>/assets/js/jquery.gallery.js"></script>
<!-- <script type="text/javascript" src="<?=VIEW?>Themes/<?=$this->getPage('theme')?>/assets/js/jquery.carouFredSel-6.2.1-packed.js"></script> -->
<!-- <script type="text/javascript" src="<?=VIEW?>Themes/<?=$this->getPage('theme')?>/assets/js/jquery.sliderControl.js"></script> -->
<!-- <script type="text/javascript" src="<?=VIEW?>Themes/<?=$this->getPage('theme')?>/assets/js/jquery.touchSwipe.min.js"></script> -->
