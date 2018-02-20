<div class="clearfix">
	<div class="product-image-wrap">
		<div class="product-image"><div class="pic"><img src="<?=$this->item['image_cover_url']?>"></div></div>
	</div>

	<div class="product-content-wrap">
	
		<?php
		$pdf = !empty($this->item['pdf']['url']) ? $this->item['pdf']['url']: '#';
		?>
		
		<div class="description">

			<div class="clearfix">
				
				<h3 class="lfloat"><i class="icon-lightbulb-o mrs"></i> ไฮไลท์</h3>
				<!-- <div class="download rfloat">
					<a href="<?=$pdf?>" target="_blank" class="btn btn-blue btn-blue btn-large btn-block"><i class="icon-book"></i><span class="mls">จองทัวร์</span></a>
				</div> -->
			</div>
			<div class="text editor-text">
				<?=nl2br($this->item['remark'])?>
			</div>
			
			<?php if( !empty($this->item['url_pdf']) || !empty($this->item['url_word'])) { ?> 
			<div class="tar pvm mvm" style="border-top: 1px dotted #ccc;border-bottom: 1px dotted #ccc;">
				<?php

				if(!empty($this->item['url_pdf'])){

	            	echo '<a href="'.$this->item['url_pdf'].'" class="btn btn-blue" target="_blank"><i class="icon-file-pdf-o mrs"></i>ดาวน์โหลด PDF</a>';
	            	
	            }

	            if(!empty($this->me) && !empty($this->item['url_word'])){

	                echo '<a href="'.$this->item['url_word'].'" class="btn btn-blue" target="_blank"><i class="icon-file-word-o mrs"></i>ดาวน์โหลด Word</a>';
	            }
	            ?>

			</div>
			<?php } ?>

			<!-- program -->
			<?php if( !empty($this->item['period']) ){ include_once 'programs.php'; } ?>
			<!-- end: program -->

		</div>
	</div>

</div>