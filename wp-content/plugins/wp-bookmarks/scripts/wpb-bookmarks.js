function wpb_bm_dialog(elem, html, position){
	if (!position){ position = 'left'; }

	if (html == 'new_collection'){
	
		elem.append('<div class="wpb-bm-dialog bm-'+position+'"></div><div class="wpb-bm-dialog-icon bm-'+position+'"><i class="wpb-icon-caret-up"></i></div>');
		elem.find('.wpb-bm-dialog').width( elem.parents('.wpb-bm').width() - 42 );
		custom_html = '<form action="" method="post"><input type="text" name="wpb_bm_new" id="wpb_bm_new" value="" class="wpb-bm-input" placeholder="' + elem.parents('.wpb-bm').data('new_collection_placeholder') + '" /><div class="wpb-bm-btn-contain bm-block"><a href="#" class="wpb-bm-btn" data-action="submit_collection">' + elem.parents('.wpb-bm').data('add_new_collection') + '</a></div></form>';
	
	} else {
	
		elem.append('<div class="wpb-bm-dialog bm-'+position+' autoclose"></div><div class="wpb-bm-dialog-icon bm-'+position+' autoclose"><i class="wpb-icon-caret-up"></i></div>');
		elem.find('.wpb-bm-dialog').width( elem.parents('.wpb-bm').width() - 42 );
		custom_html = html;
	
	}
	elem.find('.wpb-bm-dialog').html('<span class="wpb-bm-dialog-content">' + custom_html + '</span>');
	
	if (jQuery('#wpb_bm_new').length) jQuery('#wpb_bm_new').focus();
	
	var timer = setTimeout(function(){ jQuery('.wpb-bm-dialog.autoclose,.wpb-bm-dialog-icon.autoclose').hide().remove(); }, 3000);
	
}

function wpb_bm_newaction( elem, parent ) {
	elem.addClass('stop');
	jQuery('.wpb-bm-dialog,.wpb-bm-dialog-icon').hide().remove();
}

function wpb_bm_donebookmark( elem, html ) {
	elem.addClass('bookmarked').removeClass('unbookmarked').removeClass('stop');
	elem.html( html );
}

function wpb_bm_addbookmark( elem, html ) {
	elem.addClass('unbookmarked').removeClass('bookmarked').removeClass('stop');
	elem.html( html );
}

function wpb_bm_removedialog() {
	jQuery('.wpb-bm-dialog,.wpb-bm-dialog-icon').hide().remove();
}

function wpb_bm_update_active_collection( parent, value ){
	parent.find('input:hidden#collection_id').val( value );
}

/* Custom JS starts here */
jQuery(document).ready(function() {
	
	jQuery(document).on('click', '.wpb-coll a,.wpb-bm', function(e){

	});
	
	/* remove a collection */
	jQuery(document).on('click', '.wpb-remove-collection', function(e){
		e.preventDefault();
		element = jQuery(this).parents('.wpb-coll-count');
		if (element.find('.wpb-coll-remove').is(':hidden')){
		jQuery(this).html( jQuery(this).data('undo') );
		element.find('.wpb-coll-remove').slideToggle();
		} else {
		jQuery(this).html( jQuery(this).data('remove') );
		element.find('.wpb-coll-remove').slideToggle();
		}
		return false;
	});
	
	/* remove a collection */
	jQuery(document).on('click', '.wpb-hard-remove', function(e){
		e.preventDefault();
		collection_id = jQuery(this).data('collection_id');

		/* switch tab */
		list = jQuery(this).parents('.wpb-coll').find('.wpb-coll-list');
		
		jQuery.ajax({
			url: wpb_ajax_url,
			data: 'action=wpb_hard_remove_collection&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				list.find('a.active').remove();
				list.find('a:first').trigger('click');
			}
		});
		return false;
	});
	
	/* soft-remove a collection */
	jQuery(document).on('click', '.wpb-soft-remove', function(e){
	
		e.preventDefault();
		collection_id = jQuery(this).data('collection_id');

		/* switch tab */
		list = jQuery(this).parents('.wpb-coll').find('.wpb-coll-list');
		
		jQuery.ajax({
			url: wpb_ajax_url,
			data: 'action=wpb_soft_remove_collection&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				list.find('a.active').remove();
				list.find('a:first').trigger('click');
			}
		});
		return false;
	});
	
	/* Switch a collection */
	jQuery(document).on('click', '.wpb-coll-list a', function(e){
		e.preventDefault();
		collection_id = jQuery(this).data('collection_id');
		container = jQuery(this).parents('.wpb-coll').find('.wpb-coll-body');
		if (container.hasClass('loading') == false){

		/* switch tab */
		list = jQuery(this).parents('.wpb-coll-list');
		list.find('a').removeClass('active');
		list.find('a').find('i').addClass('wpb-coll-hide');
		list.find('a').find('span').removeClass('wpb-coll-hide');
		jQuery(this).addClass('active');
		jQuery(this).find('i').removeClass('wpb-coll-hide');
		jQuery(this).find('span').addClass('wpb-coll-hide');
		
		container.addClass('loading').find('.wpb-coll-body-inner').find('div:not(.wpb-coll-remove)').fadeTo(0, 0);
		
		jQuery.ajax({
			url: wpb_ajax_url,
			data: 'action=wpb_change_collection&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				container.removeClass('loading').find('.wpb-coll-body-inner').empty().html(data.res);
			}
		});
		
		}
		return false;
	});
	
	/* Disable forms */
	jQuery(document).on('submit', '.wpb-bm form', function(e){
		e.preventDefault();
		return false;
	});

	/* Capture change in collection */
	jQuery(document).on('change', '.wpb-bm-list select', function(e){
		dd = jQuery(this);
		parent = dd.parents('.wpb-bm');
		bookmarked_link = parent.find('a.bookmarked');
		unbookmarked_link = parent.find('a.unbookmarked');
		collection_id = parent.find('input:hidden#collection_id').val();
		if (dd.val() != collection_id){
			wpb_bm_addbookmark( bookmarked_link, parent.data('add_to_collection') );
		} else {
			wpb_bm_donebookmark( unbookmarked_link, parent.data('remove_bookmark') );
		}
	});

	/* trigger submit new collection */
	jQuery(document).on('click', '.wpb-bm-dialog a[data-action="submit_collection"]', function(e){
		jQuery(this).parents('form').trigger('submit');
	});
	
	/* submit new collection */
	jQuery(document).on('submit', '.wpb-bm-dialog form:not(.stop)', function(e){
		e.preventDefault();
		elem = jQuery(this);
		dialog = jQuery(this).parents('.wpb-bm-dialog');
		parent = jQuery(this).parents('.wpb-bm');
		
		collection_name = dialog.find('#wpb_bm_new').val();
		if (collection_name != ''){
		
		elem.addClass('stop');
		default_collection = parent.data('default_collection');
		post_id = parent.data('post_id');
		jQuery.ajax({
			url: wpb_ajax_url,
			data: 'action=wpb_addcollection&post_id='+post_id+'&default_collection='+default_collection+'&collection_name='+collection_name,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				elem.removeClass('stop');
				parent.find('#wpb_bm_collection').replaceWith( data.options );
				parent.find("select").removeClass("chzn-done").css('display', 'inline').data('chosen', null);
				parent.find("*[class*=chzn], .chosen-container").remove();
				jQuery(".chosen-select-collections").chosen({
					disable_search_threshold: 10,
					width: '100%'
				});
				parent.find('#wpb_bm_collection').val( parent.find('#wpb_bm_collection option:last').val() ).trigger("chosen:updated");
				parent.find('.wpb-bm-list select').trigger('change');
				wpb_bm_removedialog();
			}
		});
		
		}
		return false;
	});
	
	/* chosen jquery */
	jQuery(".chosen-select-collections").chosen({
		disable_search_threshold: 10,
		width: '100%'
	});

	/* New collection */
	jQuery(document).on('click', '.wpb-bm a[data-action=newcollection]', function(e){
		e.preventDefault();
		elem = jQuery(this);
		parent = jQuery(this).parents('.wpb-bm');
		
		if ( parent.find('.wpb-bm-dialog form').length == 0){
			wpb_bm_newaction( elem, parent );
			elem.addClass('active');
			wpb_bm_dialog( elem.parent(), 'new_collection', 'right' );
		} else {
			elem.removeClass('active');
			wpb_bm_removedialog();
		}
		return false;
	});

	/* New bookmark */
	jQuery(document).on('click', '.wpb-bm a[data-action=bookmark].unbookmarked:not(.stop)', function(e){
		elem = jQuery(this);
		parent = jQuery(this).parents('.wpb-bm');
		post_id = parent.data('post_id');
		collection_id = parent.find('#wpb_bm_collection').val();
		
		wpb_bm_newaction( elem, parent );

		jQuery.ajax({
			url: wpb_ajax_url,
			data: 'action=wpb_newbookmark&post_id='+post_id+'&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				wpb_bm_update_active_collection( parent, data.collection_id );
				wpb_bm_donebookmark( elem, parent.data('remove_bookmark') );
				wpb_bm_dialog( elem.parent(), parent.data('dialog_bookmarked') );
			}
		});
		return false;
		
	});
	
	/* Remove bookmark */
	jQuery(document).on('click', '.wpb-bm a[data-action=bookmark].bookmarked:not(.stop)', function(e){
		elem = jQuery(this);
		parent = jQuery(this).parents('.wpb-bm');
		post_id = parent.data('post_id');
		collection_id = parent.find('#wpb_bm_collection').val();
		
		wpb_bm_newaction( elem, parent );

		jQuery.ajax({
			url: wpb_ajax_url,
			data: 'action=wpb_removebookmark&post_id='+post_id+'&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				wpb_bm_addbookmark( elem, parent.data('add_to_collection') );
				wpb_bm_dialog( elem.parent(), parent.data('dialog_unbookmarked') );
			}
		});
		return false;
		
	});
	
	/* Remove bookmark */
	jQuery(document).on('click', 'a.wpb-coll-abs', function(e){
		elem = jQuery(this);
		parent = jQuery(this).parents('.wpb-coll-item');
		post_id = elem.data('post_id');
		collection_id = elem.data('collection_id');

		parent.fadeOut('fast');
		
		jQuery.ajax({
			url: wpb_ajax_url,
			data: 'action=wpb_removebookmark&post_id='+post_id+'&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){

			}
		});
		return false;
		
	});
	
});