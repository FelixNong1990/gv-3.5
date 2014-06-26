jQuery(document).ready(function( $ ) {

	//show hide the "left menu width" setting
	var menu_pos_switch = $('#sdmn_menu_pos'),
		menu_pos_switch_val = menu_pos_switch.val(),
		left_width_set = menu_pos_switch.closest('tr').first().next('tr');

	(menu_pos_switch_val == 'top')? left_width_set.hide() : left_width_set.show();

	menu_pos_switch.on('change', function() {
		menu_pos_switch_val = $(this).val();
		(menu_pos_switch_val == 'top')? left_width_set.hide() : left_width_set.show();
	});

	$('#upload_bar_logo_button').click(function(e) {
    	e.preventDefault();

    	var sdrn_logo_uploader = wp.media({
        	title: 'Sellect the logo for Mobile.Nav menu bar',
        	button: {
            	text: 'Select image'
        	},
        	multiple: false
    	}).on('select', function() {
        var attachment = sdrn_logo_uploader.state().get('selection').first().toJSON();
        console.log(attachment.url);
        	$('.sdrn_bar_logo_prev').attr('src', attachment.url).show();
        	$('.sdrn_bar_logo_url').val(attachment.url);
    	}).open();
	});

	$('.sdrn_disc_bar_logo').click(function(e) {
    	e.preventDefault();
    	$('.sdrn_bar_logo_prev').hide();
    	$('.sdrn_bar_logo_url').val('');
    });


});
