// app js
(function($) {

	// Adjust scroll view
	window.getVisible = function () {    
		var $el = $('.scrollbar'),
			scrollTop = $(this).scrollTop(),
			scrollBot = scrollTop + $(this).height(),
			elTop = $el.offset().top,
			elBottom = elTop + $el.outerHeight(),
			visibleTop = elTop < scrollTop ? scrollTop : elTop,
			visibleBottom = elBottom > scrollBot ? scrollBot : elBottom,
			height = visibleBottom - visibleTop;
		$('.scrollbar').css('height', height+"px");
	}

	// AJAX for laravel setup
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	// Active sidebar link setup
	$('aside a[href="' + window.location.href + '"]').addClass('active');
	
	// show and hide menu on small screens
	$(".humbager, .close-aside").click(function(event){
		event.preventDefault();
		$("aside").toggleClass("show cave");
	});
	
	// open modal
	$('[data-toggle="modal"]').click(function(event){
		event.preventDefault();
		var modalToOpen = $(this).attr('data-target') || $(this).closest('[data-toggle="modal"]').attr('data-target');
		$(modalToOpen).modal('show');
	});
	
	// Toggle slide
	$('[data-toggle="slide"]').click(function(event){
		event.preventDefault();
		var elementToToggle = $(this).attr('data-target');
		$(elementToToggle).slideToggle();
	});
	
	$('div.message i.close').on('click', function() {
		$(this).closest('.message').transition('fade');
	});

})(window.jQuery);
