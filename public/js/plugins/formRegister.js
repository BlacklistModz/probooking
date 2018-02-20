// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
    var formRegister = {
		init: function(options, elem) {
			var self = this;
            self.elem = elem;
            self.$elem = $( elem );

            self.is_keycodes = [37,38,39,40,13];
            self.has_load = false;
            self._otext = '';
            self.is_focus = false;

            self.options = $.extend( {}, $.fn.formRegister.options, options );

            self.$tap = self.$elem.find('.js-current-step');
            self.$company = self.$elem.find('input#agen_com_name');
            self.$company.wrap( '<div class="ui-search"></div>' );
            self.$company.parent().append( 
                  $('<div>', {class: 'loader loader-spin-wrap'}).html( $('<div>', {class: 'loader-spin'}) )
                , $('<div>', {class: 'overlay'})
            );

            self.$type = self.$elem.find('input[name=type]');

            self.$next = self.$elem.find('#btn-next');
            self.$back = self.$elem.find('#btn-back');
            self.$submit = self.$elem.find('.btn-submit');

            self.currTap = self.$elem.find(".js-current-step li.active").find('a').data('target').substr(5); // get current state

            self.setMenuCompany();
            self.setElem();
            self.Events();
        },
        setElem: function(){
            var self = this;
            self.$elem.find('#form2').addClass('hidden_elem')
            self.$elem.find('#form3').addClass('hidden_elem')
            self.setButton( self.currTap );
            
        },
        Events: function(){
            var self = this;
            // self.$tap.click(function(){
            //   const whereisgo = $(this).data("target").substr(5);   
            //   self.$tap.removeClass('active');
            //   $(this).addClass('active');
            //   self.$elem.find(".js-hidden-form").addClass("hidden_elem");
            //   self.$elem.find("#form"+whereisgo).removeClass("hidden_elem");

            //   self.setButton( whereisgo );
            //   if( whereisgo == 3 ){
            //     self.setPreview();
            //   }
            // });

            self.$next.click(function( e ){
                if( self.currTap == 3 ){
                    return false;
                }
                else{
                    self.submit( self.currTap );
                    e.preventDefault();

                    // self.currTap = parseInt(self.currTap)+1;
                    // self.setTap( self.currTap );
                    // self.setButton( self.currTap );
                }
            });

            self.$back.click(function(){
                if( self.currTap == 1 ){
                    return false;
                }
                else{
                    self.currTap = parseInt(self.currTap)-1;
                    self.setTap( self.currTap );
                    self.setButton( self.currTap );
                }
            });

            self.$submit.click(function(e){
                $(this).addClass('disabled');
                $(this).attr('disabled', true);

                self.submit( self.currTap );
                e.preventDefault();
            });

            var v;
            self.$company.keyup(function (e) {
                var $this = $(this);
                var value = $.trim($this.val());

                if( self.is_keycodes.indexOf( e.which )==-1 && !self.has_load ){

                    self.$company.parent().addClass('has-load');
                    self.hideCompany();
                    clearTimeout( v );

                    if(value==''){

                        self.$company.parent().removeClass('has-load');
                        return false;
                    }

                    v = setTimeout(function(argument) {

                        self.has_load = true;
                        self.search( value );
                    }, 500);

                }
            }).keydown(function (e) {
                var keyCode = e.which;

                if( keyCode==40 || keyCode==38 ){

                    self.changeUpDownCompany( keyCode==40 ? 'donw':'up' );
                    e.preventDefault();
                }

                if( keyCode==13 && self.$menuCompany.find('li.selected').length==1 ){

                    self.activeCompany(self.$menuCompany.find('li.selected').data());
                }
            }).click(function (e) {
                var value = $.trim($(this).val());

                if(value!=''){

                    if( self._otext==value ){
                        self.displayCompany();
                    }
                    else{

                        self.$company.parent().addClass('has-load');
                        self.hideCompany();
                        clearTimeout( v );

                        self.has_load = true;
                        self.search( value );
                    }
                }

                e.stopPropagation();
            }).blur(function () {

                if( !self.is_focus ){
                    self.hideCompany();
                }
            });

            self.$menuCompany.delegate('li', 'mouseenter', function() {
                $(this).addClass('selected').siblings().removeClass('selected');
            });
            self.$menuCompany.delegate('li', 'click', function(e) {
                $(this).addClass('selected').siblings().removeClass('selected');
                self.activeCompany($(this).data());

                    // e.stopPropagation();
                });
            self.$menuCompany.mouseenter(function() {
                self.is_focus = true;
            }).mouseleave(function() { 
                self.is_focus = false;
            });

            $('html').on('click', function() {
                self.hideCompany();
            });
        },
        setTap: function( tap ){
            var self = this;

            self.$tap.find('li').removeClass('active');
            self.$elem.find('[data-target=#form'+tap+']').closest('li.part').addClass('active');
            self.$elem.find(".js-hidden-form").addClass("hidden_elem");
            self.$elem.find("#form"+tap).removeClass("hidden_elem");
            self.setButton( tap );
        },
        setButton: function( tap ){
            var self = this;
            if( tap == 1 ){
                self.$type.val( 'company' );
                self.$back.addClass('hidden_elem');
                self.$next.removeClass('hidden_elem');
                self.$submit.addClass('hidden_elem');
            }
            else if( tap == 2 ){
                self.$type.val( 'agency' );
                self.$back.removeClass('hidden_elem');
                self.$next.removeClass('hidden_elem');
                self.$submit.addClass('hidden_elem');
            }
            else if( tap == 3){
                self.$type.val( 'save' );
                self.$back.removeClass('hidden_elem');
                self.$next.addClass('hidden_elem');
                self.$submit.removeClass('hidden_elem');
            }
        },
        setPreview: function(){
            var self = this;
            self.$elem.find('.preview').empty();
            if(self.$elem.find('input[name=agency_company_id]').length ==0){
                var dataCompany = {
                    "บริษัท":$('#agen_com_name').val(),
                    "ที่อยู่บริษัท":$('#agen_com_address1').val(),
                    "เบอร์โทรศัพท์":$('#agen_com_tel').val(),
                    "แฟกซ์":$('#agen_com_fax').val(),
                    "เลขที่ ททท":$('#agen_com_ttt_on').val(),
                    "อีเมล บริษัท":$('#agen_com_email').val(),
                            }         
            }else{
                var dataCompany = {
                    "บริษัท":$('#agen_com_name').val()                    
                            }            
            }
            dataSales = {
                "ชื่อ":$('#agen_fname').val(),
                "นามสกุล":$('#agen_lname').val(),
                "ชื่อเล่น":$('#agen_nickname').val(),
                "ตำแหน่ง":$('#agen_position').val(),
                "อีเมล":$("#agen_email").val(),
                "มือถือ":$("#agen_tel").val(),
            }
            dataSalesE = {
                "Line ID":$("#agen_line_id").val() =="" ? "-":$("#agen_line_id").val(),
                "Skype ID":$("#agen_skype").val() =="" ? "-":$("#agen_skype").val(), 
                "Username":$("#agen_user_name").val(),
            }
          

            let  _table = $('<table>', {class:"table"});
            let _tbody =$('<tbody>', {class:"tbody"});
            $.each(dataCompany, function(key, values){
                    _tbody.append(
                        $('<tr>').append(
                            $('<td>', {text:key}),
                            $('<td>', {text:values})
                        )
                    )
                  
            })
            _table.append(_tbody);
            self.$elem.find('.preview').append(
                $('<h3>',{text:'ข้อมูลบริษัท',class:'tac'}),
                _table
    
            )
              _table = $('<table>', {class:"table"}); //clear data
             _tbody =$('<tbody>', {class:"tbody"}); // clear data
            $.each(dataSales, function(key, values){
                    _tbody.append(
                        $('<tr>').append(
                            $('<td>', {text:key}),
                            $('<td>', {text:values})
                        )
                    )
                  
            })
            $.each(dataSalesE, function(key, values){
                _tbody.append(
                    $('<tr>').append(
                        $('<td>', {text:key}),
                        $('<td>', {text:values})
                    )
                )
              
        })
            _table.append(_tbody);
            self.$elem.find('.preview').append(
                $('<h3>',{text:'ข้อมูลเซลล์',class:'tac'}),
                _table
            )
         // Event.showMsg({text:'TEST' , load:1, auto:1, color:'red'});
         
        },
        setMenuCompany: function () {
            var self = this;

            var $box = $('<div/>', {class: 'uiTypeaheadView selectbox-selectview'});
            self.$menuCompany = $('<ul/>', {class: 'search has-loading', role: "listbox"});

            $box.html( $('<div/>', {class: 'bucketed'}).append( self.$menuCompany ) );

            var settings = self.$company.offset();
            settings.top += self.$company.outerHeight();

            uiLayer.get(settings, $box);
            self.$layerCompany = self.$menuCompany.parents('.uiLayer');
            self.$layerCompany.addClass('hidden_elem');

            self.$menuCompany.mouseenter(function () {
                self.is_focus = true;
            }).mouseleave(function () {
                self.is_focus = false;
            });

            self.resizeMenuCompany();
            $( window ).resize(function () {
                self.resizeMenuCompany();
            });
        },
        resizeMenuCompany: function() {
            var self = this;

            self.$menuCompany.width( self.$company.outerWidth()-2 );
            var settings = self.$company.offset();
            settings.top += self.$company.outerHeight();
            settings.top -= 1;

            self.$menuCompany.css({
                overflowY: 'auto',
                overflowX: 'hidden',
                maxHeight: $( window ).height()-settings.top
            });

            self.$menuCompany.parents('.uiContextualLayerPositioner').css( settings );
        },
        displayCompany: function () {
            var self = this;

            if( self.$menuCompany.find('li').length == 0 ){
                return false;
            }

            if( self.$menuCompany.find('li.selected').length==0 ){
                self.$menuCompany.find('li').first().addClass('selected');
            }

            self.resizeMenuCompany();
            self.$layerCompany.removeClass('hidden_elem');
        },
        hideCompany: function() {
            this.$layerCompany.addClass('hidden_elem');
        },
        changeUpDownCompany: function( active ) {
            var self = this;

            var length = self.$menuCompany.find('li').length;
            var index = self.$menuCompany.find('li.selected').index();

            if( active=='up' ) index--;
            else index++;

            if( index < 0) index=0;
            if( index >= length) index=length-1;

            self.$menuCompany.find('li').eq( index ).addClass('selected').siblings().removeClass('selected');
        },
        activeCompany: function ( data ) {
            var self = this;

            $remove = $('<button>', {type: 'button', class: 'remove'}).html( $('<i>', {class: 'icon-remove'}) );
            self.$company.prop('disabled', true).val('').parent().addClass('active').find('.overlay').empty().append(
                $remove
                , $('<div>', { class: 'text'}).text( data.com_name )
                , $('<input>', { type: 'hidden', class: 'hiddenInput', value:data.com_id, autocomplete:'off', name: 'agency_company_id' })
                );

            self.$elem.find('input#agen_com_name').val( data.com_name ).addClass('disabled').prop('disabled', true);
            self.$elem.find('input#agen_com_address1').val( data.com_address1 ).addClass('disabled').prop('disabled', true);
            self.$elem.find('input#agen_com_address2').val( data.com_address2 ).addClass('disabled').prop('disabled', true);
            self.$elem.find('input#agen_com_tel').val( data.com_tel ).addClass('disabled').prop('disabled', true);
            self.$elem.find('input#agen_com_fax').val( data.com_fax ).addClass('disabled').prop('disabled', true);
            self.$elem.find('input#agen_com_ttt_on').val( data.com_ttt_on ).addClass('disabled').prop('disabled', true);
            self.$elem.find('input#agen_com_email').val( data.com_email ).addClass('disabled').prop('disabled', true);
            self.$elem.find('.notification').empty();

            self.hideCompany();
            $remove.click(function() {
                self.$elem.find('input#agen_com_name').val( '' ).removeClass('disabled').prop('disabled', false);
                self.$elem.find('input#agen_com_address1').val( '' ).removeClass('disabled').prop('disabled', false);
                self.$elem.find('input#agen_com_address2').val( '' ).removeClass('disabled').prop('disabled', false);
                self.$elem.find('input#agen_com_tel').val( '' ).removeClass('disabled').prop('disabled', false);
                self.$elem.find('input#agen_com_fax').val( '' ).removeClass('disabled').prop('disabled', false);
                self.$elem.find('input#agen_com_ttt_on').val( '' ).removeClass('disabled').prop('disabled', false);
                self.$elem.find('input#agen_com_email').val( '' ).removeClass('disabled').prop('disabled', false);
                self.$company.prop('disabled', false).focus().parent().removeClass('active').find('.overlay').empty();
            });
        },
        search: function ( text ) {
            var self = this;

            var data = {
                q: text,
                limit: 5
            };
            self.$menuCompany.empty();

            $.ajax({
                url: Event.URL + "agency/listsCompany/",
                data: data,
                dataType: 'json'
            }).done(function( results ) {

                if( results.total==0 ){
                    return false;
                }

                self.buildFrag( results.lists );
                self.displayCompany();

            }).fail(function() {

            }).always(function() {

                self._otext = text;
                self.has_load = false;
                self.$company.parent().removeClass('has-load');
            });
        },
        buildFrag: function( results ) {
            var self = this;

            $.each(results, function (i, obj) {
                var li = $('<li/>', {class:'picThumb'} ).html( $('<a>').append( 
                      $('<span/>', {class: 'text', text: obj.com_name}) 
                    , $('<span/>', {class: 'subtext', text: obj.com_address1})
                    ) 
                );

                li.data(obj);
                self.$menuCompany.append( li );
            }); 
        },
        submit: function( type ){
            var self = this;

            if( self.$next.hasClass('btn-error') ){
                self.$next.removeClass('btn-error');
            }

            var formData = Event.formData( self.$elem );

            $.ajax({
                type:'POST',
                url : Event.URL + 'agency/set',
                data : formData,
                dataType : 'json'
            }).done(function (results) {

                Event.processForm(self.$elem, results);

                if( results.error ){
                    self.$next.addClass('btn-error');
                    return false;
                }
                
                if( results.status == 1 ){
                    if( type!=3 && !results.error ){

                        self.currTap = parseInt(self.currTap)+1;
                        self.setTap( self.currTap );
                        self.setButton( self.currTap );

                        if( type == 2 ){
                            self.setPreview();
                        }
                    }
                    else{
                        Event.showMsg({text:results.message,load:1,auto:1});
                        if( results.url == 'refresh' ){
                            window.location.reload();
                        }
                        else{
                            window.location.href(results.url);
                        }
                    }
                }
            });
        }
    }
    $.fn.formRegister = function( options ) {
		return this.each(function() {
			var $this = Object.create( formRegister );
			$this.init( options, this );
			$.data( this, 'formRegister', $this );
		});
	};
	$.fn.formRegister.options = {
		scaledX: 640,
		scaledY: 360
	};
	
})( jQuery, window, document );