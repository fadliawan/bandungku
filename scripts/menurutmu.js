/**
* Scripts for menurutmu.com
*
* Author: menurutmu.com
*
* Version: 1.0
*
* Requires jQuery JavaScript library
*
*/

(function ($) {
	
     // show the notifications
	$('.success, .notice, .error')
        .css({
            display: 'none'
        })
        .show('normal');
	
	// initialize jQuery Fancybox

	$('.attached_image, .to_fancybox').fancybox({
		'centerOnScroll' : true
	});
		
	// search form utility
	$('#search').find('[name=search_term]')
		.focus(function () {
			if ( $(this).val() === 'cari topik' ) {
				$(this).val('');
			}
		})
		.blur(function () {
			if ( $(this).val() === '' || $(this).val() === 'cari topik' ) {
				$(this).val('cari topik');
			}
		});
			
	// autofocus email fields
	$('input#email').focus();
		
	// autofocus content field
	$('textarea#content').focus();
			
	// character left count
	$('label[for=content]').append(' <span id="char_left" style="color:green">160</span>');
	$('#content').keyup(function () {
		var charLeft = 160 - $(this).val().length;
		$('#char_left').text(charLeft).each(function () {
			if ( charLeft >= 0 ) {
				$(this).css('color', 'green');
			} else {
				$(this).css('color', 'red');
			}
		});
	});
	
	// close the notifications
	$('.success, .notice, .error').click(function () {
		$(this).hide('normal');
	});
	
	// preventing double submit
	$('form').submit(function () {
		$(this).find('input[type=submit]').attr('disabled', 'disabled');
	});
	
})(jQuery);