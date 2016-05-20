/* responsiveness of sidebar starts */
jQuery(document).ready(function ($) {

	var go_to_elm = false;

	$( '.pado-back-top a' ).click( function ( e ) {
		$( 'body,html' ).animate( { scrollTop: pado_top_offset }, 800 );
		e.preventDefault();
	});

	//$('#pado-sidebar').css({ position: 'relative', top: pado_offset });

	var scroll_resize = function(){
		var stickyTop = $('#pado-sidebar').offset().top; // returns number
		var windowWidth = jQuery(window).width();
		var windowTop = $(window).scrollTop(); // returns number
		var widthMain = parseInt($("#pado-main").css("width")) ;
		var documentMainTop = $("#pado-main").offset().top;
		var windowHeight = $( window ).height();
		var documentSidebarTop = document.getElementById( 'pado-sidebar' ).getBoundingClientRect().top;

		widthContent = widthMain * ((98 - pado_sidebar_width) / 100);
		widthSidebar = widthMain * (pado_sidebar_width / 100);

		if ( 767 >= windowWidth ) {
			$('#pado-sidebar').perfectScrollbar( 'destroy' );
			$('#pado-sidebar').height( '' );
		} else {
			$('#pado-sidebar').perfectScrollbar();
			$('#pado-sidebar').height( windowHeight - documentSidebarTop ).perfectScrollbar( 'update' );
			$('#pado-content').css({ width: widthContent });
		}

		if ( documentMainTop < windowTop ){
			if ( 767 >= windowWidth ) {
				$('#pado-sidebar').css({ position: 'relative', top: 0 });
				$('#pado-sidebar').css({ width: widthSidebar });
			} else {
				$('#pado-sidebar').css({ position: 'fixed', top: pado_offset });
				$('#pado-sidebar').css({ width: widthSidebar });
			}

		} else {
			$('#pado-sidebar').css({ position: 'relative', top: 0 });
			$('#pado-sidebar').css({ width: widthSidebar });
		}

		return;
	};

	$(window).scroll(scroll_resize);
	$(window).resize(scroll_resize);

	// Resize once
	$(window).trigger( 'scroll' );

	/* responsiveness of sidebar ends */


	$('.sidebar_cat_title').parent().find('.sidebar_doc_title').hide();
	$('.sidebar_cat_title a').click(function(){
		$this = $(this);
		if($this.parent().parent().hasClass('open_arrow')) {
			$this.parent().parent().removeClass('open_arrow');
			$this.parents('ul').find('.sidebar_doc_title').slideUp('fast');
		} else {
			$('.sidebar_doc_title').slideUp();
			$('.open_arrow').removeClass('open_arrow');
			$this.parent().parent().addClass('open_arrow');
			$this.parents('ul').find('.sidebar_doc_title').slideDown('fast');
		}
	}).first().trigger('click');

	$('.pado-section-enter').each(function(i) {
		var position = $(this).position();
		var cls = $(this).attr('id');

		$(this).scrollspy({
			min: position.top,
			max: position.top + $(this).height(),

			onEnter: function(element, position) {
				if(go_to_elm) {
					return;
				}

				var $element = $('.' + cls);
				if($element.parent().hasClass('open_arrow')) {
					//we don't need to do anything here
				} else {
					$('.sidebar_doc_title').slideUp();
					$('.open_arrow').removeClass('open_arrow');
					$element.parent().addClass('open_arrow');
					$element.parents('ul').find('.sidebar_doc_title').slideDown('fast');
				}

			},

			onLeave: function(element, position) {
				//console.log(cls + ' leave');
			}
		});
	});

	$('.type-pressapps_document').each(function(i) {
		var position = $(this).position();
		var cnt = $(this).data('count');
		//console.log(cnt);
		var elem = $('.pado-document-' + cnt).first();

		$(this).scrollspy({
			min: position.top,
			max: position.top + $(this).height(),

			onEnter: function(element, position) {
				if(go_to_elm) {
					return;
				}
				elem.addClass('sidebar_doc_active');
			},

			onLeave: function(element, position) {
				elem.removeClass('sidebar_doc_active');
			}
		});
	});

	$( '.sidebar_doc_title a,.pado_sidebar_cat_title' ).each( function () {

		var destination = '';
		$( this ).click( function( e ) {
			e.preventDefault();
			go_to_elm = true;
			var elementClicked = $( this ).attr( 'href' );
			var elementOffset = jQuery( 'body' ).find( elementClicked ).offset();
			destination = elementOffset.top;
			jQuery( 'html,body' ).animate( { scrollTop: destination - pado_offset }, 300 );
			setTimeout(function(){
				go_to_elm = false;
			}, 800);

		} );

	});

	/***************************************************
	 Docs Voting
	 ***************************************************/

	$('a.pado-like-btn').click(function(){
		response_div = jQuery(this).parent().parent();
		$.ajax({
			url         : PADO.base_url,
			data        : {'vote_like':jQuery(this).attr('post_id')},
			beforeSend  : function(){},
			success     : function(data){
				response_div.hide().html(data).fadeIn(400);
			},
			complete    : function(){}
		});
	});

	$('a.pado-dislike-btn').click(function(){
		response_div = jQuery(this).parent().parent();
		$.ajax({
			url         : PADO.base_url,
			data        : {'vote_dislike':jQuery(this).attr('post_id')},
			beforeSend  : function(){},
			success     : function(data){
				response_div.hide().html(data).fadeIn(400);
			},
			complete    : function(){}
		});
	});

	$('p.pado-likes').tooltip({
		'placement' : 'top'
	});

	$('p.pado-dislikes').tooltip({
		'placement' : 'top'
	});

});