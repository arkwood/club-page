$(document).ready(function() {
	
	
	/*
	 * Navigation: click on div triggers click on a
	 */
	$('#admin #navigation .nav-entry').on('click', function(e) {
		if ($(e.target)[0].tagName != 'A') {
			$(e.target).find('a')[0].click();
		}
	});
});



function addLoadingAnimation(element) {
	$('#spin-template').clone().prepend($(element));
}

function removeLoadingAnimation(element) {
	$(element).find('span.fa').remove();
}