$(document).ready(function() {
	
	// tool tips for layout editor
	$('#admin #cms-layout [data-title]').tooltip();
	
	// set active layout
	$('#admin #cms-layout .layout-container-row').each(function(index, item) {
		var activeLayout = $(item).attr('data-active-layout');
		$(item).find('[data-title="' + activeLayout + '"] .col').addClass('active-layout');
	});
	
	// scroll to active layout
	$('#admin #cms-layout .cms-layout-menubar-content').each(function(index, item) {
		// offset of the container (+ 15 for the top margin)
		var offSet = $(item).offset().top + 15;
		// scroll the container to the active layout
		$(item).animate({
	        scrollTop: $(item).find(".active-layout").offset().top - offSet
	    }, 1000);
	});
	
	// modal dialog for section layout preferences
	$('#admin #cms-layout .section').on('click', function(e) {
		// determine form values
		var sectionType = $(e.target).closest('.section').attr('data-section-type');
		var sectionLabel = $(e.target).closest('.section').attr('data-section-label');
		// set form values
		$('#cms-layout-modal #cms-layout-new-sectiontype option[value="' + sectionType + '"]').attr('selected', 'selected');
		$('#cms-layout-modal #cms-layout-new-label').val(sectionLabel);
		// show modal
		$('#cms-layout-modal').modal('show');
	});
	
});