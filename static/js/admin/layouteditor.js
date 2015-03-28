$(document).ready(function() {
	
	
	// don't initialize layout editor if not available
	if ($('#admin #cms-layout').length == 0) {
		return;
	}
	
	/*
	 * ============================================================
	 * 						initialize elements
	 * ============================================================
	 */
	var elements = refreshLayoutElements();

	
	// find out how wide 1 col is
	var columnWidth = 0;
	var elementMargin = 0;
	if (elements.length > 0) {
		var element = elements[0];
		columnWidth = element.width / element.col;
		elementMargin = $('#admin #cms-layout .section[data-section="' + element.id + '"]').parent('.section-container').outerWidth(true) - element.width;
	}
	
	// prevent context menu when the user rightclicks on the section (open split/merge menu)
	$('#admin #cms-layout').on('contextmenu', '.section', function(e) {
		if ($(e.target).hasClass('section')) {
			e.preventDefault();
		}
	});
	
	// fetch color palette:
	var colorPalette = {};
	$('#cms-layout-type-palette div').each(function(index, item) {
		colorPalette[$(item).attr('data-type')] = $(item).attr('data-color');
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
	var dragAndDropStart = {
		element: {
			x: 0,
			y: 0
		},
		mouse: {
			x: 0,
			y: 0
		}
	}
	var lastDragAndDropUpdate = new Date().getTime();
	
	
	/*
	 * Start Drag & Drop / Open Context Menu
	 */
	$('#admin #cms-layout').on('mousedown', '.section', function(e) {
		// click on dropdown, don't intervene
		if (e.target.tagName == 'SELECT') {
			return;
		}
		
		var coord = getEventCoordinates(e);
		// right click - open context menu
		if (e.which == 3) {
			$('#cms-layout-context').appendTo('body').show().css('left', coord.x + 5).css('top', coord.y + 5);
			$('#cms-layout-context').attr('data-section', $(e.target).closest('.section').attr('data-section'));
		}
		// move element (drag + drop)
		else if ($(e.target).hasClass('section')) {
			// find element to drag
			var hoverElement = getElementByCoordinates(elements, coord);
			
			// check if there is more than one element in the currently selected line
			
			// store drag & drop start
			dragAndDropStart = {
				element: {
					x: hoverElement.left,
					y: hoverElement.top
				},
				mouse: {
					x: coord.x,
					y: coord.y
				}
			}
			
			// show overlay
			var overlay = $('#cms-layout-overlay');
			$('#cms-layout-overlay')
				.attr('data-section', hoverElement.id)
				.css('left', hoverElement.left).css('top', hoverElement.top).css('width', hoverElement.width + 'px')
				.appendTo('body').show();
			
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
				var sectionId = $('#cms-layout-context').attr('data-section');
				var type = $('#admin #cms-layout .section[data-section="' + sectionId + '"]').attr('data-type');
				$('#cms-layout-context').hide();
				if (operation == 'remove-section') {
					// remove section
					$('#cms-layout-remove-modal').modal('show');
				}
				else if (operation == 'split-section') {
					// split section
					$('#cms-layout-split-modal').modal('show');
					$('#cms-layout-split-modal select option[value="' + type + '"]').attr('selected', 'selected');
				}
			}
			
		}
	});
	
	
	/*
	 * Confirm split of section
	 */
	$('#cms-layout-split-section-button').on('click', function(e) {
		$('#cms-layout-split-modal').modal('hide');
		
		// find element to change
		var elementId = $('#cms-layout-context').attr('data-section');
		var element = getLayoutElementById(elements, elementId);
		
		if (element && element.col < 4) {
			alert('The section is too small to be split.');
			return;
		}
		else {
			var newElementId = new Date().getTime();
			var newElementType = $('#new-split-type option:selected').val();
			
			// clone container element
			var originalContainer = $(element.uiElement).parent();
			var newUi = $(originalContainer).clone();
			// append clone to original container
			newUi.insertAfter(originalContainer);
			// set data attributes
			var newSection = $(newUi).find('.section')[0];
			$(newSection).attr('data-section', newElementId).attr('data-type', newElementType).css('background-color', colorPalette[newElementType]).attr('data-new', 'true');
			
			// refresh sections
			elements = refreshLayoutElements();
			element = getLayoutElementById(elements, elementId);
			var newElement = getLayoutElementById(elements, newElementId);
			
			// resize elements
			var col1 = Math.floor(element.col / 2) + (element.col % 2);
			var col2 = Math.floor(element.col / 2);			
			element.resize(col1);
			newElement.resize(col2);
			
			// refresh sections again to allow the new top-positions to be employed (after resizing)
			elements = refreshLayoutElements();
		}
	});
	
	/*
	 * Confirm removal of section 
	 */
	$('#cms-layout-delete-section-button').on('click', function(e) {
		$('#cms-layout-remove-modal').modal('hide');
		if (elements.length == 1) {
			alert('You cannot delete the last section.');
			return;
		}
		else {
			// find element to change
			var elementId = $('#cms-layout-context').attr('data-section');
			var element = getLayoutElementById(elements, elementId);
			
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
					total += newWidth;
				});
				// in case the rounding didn't work properly resize last item
				if (total != 12) {
					var lastItem = lineElements[lineElements.length - 1];
					lastItem.resize(12 - total + lastItem.col);
				}
			}

			// finally delete the item
			element.remove();
			
			// refresh the elements
			elements = refreshLayoutElements();
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
	 * Move operation (drag/drop + resize)
	 */
	$('#admin #cms-layout').mousemove(function(e) {
		if (resizeDown) {
			/* resize operation */
			if (!resizeElements.left || !resizeElements.right) {
				// check if left and right element are defined
				return;
			}
			
			// get coordinates
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
		}
		else if (moveDown) {
			if ((new Date().getTime()) - lastDragAndDropUpdate > 50) {
				/* drag and drop move operation (change position of overlay) */
				var overlay = $('#cms-layout-overlay');
				var coord = getEventCoordinates(e);
				var hoverElement = getElementByCoordinates(elements, coord);
				
				$('#cms-layout .section.hover').removeClass('hover');
				
				if (hoverElement !== false) {
					$(hoverElement.uiElement).addClass('hover');
				}
				
				// detect difference between start
				var diffX = coord.x - dragAndDropStart.mouse.x;
				var diffY = coord.y - dragAndDropStart.mouse.y;
				
				// move overlay
				$(overlay).css('left', (dragAndDropStart.element.x + diffX) + 'px')
						  .css('top', (dragAndDropStart.element.y + diffY) + 'px');
			}
			lastDragAndDropUpdate = new Date().getTime();
		}
	});
	
	
	/*
	 * ============================================================
	 * 			Stop resizing / dragging & dropping
	 * ============================================================
	 */
	$('body').on('mouseup', function(e) {
		/*
		 * finish resizing
		 */
		if (resizeDown) {
			// change state to not down anymore
			resizeDown = false;
		}
		
		/*
		 * swap elements
		 */
		else if (moveDown) {
			// fetch currently involved elements
			var dragElement = getLayoutElementById(elements, $('#cms-layout-overlay').attr('data-section'));
			var dropElement = $('#cms-layout .section.hover');
			var dragId = dragElement.id;
			var dropId = $(dropElement).attr('data-section');
			var isMoveForward = getLayoutElementById(elements, dropId).position > getLayoutElementById(elements, dragId).position;
			
			// fetch parent containers of elements
			var dragContainer = $(dragElement.uiElement).parent('.section-container');
			var dropContainer = $(dropElement).parent('.section-container');
			
			// find next and previous elements for insertion
			var nextDropContainer = $(dropContainer).next();
			var prevDropContainer = $(dropContainer).prev();
			
			if (elements.length == 2) {
				// swap elements if there is only 2
				if (isMoveForward) {
					$(dragContainer).insertAfter(dropContainer);
				}
				else {
					$(dropContainer).insertAfter(dragContainer);
				}
			}
			else if (nextDropContainer.length == 1) {
				// move elements in their correct position (related to next container)
				$(dropContainer).insertAfter(dragContainer);
				$(dragContainer).insertBefore(nextDropContainer);
			}
			else {
				// move elements in their correct position (related to prev container)
				$(dropContainer).insertAfter(dragContainer);
				$(dragContainer).insertAfter(prevDropContainer);
			}
			
			// cleanup UI
			$('#cms-layout .section.hover').removeClass('hover');
			$('#cms-layout-overlay').removeAttr('data-section').hide();
			
			// refresh elements
			elements = refreshLayoutElements();
			
			// swap size of elements
			dragElement = getLayoutElementById(elements, dragId);
			dropElement = getLayoutElementById(elements, dropId);
			var ringData = {
				drag: dragElement.col,
				drop: dropElement.col
			};
			dragElement.resize(ringData.drop);
			dropElement.resize(ringData.drag);
			
			// reset flag
			moveDown = false;
		}
	});
	
	
	/*
	 * ============================================================
	 * 							Save Layout
	 * ============================================================
	 */
	$('#cms-layout-buttons').on('click', '#save-layout-button', function(e) {
		
	});
	
	/*
	 * ============================================================
	 * 							Add Section
	 * ============================================================
	 */
	$('#cms-layout-buttons').on('click', '#add-section-button', function(e) {
		e.preventDefault();
		var clone = $(elements[0].uiElement).parent('.section-container').clone();
		
		$(clone).find('.section').attr('data-section', new Date().getTime()).attr('data-new', 'true');
		$(clone).insertAfter($('#cms-layout .section-container').last());
		
		elements = refreshLayoutElements();
		elements[elements.length - 1].resize(12);
		
		return false;
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

/**
 * Sorts the UI elements in an array according to their position. 
 * After the sorting is done, the position is re-created for all 
 * elements in sequential order.
 * 
 * @param elements - a list of UI elements
 * @returns the sorted list of elements with re-assigned positions.
 */
function sortLayoutElements(elements) {
	// sort elements according to their position
	elements.sort(function(a, b) {
		return a.position - b.position;
	});
	
	// re-assign position (incremental)
	var position = 0;
	elements.forEach(function(item) {
		if (!item.isDeleted) {
			item.position = position;
			position++;
		}
	});
	
	return elements;
}


function getLayoutElementById(elements, id) {
	var elementFound = false;
	elements.forEach(function(item) {
		if (item.id == id) {
			elementFound = item;
		}
	});
	
	return elementFound;
}


function getElementByCoordinates(elements, coord) {
	var elementFound = false;
	elements.forEach(function(item) {
		// same height
		if (coord.y > item.top && coord.y < item.top + item.height) {
			if (coord.x > item.left && coord.x < item.left + item.width) {
				// same width - match
				elementFound = item;
			}
		}
	});
	
	return elementFound;
}


function refreshLayoutElements() {
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
			type: $(item).attr('data-type'),
			uiElement: $(item),
			position: index,
			isNew: ($(item).attr('data-new') == 'true'),
			
			
			// change ui and in-memory model
			resize: function(newWidth) {
				// handle UI
				$(this.uiElement).parent('.section-container').removeClass('col-md-' + this.col);
				$(this.uiElement).parent('.section-container').addClass('col-md-' + newWidth);
				$(this.uiElement).attr('data-width', newWidth);
				// handle data
				this.col = newWidth;
				this.width = $(this.uiElement).outerWidth();
				this.left = $(this.uiElement).offset().left;
			},
			
			insert: function() {
				$.post('/admin/api.php', {
					action: 'cms/updateLayout',
					width: this.col,
					type: this.type,
					position: this.position
				}, function(data) {});
			},
			
			// save current width
			save: function() {
				// update database
				$.post('/admin/api.php', {
					action: 'cms/updateLayout',
					id: this.id,
					width: this.col,
					type: this.type,
					position: this.position
				}, function(data) {});
			},
			
			remove: function() {
				if (!this.isNew) {
					$('<li>').attr('data-id', this.id).attr('data-type', this.type).attr('data-width', this.col).attr('data-position', this.position).appendTo('#cms-layout-recycle-bin');
				}
				$('.section[data-section="' + this.id + '"]').closest('.section-container').remove();
			},
			
			// remove section
			delete: function() {
				
				/*
				$.post('/admin/api.php', {
					action: 'cms/updateLayout',
					id: this.id
				}, function(data) {});
				*/
			}
		}
		elements.push(element);
	});
	
	return sortLayoutElements(elements)
}