// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var Slideshow = {
		init: function ( options, elem ) {

			var self = this;
			self.elem = elem;
			self.$elem = $(elem);
			self.options = $.extend( {}, $.fn.slideshow.options, options );

			self.max_width = self.options.max_width || self.$elem.width();
			self.max_height = self.options.max_height || self.$elem.height();


			self.width = self.max_width;
			self.height = (self.options.height*self.max_width)/ self.options.width;

			self.$elem.css({
			 	width: self.width,
			 	height: self.height > self.max_height ? self.max_height : self.height
			});



			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				self.$elem.addClass('ms-touch');
			}

			self.limit = 1;

			self.$elem.addClass('effect-'+self.options.effect);

			self.$slide = self.$elem.find('#slide-image');
			self.length = self.$slide.find('li').length;
			self.prevnext = 'next';

			self.$dotnav = self.$elem.find('.dotnav ul');

			// console.log( self.length, self.options.effect, self.$slide.find('li').first() );

			$.each(self.$slide.find('img[data-src]'), function(index, el) {
				var src = $(this).data('src');

				$(this).removeAttr('data-src').attr('src', src + '?w='+self.max_width)
				
			});


			if( self.length==1 ){

				li = self.$slide.find('li').first();
				

				li.animate({
					opacity: 1,
				}, self.options.speed, 'linear', function() {
					$(this).addClass('on').siblings().removeClass('on');
				});
				return false;
			}

			console.log( self.length );
			if( self.length < 3 && self.options.effect=='slide' ){
				if( self.length==2 ){
					self.$slide.append( self.$slide.find('li').eq(0).clone() );
					self.$slide.append( self.$slide.find('li').eq(1).clone() );
				}
			}

			self.$items = self.$slide.find('li');
			self.length = self.$items.length;
			console.log( self.length );


			// frist
			self.$current = self.$items.eq( self.options.index );
			if( self.options.effect == 'slide' ){
				self.setItemBeforeSlide();
			}
			self.update();

			self.refresh();
			self.event();

		},

		event: function () {
			var self = this;

			$('.prev, .next', self.$elem).click(function(e){
				e.preventDefault();

				self.$items.stop();
				clearTimeout( self.timeout );

				if( $(this).hasClass('prev') ){
					self.prevnext = 'prev';
				}
				else{
					self.prevnext = 'next';
				}

				self.display();
				self.refresh();
			});


			self.$dotnav.find('li').click(function () {
				
				if( $(this).hasClass('current') ) return false;

				clearTimeout( self.timeout );
				self.$next = self.$slide.find('li').eq( $(this).index() ); 

				self.display();
				self.refresh();
			});


			if( navigator.msMaxTouchPoints ){

			}
			else{

				self.$elem.find('#slide-image').on("touchstart", function(event) {
					$('#touchStart').text('start' + event.originalEvent.touches[0].pageX);
					self.touchStart(event);
			    });

			    self.$elem.find('#slide-image').on("touchmove", function(event) {
			    	$('#touchMove').text('move' + event.originalEvent.touches[0].pageX);
			    	self.touchMove(event);
			    	
			    });

			    self.$elem.find('#slide-image').on("touchend", function(event) {
			    	
			    	$('#touchEnd').text('end:');
			        self.touchEnd(event);

			    });
		    }
		},

		setItemBeforeSlide: function () {
			var self = this;


			self.$current.addClass('current').siblings().removeClass('current');

			self.$next = self.$current.next().length==1 ? self.$current.next(): self.$items.first();
			self.$next.css('transform','translate3d(' + self.slideWidth + 'px,0,0)').addClass('next').siblings().removeClass('next');

			// self.$next.css({left: self.slideWidth, opacity: 1});

			self.$prev = self.$current.prev().length==1 ? self.$current.prev(): self.$items.last();
			self.$prev.css('transform','translate3d(' + self.slideWidth*-1 + 'px,0,0)').addClass('prev').siblings().removeClass('prev');

			// self.$prev.css({left: self.slideWidth*-1, opacity: 1});	


			setTimeout(function () {
				self.$items.removeClass('animate');
			}, 250);
		},	

		resize: function(){
			var self = this;

			var img = self.$elem.find('img').eq(0);
			var image = new Image();
			image.src = img.attr('src');
			image.onload = function() {
				
				var scaledW = this.width;
				var scaledH = this.height;

				var fullW = $(window).width();
				var fullH = ( scaledH*fullW )/scaledW;

				if(fullH > self.options.max_height){
					fullH = self.options.max_height;
					self.$elem.addClass('hY');
				}else if( fullH < self.options.min_height ){
					fullH = self.options.min_height;
					self.$elem.addClass('hY');
				}
				else if( self.$elem.hasClass('hY') ){
					self.$elem.removeClass('hY');
				}

				self.$elem.find('#slideshow').css('height', 336  );
			}		
		},

		setup: function(){
			var self = this;
			
			self.limit = self.$elem.find('#slide-image>li').length;
			self.prevnext = 'next';
			self.is_first = true;

			if( self.options.effect=='slide' ){
				self.options.random = false;
			} 

			self.inx = -1;
			self.$ol = self.$elem.find('#slide-image');
			self.$ol.find('li').each(function () {
				$(this).css({opacity:0, display: 'none'});
			});
		},
		refresh: function( length ){
			var self = this;
			
			// self.limit++;
			self.timeout = setTimeout(function () {
				
				// self.buildFrag();
				self.display();
				
				if ( self.options.refresh && self.limit == 1 ) {

					self.prevnext = 'next';
					self.refresh();
				}
				
            }, length || self.options.refresh );
		},
		
		buildFrag:function(){
			var self = this;


			/*if( self.prevnext ){

			}*/

			// self.$current = self.$items.eq( self.options.index );
			

			/*if( random || self.options.random ){
				var inx = Math.floor(Math.random() * (self.limit-1) );

				if( self.inx==inx ){
					self.inx = self.inx>=(self.limit-1) ||  self.inx < 0 ? 0: self.inx+1;
				}
				else{
					self.inx=inx;
				}
			}
			else{
				self.inx = self.inx>=(self.limit-1) ||  self.inx < 0? 0: self.inx+1;
			}*/
		},
		
		display: function( length ){
			var self = this;


			if( self.options.effect == 'slide' ){

				var tempo = 500;
				var val = self.slideWidth;

				if( self.prevnext == 'next' ){


					self.$next.addClass('animate').css('transform','translate3d(0,0,0)');
					self.$current.addClass('animate').css('transform','translate3d(' + val*-1 + 'px,0,0)');

					self.$current = self.$next;
				}
				else{

					self.$prev.addClass('animate').css('transform','translate3d(0,0,0)');
					self.$current.addClass('animate').css('transform','translate3d(' + val + 'px,0,0)');

					self.$current = self.$prev;
				}
				
				self.setItemBeforeSlide();
			}
			else if( self.options.effect == 'fade' ){

			}


			/*if( self.is_first ){
				self.$ol.find( 'li' ).eq(self.inx).css({opacity:1, display:'block'}).addClass('on');

				self.$elem.find('.slider-caption-content li').eq(self.inx).addClass('show').addClass('active');

				self.$elem.find('.slide-header>ul>li').eq(self.inx).addClass('show').addClass('active');

				self.id_load = false;
				self.is_first = false;

				if( self.options.effect=='slide' ){
					self.$ol.find( 'li' ).css({display:'block', opacity: 1});
					self.slide_prevnext();
				}

			}
			else{
				self.$elem.find('.slide-header>ul>li').eq(self.inx).addClass('active').siblings().removeClass('show');
				self.$elem.find('.slider-caption-content li').eq(self.inx).addClass('show').siblings().removeClass('show');

				self.$elem.find('.slider-caption-content li').eq(self.inx).addClass('active').siblings().removeClass('active');

				if( self.options.effect=='slide' && self.limit > 2 ){
					var li = self.$ol.find( 'li' ).eq(self.inx);

					var width = self.$ol.width();

					next = li.next().length ? li.next() : self.$ol.find('li:first-child');
					next.css({
						'left': width*2,
						zIndex: 1
					});
				}

				self.effect[ self.options.effect ](self, length, function () {

					self.$elem.find('.slide-header>ul>li').eq(self.inx).addClass('show').siblings().removeClass('active').removeClass('show');
					
				});
			}

			*/

			self.update();
		},

		update: function () {
			var self = this;

			// dotnav
			self.$dotnav.find('li').eq( self.$current.index() ).addClass('current').siblings().removeClass('current');
		},

		effect: {
			fade: function ( then, length, callback ) {
				var self = then;

				var active = self.$ol.find( 'li.on' );
			
				if( active.length==1 ){
					active.animate({opacity:0}, length || 300, function()
					{
						active.hide().removeClass('on');
					});
				}

				self.$ol.find( 'li' ).eq(self.inx).show().animate({opacity:1}, length || 300, function () {
					$( this ).addClass('on');
					self.id_load = false;

					if( typeof callback == 'function' ){
						callback();
					}
				});

			},

			slide: function (then, tempo, callback ) {
				var self = then;

			},

		},

		slide_prevnext: function () {
			var self = this;

			if( self.limit < 2 ) return false;


			var li = self.$ol.find( 'li' ).eq(self.inx);
			var width = self.$ol.width();
			li.css({zIndex: 2});

			/*for (var i = Things.length - 1; i >= 0; i--) {
				Things[i]
			}*/
			prev = li.prev().length ? li.prev() : self.$ol.find('li:last-child');
			prev.css({
				'left': width*-1,
				zIndex: 1
			});	

			next = li.next().length ? li.next() : self.$ol.find('li:first-child');

			next.css({
				'left': width,
				zIndex: 1
			});

		},

		control: function(){
			var self = this;

			$('.dotnav-item', self.$elem).click(function(e){
				e.preventDefault();

				if( $(this).hasClass('current') || self.id_load || $(this).parent().hasClass('current') ){
					return false;
				}

				self.prevnext = 'next';
				self.$ol.stop();
				self.id_load = true;
				clearTimeout( self.timeout );
				self.inx = $(this).parent().index();

				self.display();
				self.refresh();
			});

			$('.prev, .next', self.$elem).click(function(e){
				e.preventDefault();

				if( self.id_load ) return false;

				self.$ol.stop();
				self.id_load = true;
				clearTimeout( self.timeout );

				if( $(this).hasClass('prev') ){
					self.inx --;

					if(self.inx < 0){
						self.inx = self.limit;
						self.inx --;
					}
					self.prevnext = 'prev';
				}
				else{
					self.inx ++;

					if(self.inx > (self.limit-1)){
						self.inx = 0;
					}
					self.prevnext = 'next';
				}

				self.display();
				self.refresh();
			});

		},

		touchStart: function(event) {
			var self = this;

			clearTimeout( self.timeout );

			// Get the original touch position.
			self.touchstartx = event.originalEvent.touches[0].pageX;

			// The movement gets all janky if there's a transition on the elements.
			self.$items.removeClass('animate');

			$('#txt').text('start');
	    },
	    touchMove: function(event) {
	    	var self = this;

			self.touchmovex =  event.originalEvent.touches[0].pageX;
			self.movex = self.touchstartx - self.touchmovex;
			var panx = 100-self.movex/6;

			var val = self.movex*-1;

			var nextVal = self.slideWidth + val;
			self.$next.css('transform','translate3d(' + nextVal + 'px,0,0)');

			var prevVal =( self.slideWidth*-1) + val;
			self.$prev.css('transform','translate3d(' + prevVal + 'px,0,0)');
			self.$current.css('transform','translate3d(' + val + 'px,0,0)');

			$('#txt').text('move');
	    },
	    touchEnd: function (event) {
	    	var self = this;

	    	if( self.movex > 0 ){
	    		self.prevnext = 'next';
	    	}
	    	else{
	    		self.prevnext = 'prev';
	    	}

	    	self.display();
			self.refresh();

	    	// self.refresh();
	    	$('#txt').text('end');
	    }
	};

	$.fn.slideshow = function( options ) {
		return this.each(function() {
			var $this = Object.create( Slideshow );
			$this.init( options, this );
			$.data( this, 'slideshow', $this );
		});
	};

	$.fn.slideshow.options = {

		effect: 'slide',
		speed: 500,
		// wrapEachWith: '<div></div>',
		auto: true,
		refresh: 5000,
		random: true,

		width: 1920,
		height: 567,
		// max_height: 567,

		// Define these as global variables so we can use them across the entire script.
	    // touchstartx: undefined,
	    // touchmovex: undefined, 
	    // movex: undefined,
	    index: 0,


	    // longTouch: undefined,
	};
	
})( jQuery, window, document );