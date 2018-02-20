// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
    var formPayment = {
    	init: function(options, elem) {
			var self = this;
            self.elem = elem;
            self.$elem = $( elem );

            self.options = $.extend( {}, $.fn.formPayment.options, options );

            self.$bank = self.$elem.find('select#bankbook_id');
            self.currBank = self.options.bank_id;

            self.setElem();
            self.Events();
        },
        setElem: function(){
        	var self = this;
        },
        Events: function(){
        	var self = this;

        	self.$bank.change(function(){
        		self.setBank( $(this).val() );
        	});

        	if( self.currBank != '' ){
        		self.setBank( self.currBank );
        	}
        },
        setBank: function(id){
        	var self = this;
        	$.get( Event.URL + 'bankbook/getBank/' + id, function(res){
        		self.$elem.find('input#branch').val( res.bankbook_branch );
        		self.$elem.find('input#name').val( res.bankbook_name );
        		self.$elem.find('input#number').val( res.bankbook_code );
        	},'json');
        }
	}
    $.fn.formPayment = function( options ) {
		return this.each(function() {
			var $this = Object.create( formPayment );
			$this.init( options, this );
			$.data( this, 'formPayment', $this );
		});
	};
	$.fn.formPayment.options = {
	};
	
})( jQuery, window, document );