<script>
	$( function() {
		$( "#slider-range" ).slider({
			range: true,
			min: 5000,
			max: 50000,
			values: [ 10000, 30000 ],
			slide: function( event, ui ) {
				$( "#amount" ).text( ui.values[ 0 ] + "฿ - " + ui.values[ 1 ]+ "฿" );
				$('input[name=price_start]').val( ui.values[ 0 ] );
				$('input[name=price_end]').val( ui.values[ 1 ] );
			}
		});
		$( "#amount" ).text( $( "#slider-range" ).slider( "values", 0 ) +
			"฿ - " + $( "#slider-range" ).slider( "values", 1 )+ "฿" );

		$('input[name=price_start]').val( $( "#slider-range" ).slider( "values", 0 ) );
		$('input[name=price_end]').val( $( "#slider-range" ).slider( "values", 1 ) );
	} );
</script>