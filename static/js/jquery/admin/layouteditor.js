$(document).ready(function() {
	
	// tool tips for layout editor
	$('#admin #cms-layout [data-title]').tooltip();
	
	// set active layout
	$('#admin #cms-layout .layout-container-row').each(function(index, item) {
		var activeLayout = $(item).attr('data-active-layout');
		$(item).find('[data-title="' + activeLayout + '"] .col').addClass('active-layout');
	});
	
	// scroll to active layout
	$('a[aria-controls="cms-layout"]').on('click', function(e) {
		setTimeout(function() {
			$('#admin #cms-layout .cms-layout-menubar-content').each(function(index, item) {
				// offset of the container (+ 15 for the top margin)
				var offSet = $(item).offset().top + 15;
				var activeOffset = $(item).find(".active-layout").offset().top; 
				// scroll the container to the active layout
				if (Math.abs(activeOffset - offSet) > 300) {
					$(item).animate({
				        scrollTop: ($(item).find(".active-layout").offset().top - offSet)
				    }, 1000);
				}
			});
		}, 100);
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
	
	
	// switch to different layout (open modal)
	$('.cms-layout-menubar .layout').on('click', function(e) {
		var layoutId = $(e.target).closest('.layout').attr('data-identifier');
		var containerId = $(e.target).closest('.cms-layout-menubar').attr('data-container-id');
		if (!($(e.target).closest('.layout').hasClass('active-layout'))) {
			var modal = $('#cms-layout-switch-modal');
			$(modal).attr('data-container', containerId);
			$(modal).attr('data-layout', layoutId);
			$(modal).modal('show');
		}
	});
	
	// switch to different layout (confirm modal)
	$('#cms-layout-switch-modal button.btn-primary').on('click', function(e) {
		addLoadingAnimation(e.target);
		var modal = $('#cms-layout-switch-modal');
		var url = $(modal).attr('data-url');
		var action = $(modal).attr('data-action');
		var containerId = $(modal).attr('data-container');
		var newLayout = $(modal).attr('data-layout');
		$.post(url, {
			action: action,
			containerId: containerId,
			newLayout: newLayout
		}).done(function(data) {
			removeLoadingAnimation(e.target);
			$(modal).modal('hide');
			location.reload();
		});
	});
});