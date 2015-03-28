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