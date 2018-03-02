var __Elem = {
	anchorBucketed: function (data) {
		
		var anchor = $('<div>', {class: 'anchor ui-bucketed clearfix'});
		var avatar = $('<div>', {class: 'avatar lfloat no-avatar mrm'});
		var content = $('<div>', {class: 'content'});
		var icon = '';

		if( !data.image_url || data.image_url=='' ){

			icon = 'user';
			if( data.icon ){
				icon = data.icon;
			}
			icon = '<div class="initials"><i class="icon-'+icon+'"></i></div>';
		}
		else{
			icon = $('<img>', {
				class: 'img',
				src: data.image_url,
				alt: data.text
			});
		}

		avatar.append( icon );

		var massages = $('<div>', {class: 'massages'});

		if( data.text ){
			massages.append( $('<div>', {class: 'text fwb u-ellipsis'}).html( data.text ) );
		}

		if( data.category ){
			massages.append( $('<div>', {class: 'category'}).html( data.category ) );
		}
		
		if( data.subtext ){
			massages.append( $('<div>', {class: 'subtext'}).html( data.subtext ) );
		}

		content.append(
			  $('<div>', {class: 'spacer'})
			, massages
		);
		anchor.append( avatar, content );

        return anchor;
	},
	anchorFile: function ( data ) {
		
		if( data.type=='jpg' ){
			icon = '<div class="initials"><i class="icon-file-image-o"></i></div>';
		}
		else{
			icon = '<div class="initials"><i class="icon-file-text-o"></i></div>';
		}
		
		var anchor = $('<div>', {class: 'anchor clearfix'});
		var avatar = $('<div>', {class: 'avatar lfloat no-avatar mrm'});
		var content = $('<div>', {class: 'content'});
		var meta =  $('<div>', {class: 'subname fsm fcg'});

		if( data.emp ){
			meta.append( 'Added by ',$('<span>', {class: 'mrs'}).text( data.emp.fullname ) );
		}

		if( data.created ){
			var theDate = new Date( data.created );
			meta.append( 'on ', $('<span>', {class: 'mrs'}).text( theDate.getDate() + '/' + (theDate.getMonth()+1) + '/' + theDate.getFullYear() ) );
		}

		avatar.append( icon );

		content.append(
			  $('<div>', {class: 'spacer'})
			, $('<div>', {class: 'massages'}).append(
				  $('<div>', {class: 'fullname u-ellipsis'}).text( data.name )
				, meta
			)
		);
		anchor.append( avatar, content );

        return anchor;
	} 
};

// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {


	var ActiveForm = {
		init: function (options, elem) {
			var self = this;
			self.$elem = $(elem);


			$.each( self.$elem.find(':input.js-change'), function () {
				self.change( $(this).attr('name'), $(this).val() );
			} );
			self.$elem.find(':input.js-change').change(function () {
				self.change( $(this).attr('name'), $(this).val() );
			});

			$.each( self.$elem.find(':input.js-openset'), function () {
				self.openset( $(this).attr('name'), $(this).prop('checked') );
			} );
			self.$elem.find(':input.js-openset').change(function () {
				self.openset( $(this).attr('name'), $(this).prop('checked') );
			});
			
		},

		change: function ( name, val ) {
			var self = this;

			self.$elem.find('.sidetip').find('[data-name='+ name +'][data-value='+ val +']').addClass('active').siblings().removeClass('active');
		},

		openset: function (name, checked) {
			var self = this;

			self.$elem.find('[data-name='+ name +']').toggleClass('active', checked);
		}
	}
	$.fn.activeform = function( options ) {
		return this.each(function() {
			var $this = Object.create( ActiveForm );
			$this.init( options, this );
			$.data( this, 'activeform', $this );
		});
	};

	/**/
	/* Datalist */
	/**/
	var Datalist = {
		init: function (options, elem) {
			var self = this;
			self.$elem = $(elem);

			self.options = $.extend( {}, $.fn.datalist.options, options );

			// set Data
			self.orders = [];
			self.data = {
				start: null,
				end: null,
				q: '',
			};

			// set Elem
			self.setElem();
			self.Events();
			self.setCountHeader();
		},

		setElem: function () {
			var self = this;

			self.$listsbox = self.$elem.find('[role=listsbox]');
			self.$profile = self.$elem.find('[role=profile]');
			self.$content = self.$elem.find('.datalist-content');

			self.$profile.css({
				opacity: 0,
				left: '20%'
			});

			self.$elem.find('.js-setDate').closedate({
				lang: 'en',
				options: [
				{
					text: 'Last',
					value: 'last',
				},
				{
					text: 'Today',
					value: 'daily',
				},
				{
					text: 'Yesterday',
					value: 'yesterday',
				},
				{
					text: 'This week',
					value: 'weekly',
				},
				{
					text: 'Last week',
					value: 'last1week',
				},
				{
					text: 'This month',
					value: 'monthly', 
				},
				{
					text: 'Last 7 days',
						value: 'last7days', // weekly
				},
				{
					text: 'Last 14 days',
					value: 'last14days',
				},
				{
					text: 'Last 28 days28',
					value: 'last28days',
				},
				{
					text: 'Last 90 days',
					value: 'last90days',
				},
				{
					text: 'Custom',
					value: 'custom',
				}],

				onChange: function (date) {

					self.data.start = date.startDateStr + ' 00:00:00';
					self.data.end = date.endDateStr + ' 23:59:59';

					if( date.activeIndex == 0 ){
						self.data.start = null;
						self.data.end = null;
					}

					self.refresh( 1 );
				}
			});
		},
		Events: function () {
			var self = this;

			self.$elem.find('.js-new').click(function () {

				if( $(this).hasClass('disabled') ){
					return false;
				}

				self.hide();
				self.$listsbox.find('li.active').removeClass('active');
				$(this).addClass('disabled');
				self.newOrder();

				self.setLocation( self.options.URL + 'create', 'Create - Booking' );
			});


			/*$('body').find('.navigation-trigger').click(function(){
				self.reset();
			});

			$('body').find('select[role=selection]').change(function(){
				
				self.data[ $(this).attr('name') ] = $(this).val();
				self.refresh( 1 );
			});


			$('body').find('input[role=search]').keydown(function(e){
				var keyCode = e.which;

				var val = $.trim($(this).val());

				if( keyCode==13 && self.data.q!=val && val!='' ){

					$(this).val(val);
					self.data.q = val;
					self.refresh( 1 );
				}
			}).keyup(function(e){
				var val = $.trim($(this).val());

				if( self.data.q!=val && val=='' ){

					self.data.q = '';
					self.refresh( 1 );
				}
			});

			*/

			self.$listsbox.delegate('li', 'click', function() {
				if( $(this).hasClass('head') || $(this).hasClass('active') ) return false;
				self.active( $(this) );
			});

			self.$profile.delegate('.js-cancel', 'click', function() {
				self.hide( function() {
					self.$profile.empty();

					if( self.$elem.find('.js-new').hasClass('disabled')  ){
						self.$elem.find('.js-new').removeClass('disabled');
					}
				});

				self.$listsbox.find('li.active').removeClass('active');
			});
		},
		setCountHeader: function() {
			var self = this;

			var a = ['count-today', 'count-yesterday', 'count-total'];

			$.each(a, function (i, key) {
				
				var res = key.split('-');

				var text = '';
				if( self.options[res[0]] ){
					text = self.options[res[0]][res[1]];
				}
				self.$elem.find('[view-text='+key+']').text( text || '-' );
			});
		},
		refresh: function (length) {
			var self = this;

			if( self.$listsbox.parent().hasClass('has-empty') ){
				self.$listsbox.parent().removeClass('has-empty');
			}
			self.$listsbox.parent().addClass('has-loading');
			
			setTimeout(function () {
				self.fetch().done(function( results ) {

					self.data = $.extend( {}, self.data, results.options );

					// reset 
					self.orders = [];
					self.$listsbox.empty();
					self.$profile.addClass('hidden_elem');

					var total = results.total;
					self.$elem.find('[view-text=total]').text( total );

					if( results.total==0 ){

						self.$listsbox.parent().addClass('has-empty');
						return false;
					}
					self.buildFrag( results.lists );
					
					// self.resize();
				});
				
			}, length || 1);
		},
		fetch: function(){
			var self = this;

			if( !self.data ) self.data = {};

			return $.ajax({
				url: self.options.load_orders_url,
				data: self.data,
				dataType: 'json'
			}).always(function() {
				
				self.$listsbox.parent().removeClass('has-loading');
			}).fail(function() {
				self.$listsbox.parent().addClass('has-empty');
			});
		},
		buildFrag: function ( results ) {
			var self = this;

			$.each(results, function (i, obj) {
				
				self.displayItem( obj );
			});

			if( self.$listsbox.find('li.active').length==0 && self.$listsbox.find('li').not('.head').first().length==1 ){

				self.active( self.$listsbox.find('li').not('.head').first() );
			}
		},
		setItem: function (data, options) {
			var self = this;

			var date = new Date( data.created );
			var minute = date.getMinutes();
			minute = minute < 10? '0'+minute:minute;

			var options = options || data.options || {};

			// set Elem
			var li = $('<li/>');
			var inner = $( data.url ? '<a>': '<div>', {
				class: 'inner'
			});


			// avatar
			if( data.image_url ){
				inner.append( $('<div/>', {class:'avatar'}).html( $('<img/>', {calss: 'img', src: data.image_url}) ) );
				li.addClass('picThumb');
			}

			// time
			if( options.time === 'disabled' ){
				li.addClass( 'hide_time' );
			}
			else{
				inner.append( $('<div/>', {class: 'time', text: date.getHours() + ":" + minute }) );
			}

			// text
			if( data.text ){
				inner.append( $('<div/>', {class: 'text', text: data.text}) );
			}

			// subtext
			if( data.subtext ){
				inner.append( $('<div/>', {class: 'subtext', text: data.subtext}) );
			}

			// category
			if( data.category ){
				inner.append( $('<div/>', {class: 'category', text: data.category}) );
			}

			// status
			if( data.status ){
				if( typeof data.status === 'object' ){
					inner.append( $('<div/>', {class: 'status', text: data.status.name}).css('background-color', data.status.color ) );
				}
				else{
					inner.append( $('<div/>', {class: 'status', text: data.status}) );
				}
			}
			
			li.html( inner );
			li.data( data );
			return li;	
		},
		displayItem: function ( data, before ) {
			var self = this;

			var res = data.created.split(' ');

			if( !self.orders[res[0]] ){

				var li = $('<li>', {class: 'head'});
				var date = new Date( data.created );

				var m = Datelang.month(date.getMonth(), 'normal', 'en');
				var day = Datelang.day(date.getDay(), 'normal', 'en');
				li.text( day +', '+  date.getDate() + ' ' + m + ' ' + date.getFullYear() );

				if( before && self.$listsbox.find('li').length>0){
					self.$listsbox.find('li').first().before( li );
				}
				else{
					self.$listsbox.append( li );
				}
				
				
				self.orders[res[0]] = [];
			} 

			if( before ){
				self.$listsbox.find('li.head').first().after( self.setItem( data ) );
			}
			else{
				self.$listsbox.append( self.setItem( data ) );
			}
			
			self.orders[res[0]].push( data );
		},

		show: function ( callback ) {
			var self = this;

			if( self.is_show ){

				self.hide( function () {

					setTimeout(function () {
						self._show(callback);
					}, 200)
					
				} );
			}
			else{
				self._show(callback);
			}
		},
		_show: function ( callback ) {
			var self = this;

			self.is_show = true;
			self.$profile.stop().animate({
				left: 0,
				opacity: 1
			}, 200, callback||function () {});
		},
		hide: function ( callback ) {
			var self = this;

			self.is_show = false;
			self.$profile.stop().animate({
				left: '20%',
				opacity: 0
			}, 200, callback||function () {});
		},
		active: function ( $el ) {
			var self = this;

			if( self.$elem.find('.js-new').hasClass('disabled')  ){
				self.$elem.find('.js-new').removeClass('disabled');
			}

			var data = $el.data();
			$el.addClass('active').siblings().removeClass('active');

			var t = setTimeout(function () {
				self.$content.addClass('has-loading');
			}, 800);

			$.get( self.options.load_profile_url, {id: data.id}, function( body ) {
				clearTimeout( t );

				self.$content.removeClass('has-loading');
				self._profile.init( {}, body, self );

				self.current = data;
				self.setLocation( self.options.URL + data.id, data.text + ' - Booking' );
			});
		},
		setLocation: function (href, title) {
			
			var returnLocation = history.location || document.location;

			var title = title || document.title;

			history.pushState('', title, href);
			document.title = title;
		},
		_profile: {
			init: function (options, elem, parent) {
				var self = this;

				self.parent = parent;
				self.options = options;
				self.$elem = $(elem);

				// set elem
				self.parent.$profile.html( self.$elem ).removeClass('hidden_elem');

				Event.plugins( self.$elem.parent() );
				self.resize();

				self.setData();

				// show 
				self.parent.show();

				self.Events();
				
			},

			resize: function ( ) {
				var self = this, top = 20, bottom = 20;
				var w = self.parent.$profile.outerWidth();

				if( self.$elem.find('.datalist-main-header').length==1 ){
					top = self.$elem.find('.datalist-main-header').outerHeight();
					self.$elem.find('.datalist-main-header').css( 'max-width', w );
				}

				if( self.$elem.find('.datalist-main-footer').length==1 ){
					bottom = self.$elem.find('.datalist-main-footer').outerHeight();
					self.$elem.find('.datalist-main-footer').css( 'max-width', w );
				}

				self.$elem.find('.datalist-main-content').css({
					paddingTop: top,
					paddingBottom: bottom
				});
			},

			Events: function () {
				var self = this;

				self.$elem.find('.settingsLabel, .js-settings-cancel').click( function () {

					var $elem = $(this).closest('.settingsForm');
					var section = $elem.data('section');
					var is = $elem.hasClass('is-active');
					var q = '';

					if( !is ){

						q = section;
						$elem.addClass('is-active').siblings().removeClass('is-active');
					}
					else{
						$elem.removeClass('is-active');
					}

					if( q!='' ) q = '/' + q;
					self.parent.setLocation( self.parent.options.URL+self.parent.current.id + q );

					self.setData();
				});

			},

			setData: function () {
				var self = this;

				$.each(self.$elem.find('.settingsForm'), function () {
					
					var $form = $(this);

					var data = '';
					var cell = 0;
					$.each($form.find('.js-data'), function(i, obj) {
						cell++;

						// tap = 
						var str = '';
						if( $(obj).data('type')=='BR' || $(obj).context.nodeName=='BR' ){
							str = '<br>';
							cell = 0;
						}else if( $(obj).context.nodeName == 'SELECT' ){
							str = $.trim( $(obj).find(':selected').text() );
						}
						else if( $(obj).context.nodeName == 'SPAN' ){
							str = $(obj).html();
						}
						else{
							str = $.trim( $(obj).val() );
						}
			
						data+= cell<=1 ?'':', ';
						data+= str;

					});

					$form.find('.settingsLabel .settingsLabelTable .data-wrap').html( data );   
				});
				
				// js-data
			}
		},

		newOrder: function () {
			
			var self = this;

			var t = setTimeout(function () {
				self.$content.addClass('has-loading');
			}, 800);
			
			$.get( self.options.load_create_url, function( body ) {
				clearTimeout( t );

				self.$content.removeClass('has-loading');
				self._newOrder.init( {}, body, self );
			});
		},
		_newOrder: {
			init: function (options, elem, parent) {
				var self = this;

				self.parent = parent;
				self.options = options;
				self.$elem = $(elem);

				// set elem
				self.parent.$profile.html( self.$elem ).removeClass('hidden_elem');

				Event.plugins( self.$elem.parent() );
				self.resize();
				// show 
				self.parent.show();
			},

			resize: function ( ) {
				var self = this, top = 20, bottom = 20;
				var w = self.parent.$profile.outerWidth();

				if( self.$elem.find('.datalist-main-header').length==1 ){
					top = self.$elem.find('.datalist-main-header').outerHeight();
					self.$elem.find('.datalist-main-header').css( 'max-width', w );
				}

				if( self.$elem.find('.datalist-main-footer').length==1 ){
					bottom = self.$elem.find('.datalist-main-footer').outerHeight();
					self.$elem.find('.datalist-main-footer').css( 'max-width', w );
				}

				self.$elem.find('.datalist-main-content').css({
					paddingTop: top,
					paddingBottom: bottom
				});
			}
		},
	};
	$.fn.datalist = function( options ) {
		return this.each(function() {
			var $this = Object.create( Datalist );
			$this.init( options, this );
			$.data( this, 'datalist', $this );
		});
	};
	$.fn.datalist.options = {}


	var SearchInput = {
		init: function (options, elem) {
			var self = this;

			self.$elem = $( elem );
			self.data = $.extend( {}, $.fn.searchinput.options, options );

			self.url = self.data.url || Event.URL + "customers/search/";

			self.$elem
				.parent()
				.addClass('ui-search')
				.append( 
					  $('<div>', {class: 'loader loader-spin-wrap'}).html( $('<div>', {class: 'loader-spin'}) )
					, $('<div>', {class: 'overlay'}) 
			);


			self.is_focus = false;
			self.is_keycodes = [37,38,39,40,13];
			self.load = false;
			self.is_focus2 = false;

			// Event
			var v;
			self.$elem.keyup(function (e) {
				var $this = $(this);
				var value = $.trim( $this.val() );

				self.is_focus2 = true;

				if( self.is_keycodes.indexOf( e.which )==-1 && !self.has_load ){

					self.$elem.parent().addClass('has-load');
					self.hide();

					clearTimeout( v );

					if(value==''){
						self.$elem.parent().removeClass('has-load');
						return false;
					}

					v = setTimeout(function(argument) {
						self.load = true;
						self.data.options.q = $.trim($this.val());
						self.search();
					}, 500);

				}
			}).keydown(function (e) {
				var keyCode = e.which;

				if( keyCode==40 || keyCode==38 ){

					self.changeUpDown( keyCode==40 ? 'donw':'up' );
					e.preventDefault();
				}

				if( self.$menu ){
					if( keyCode==13 && self.$menu.find('li.selected').length==1 ){
						self.active(self.$menu.find('li.selected').data());
					}
				}
			}).click(function (e) {
				var value = $.trim($(this).val());

				if(value!=''){

					if( self.data.options.q==value ){
						self.setMenu();
					}
					else{

						self.$elem.parent().addClass('has-load');
						self.hide();
						clearTimeout( v );

						self.load = true;
						self.data.options.q = value;
						self.search();
					}
				}

				e.stopPropagation();
			}).blur(function () {
				
				if( !self.is_focus ){
					self.hide();
				}

				self.is_focus2 = false;

			}).focus(function () {
				self.is_focus2 = true;
			});
		},

		search: function () {
			var self = this;

			$.ajax({
				url: self.url,
				data: self.data.options,
				dataType: 'json'
			}).done(function( results ) {

				self.data = $.extend( {}, self.data, results );
				if( results.total==0 || results.error || self.is_focus2==false ){
					return false;
				}

				self.setMenu();

			}).fail(function() {

				self.has_load = false;
				self.$elem.parent().removeClass('has-load');
				
			}).always(function() {

				self.has_load = false;
				self.$elem.parent().removeClass('has-load');
			});
		},

		hide: function () {
			var self = this;

			if( self.$layer ){
				self.$layer.addClass('hidden_elem');
			}
		},

		changeUpDown: function ( active ) {
			var self = this;
			var length = self.$menu.find('li').length;
			var index = self.$menu.find('li.selected').index();

			if( active=='up' ) index--;
			else index++;

			if( index < 0) index=0;
			if( index >= length) index=length-1;

			self.$menu.find('li').eq( index ).addClass('selected').siblings().removeClass('selected');
		},

		setMenu: function () {
			var self = this;

			var $box = $('<div/>', {class: 'uiTypeaheadView selectbox-selectview'});
			self.$menu = $('<ul/>', {class: 'search has-loading', role: "listbox"});

			$box.html( $('<div/>', {class: 'bucketed'}).append( self.$menu ) );

			var settings = self.$elem.offset();
			settings.parent = self.data.parent;
			if( settings.parent ){

				var parentoffset = $(settings.parent).offset();
				settings.left-=parentoffset.left;
				settings.top+=$(settings.parent).parent().scrollTop();
			}

			settings.top += self.$elem.outerHeight();
			settings.$elem = self.$elem;

			uiLayer.get(settings, $box );
			self.$layer = self.$menu.parents('.uiLayer');
			self.$layer.addClass('hidden_elem');

			self.buildFrag( self.data.lists );
			self.display();
		},
		buildFrag: function ( results ) {
			var self = this;

			$.each(results, function (i, obj) {

				var item = $('<a>');
				var li = $('<li/>');


				if( obj.image_url ){

					item.append( $('<div/>', {class:'avatar'}).html( $('<img/>', {calss: 'img', src: obj.image_url}) ) );

					li.addClass('picThumb');
				}

				if( obj.text ){
					item.append( $('<span/>', {class: 'text', text: obj.text}) );
				}

				if( obj.subtext ){
					item.append( $('<span/>', {class: 'subtext', text: obj.subtext}) );
				}

				if( obj.category ){
					item.append( $('<span/>', {class: 'category', text: obj.category}) );
				}

				li.html( item );

				li.data(obj);
				self.$menu.append( li );
			});
		},
		display: function () {
			var self = this;

			if( self.$menu.find('li').length == 0 ){
				return false;
			}

			if( self.$menu.find('li.selected').length==0 ){
				self.$menu.find('li').first().addClass('selected');
			}

			self.$layer.removeClass('hidden_elem');

			self.$menu.delegate('li', 'mouseenter', function() {
				$(this).addClass('selected').siblings().removeClass('selected');
			});
			self.$menu.delegate('li', 'click', function(e) {
				$(this).addClass('selected').siblings().removeClass('selected');
				self.active($(this).data());
				// e.stopPropagation();
			});

			self.$menu.mouseenter(function() {
				self.is_focus = true;
		  	}).mouseleave(function() { 
		  		self.is_focus = false;
		  	});
		},

		active: function ( data ) {
			var self = this;

			if( typeof self.data.onSelected === 'function' ){
				self.data.onSelected( data, self );
			}

			self.hide();
		},
	}
	$.fn.searchinput = function( options ) {
		return this.each(function() {
			var $this = Object.create( SearchInput );
			$this.init( options, this );
			$.data( this, 'searchinput', $this );
		});
	};
	$.fn.searchinput.options = {
		options: { q: '', limit: 5, view_stype: 'bucketed' },
		onSelected: function () {},
		parent: ''
	};

	var StepsForm = {
		init: function (options, elem) {
			var self = this;

			self.$elem = $( elem );
			self.options = $.extend( {}, $.fn.stepsform.options, options );

			self.setElem();
			self.changeStep( self.options.index );
			self.setPrev();

			self.Events();
		},
		setElem: function () {
			var self = this;

			self.$prev = self.$elem.find('.js-prev');
			self.$next = self.$elem.find('.js-next');

			self.$nav = self.$elem.find('[steps-nav]');
			self.$content = self.$elem.find('[steps-content]');
		},
		Events: function () {
			var self = this;

			self.$next.click(function(e) {

				var li = self.$elem.find('.uiStepSelected');

				self.submit( li.next().length==1 ? li.data('id'): 'save' );
				e.preventDefault();
			});

			self.$prev.click(function(e) {

				var li = self.$elem.find('.uiStepSelected');
				if( li.prev().length == 1 ){
					self.changeStep('prev');
					self.setPrev();
				}
				
				e.preventDefault();
			});

			self.$nav.find('.uiStep').click(function(e) {

				var li = $(this);
				var index = $(this).index();

				if( li.hasClass('uiStepSelected') ){
					return false;
				}

				if( index == (self.indexStepSelected()+1) ){

					self.submit( self.$elem.find('.uiStepSelected').data('id') );
					return false;
				}

				if( index < self.indexStepSelected() || li.hasClass('is_success') ){
					self.changeStep(index);
				}

				if( li.prev().length ){
					if( li.prev().hasClass('is_success') ){
						self.changeStep(index);

					}
				}

				e.preventDefault();
			});
		},
		indexStepSelected: function () {
			var self = this;
			return self.$nav.find('.uiStepSelected').index();
		},

		changeStep: function ( type, index ) {
			var self = this;

			if( type=='next' || type=='prev' ){
				var index = self.indexStepSelected();

				if( type=='next' ){
					index++; 
				}
				else if( type=='prev' ){
					index--;
				}
			}else index = type;

			self.$nav.find('.uiStep').eq(index).addClass('uiStepSelected').siblings().removeClass('uiStepSelected');
			self.$content.eq( index ).removeClass('hidden_elem').siblings().addClass('hidden_elem');

			self.setPrev();
		},
		setPrev: function () {
			var self = this;

			self.$prev.toggleClass('hidden_elem', self.indexStepSelected()==0);
			self.$next.find('.btn-text').text( self.$nav.find('.uiStepSelected').next().length==0 ? 'บันทึก':'ต่อไป');
		},
		submit: function ( type ) {
			var self = this;

			var $form = self.$elem;
			var url = $form.attr('action');

			$form.find('[name=type_form]').val( type );
			if( self.$next.hasClass('btn-error') ){
				self.$next.removeClass('btn-error');
			}

			self.$next.addClass('disabled').addClass('is-loader').prop('disabled', true);

			var formData = Event.formData($form);

			$.ajax({
				type: 'POST',
				url: url,
				data: formData,
				dataType: 'json'
			}).done(function( results ) {

				results.onDialog = true;
				Event.processForm($form, results);

				if( results.error ){

					self.$next.addClass('btn-error');
					return false;
				}

				$form.find('.newOrder_inputs-main').scrollTop( 0 );

				if( type!='save' && !results.error ){

					self.$elem.find('.uiStep[data-id='+ type +']').addClass('is_success');
					self.changeStep( 'next' );
					self.setPrev();
					
					return false;
				}
			}).fail(function() {
				
				Event.showMsg({text: 'การเชื่อมต่อผิดผลาด', auto: true, load: true})
				self.$next.removeClass('disabled').removeClass('is-loader').prop('disabled', false);
			}).always(function() {

				self.$next.removeClass('disabled').removeClass('is-loader').prop('disabled', false);

			});
		}
	}
	$.fn.stepsform = function( options ) {
		return this.each(function() {
			var $this = Object.create( StepsForm );
			$this.init( options, this );
			$.data( this, 'stepsform', $this );
		});
	};
	$.fn.stepsform.options = {
		items: [],
		steps: [],
		index: 0
	}

	var ActionsListHiden = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $( elem );
			self.options = $.extend( {}, $.fn.actionsListHiden.options, options );

			self.$elem.find('[data-actions]').change(function () {
				
				var action = $(this).data('actions');
				var $box = self.$elem.find('[data-active='+ action +']');

				self.$elem.find(':input').not('[data-actions]').addClass('disabled').prop('disabled', true);


				// $.each( )
				$box.find(':input').not('[data-actions]').removeClass('disabled').prop('disabled', false);

			});
		}
	}
	$.fn.actionsListHiden = function( options ) {
		return this.each(function() {
			var $this = Object.create( ActionsListHiden );
			$this.init( options, this );
			$.data( this, 'actionsListHiden', $this );
		});
	};
	$.fn.actionsListHiden.options = {}


	/**/
	/* RUpload */
	/**/
	var RUpload = {
		init: function (options, elem) {
			var self = this;

			self.$elem = $(elem);
			self.$listsbox = self.$elem.find('[rel=listsbox]');
			self.$add = self.$elem.find('[rel=add]');
			self.data = $.extend( {}, $.fn.rupload.options, options );
			self.up_length = 0;

			self.refresh( 1 );
			self.Events();
		},

		Events: function () {
			var self = this;

			self.$elem.find('.js-upload').click(function (e) {
				e.preventDefault();

				self.change();
			});

			self.$elem.delegate('.js-remove', 'click', function (e) {

				self.loadRemove( $(this).closest('li').data() );
				e.preventDefault();
			});
			// has-loading
		},
		change: function () {
			var self = this;

			var $input = $('<input/>', { type: 'file', accept: "image/*"});
			if( self.data.multiple ){
				$input.attr('multiple', 1);
			}
			$input.trigger('click');

			$input.change(function(){

				self.$add.addClass('disabled').addClass('is-loader').prop('disabled', true);
				
				self.files = this.files;
				
				self.setFile();
			});
		},
		loadRemove: function (data) {
			var self = this;

			Dialog.load( self.data.remove_url, {id: data.id, callback: 1}, {
				onSubmit: function (el) {
					
					$form = el.$pop.find('form');
					Event.inlineSubmit( $form ).done(function( result ) {

						result.onDialog = true;
						result.url = '';
						Event.processForm($form, result);

						if( result.error ){
							return false;
						}
						
						self.$elem.find('[data-id='+ data.id +']').remove();
						self.sort();
						Dialog.close();

					});
				}
			} );
		},

		setFile: function () {
			var self = this;

			$.each( self.files, function (i, file) {
				self.up_length++;
				self.displayFile( file );

				self.sort();
			} );	
		},
		displayFile: function ( file ) {
			var self = this;

			var item = $('<li>', {class: 'has-upload' }).append( __Elem.anchorFile( file ) );
			item.append( self.setBTNRemove() );


			var progress = $('<div>', {class:'progress-bar medium mts'});
			var bar = $('<span>', {class:'blue'});

			progress.append( bar );

			item.find('.massages').append( progress );

			if( self.$listsbox.find('li').length==0 ){
				self.$listsbox.append( item );
			}
			else{
				self.$listsbox.find('li').first().before( item );
			}

			var formData = new FormData();
			formData.append( self.data.name, file);

			$.ajax({
			    type: 'POST',
			    dataType: 'json',
			    url: self.data.upload_url,
			    data: formData,
			    cache: false,
			    processData: false,
			    contentType: false,
			    error: function (xhr, ajaxOptions, thrownError) {

			        /*alert(xhr.responseText);
			        alert(thrownError);*/
			        Event.showMsg({text: 'อัพโหลดไฟล์ไม่ได้', auto: true, load: true, bg: 'red'});
			        item.remove();
			    },

			    xhr: function () {
			        var xhr = new window.XMLHttpRequest();
			        //Download progress
			        xhr.addEventListener("progress", function (evt) {
			            if (evt.lengthComputable) {
			                var percentComplete = evt.loaded / evt.total;
			                bar.css('width', Math.round(percentComplete * 100));
			                // progressElem.html(  + "%");
			            }
			        }, false);
			        return xhr;
			    },
			    beforeSend: function () {
			        // $('#loading').show();
			    },
			    complete: function () {

			    	self.up_length--;
			    	if( self.up_length==0 ){
			    		self.$add.removeClass('disabled').removeClass('is-loader').prop('disabled', false);
			    	}
			        // $("#loading").hide();
			    },
			    success: function (json) {

			    	if( json.error ){

			    		return false;
			    	}

			    	item.attr('data-id', json.id);
			    	item.data( json )
			    	progress.remove();
			    }
			});
		},

		refresh: function ( length ) {
			var self = this;

			if( self.is_loading ) clearTimeout( self.is_loading ); 

			if ( self.$elem.hasClass('has-error') ){
				self.$elem.removeClass('has-error')
			}

			if ( self.$elem.hasClass('has-empty') ){
				self.$elem.removeClass('has-empty')
			}

			self.$elem.addClass('has-loading');

			self.is_loading = setTimeout(function () {

				self.fetch().done(function( results ) {

					self.data = $.extend( {}, self.data, results );

					if( results.error ){

						if( results.message ){
							self.$elem.find('.js-message').text( results.message );
							self.$elem.addClass('has-error');
						}
						return false;
					}

					self.$elem.toggleClass( 'has-empty', parseInt(self.data.total)==0 );

					$.each( results.lists, function (i, obj) {
						self.display( obj );
					} );
				});
			}, length || 1);
			
		},
		fetch: function () {
			var self = this;

			return $.ajax({
				url: self.data.url,
				data: self.data.options,
				dataType: 'json'
			}).always(function () {

				self.$elem.removeClass('has-loading');
				
			}).fail(function() { 
				self.$elem.addClass('has-error');
			});
		},

		display: function ( data ) {
			var self = this;

			var item = $('<li>', {'data-id': data.id}).append( __Elem.anchorFile( data ) );
			item.append( self.setBTNRemove() );
			item.data( data );

			if( self.$listsbox.find('li').length==0 ){
				self.$listsbox.append( item );
			}
			else{
				self.$listsbox.find('li').first().before( item );
			}
		},
		setBTNRemove: function () {
			
			return $('<button>', {type: 'button', class: 'js-remove icon-remove btn-remove'});
		},
		sort: function () {
			var self = this;

			self.$elem.toggleClass('has-empty', self.$listsbox.find('li').length==0 );
		}
	}
	$.fn.rupload = function( options ) {
		return this.each(function() {
			var $this = Object.create( RUpload );
			$this.init( options, this );
			$.data( this, 'rupload', $this );
		});
	};
	$.fn.rupload.options = {
		options: {},
		multiple: false,
		name: 'file1'
	}

	/**/
	/* Invite */
	/**/
	var Invite = {
		init: function (options, elem) {
			var self = this;

			self.$elem = $(elem);

			$.each( self.$elem.find('[ref]'), function () {
				var key = $(this).attr('ref');
				self['$'+key] = $(this);
			} );

			$.each( self.$elem.find('[act]'), function () {
				var key = $(this).attr('act');
				self['$'+key] = $(this);
			} );

			self.settings = $.extend( {}, $.fn.invite.settings, options );
			self.resize();

			self.checked = self.settings.checked || {};

			if( self.settings.invite ){
				$.each( self.settings.invite, function(key, users) {
					$.each( users, function(i, obj) {
						self.checked[ key+'_'+obj.id ] = obj;
					});
				} );
			}

			self.data = {
				options: self.settings.options || {}
			};

			self.refresh();
			self.Events();
		},

		resize: function () {
			var self = this;

			var parent = self.$listsbox.parent();

			var offset = parent.position();
			var outerHeight = self.$elem.outerHeight();
			parent.css('height', self.$elem.outerHeight() - (self.$header.outerHeight() + 40) );
		},

		refresh: function ( length ) {
			var self = this;

			if( self.is_loading ) clearTimeout( self.is_loading ); 

			if ( self.$listsbox.parent().hasClass('has-error') ){
				self.$listsbox.parent().removeClass('has-error')
			}

			if ( self.$listsbox.parent().hasClass('has-empty') ){
				self.$listsbox.parent().removeClass('has-empty')
			}

			self.$listsbox.parent().addClass('has-loading');

			self.is_loading = setTimeout(function () {

				self.fetch().done(function( results ) {
					
					// self.data = $.extend( {}, self.data, results );

					self.$listsbox.toggleClass( 'has-empty', results.length==0 );
					self.buildFrag( results );
					self.display();

				});
			}, length || 1);
		},
		fetch: function () {
			var self = this;

			if( self.is_search ){
				self.$actions.find(':input').addClass('disabled').prop('disabled', true);
			}

			$.each( self.$actions.find('select[act=selector]'), function () {
				self.data.options[ $(this).attr('name') ] = $(this).val();
			} );


			return $.ajax({
				url: self.settings.url,
				data: self.data.options,
				dataType: 'json'
			}).always(function () {

				self.$listsbox.parent().removeClass('has-loading');

				if( self.is_search ){
					self.$actions.find(':input').removeClass('disabled').prop('disabled', false);
					self.$inputsearch.focus();
					self.is_search = false;
				}
				
			}).fail(function() { 
				self.$listsbox.parent().addClass('has-error');
			});
		},
		buildFrag: function ( results ) {
			var self = this;

			var data = {
				total: 0,
				options: {},
				lists: []
			};

			$.each( results, function (i, obj) {
				
				data.options = $.extend( {}, data.options, obj.data.options );
				if( obj.data.options.more==true ){
					data.options.more = true;
				}

				data.total += parseInt( obj.data.total );

				$.each(obj.data.lists, function (i, val) {

					if( !val.type ){
						val.type = obj.object_type;
					}

					if( !val.category ){
						val.category = obj.object_name;
					}

					data.lists.push( val ); 
				});
			} );

			self.data = $.extend( {}, self.data, data );

			self.$listsbox.parent().toggleClass('has-more', self.data.options.more);
			self.$listsbox.parent().toggleClass('has-empty', self.data.total==0 );
		},
		display: function( item ) {
			var self = this;

			self.$listsbox.parent().removeClass('has-loading');

			$.each( self.data.lists, function (i, obj) {
				
				var item = $('<li>', {class: 'ui-item', 'data-id': obj.id, 'data-type': obj.type}).html( __Elem.anchorBucketed( obj ) );

				item.data( obj );
				item.append('<div class="btn-checked"><i class="icon-check"></i></div>');

				if( self.checked[ obj.type + "_" + obj.id ] ){
					item.addClass('has-checked');
					self.setToken( obj, true );
				}

				self.$listsbox.append( item );
			});

			if( !self.$elem.hasClass('on') ){
				self.$elem.addClass('on');
			}

			self.resize();
		},

		Events: function () {
			var self = this;

			self.$listsbox.parent().find('.ui-more').click(function (e) {
				self.data.options.pager++;

				self.refresh( 300 );
				e.preventDefault();
			});

			self.$listsbox.delegate('.ui-item', 'click', function (e) {
				
				var checked = !$(this).hasClass('has-checked');

				$(this).toggleClass('has-checked', checked );
				self.setToken( $(this).data(),  checked );
				e.preventDefault();
			});

			self.$tokenbox.delegate('.js-remove-token', 'click', function (e) {
				
				var data = $(this).closest('[data-id]').data();

				delete self.checked[ data.type + "_" + data.id ];
				self.$tokenbox.find('[data-id='+ data.id +'][data-type='+ data.type +']').remove();
				self.$listsbox.find('[data-id='+ data.id +'][data-type='+ data.type +']').removeClass('has-checked');
				self.resize();
				e.preventDefault();
			});

			self.$actions.find('select[act=selector]').change(function () {

				self.data.options.q = $.trim( self.$inputsearch.val() );
				self.data.options.pager = 1;
				self.data.options[ $(this).attr('name') ] = $(this).val();

				self.$listsbox.empty();
				self.refresh( 1 );
			});

			self.$elem.find('.js-selected-all').click(function () {

				var item = self.$listsbox.find('.ui-item').not('.has-checked');
				
				var checked = true;
				if( item.length == 0 ){
					checked = false;
				}

				$.each(self.$listsbox.find('.ui-item'), function (i, obj) {
					
					if( checked && !$(this).hasClass('has-checked') ){
						$(this).toggleClass('has-checked', checked );
						self.setToken( $(this).data(),  checked );
					}
					else if( $(this).hasClass('has-checked') ){
						$(this).toggleClass('has-checked', checked );
						self.setToken( $(this).data(),  checked );
					}
					

				});
			});


			var searchVal = $.trim( self.$inputsearch.val() );			
			self.$inputsearch.keyup(function () {
				var val  = $.trim( $(this).val() );

				if( val=='' && val!=searchVal ){
					searchVal = '';
					self._search( searchVal );
				}				
			}).keypress(function (e) {
				if(e.which === 13){
					e.preventDefault();

					var text = $.trim( $(this).val() );
					if( text!='' ){
						searchVal = text;
						self._search( text );
					} 
				}
			});
		},

		setToken: function (data, checked) {
			var self = this;

			if( checked ){
				self.checked[ data.type + "_" + data.id ] = data;

				if( self.$tokenbox.find('[data-id='+ data.id +'][data-type='+ data.type +']').length==1 ){ return false; }
				
				var $el = __Elem.anchorBucketed(data);
				$el.addClass('anchor24');

				var item = $('<li>', {class: 'ui-item has-action', 'data-id': data.id, 'data-type': data.type}).append(
					  $el
					, $('<input>', {type: 'hidden', name: 'invite[id][]', value: data.id })
					, $('<input>', {type: 'hidden', name: 'invite[type][]', value: data.type})
					, $('<button>', {type: 'button', class: 'ui-action top right js-remove-token'}).html( $('<i>', {class: 'icon-remove'}) )
				);

				item.data( data );

				self.$tokenbox.append( item );
				self.$tokenbox.scrollTop(self.$tokenbox.prop("scrollHeight"));
			}
			else{
				delete self.checked[ data.type + "_" + data.id ];
				self.$tokenbox.find('[data-id='+ data.id +'][data-type='+ data.type +']').remove();
			}

			self.resize();

			self.$elem.find('.js-selectedCountVal').text( Object.keys( self.checked ).length );
		},
		_search: function (text) {
			var self = this;

			self.data.options.pager = 1;
			self.data.options[ 'q' ] = text;
			self.is_search = true;

			self.$listsbox.empty();
			self.refresh( 500 );
		},	
	}
	$.fn.invite = function( options ) {
		return this.each(function() {
			var $this = Object.create( Invite );
			$this.init( options, this );
			$.data( this, 'invite', $this );
		});
	};
	$.fn.invite.settings = {
		multiple: false,
	}

	/**/
	/* listplan */
	/**/
	var Listplan = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.options = $.extend( {}, $.fn.listplan.settings, options );

			// upcoming
			self.upcoming.init( self.options.upcoming || {}, self.$elem.find('[ref=upcoming]'), self );

			self.Events();
		},
		upcoming: {

			init: function ( options, $elem, than  ) {
				var self = this;

				self.than = than;
				self.$elem = $elem;
				self.$listsbox = self.$elem.find('[ref=listsbox]');
				self.$more = self.$elem.find('[role=more]');

				self.data = options;

				self.refresh( 1 );

				self.$more.click(function (e) {
					e.preventDefault();

					self.data.options.pager++;
					self.refresh( 300 );
				});
			},
			refresh: function ( length ) {
				var self = this;

				if( self.is_loading ) clearTimeout( self.is_loading ); 

				if ( self.$elem.hasClass('has-error') ){
					self.$elem.removeClass('has-error')
				}

				if ( self.$elem.hasClass('has-empty') ){
					self.$elem.removeClass('has-empty')
				}

				self.$elem.addClass('has-loading');

				self.is_loading = setTimeout(function () {

					self.fetch().done(function( results ) {

						self.data = $.extend( {}, self.data, results );

						self.$elem.toggleClass( 'has-empty', parseInt(self.data.total)==0 );
						if( results.error ){

							if( results.message ){
								self.$elem.find('[ref=message]').text( results.message );
								self.$elem.addClass('has-error');
							}
							return false;
						}

						$.each( results.lists, function (i, obj) {
							self.display( obj );
						} );

						self.$elem.toggleClass('has-more', self.data.options.more);
					});
				}, length || 1);			
			},
			fetch: function () {
				var self = this;

				return $.ajax({
					url: self.data.url,
					data: self.data.options,
					dataType: 'json'
				}).always(function () {

					self.$elem.removeClass('has-loading');
					
				}).fail(function() { 
					self.$elem.addClass('has-error');
				});
			},
			display: function ( data ) {
				var self = this;
				self.$listsbox.append( self.than.setItem( data ) );
			},
		},

		setItem: function (data) {
			
			data.icon = 'calendar';
			var li = $('<li>', {class: 'ui-item', 'data-id': data.id}).html( __Elem.anchorBucketed(data) );

			li.data( data );

			var actions = $('<div>', {class: 'ui-actions'});

			actions.append(
				  $('<button>', {type: 'button', class: 'action js-edit'}).html( $('<i>', {class: 'icon-pencil'}) ) 
				, $('<button>', {type: 'button', class: 'action js-remove'}).html( $('<i>', {class: 'icon-remove'}) ) 
			)

			li.append( actions );

			return li;
		},

		Events: function () {
			var self = this;

			if( self.options.add_url ){

				self.$add = self.$elem.find('[role=add]');
				self.$elem.find('.js-add').click(function () {
					self.add();
				});
			}

			if( self.options.edit_url ){
				self.$elem.delegate('.js-edit', 'click', function (e) {
					e.preventDefault();

					var $parent = $(this).closest('[data-id]');
					self.edit( $(this).closest('[data-id]').data(), $parent );
				});
			}

			if( self.options.remove_url ){
				self.$elem.delegate('.js-remove', 'click', function (e) {

					var $parent = $(this).closest('[data-id]');
					self.remove( $parent.data(), $parent );
				});
			}
			
		},

		add: function () {
			var self = this;

			self.$add.addClass('disabled').addClass('is-loader').prop('disabled', true);
			Dialog.load(self.options.add_url, {callback: 'bucketed'}, {
				onClose: function () {
					self.$add.removeClass('disabled').removeClass('is-loader').prop('disabled', false);
				},
				onSubmit: function ($el) {
					self.$add.removeClass('disabled').removeClass('is-loader').prop('disabled', false);

					$form = $el.$pop.find('form');
					Event.inlineSubmit( $form ).done(function( result ) {

						result.url = '';
						Event.processForm($form, result);

						if( result.error ){
							return false;
						}

						var item = self.setItem( result.data );
						var $listsbox = self.$elem.find('[ref=upcoming]').find('[ref=listsbox]');

						if( $listsbox.find('li').length > 0){
							$listsbox.find('li').first().before( item );
						}
						else{
							$listsbox.append( item );
						}

						Dialog.close();
					});
				}
			});
		},

		edit: function ( data, $el ) {
			var self = this;

			$el.addClass('disabled').prop('disabled', true);
			Dialog.load(self.options.edit_url, {id: data.id, callback: 'bucketed'}, {
				onClose: function () {
					$el.removeClass('disabled').prop('disabled', false);
				},
				onSubmit: function ($d) {
					$el.removeClass('disabled').prop('disabled', false);

					$form = $d.$pop.find('form');
					Event.inlineSubmit( $form ).done(function( result ) {

						$el.removeClass('disabled').prop('disabled', false);

						result.url = '';
						Event.processForm($form, result);

						if( result.error ){
							return false;
						}

						var $listsbox = self.$elem.find('[ref=upcoming]').find('[ref=listsbox]');
						$listsbox.find('[data-id='+ data.id +']').replaceWith( self.setItem( result.data ) );

						Dialog.close();
					});
				}
			});
		},

		remove: function (data, $el) {
			var self = this;

			$el.addClass('disabled').prop('disabled', true);
			Dialog.load(self.options.remove_url, {id: data.id, callback: 1}, {
				onClose: function () {
					$el.removeClass('disabled').prop('disabled', false);
				},
				onSubmit: function ($d) {

					$form = $d.$pop.find('form');
					Event.inlineSubmit( $form ).done(function( result ) {

						$el.removeClass('disabled').prop('disabled', false);

						result.url = '';
						Event.processForm($form, result);

						if( result.error ){
							return false;
						}

						var $listsbox = self.$elem.find('[ref=upcoming]').find('[ref=listsbox]');
						$listsbox.find('[data-id='+ data.id +']').remove();

						Dialog.close();
					});
				}
			});
		}
	}
	$.fn.listplan = function( options ) {
		return this.each(function() {
			var $this = Object.create( Listplan );
			$this.init( options, this );
			$.data( this, 'listplan', $this );
		});
	};
	$.fn.listplan.options = {
		multiple: false,
	}

	var imageCover = {
		init: function(options, elem) {
			var self = this;
			self.elem = elem;

			self.options = $.extend( {}, $.fn.imageCover.options, options );

			self.initElem();
			self.initEvent();
		},
		initElem: function () {
			var self = this;
			self.$elem = $( self.elem );

			var width = self.$elem.width();
			var height = ( self.options.scaledY * width ) / self.options.scaledX;
			self.$elem.css({
				width: width,
				height: height
			});

			if( self.options.url ){
				self.updateImage();
			}
		},
		initEvent: function () {
			var self = this;
			self.$elem.find('[type=file]').change(function () {
				self.setImage(this.files[0]);
			});
		},

		setImage: function (file) {
			var self = this;

			self.$elem.addClass('has-loading');
			var $progress = self.$elem.find('.progress-bar');
			var $remove = $('<a/>', {class:"preview-remove"}).html( $('<i/>', {class:'icon-remove'}) );

			$remove.click(function (e) {
				e.preventDefault();
				self.clear();
			});

			var $img = $('<div/>',{ class:'image-crop'});
			self.$elem.find('.preview').append( $remove, $img );

			var width = self.$elem.width();

			var reader = new FileReader();
			reader.onload = function (e) {
				var image = new Image();
				image.src = e.target.result;
				$image = $(image).addClass('img img-crop');

				image.onload = function() {
					
					var scaledW = this.width;
					var scaledH = this.height;
					var height = ( scaledH * width ) / scaledW;
					$image.width( width );
					$image.height( height );

					var scaledW = self.options.scaledX;
					var scaledH = self.options.scaledY;
					var height = ( scaledH * width ) / scaledW;
					
					$img.css({ width: width, height: height });
					
					self.$elem.removeClass('has-loading').addClass('has-file');
					$img.html( $image );

					self.cropperImage( self.$elem.find('.preview') );
				}
			}

			reader.onprogress = function(data) {
				if (data.lengthComputable) {                                            
	                var progress = parseInt( ((data.loaded / data.total) * 100), 10 );
	                $progress.find('.bar').width( progress+"%" );
	            }
        	}

			reader.readAsDataURL( file );
		},
		clear:function () {
			var self = this;

			self.$elem.find('[type=file]').val('');
			self.$elem.find('.preview').empty();
			self.$elem.removeClass('has-file');
		},

		cropperImage: function ( $el ) {
			var self = this;

			var $x = $('<input/>', {type: 'hidden', name:'cropimage[x]', value: 0});
			var $y = $('<input/>', {type: 'hidden', name:'cropimage[y]', value: 0});
			var $width = $('<input/>', {type: 'hidden', name:'cropimage[width]', value: 0 });
			var $height = $('<input/>', {type: 'hidden', name:'cropimage[height]', value: 0 });
			var $rotate = $('<input/>', {type: 'hidden', name:'cropimage[rotate]', value: 0 });
			var $scaleX = $('<input/>', {type: 'hidden', name:'cropimage[scaleX]', value: 0 });
			var $scaleY = $('<input/>', {type: 'hidden', name:'cropimage[scaleY]', value: 0 });
			
			$el.find('.image-crop').append($x, $y,$width, $height, $rotate, $scaleX, $scaleY);

			Event.setPlugin( $el.find('img.img-crop'), 'cropper', {
				aspectRatio: self.options.scaledX / self.options.scaledY,
				autoCropArea: .95,
				strict: true,
				guides: true,
				highlight: false,
				dragCrop: false,
				cropBoxMovable: true,
				cropBoxResizable: false,
				crop: function(e) {

					if( $el.find('.image-wrap').length ){

					 	$el.find('.image-wrap').addClass('hidden_elem');
					}

					if( $el.find('.image-crop').hasClass('hidden_elem') ){
					 	$el.find('.image-crop').removeClass('hidden_elem');
					}

				    // Output the result data for cropping image.
				    $x.val(e.x);
				    $y.val(e.y);
				    $width.val(e.width);
				    $height.val(e.height);
				    $rotate.val(e.rotate);
				    $scaleX.val(e.scaleX);
				    $scaleY.val(e.scaleY);

				}
			} );
		},

		updateImage: function() {
			
			var self = this;
			var $remove = $('<a/>', {class:"preview-remove"}).html( $('<i/>', {class:'icon-remove'}) );
			var $img = $('<div/>', { class:'image-crop hidden_elem'});
			var $wrap = $('<div/>',{ class:'image-wrap'});
			var $edit = $('<div/>',{ class:'image-cover-edit', text: 'ปรับตำแหน่ง'});
			self.$elem.addClass('has-file').find('.preview').append( $remove, $edit, $img, $wrap );

			$edit.click(function (e) {

				if( self.$elem.hasClass('has-cropimage') ){
					$edit.text('ปรับตำแหน่ง');
					self.$elem.removeClass('has-cropimage');
					$wrap.removeClass('hidden_elem');
					$img.addClass('hidden_elem').empty();
				}
				else{
					$edit.text('ยกเลิก');
					self.$elem.addClass('has-cropimage');
					setcrop();
					self.cropperImage( self.$elem.find('.preview') );
				}	
			});

			$remove.click(function (e) {
				e.preventDefault();

				Dialog.load( self.options.action_url, {}, {

					onSubmit: function ( data ) {
						$form = data.$pop.find('form.model-content');
						Event.inlineSubmit( $form ).done(function( result ) {
							Event.processForm($form, result);

							if( result.status==1 ){
								self.clear();
							}
						});
					},
					onClose: function () {}
				});
			});

			var scaledW = self.options.scaledX;
			var scaledH = self.options.scaledY;

			var width = self.$elem.width();
			var height = ( scaledH * width ) / scaledW;

			function setcrop() {
				$img.css({
					width: width,
					height: height
				}).append( 
					$('<img>', {class: 'img img-crop',src: self.options.original_url })
				);
			}

			$wrap.css({
				width: width,
				height: height
			}).html( $('<img>', {class: 'img', src: self.options.url }) );
		},

	};

	$.fn.imageCover = function( options ) {
		return this.each(function() {
			var $this = Object.create( imageCover );
			$this.init( options, this );
			$.data( this, 'imageCover', $this );
		});
	};
	$.fn.imageCover.options = {
		scaledX: 640,
		scaledY: 360
	};

	var reportDaily = {
		init: function(options, elem) {
			var self = this;
			self.elem = elem;
			self.$elem = $(elem);

			self.options = $.extend( {}, $.fn.reportDaily.options, options );
			self.$country = self.$elem.find("[name=country_id]");
			self.$series = self.$elem.find("[name=ser_id]");
			self.$company = self.$elem.find("[name=agency_company_id]");
			self.$agency = self.$elem.find("[name=agen_id]");

			self.setElem();
			self.Event();
		},
		setElem: function(){
			var self = this;

			self.setSeries();
		},
		Event: function(){
			var self = this;

			self.$country.change(function(){
				self.setSeries();
			});

			self.$company.change(function(){
				self.setAgency();
			});
		},
		setSeries: function(){
			var self = this;
			var country = self.$country.val();
			self.$series.empty();
			self.$series.append( $('<option>', {text:'- ทั้งหมด -', value:''}) );
			$.get( Event.URL + 'reports/getProducts/' + country, function(res){
				$.each( res, function(i, obj) {
					self.$series.append( $('<option>', {text:obj.code, value:obj.id}) );
				});
			},'json');
		},
		setAgency: function(){
			var self = this;
			var company = self.$company.val();
			self.$agency.empty();
			self.$agency.append( $('<option>', {text:'- ทั้งหมด -', value:''}) );
			$.get( Event.URL + 'reports/getAgency/'+company, function(res){
				$.each( res, function(i, obj){
					self.$agency.append( $('<option>', {text:obj.fname+' '+obj.lname+' ('+obj.nickname+')', value:obj.id}) );
				});
			},'json');
		}
	};
	$.fn.reportDaily = function( options ) {
		return this.each(function() {
			var $this = Object.create( reportDaily );
			$this.init( options, this );
			$.data( this, 'reportDaily', $this );
		});
	};
	$.fn.reportDaily.options = {};

	var reportRecevied = {
		init: function(options, elem) {
			var self = this;
			self.elem = elem;
			self.$elem = $(elem);

			self.options = $.extend( {}, $.fn.reportDaily.options, options );

			// self.$date = self.$elem.find('[name=date]');
			self.$country = self.$elem.find('[name=country_id]');
			self.$series = self.$elem.find('[name=ser_id]');
			self.$bankbook = self.$elem.find('[name=bankbook_id]');

			self.$submit = self.$elem.find('.js-search');

			self.setElem();
			self.Event();
		},
		setElem: function(){
			var self = this;
			self.setSeries();
		},
		Event: function(){
			var self = this;

			self.$country.change(function(){
				self.setSeries();
			});

			self.$submit.click(function(){
				self.setReport();
			});
		},
		setSeries: function(){
			var self = this;
			var country = self.$country.val();
			self.$series.empty();
			self.$series.append( $('<option>', {text:'- ทั้งหมด -', value:''}) );
			$.get( Event.URL + 'reports/getProducts/' + country, function(res){
				$.each( res, function(i, obj) {
					self.$series.append( $('<option>', {text:obj.code, value:obj.id}) );
				});
			},'json');
		},
		setReport: function(){
			var self = this;

			self.$elem.find("#reportDaily").html( '<div class="tac"><div class="loader-spin-wrap" style="display:inline-block;"><div class="loader-spin"></div></div></div>' );

			var date = self.$elem.find('[name=date]').val();
			var country = self.$country.val();
			var series = self.$series.val();
			var bankbook = self.$bankbook.val();

			$.get( Event.URL + 'reports/recevied_daily/', {date:date, country:country, series:series, bankbook:bankbook}, function(res){
				self.$elem.find("#reportDaily").html( res );
			});
		}
	};
	$.fn.reportRecevied = function( options ) {
		return this.each(function() {
			var $this = Object.create( reportRecevied );
			$this.init( options, this );
			$.data( this, 'reportRecevied', $this );
		});
	};
	$.fn.reportRecevied.options = {};

	var formAgencyCompany = {
		init: function(options, elem) {
			var self = this;
			self.elem = elem;
			self.$elem = $(elem);

			self.options = $.extend( {}, $.fn.reportDaily.options, options );
			self.$geo = self.$elem.find("#agen_com_geo");
			self.$province = self.$elem.find('#agen_com_province');
			self.$amphur = self.$elem.find("#agen_com_amphur");

			self.currProvince = self.options.province;
			self.currAmphur = self.options.amphur;

			self.setElem();
			self.Event();
		},
		setElem: function(){
			var self = this;

			if( self.$geo.val() ){
				self.setProvince();
			}
			if( self.currProvince ){
				self.setAmphur();
			}
		},
		Event: function(){
			var self = this;

			self.$geo.change(function(){
				self.setProvince();
			});
			self.$province.change(function(){
				self.setAmphur();
			});
		},
		setProvince: function(){
			var self = this;
			var geo = self.$geo.val();
			$.get( Event.URL + 'agency_company/listsProvince/'+geo, function(res){
				self.$province.empty();
				self.$province.append( $('<option>', {value:"", text:"--- เลือกจังหวัด ---"}) );
				$.each( res, function(i, obj) {
					var li = $('<option>', {value:obj.id, text:obj.name, 'data-id':obj.id});
					if( self.currProvince == obj.id ){
						li.prop('selected', true);
					}
					self.$province.append( li );
				});
			},'json' );
		},
		setAmphur: function(){
			var self = this;
			var province = self.$province.val();
			$.get( Event.URL + 'agency_company/listsAmphur/'+province, function(res){
				self.$amphur.empty();
				self.$amphur.append( $('<option>', {value:"", text:"--- เลือกเขต/อำเภอ ---"}) );
				$.each( res, function(i, obj){
					var li = $('<option>', {value:obj.id, text:obj.name, 'data-id':obj.id});
					if( self.currAmphur == obj.id ){
						li.prop('selected', true);
					}
					self.$amphur.append( li );
				});
			},'json' );
		}
	}
	$.fn.formAgencyCompany = function( options ) {
		return this.each(function() {
			var $this = Object.create( formAgencyCompany );
			$this.init( options, this );
			$.data( this, 'formAgencyCompany', $this );
		});
	};
	$.fn.formAgencyCompany.options = {};

	var bookingForm = {
		init: function(options, elem) {
			var self = this;
			self.elem = elem;
			self.$elem = $(elem);

			self.options = $.extend( {}, $.fn.bookingForm.options, options );

			self.deposit = self.options.deposit;
			self.$company = self.$elem.find("#sgent");
			self.currAgency = self.options.agen_id;
			self.$agency = self.$elem.find("#sale");
			self.currDiscount = self.options.discount;

			self.$discount = self.$elem.find("[name=book_discount]");

			self.$listsItem = self.$elem.find("[role=listsItem]");

			self.setElem();
			self.Event();
			self.calculate();
		},
		setElem: function(){
			var self = this;

			if( self.options.items.length==0 ){
				self.getItem();
			}else{
				$.each( self.options.items, function (i, obj) {
					self.getItem(obj);
				} );
			}

			$.each( self.$elem.find('[data-action=selector]'), function(){
				var key = $(this).attr('id'),
				val = $(this).val() || 0;
				self.$elem.find('[data-summary-id='+ key +']').find('[data-qty]').attr('data-qty', val).text( val );
			} );

			if( self.$company.val() != '' ){
				self.setAgency();
			}

			self.$company.change(function(){
				self.setAgency();
			});
		},
		Event: function(){
			var self = this;
			self.$elem.find('[data-action=selector]').change(function() {
				var key = $(this).attr('id'),
				val = $(this).val() || 0;
				self.$elem.find('[data-summary-id='+ key +']').find('[data-qty]').attr('data-qty', val).text( val );
				self.$discount.val( 0 );
				self.calculate();
			});

			self.$elem.find(".js-add-item").click(function(){
				self.$listsItem.append( self.setItem( {} ) );
				self.sortItem();
			});
			self.$elem.delegate(".js-remove", "click", function(){
				var tr = $(this).closest("tr");

				if( self.$listsItem.find('tr').length==1 ){
					tr.find(':input').val('');
					tr.find('.js-total').empty();
					tr.find(':input').first().focus();
				}
				else{
					tr.remove();
				}

				self.sortItem();
				self.calculate();
			});
			self.$elem.delegate(".js-price", "change", function(){
				var tr = $(this).closest("tr");
				self.summaryBox( tr );
				self.calculate();
			});
			self.$elem.delegate(".js-qty", "change", function(){
				var tr = $(this).closest("tr");
				self.summaryBox( tr );
				self.calculate();
			});
			self.$elem.find("input[name=book_discount]").change(function(){
				self.calculate();
			});
		},
		calculate: function(){
			var self = this;

			var comQty = 0;
			$.each( self.$elem.find('.tablePrice .sum-qty'), function () {
				comQty += parseInt( $(this).find('[data-qty]').attr('data-qty') ) || 0;
			} );

			var disQty = 0;
			$.each( self.$elem.find('.tablePrice .sum-dis'), function() {
				disQty += parseInt( $(this).find('[data-qty]').attr('data-qty') ) || 0;
			} );

			self.$elem.find('[data-summary-id=agency_com], [data-summary-id=sales_com]').find('[data-qty]').attr('data-qty', comQty).text( comQty );
			self.$elem.find('[data-summary-id=discount], [data-summary-id=promotion]').find('[data-qty]').attr('data-qty', disQty).text( disQty );

			var _deposit = self.deposit*comQty;
			self.$elem.find('[data-deposit]').text( PHP.number_format(_deposit) );

			var income = 0;
			var discount_extra = 0;
			var discount = 0;
			var input_discount = parseInt( self.$discount.val() ) || 0;
			$.each( self.$elem.find('[data-summary=item]'), function(index, el) {

				var type = $(this).attr('data-type');
				var price = parseInt( $(this).find('[data-price]').attr('data-price') ) || 0;
				var qty = parseInt( $(this).find('[data-qty]').attr('data-qty') ) || 0;
				var total = price*qty;

				if( type=='discount' || type=='discount_extra' ){
					discount+=total;
					if( type == 'discount_extra' ){
						discount_extra += total;
					}
				}
				else{
					income += total;
				}

				$(this).find('[data-total]').text( PHP.number_format(total) );
			});

			if( input_discount > 0 ){
				discount += (input_discount - discount_extra);
				discount_extra += (input_discount - discount_extra);
			}

			var subtotal = income-discount;
			if(subtotal < 0) subtotal = 0;

			$.each( self.$listsItem.find("tr"), function(){
				var ex_total = $(this).find(".js-input-total").val() || 0;
				subtotal += parseInt(ex_total);
			});

			self.$elem.find('[data-summary=subtotal]').text( PHP.number_format(subtotal) );
			self.$elem.find('[data-fullpay]').text( PHP.number_format(subtotal-_deposit) );

			/* SET DISCOUNT */
			self.$elem.find('[name=book_discount]').val( discount_extra );
		},
		setAgency: function(){
			var self = this;
			var company = self.$company.val();

			$.get( Event.URL + 'booking/listsAgency/' + company, function(res){
				self.$agency.empty();
				self.$agency.append( $("<option>", {value:"", text:"-- เลือกเซลล์ --"}) );
				$.each( res.lists, function(i,obj){
					var li = $("<option>", {value:obj.id, text:obj.fullname});

					if( self.currAgency == obj.id ){
						li.prop("selected", true);
					}

					self.$agency.append( li );
				});
			}, 'json' );
		},
		getItem: function (data) {
			var self = this;

			self.$listsItem.append( self.setItem( data || {} ) );
			self.sortItem();
		},
		setItem: function( data ){
			var self = this;

			$tr = $('<tr>', {style:""});
			$tr.append(
				$('<td>', {class: "no tac"}),
				$('<td>').append( 
					$("<input>", {type:"text", name:"book_list[name][]", class:"inputtext", style:"width: 100%;"}) 
				),
				$('<td>').append(
					$("<input>", {type:"text", name:"book_list[price][]", class:"inputtext js-price tac", style:"width: 100%;"})
				),
				$('<td>').append(
					$("<input>", {type:"text", name:"book_list[qty][]", class:"inputtext js-qty tac", style:"width: 100%;"})
				),
				$('<td>', {class:"tac"}).append(
					$("<span>", {class:"js-total"}),
					$("<input>", {type:"hidden", name:"book_list[total][]", class:"inputtext js-input-total", style:"width: 100%;"})
				),
				$('<td>', {class:"tac"}).append(
					$("<span>", {class:"gbtn"}).append(
						$("<a>", {class:"btn btn-red btn-no-padding js-remove", style:"color:#fff;"}).append( $("<i>", {class:"icon-remove"}) )
					)
				)
			);

			return $tr;
		},
		sortItem: function(){
			var self = this;

			var c = 0;
			$.each(self.$listsItem.find('tr'), function() {
				var $item = $(this);
				c++;
				$item.find('.no').text( c+'.' );
			});
		},
		summaryBox: function( box ){
			var self = this;

			var price = box.find('.js-price').val();
			var qty = box.find('.js-qty').val();
			var total = box.find(".js-total");
			var input_total = box.find('.js-input-total');

			qty = parseInt(qty) || 0;
			price = parseInt(price) || 0;

			$total = parseInt(qty) * parseInt(price);
			total.text( PHP.number_format( $total ) );
			input_total.val( $total );

			// self.setExtraPrice();
		}
	}
	$.fn.bookingForm = function( options ) {
		return this.each(function() {
			var $this = Object.create( bookingForm );
			$this.init( options, this );
			$.data( this, 'bookingForm', $this );
		});
	};
	$.fn.bookingForm.options = {};
	
})( jQuery, window, document );


$(function () {
	
	// navigation
	$('.navigation-trigger').click(function(e){
		e.preventDefault();
		$('body').toggleClass('is-pushed-left', !$('body').hasClass('is-pushed-left'));

		$.get( Event.URL + 'me/navTrigger', {
			'status': $('body').hasClass('is-pushed-left') ? 1:0
		});
	});

	$('.customers-main').click(function(e){

		var $parent = $(this).closest('.customers-content');
		if( $parent.hasClass('is-pushed-right') ){
			$parent.removeClass('is-pushed-right');
		}
		e.preventDefault();
	});


	$('.customers-right-link-toggle').click(function(e){
		var $parent = $(this).closest('.customers-content');
		$parent.toggleClass('is-pushed-right', !$parent.hasClass('is-pushed-right'));

		e.preventDefault();
		// e.stopPropagation();
	});
	
	
});