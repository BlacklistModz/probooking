<div class="customers-content<?=!empty($this->tabs_right) ? ' has-right':''?>">
	<div class="customers-main"><div class="mbl"><?php 
	foreach ($this->tabs as $key => $value) {
		$active = $value['id']==$this->tab ? ' active':'';
	?>
		
		<div data-content="<?=$value['id']?>" class="tab-content<?=$active?>"><?php
		require "sections/{$value['id']}.php";
		?></div>

	<?php } ?>
	</div></div>
</div>
<!-- end: customers-content -->

<script type="text/javascript">
	$(".tab-action").find('a').click(function(){
		var type = $(this).data("action");
		var $customersMain = $("[data-content=customers]").find( $("#customersMain") );

		if( type != "customers" ){
			$customersMain.empty();
		}
		else{

			if( $(this).hasClass('active') ){
				return false;
			}
			
			$customersMain.html( 
				$('<div>', {class:"tac", style:"padding-top:25%;"}).append( 
					$('<div>', {class:"loader-spin-wrap", style:"display:inline-block;"}).append( 
						$('<div>', {class:"loader-spin", style:"padding:25px;"}) 
						)
					)
				);

			$.get( Event.URL + 'office/room_detail',{period:<?=$this->item["per_id"]?>, bus:<?=$this->item["bus_no"]?>}, function( res ){
				$customersMain.html( res );
				Event.plugins( $customersMain );
			} );
			
		}
	});
</script>