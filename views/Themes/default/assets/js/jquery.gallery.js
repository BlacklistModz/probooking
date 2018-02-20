// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var windowsize = $(window).width();
	$(window).resize(function(){
	  	windowsize = $(window).width();
	});

	var Gallery = {
		init: function( options, elem ){
			var self = this;

			self.$el = $(elem);

			self.options 		= $.extend( true, {}, $.fn.gallery.options, options );
			
			// support for 3d / 2d transforms and transitions
			self.support3d		= Modernizr.csstransforms3d;
			self.support2d		= Modernizr.csstransforms;
			self.supportTrans	= Modernizr.csstransitions;
			
			self.$wrapper		= self.$el.find('.dg-wrapper');
			
			self.$items			= self.$wrapper.children();
			self.itemsCount		= self.$items.length;
			
			self.$nav			= self.$el.find('nav');
			self.$navPrev		= self.$nav.find('.dg-prev');
			self.$navNext		= self.$nav.find('.dg-next');

			// minimum of 3 items
			if( self.itemsCount < 3 ) {
					
				self.$nav.remove();
				return false;
			
			}

			self.current		= self.options.current;
			
			self.isAnim			= false;
			
			self.$items.css({
				'opacity'	: 0,
				'visibility': 'hidden'
			});

			self._validate();
			
			this._layout();
			
			// load the events
			this._loadEvents();
			
			// slideshow
			if( this.options.autoplay ) {
				this._startSlideshow();
			}

			console.log( self.options );
		},
		_validate: function () {
			
			if( this.options.current < 0 || this.options.current > this.itemsCount - 1 ) {
				this.current = 0;
			}
		},

		_layout				: function() {
			
			// current, left and right items
			this._setItems();
			
			// current item is not changed
			// left and right one are rotated and translated
			var leftCSS, rightCSS, currentCSS;
			
			if( this.support3d && this.supportTrans && windowsize >= 900 ) {
			
				leftCSS 	= {
					'-webkit-transform'	: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
					'-moz-transform'	: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
					'-o-transform'		: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
					'-ms-transform'		: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
					'transform'			: 'translateX(-350px) translateZ(-400px) rotateY(50deg)'
				};
				
				rightCSS	= {
					'-webkit-transform'	: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
					'-moz-transform'	: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
					'-o-transform'		: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
					'-ms-transform'		: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
					'transform'			: 'translateX(350px) translateZ(-400px) rotateY(-50deg)'
				};
				
				leftCSS.opacity		= 0.6;
				leftCSS.visibility	= 'visible';
				rightCSS.opacity	= 0.6;
				rightCSS.visibility	= 'visible';
			}
			else if( this.support3d && this.supportTrans && windowsize > 568 ) {
			
				leftCSS 	= {
					'-webkit-transform'	: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
					'-moz-transform'	: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
					'-o-transform'		: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
					'-ms-transform'		: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
					'transform'			: 'translateX(-220px) translateZ(-400px) rotateY(50deg)'
				};
				
				rightCSS	= {
					'-webkit-transform'	: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
					'-moz-transform'	: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
					'-o-transform'		: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
					'-ms-transform'		: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
					'transform'			: 'translateX(220px) translateZ(-400px) rotateY(-50deg)'
				};
				
				leftCSS.opacity		= 0.6;
				leftCSS.visibility	= 'visible';
				rightCSS.opacity	= 0.6;
				rightCSS.visibility	= 'visible';
			}
			else if( this.support3d && this.supportTrans && windowsize <= 568 ) {
			
				leftCSS 	= {
					'-webkit-transform'	: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
					'-moz-transform'	: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
					'-o-transform'		: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
					'-ms-transform'		: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
					'transform'			: 'translateX(-120px) translateZ(-500px) rotateY(50deg)'
				};
				
				rightCSS	= {
					'-webkit-transform'	: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
					'-moz-transform'	: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
					'-o-transform'		: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
					'-ms-transform'		: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
					'transform'			: 'translateX(120px) translateZ(-500px) rotateY(-50deg)'
				};
				
				leftCSS.opacity		= 0.6;
				leftCSS.visibility	= 'visible';
				rightCSS.opacity	= 0.6;
				rightCSS.visibility	= 'visible';
			}
			else if( this.support2d && this.supportTrans ) {
				
				leftCSS 	= {
					'-webkit-transform'	: 'translate(-350px) scale(0.8)',
					'-moz-transform'	: 'translate(-350px) scale(0.8)',
					'-o-transform'		: 'translate(-350px) scale(0.8)',
					'-ms-transform'		: 'translate(-350px) scale(0.8)',
					'transform'			: 'translate(-350px) scale(0.8)'
				};
				
				rightCSS	= {
					'-webkit-transform'	: 'translate(350px) scale(0.8)',
					'-moz-transform'	: 'translate(350px) scale(0.8)',
					'-o-transform'		: 'translate(350px) scale(0.8)',
					'-ms-transform'		: 'translate(350px) scale(0.8)',
					'transform'			: 'translate(350px) scale(0.8)'
				};
				
				currentCSS	= {
					'z-index'			: 999
				};
				
				leftCSS.opacity		= 0.6;
				leftCSS.visibility	= 'visible';
				rightCSS.opacity	= 0.6;
				rightCSS.visibility	= 'visible';
			}
			
			this.$leftItm.css( leftCSS || {} );
			this.$rightItm.css( rightCSS || {} );
			
			this.$currentItm.css( currentCSS || {} ).css({
				'opacity'	: 1,
				'visibility': 'visible'
			}).addClass('dg-center');
			
		},

		_setItems			: function() {
			
			this.$items.removeClass('dg-center');
			
			this.$currentItm	= this.$items.eq( this.current );
			this.$leftItm		= ( this.current === 0 ) ? this.$items.eq( this.itemsCount - 1 ) : this.$items.eq( this.current - 1 );
			this.$rightItm		= ( this.current === this.itemsCount - 1 ) ? this.$items.eq( 0 ) : this.$items.eq( this.current + 1 );
			
			if( !this.support3d && this.support2d && this.supportTrans ) {
			
				this.$items.css( 'z-index', 1 );
				this.$currentItm.css( 'z-index', 999 );
			
			}
			
			// next & previous items
			if( this.itemsCount > 3 ) {
			
				// next item
				this.$nextItm		= ( this.$rightItm.index() === this.itemsCount - 1 ) ? this.$items.eq( 0 ) : this.$rightItm.next();
				this.$nextItm.css( this._getCoordinates('outright') );
				
				// previous item
				this.$prevItm		= ( this.$leftItm.index() === 0 ) ? this.$items.eq( this.itemsCount - 1 ) : this.$leftItm.prev();
				this.$prevItm.css( this._getCoordinates('outleft') );
			
			}
			
		},
		_loadEvents			: function() {
			
			var _self	= this;
			
			this.$navPrev.on( 'click.gallery', function( event ) {
				
				if( _self.options.autoplay ) {
				
					clearTimeout( _self.slideshow );
					_self.options.autoplay	= false;
				
				}
				
				_self._navigate('prev');
				return false;
				
			});
			
			this.$navNext.on( 'click.gallery', function( event ) {
				
				if( _self.options.autoplay ) {
				
					clearTimeout( _self.slideshow );
					_self.options.autoplay	= false;
				
				}
				
				_self._navigate('next');
				return false;
				
			});
			
			this.$wrapper.on( 'webkitTransitionEnd.gallery transitionend.gallery OTransitionEnd.gallery', function( event ) {
				
				_self.$currentItm.addClass('dg-center');
				_self.$items.removeClass('dg-transition');
				_self.isAnim	= false;
				
			});
			
		},
		_getCoordinates		: function( position ) {
			
			if( this.support3d && this.supportTrans && windowsize >= 900 ) {
			
				switch( position ) {
					case 'outleft':
						return {
							'-webkit-transform'	: 'translateX(-0px) translateZ(-800px) rotateY(50deg)',
							'-moz-transform'	: 'translateX(-0px) translateZ(-800px) rotateY(50deg)',
							'-o-transform'		: 'translateX(-0px) translateZ(-800px) rotateY(50deg)',
							'-ms-transform'		: 'translateX(-0px) translateZ(-800px) rotateY(50deg)',
							'transform'			: 'translateX(-0px) translateZ(-800px) rotateY(50deg)',
							'opacity'			: 0,
							'visibility'		: 'hidden'
						};
						break;
					case 'outright':
						return {
							'-webkit-transform'	: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'-moz-transform'	: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'-o-transform'		: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'-ms-transform'		: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'transform'			: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'opacity'			: 0,
							'visibility'		: 'hidden'
						};
						break;
					case 'left':
						return {
							'-webkit-transform'	: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
							'-moz-transform'	: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
							'-o-transform'		: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
							'-ms-transform'		: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
							'transform'			: 'translateX(-350px) translateZ(-400px) rotateY(50deg)',
							'opacity'			: 0.6,
							'visibility'		: 'visible'
						};
						break;
					case 'right':
						return {
							'-webkit-transform'	: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'-moz-transform'	: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'-o-transform'		: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'-ms-transform'		: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'transform'			: 'translateX(350px) translateZ(-400px) rotateY(-50deg)',
							'opacity'			: 0.6,
							'visibility'		: 'visible'
						};
						break;
					case 'center':
						return {
							'-webkit-transform'	: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-moz-transform'	: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-o-transform'		: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-ms-transform'		: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'transform'			: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'opacity'			: 1,
							'visibility'		: 'visible'
						};
						break;
				};
			}
			else if( this.support3d && this.supportTrans && windowsize > 568 ) {
			
				switch( position ) {
					case 'outleft':
						return {
							'-webkit-transform'	: 'translateX(0px) translateZ(-400px) rotateY(50deg)',
							'-moz-transform'	: 'translateX(0px) translateZ(-400px) rotateY(50deg)',
							'-o-transform'		: 'translateX(0px) translateZ(-400px) rotateY(50deg)',
							'-ms-transform'		: 'translateX(0px) translateZ(-400px) rotateY(50deg)',
							'transform'			: 'translateX(0px) translateZ(-400px) rotateY(50deg)',
							'opacity'			: 0,
							'visibility'		: 'hidden'
						};
						break;
					case 'outright':
						return {
							'-webkit-transform'	: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'-moz-transform'	: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'-o-transform'		: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'-ms-transform'		: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'transform'			: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'opacity'			: 0,
							'visibility'		: 'hidden'
						};
						break;
					case 'left':
						return {
							'-webkit-transform'	: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
							'-moz-transform'	: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
							'-o-transform'		: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
							'-ms-transform'		: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
							'transform'			: 'translateX(-220px) translateZ(-400px) rotateY(50deg)',
							'opacity'			: 0.6,
							'visibility'		: 'visible'
						};
						break;
					case 'right':
						return {
							'-webkit-transform'	: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'-moz-transform'	: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'-o-transform'		: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'-ms-transform'		: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'transform'			: 'translateX(220px) translateZ(-400px) rotateY(-50deg)',
							'opacity'			: 0.6,
							'visibility'		: 'visible'
						};
						break;
					case 'center':
						return {
							'-webkit-transform'	: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-moz-transform'	: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-o-transform'		: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-ms-transform'		: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'transform'			: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'opacity'			: 1,
							'visibility'		: 'visible'
						};
						break;
				};
			}
			else if( this.support3d && this.supportTrans && windowsize <= 568 ) {
			
				switch( position ) {
					case 'outleft':
						return {
							'-webkit-transform'	: 'translateX(0px) translateZ(-500px) rotateY(50deg)',
							'-moz-transform'	: 'translateX(0px) translateZ(-500px) rotateY(50deg)',
							'-o-transform'		: 'translateX(0px) translateZ(-500px) rotateY(50deg)',
							'-ms-transform'		: 'translateX(0px) translateZ(-500px) rotateY(50deg)',
							'transform'			: 'translateX(0px) translateZ(-500px) rotateY(50deg)',
							'opacity'			: 0,
							'visibility'		: 'hidden'
						};
						break;
					case 'outright':
						return {
							'-webkit-transform'	: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'-moz-transform'	: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'-o-transform'		: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'-ms-transform'		: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'transform'			: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'opacity'			: 0,
							'visibility'		: 'hidden'
						};
						break;
					case 'left':
						return {
							'-webkit-transform'	: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
							'-moz-transform'	: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
							'-o-transform'		: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
							'-ms-transform'		: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
							'transform'			: 'translateX(-120px) translateZ(-500px) rotateY(50deg)',
							'opacity'			: 0.6,
							'visibility'		: 'visible'
						};
						break;
					case 'right':
						return {
							'-webkit-transform'	: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'-moz-transform'	: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'-o-transform'		: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'-ms-transform'		: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'transform'			: 'translateX(120px) translateZ(-500px) rotateY(-50deg)',
							'opacity'			: 0.6,
							'visibility'		: 'visible'
						};
						break;
					case 'center':
						return {
							'-webkit-transform'	: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-moz-transform'	: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-o-transform'		: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'-ms-transform'		: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'transform'			: 'translateX(0px) translateZ(0px) rotateY(0deg)',
							'opacity'			: 1,
							'visibility'		: 'visible'
						};
						break;
				};
			}
			else if( this.support2d && this.supportTrans ) {
			
				switch( position ) {
					case 'outleft':
						return {
							'-webkit-transform'	: 'translate(-450px) scale(0.7)',
							'-moz-transform'	: 'translate(-450px) scale(0.7)',
							'-o-transform'		: 'translate(-450px) scale(0.7)',
							'-ms-transform'		: 'translate(-450px) scale(0.7)',
							'transform'			: 'translate(-450px) scale(0.7)',
							'opacity'			: 0,
							'visibility'		: 'hidden'
						};
						break;
					case 'outright':
						return {
							'-webkit-transform'	: 'translate(450px) scale(0.7)',
							'-moz-transform'	: 'translate(450px) scale(0.7)',
							'-o-transform'		: 'translate(450px) scale(0.7)',
							'-ms-transform'		: 'translate(450px) scale(0.7)',
							'transform'			: 'translate(450px) scale(0.7)',
							'opacity'			: 0,
							'visibility'		: 'hidden'
						};
						break;
					case 'left':
						return {
							'-webkit-transform'	: 'translate(-350px) scale(0.8)',
							'-moz-transform'	: 'translate(-350px) scale(0.8)',
							'-o-transform'		: 'translate(-350px) scale(0.8)',
							'-ms-transform'		: 'translate(-350px) scale(0.8)',
							'transform'			: 'translate(-350px) scale(0.8)',
							'opacity'			: 0.6,
							'visibility'		: 'visible'
						};
						break;
					case 'right':
						return {
							'-webkit-transform'	: 'translate(350px) scale(0.8)',
							'-moz-transform'	: 'translate(350px) scale(0.8)',
							'-o-transform'		: 'translate(350px) scale(0.8)',
							'-ms-transform'		: 'translate(350px) scale(0.8)',
							'transform'			: 'translate(350px) scale(0.8)',
							'opacity'			: 0.6,
							'visibility'		: 'visible'
						};
						break;
					case 'center':
						return {
							'-webkit-transform'	: 'translate(0px) scale(1)',
							'-moz-transform'	: 'translate(0px) scale(1)',
							'-o-transform'		: 'translate(0px) scale(1)',
							'-ms-transform'		: 'translate(0px) scale(1)',
							'transform'			: 'translate(0px) scale(1)',
							'opacity'			: 1,
							'visibility'		: 'visible'
						};
						break;
				};
			}
			else {
			
				switch( position ) {
					case 'outleft'	: 
					case 'outright'	: 
					case 'left'		: 
					case 'right'	:
						return {
							'opacity'			: 0.6,
							'visibility'		: 'hidden'
						};
						break;
					case 'center'	:
						return {
							'opacity'			: 1,
							'visibility'		: 'visible'
						};
						break;
				};
			}
		
		},
		_navigate			: function( dir ) {
			
			if( this.supportTrans && this.isAnim )
				return false;
				
			this.isAnim	= true;
			
			switch( dir ) {
			
				case 'next' :
					
					this.current	= this.$rightItm.index();
					
					// current item moves left
					this.$currentItm.addClass('dg-transition').css( this._getCoordinates('left') );
					
					// right item moves to the center
					this.$rightItm.addClass('dg-transition').css( this._getCoordinates('center') );	
					
					// next item moves to the right
					if( this.$nextItm ) {
						
						// left item moves out
						this.$leftItm.addClass('dg-transition').css( this._getCoordinates('outleft') );
						
						this.$nextItm.addClass('dg-transition').css( this._getCoordinates('right') );
						
					}
					else {
					
						// left item moves right
						this.$leftItm.addClass('dg-transition').css( this._getCoordinates('right') );
					
					}
					break;
					
				case 'prev' :
				
					this.current	= this.$leftItm.index();
					
					// current item moves right
					this.$currentItm.addClass('dg-transition').css( this._getCoordinates('right') );
					
					// left item moves to the center
					this.$leftItm.addClass('dg-transition').css( this._getCoordinates('center') );
					
					// prev item moves to the left
					if( this.$prevItm ) {
						
						// right item moves out
						this.$rightItm.addClass('dg-transition').css( this._getCoordinates('outright') );
					
						this.$prevItm.addClass('dg-transition').css( this._getCoordinates('left') );
						
					}
					else {
					
						// right item moves left
						this.$rightItm.addClass('dg-transition').css( this._getCoordinates('left') );
					
					}
					break;	
			};
			
			this._setItems();
			
			if( !this.supportTrans ) this.$currentItm.addClass('dg-center');

			if( typeof this.options.onChange === 'function' ) {
				this.options.onChange( this.$currentItm.index() );
			}
			
		},
		_startSlideshow		: function() {
		
			var _self	= this;
			
			this.slideshow	= setTimeout( function() {
				
				_self._navigate( 'next' );
				
				if( _self.options.autoplay ) {
				
					_self._startSlideshow();
				
				}
			
			}, this.options.interval );
		
		},
		destroy				: function() {
			
			this.$navPrev.off('.gallery');
			this.$navNext.off('.gallery');
			this.$wrapper.off('.gallery');
			
		}
	};

	var logError 			= function( message ) {
		if ( this.console ) {
			console.error( message );
		}
	};

	$.fn.gallery = function( options ) {
		return this.each(function() {
			var $this = Object.create( Gallery );
			$this.init( options, this );
			$.data( this, 'gallery', $this );
		});
	};

	$.fn.gallery.options = {
		current		: 0,	// index of current item
		autoplay	: false, //,// slideshow on / off
		interval	: 2000,  // time between transitions
		onChange	: function () {}
	};

})( jQuery, window, document );