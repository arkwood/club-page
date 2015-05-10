$(document).ready(function() {
	// start commands
	
	/*
	 * Navigation and Header
	 */
	$('#main-nav-collapse .nav-entry').mouseover(function(e) {
		$('.subnav[data-nav-content]').hide();
		var navElement = $(e.target).closest('[data-nav-id]');
		if (navElement.length > 0) {
			var navID = $(navElement).attr('data-nav-id');
			$('.subnav[data-nav-content="' + navID + '"]').show();
		}
	});
	
	$('#main-nav-collapse .nav-entry').mouseout(function(e) {
		$('.subnav[data-nav-content]').hide();
	});
});


/*
 * Google Analytics Code
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-33564731-1']);
_gaq.push(['_trackPageview']);

(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
*/


