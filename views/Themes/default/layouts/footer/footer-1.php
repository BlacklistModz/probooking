<div id="footer-1" class="module module-inner">
	<div class="container">
	<div class="row-fluid clearfix">
	

		<div class="span4">
			<!-- about -->
			<section id="footer-about" class="footer-about section">
	
				<a class="footer-logo">
                    <img src="<?php echo IMAGES; ?>logo/logo.svg" alt="<?php echo $site_name; ?>">
<!--				<img src="http://localhost/jitwilaitour.demo/public/images/logo/logo.svg" alt="">-->
				</a>
				<!-- <header><h3>เกี่ยวกับเรา</h3></header> -->
				<div class="section-content">
					<p>
						<?php echo 'Pro Booking Center บริการรับจัดทัวร์ทั่วโลก ทัวร์เวียดนาม ทัวร์พม่า ทัวร์ญี่ปุ่น คุณภาพถูกตา ราคาถูกใจ';?>
					</p>
					<p>ใบอนุญาตประกอบธุรกิจนำเที่ยวเลขที่ : <span class="fwb">11/09014</span></p>
				</div>
			</section>
			<!-- end: about -->

			
		</div>
		<!-- ข่าวฮิตทันกระแส -->
		<?php //require_once 'ratedNewsList.php'; ?>
		<!-- end: ข่าวฮิตทันกระแส -->
		
		
		<!-- ข่าวอัพเดท -->
		<?php //require_once 'lastNewsList.php'; ?>
		<!-- end: ข่าวอัพเดท -->

		
		<div class="span3 hidden_elem">
			
			<h3>เวลาทำการ</h3>
			<table class="working-time">
				<tbody>
					<?php for ($i=0; $i < 7; $i++) { ?>
					<tr>
						<td class="label">วันจันทร์:</td>
						<td class="time">9:00 - 18:00</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<p>จอดรถที่ ถนน และ ลานจอดรถ</p>
		</div>

		<div class="span4">
	
			<!-- contact -->
			<section id="footer-contact" class="footer-contact section">
				<h3>ติดต่อ</h3>
				
				<p>228 Ladprao-Wanghin Rd., กรุงเทพมหานคร</p>
				<ul class="clearfix mtl">
					
					<li class="clearfix"><i class="icon-clock-o"></i>เวลาทำการ จันทร์-เสาร์ 9:00 - 18:00 น.</li>
					<?php if( !empty($this->me) ) { ?>
					<li class="clearfix"><i class="icon-phone"></i> <a>02-9358550</a></li>
					<li class="clearfix"><i class="icon-mobile"></i> <a>086-449-4433</a>, <a>098-265-6247</a></li>
					<li class="clearfix line"><i class="icon-line"></i> <a>@Probookingcenter</a> </li>
					<li class="clearfix email"><i class="icon-envelope-o"></i> <a>saleprobooking@gmail.com</a> </li>
					<?php } ?>
					<li class="clearfix facebook"><i class="icon-facebook"></i> <a>probookingcenter</a> </li>
					<li class="clearfix map"><i class="icon-map"></i> <a>Google Map</a> </li>
				</ul>
			</section>
			<!-- end: contact -->

		</div>

		<div class="span4">
			<?php


			echo '<section class="ui-page-facebook" style="min-width: 190px;">'.

	'<div class="fb-page" data-href="https://www.facebook.com/probookingcenter" data-small-header="false" data-adapt-container-width="270" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/probookingcenter" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/probookingcenter">probookingcenter</a></blockquote></div><div id="fb-root"></div>'.

	'</section>';

	echo '<script type="text/javascript">(function(d, s, id) {'.
	  'var js, fjs = d.getElementsByTagName(s)[0];'.
	  'if (d.getElementById(id)) return;'.
	  'js = d.createElement(s); js.id = id;'.
	  'js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.7&appId=1245564155474932";'.
	  'fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script>';

	  ?>
		</div>
		
	</div>
	<!-- end: row-fluid -->
	</div>
</div>