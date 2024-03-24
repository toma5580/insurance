$(".auth-action").click(function(event){
	event.preventDefault();
	var cardToShow = "."+$(this).attr("action");
	$(this).parents('div[style=""]').fadeOut();
	$(cardToShow).fadeIn();
})

$('div.message i.close').on('click', function() {
	$(this).closest('.message').transition('fade');
});
