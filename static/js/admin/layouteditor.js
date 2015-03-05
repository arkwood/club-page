$(document).ready(function() {
	
	// don't initialize layout editor if not available
	if ($('#admin #cms-layout').length == 0) {
		return;
	}
	
	// initialize elements
	var elements = new Array();
	$('#admin #cms-layout .section').each(function(index, item) {
		var element = {
			// data
			top: $(item).offset().top,
			left: $(item).offset().left,
			width: $(item).outerWidth(),
			height: $(item).outerHeight(),
			id: $(item).attr('data-section'),
			col: parseInt($(item).attr('data-width')),
			uiElement: $(item),
			
			// change ui and in-memory model
			resize: function(newWidth) {
				// handle UI
				$(this.uiElement).parent('.section-container').removeClass('col-md-' + this.col);
				$(this.uiElement).parent('.section-container').addClass('col-md-' + newWidth);
				$(this.uiElement).attr('data-width', newWidth);
				// handle data
				this.col = newWidth;
				// refresh object
				this.width = $(this.uiElement).outerWidth();
				this.left = $(this.uiElement).offset().left;
			},
			
			// save current width
			save: function() {
				// update database
				$.post('/admin/api.php', {
					action: 'cms/updateSection',
					id: this.id,
					newWidth: this.col
				}, function(data) {});
			},
			
			// remove section
			delete: function() {
				$.post('/admin/api.php', {
					action: 'cms/updateSection',
					id: this.id
				}, function(data) {});
				$(this.uiElement).remove();
			}
		}
		elements.push(element);
	});
	
	// find out how wide 1 col is
	var columnWidth = 0;
	var elementMargin = 0;
	if (elements.length > 0) {
		var element = elements[0];
		columnWidth = element.width / element.col;
		elementMargin = $('#admin #cms-layout .section[data-section="' + element.id + '"]').parent('.section-container').outerWidth(true) - element.width;
	}
	
	// prevent context menu when the user rightclicks on the section (open split/merge menu)
	$('#admin #cms-layout .section').on('contextmenu', function(e) {
		if ($(e.target).hasClass('section')) {
			e.preventDefault();
		}
	});

	
	/*
	 * ============================================================
	 * 				drag and drop (swap elements)
	 * ============================================================
	 */
	
	/*
	 * Variables
	 */
	var moveDown = false;
	$('#admin #cms-layout .section').on('mousedown', function(e) {
		// click on dropdown, don't intervene
		if (e.target.tagName == 'SELECT') {
			return;
		}
		
		
		var coord = getEventCoordinates(e);
		if (e.which == 3) {
			// right click - open context menu
			$('#cms-layout-context').appendTo('body').show().css('left', coord.x + 5).css('top', coord.y + 5);
			$('#cms-layout-context').attr('data-section', $(e.target).closest('.section').attr('data-section'));
			
		}
		else if ($(e.target).hasClass('section')) {
			// move element (drag + drop)
			
			// check if there is more than one element in the currently selected line
			
			// find element to drag
			
			// set state for drag and drop
			moveDown = true;
		}
	});
	
	
	/*
	 * ============================================================
	 * 				context menu to split and remove
	 * ============================================================
	 */
	/*
	 * Click anywhere
	 */
	$('body').on('click', function(e) {
		// check if context menu is visible
		if ($('#cms-layout-context').is(':visible')) {
			if ($(e.target).closest('#cms-layout-context').length == 0) {
				// hide context menu
				$('#cms-layout-context').hide();
			}
			else {
				var operation = $(e.target).closest('li').attr('id');
				$('#cms-layout-context').hide();
				if (operation == 'remove-section') {
					// remove section
					$('#cms-layout-remove-modal').modal('show');
				}
				else if (operation == 'split-section') {
					
				}
			}
			
		}
	});
	
	
	/*
	 * Confirm split of section
	 */
	
	/*
	 * Confirm removal of section 
	 */
	$('#cms-layout-delete-section-button').on('click', function(e) {
		$('#cms-layout-remove-modal').modal('hide');
		if (elements.length == 1) {
			alert('You cannot delete the last section.');
		}
		else {
			var elementId = $('#cms-layout-context').attr('data-section');
			// find element to change
			var element = false;
			elements.forEach(function(item) {
				if (item.id == elementId) {
					element = item;
				}
			});
			// find all other elements on the same line
			var lineElements = new Array();
			elements.forEach(function(item) {
				if (item.id != elementId && item.top == element.top) {
					lineElements.push(item);
				}
			});

			// if there are other elements on the same line, resize
			if (lineElements.length >= 0) {
				var ratio = 12 / (12 - element.col);
				var total = 0;
				lineElements.forEach(function(item) {
					var newWidth = Math.ceil(item.col * ratio);
					if (total + newWidth > 12) {
						newWidth = 12 - total;
					}
					item.resize(newWidth);
					item.save();
				});
				// in case the rounding didn't work properly resize last item
				if (total != 12) {
					var lastItem = lineElements[lineElements.length - 1];
					lastItem.resize(12 - total + lastItem.col);
					lastItem.save();
				}
			}

			// finally delete the item
			element.delete();
		}
	});
	
	
	
	
	
	
	/* 
	 * ============================================================
	 * 						Resize element
	 * ============================================================
	 */
	
	/*
	 * Variables
	 */
	var resizeDown = false;
	var resizeElements = { 
		left: false,
		right: false
	};
	var resizeStart = {
		x: 0,
		y: 0
	}
	
	/*
	 * Start resizeing
	 */
	$('#admin #cms-layout').on('mousedown', function(e) {
		// catch drag & drop event (handled elsewhere)
		if ($(e.target).hasClass('section') || e.target.tagName == 'SELECT') {
			return;
		}
		
		// resize element (left, right)
		var coord = getEventCoordinates(e);
		
		// reset old elements
		resizeElements = {
			left: false,
			right: false
		};
		
		// find new elements
		elements.forEach(function(element) {
			// find out if the element is in the correct line
			if (element.top < coord.y && coord.y < (element.top + element.height)) {
				// find out which elements need to be resized
				if (coord.x < (element.left + element.width + elementMargin) && coord.x > (element.left + element.width)) {
					// left element
					resizeElements.left = element;
				}
				else if (coord.x > (element.left - elementMargin) && coord.x < element.left) {
					// right element
					resizeElements.right = element;
				}
			}
		});
		
		if (!resizeElements.left || !resizeElements.right) {
			// abort if the user didn't click in between 2 elements
			return;
		}
		
		// set resize
		resizeStart = {
			x: coord.x,
			y: coord.y
		};
		// change state to down
		resizeDown = true;
	});
	
	
	/*
	 * Resize operation
	 */
	$('#admin #cms-layout').mousemove(function(e) {
		e.preventDefault();
		if (!resizeDown) {
			// check if resize is in progress (mouse down)
			return;
		}
		if (!resizeElements.left || !resizeElements.right) {
			// check if left and right element are defined
			return;
		}
		
	
		var coords = getEventCoordinates(e);
		var movement = resizeStart.x - coords.x;

		// move left
		if (movement > 0) {
			// abort if element would get too small
			if (resizeElements.left.col == 2) {
				return;
			}
			
			// find out if cols need to be resized
			if (movement > columnWidth / 2) {
				// resize elements
				resizeElements.left.resize(resizeElements.left.col - 1);
				resizeElements.right.resize(resizeElements.right.col + 1);
				resizeStart = {
					x: resizeStart.x - columnWidth,
					y: resizeStart.y
				}
			}
		}
		// move right
		else {
			// abort if element would get too small
			if (resizeElements.right.col == 2) {
				return;
			}
			
			// find out if cols need to be resized
			if (Math.abs(movement) > columnWidth / 2) {
				// resize elements
				resizeElements.left.resize(resizeElements.left.col + 1);
				resizeElements.right.resize(resizeElements.right.col - 1);
				resizeStart = {
					x: resizeStart.x + columnWidth,
					y: resizeStart.y
				}
			}
		}
	});
	
	
	/*
	 * ============================================================
	 * 			Stop resizing / dragging & dropping
	 * ============================================================
	 */
	$('#admin').on('mouseup', function(e) {
		if (!resizeDown && !moveDown) {
			// if mouse was not pressed over resize, ignore event
			return;
		}
		
		/*
		 * finish resizing
		 */
		if (resizeDown) {
			// change state to not down anymore
			resizeDown = false;
		
			// save current width
			resizeElements.left.save();
			resizeElements.right.save();
		}
		
		/*
		 * swap elements
		 */
		else if (moveDown) {
			
		}
	});
});


/*
 * Retrieves the current event coordinates
 * @return a coordinate object with properties (left, x and top, y)
 */ 
function getEventCoordinates(event) {
	return {
		left: event.pageX,
		top: event.pageY,
		x: event.pageX,
		y: event.pageY
	};
}