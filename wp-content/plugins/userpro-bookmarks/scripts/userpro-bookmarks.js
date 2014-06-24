function userpro_bm_dialog(elem, html, position){
	if (!position){ position = 'left'; }

	if (html == 'new_collection'){
	
		elem.append('<div class="userpro-bm-dialog bm-'+position+'"></div><div class="userpro-bm-dialog-icon bm-'+position+'"><i class="userpro-icon-caret-up"></i></div>');
		elem.find('.userpro-bm-dialog').width( elem.parents('.userpro-bm').width() - 42 );
		custom_html = '<form action="" method="post"><input type="text" name="userpro_bm_new" id="userpro_bm_new" value="" class="userpro-bm-input" placeholder="' + elem.parents('.userpro-bm').data('new_collection_placeholder') + '" /><div class="userpro-bm-btn-contain bm-block"><a href="#" class="userpro-bm-btn" data-action="submit_collection">' + elem.parents('.userpro-bm').data('add_new_collection') + '</a></div></form>';
	
	} else {
	
		elem.append('<div class="userpro-bm-dialog bm-'+position+' autoclose"></div><div class="userpro-bm-dialog-icon bm-'+position+' autoclose"><i class="userpro-icon-caret-up"></i></div>');
		elem.find('.userpro-bm-dialog').width( elem.parents('.userpro-bm').width() - 42 );
		custom_html = html;
	
	}
	elem.find('.userpro-bm-dialog').html('<span class="userpro-bm-dialog-content">' + custom_html + '</span>');
	
	if (jQuery('#userpro_bm_new').length) jQuery('#userpro_bm_new').focus();
	
	var timer = setTimeout(function(){ jQuery('.userpro-bm-dialog.autoclose,.userpro-bm-dialog-icon.autoclose').hide().remove(); }, 3000);
	
}

function userpro_bm_newaction( elem, parent ) {
	elem.addClass('stop');
	jQuery('.userpro-bm-dialog,.userpro-bm-dialog-icon').hide().remove();
}

function userpro_bm_donebookmark( elem, html ) {
	elem.addClass('bookmarked').removeClass('unbookmarked').removeClass('stop');
	elem.html( html );
}

function userpro_bm_addbookmark( elem, html ) {
	elem.addClass('unbookmarked').removeClass('bookmarked').removeClass('stop');
	elem.html( html );
}

function userpro_bm_removedialog() {
	jQuery('.userpro-bm-dialog,.userpro-bm-dialog-icon').hide().remove();
}

function userpro_bm_update_active_collection( parent, value ){
	parent.find('input:hidden#collection_id').val( value );
}

/* Custom JS starts here */
jQuery(document).ready(function() {
	
	/* remove a collection */
	jQuery(document).on('click', '.userpro-remove-collection', function(e){
		e.preventDefault();
		element = jQuery(this).parents('.userpro-coll-count');
		if (element.find('.userpro-coll-remove').is(':hidden')){
		jQuery(this).html( jQuery(this).data('undo') );
		element.find('.userpro-coll-remove').slideToggle();
		} else {
		jQuery(this).html( jQuery(this).data('remove') );
		element.find('.userpro-coll-remove').slideToggle();
		}
		return false;
	});
	
	/* remove a collection */
	jQuery(document).on('click', '.userpro-hard-remove', function(e){
		e.preventDefault();
		collection_id = jQuery(this).data('collection_id');

		/* switch tab */
		list = jQuery(this).parents('.userpro-coll').find('.userpro-coll-list');
		
		jQuery.ajax({
			url: userpro_ajax_url,
			data: 'action=userpro_hard_remove_collection&collection_id='+collection_id,
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
	jQuery(document).on('click', '.userpro-soft-remove', function(e){
		e.preventDefault();
		collection_id = jQuery(this).data('collection_id');

		/* switch tab */
		list = jQuery(this).parents('.userpro-coll').find('.userpro-coll-list');
		
		jQuery.ajax({
			url: userpro_ajax_url,
			data: 'action=userpro_soft_remove_collection&collection_id='+collection_id,
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
	jQuery(document).on('click', '.userpro-coll-list a', function(e){
		e.preventDefault();
		collection_id = jQuery(this).data('collection_id');
		container = jQuery(this).parents('.userpro-coll').find('.userpro-coll-body');
		if (container.hasClass('loading') == false){

		/* switch tab */
		list = jQuery(this).parents('.userpro-coll-list');
		list.find('a').removeClass('active');
		list.find('a').find('i').addClass('userpro-coll-hide');
		list.find('a').find('span').removeClass('userpro-coll-hide');
		jQuery(this).addClass('active');
		jQuery(this).find('i').removeClass('userpro-coll-hide');
		jQuery(this).find('span').addClass('userpro-coll-hide');
		
		container.addClass('loading').find('.userpro-coll-body-inner').find('div:not(.userpro-coll-remove)').fadeTo(0, 0);
		
		jQuery.ajax({
			url: userpro_ajax_url,
			data: 'action=userpro_change_collection&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				container.removeClass('loading').find('.userpro-coll-body-inner').empty().html(data.res);
			}
		});
		
		}
		return false;
	});
	
	/* Disable forms */
	jQuery(document).on('submit', '.userpro-bm form', function(e){
		e.preventDefault();
		return false;
	});

	/* Capture change in collection */
	jQuery(document).on('change', '.userpro-bm-list select', function(e){
		dd = jQuery(this);
		parent = dd.parents('.userpro-bm');
		bookmarked_link = parent.find('a.bookmarked');
		unbookmarked_link = parent.find('a.unbookmarked');
		collection_id = parent.find('input:hidden#collection_id').val();
		if (dd.val() != collection_id){
			userpro_bm_addbookmark( bookmarked_link, parent.data('add_to_collection') );
		} else {
			userpro_bm_donebookmark( unbookmarked_link, parent.data('remove_bookmark') );
		}
	});

	/* trigger submit new collection */
	jQuery(document).on('click', '.userpro-bm-dialog a[data-action="submit_collection"]', function(e){
		jQuery(this).parents('form').trigger('submit');
	});
	
	/* submit new collection */
	jQuery(document).on('submit', '.userpro-bm-dialog form:not(.stop)', function(e){
		elem = jQuery(this);
		dialog = jQuery(this).parents('.userpro-bm-dialog');
		parent = jQuery(this).parents('.userpro-bm');
		
		collection_name = dialog.find('#userpro_bm_new').val();
		if (collection_name != ''){
		
		elem.addClass('stop');
		default_collection = parent.data('default_collection');
		post_id = parent.data('post_id');
		jQuery.ajax({
			url: userpro_ajax_url,
			data: 'action=userpro_fav_addcollection&post_id='+post_id+'&default_collection='+default_collection+'&collection_name='+collection_name,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				elem.removeClass('stop');
				parent.find('#userpro_bm_collection').replaceWith( data.options );
				parent.find("select").removeClass("chzn-done").css('display', 'inline').data('chosen', null);
				parent.find("*[class*=chzn], .chosen-container").remove();
				jQuery(".chosen-select-collections").chosen({
					disable_search_threshold: 10,
					width: '100%'
				});
				parent.find('#userpro_bm_collection').val( parent.find('#userpro_bm_collection option:last').val() ).trigger("chosen:updated");
				parent.find('.userpro-bm-list select').trigger('change');
				userpro_bm_removedialog();
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
	jQuery(document).on('click', '.userpro-bm a[data-action=newcollection]', function(e){
		elem = jQuery(this);
		parent = jQuery(this).parents('.userpro-bm');
		
		if ( parent.find('.userpro-bm-dialog form').length == 0){
			userpro_bm_newaction( elem, parent );
			elem.addClass('active');
			userpro_bm_dialog( elem.parent(), 'new_collection', 'right' );
		} else {
			elem.removeClass('active');
			userpro_bm_removedialog();
		}
		
	});

	/* New bookmark */
	jQuery(document).on('click', '.userpro-bm a[data-action=bookmark].unbookmarked:not(.stop)', function(e){
		elem = jQuery(this);
		parent = jQuery(this).parents('.userpro-bm');
		post_id = parent.data('post_id');
		collection_id = parent.find('#userpro_bm_collection').val();
		
		userpro_bm_newaction( elem, parent );

		jQuery.ajax({
			url: userpro_ajax_url,
			data: 'action=userpro_fav_newbookmark&post_id='+post_id+'&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				userpro_bm_update_active_collection( parent, data.collection_id );
				userpro_bm_donebookmark( elem, parent.data('remove_bookmark') );
				userpro_bm_dialog( elem.parent(), parent.data('dialog_bookmarked') );
			}
		});
		return false;
		
	});
	
	/* Remove bookmark */
	jQuery(document).on('click', '.userpro-bm a[data-action=bookmark].bookmarked:not(.stop)', function(e){
		elem = jQuery(this);
		parent = jQuery(this).parents('.userpro-bm');
		post_id = parent.data('post_id');
		collection_id = parent.find('#userpro_bm_collection').val();
		
		userpro_bm_newaction( elem, parent );

		jQuery.ajax({
			url: userpro_ajax_url,
			data: 'action=userpro_fav_removebookmark&post_id='+post_id+'&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				userpro_bm_addbookmark( elem, parent.data('add_to_collection') );
				userpro_bm_dialog( elem.parent(), parent.data('dialog_unbookmarked') );
			}
		});
		return false;
		
	});
	
	/* Remove bookmark */
	jQuery(document).on('click', 'a.userpro-coll-abs', function(e){
		elem = jQuery(this);
		parent = jQuery(this).parents('.userpro-coll-item');
		post_id = elem.data('post_id');
		collection_id = elem.data('collection_id');

		parent.fadeOut('fast');
		
		jQuery.ajax({
			url: userpro_ajax_url,
			data: 'action=userpro_fav_removebookmark&post_id='+post_id+'&collection_id='+collection_id,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){

			}
		});
		return false;
		
	});
	
});