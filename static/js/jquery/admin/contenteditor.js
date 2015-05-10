$(document).ready(function() {
	$('button[data-action="cms/updateContent"]').on('click', function(e) {
		e.preventDefault();
		
		var container = $(e.target).closest('[data-container-id]');
		
		var data = new Array();
		var labels = new Array();
		
		// fetch parameter data and labels
		$(container).find('[data-section-id]').each(function(index, section) {
			var sectionId = $(section).attr('data-section-id');
		
			// fetch label
			var label = $(section).find('input[name="label"]').val();
			labels[sectionId] = label;
			
			// fetch text area and input data
			$(section).find('textarea[data-name], input[data-name]').each(function(index, item) {
				data.push({
					section: sectionId,
					name: $(item).attr('data-name'),
					value: $(item).val()
				});
			});
			// fetch drop down data
			$(section).find('select[data-name]').each(function(index, item) {
				data.push({
					section: sectionId,
					name: $(item).attr('data-name'),
					value: $(item).find('option:selected').val()
				});
			});
		});
		
		var url = $(e.target).closest('button').attr('data-url');
		$.post(url, {
			data: data,
			labels: labels,
			action: $(e.target).closest('button').attr('data-action')
		}).done(function(data) {
			// update done
			alert('saved');
		});
	});
});
