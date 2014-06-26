jQuery(document).ready(function( $ ) {


	var bar = $('#sdrn_bar'), //top bar that shows/hides the menu
		bar_height = bar.outerHeight(true), //the bar height
		from_width = bar.attr('data-from_width'),
		menu = $('#sdrn_menu'), //the menu div
		menu_ul = $('#sdrn_menu_ul'), //the menu ul
		menu_a = menu.find('a'), //single menu link
		body = $('body'),
		html = $('html'),
		animation_speed = 300,
		ab = $('#wpadminbar'),
		menu_enabled = (bar.length > 0 && menu.length > 0)? true : false,
		menu_width = menu.width(),
		target_height = (window.innerHeight < body.height())? body.height() : window.innerHeight,
		target_width = (window.innerWidth < body.width())? body.width() : window.innerWidth;





	if(menu_enabled) {


		menu_ul.find('li').first().css({'border-top':'none'});


		$(document).mouseup(function (e) {
    		if (!menu.is(e.target) && menu.has(e.target).length === 0) {
        		if(menu.is(':visible')) {
    				close_sub_uls();
    				$.sidr('close', 'sdrn_menu');
    			}
    		}
		});





		//ENABLE NESTING

		//add arrow element to the parent li items and chide its child uls
		menu.find('ul.sub-menu').each(function() {
			var sub_ul = $(this),
				parent_a = sub_ul.prev('a'),
				parent_li = parent_a.parent('li').first();

			parent_a.addClass('sdrn_parent_item');
			parent_li.addClass('sdrn_parent_item_li');

			var expand = parent_a.before('<span class="sdrn_icon sdrn_icon_par icon_default '+menu.attr('data-custom_icon')+'"></span> ').find('.sdrn_icon_par');

			sub_ul.hide();
		});





		//adjust the a width on parent uls so iyt fits nicely with th eicon elemnt
		function adjust_expandable_items() {
			$('.sdrn_parent_item_li').each(function() {
				var t = $(this),
					main_ul_width = 0,
					icon = t.find('.sdrn_icon_par').first(),
					link = t.find('a.sdrn_parent_item').first();

				if(menu.hasClass('top')) {
					main_ul_width = window.innerWidth;
				} else {
					main_ul_width = menu_ul.innerWidth();
				}

				if(t.find('.sdrn_clear').length == 0) link.after('<br class="sdrn_clear"/>');
			});
		}
		adjust_expandable_items();





		//expand / collapse action (SUBLEVELS)
		$('.sdrn_icon_par').on('click',function() {
			var t = $(this),
				//child_ul = t.next('a').next('ul');
				child_ul = t.parent('li').find('ul.sub-menu').first();
			child_ul.slideToggle(300);
			t.toggleClass('sdrn_par_opened');
			if(menu.attr('data-custom_icon') != '') t.toggleClass(menu.attr('data-custom_icon_open'));
			t.parent('li').first().toggleClass('sdrn_no_border_bottom');
		});





		//helper - close all submenus when menu is hiding
		function close_sub_uls() {
			menu.find('ul.sub-menu').each(function() {
				var ul = $(this),
					icon = ul.parent('li').find('.sdrn_icon_par'),
					li = ul.parent('li');

				if(ul.is(':visible')) ul.slideUp(300);
				icon.removeClass('sdrn_par_opened');
				li.removeClass('sdrn_no_border_bottom');
			});
		}




		//fix the scaling issue by adding/replacing viewport metatag
		var mt = $('meta[name=viewport]');
		mt = mt.length ? mt : $('<meta name="viewport" />').appendTo('head');
		if(menu.attr('data-zooming') == 'no') {
			mt.attr('content', 'user-scalable=no, width=device-width, maximum-scale=1, minimum-scale=1');
		} else {
			mt.attr('content', 'user-scalable=yes, width=device-width, initial-scale=1.0, minimum-scale=1');
		}


		//Additional fixes on change device orientation
		if( $.browser.mozilla ) {
			screen.addEventListener("orientationchange", function() {updateOrientation()}); //firefox
		} else {
			window.addEventListener('orientationchange', updateOrientation, false);
		}
		function updateOrientation() {
			window.scrollBy(1,1);
			window.scrollBy(-1,-1);

			menu_width = menu.width();

			//update the page posion for left menu
			if(menu.is(':visible') && menu.hasClass('left')) {
				body.css({'left':menu_width});
				body.scrollLeft(0);
			}
		}




		//apply the SIDR for the left/right menu
		if(menu.hasClass('left') || menu.hasClass('right')) {


			//appy sidr
			var hor_pos = (menu.hasClass('left'))? 'left' : 'right';
			bar.sidr({
				name:'sdrn_menu',
				side: hor_pos,
				speed: animation_speed
			});


			//if menu is open  - on bar click: close sub ul-s
			bar.on('click', function() {
				if(menu.is(':visible')) {
					close_sub_uls();
				}
				bar.toggleClass('menu_is_opened');
			});


			//when link is clicked - hide the menu first and then change location to new page
			menu_a.on('click', function(e) {
				e.preventDefault();
				var url = $(this).attr('href')
				$.sidr('close', 'sdrn_menu');
				setTimeout(function() {
					window.location.href = url;
				}, animation_speed);
			});




    		$(window).touchwipe({
		        wipeLeft: function() {
		          // Close
		          $.sidr('close', 'sdrn_menu');
		        },
		        wipeRight: function() {
		          // Open
		          $.sidr('open', 'sdrn_menu');
		        },
		        preventDefaultEvents: false
	      	});


	      	$(window).resize(function(){
	      		target_width = (window.innerWidth < body.width())? body.width() : window.innerWidth;
				if(target_width > from_width && menu.is(':visible')) {
					close_sub_uls();
    				$.sidr('close', 'sdrn_menu');
				}
			});


		} else if(menu.hasClass('top')) { //The top positioned menu


			body.prepend(menu);

			//show / hide the menu
			bar.on('click', function(e) {

				//scroll window top
				$("html, body").animate({ scrollTop: 0 }, animation_speed);

				close_sub_uls();
				menu.stop(true, false).slideToggle(animation_speed);


			});


			//when link is clicked - hide the menu first and then change location to new page
			menu_a.on('click', function(e) {
				e.preventDefault();
				var url = $(this).attr('href');

				menu.slideUp(animation_speed,function() {
					//go to the url from the link
					window.location.href = url;
				});
			});


			$(window).resize(function(){
	      		target_width = (window.innerWidth < body.width())? body.width() : window.innerWidth;
				if(target_width > from_width && menu.is(':visible')) {
					close_sub_uls();
    				menu.slideUp(animation_speed, function() {});
				}
			});


		} //end if class left / top /right

	} //end if menu enabled




});