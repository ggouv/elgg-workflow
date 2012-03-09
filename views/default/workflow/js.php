
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow js
 *
 */

/**
 * Workflow initialization
 *
 * @return void
 */
elgg.provide('workflow');

workflow.init = function() {
	$(document).ready(function() {
		if ( $('.workflow-lists-container').length == 0) {
			return;
		}
		workflow.list.resize();

		// hack for button add list
		$('li.elgg-menu-item-add-list a.elgg-button-action').attr("href", "#add-list").attr("rel", "popup"); 

	});

	// for extensible template
	$(window).bind("resize", function() {
		if ( $('.workflow-lists-container').length ) {
			workflow.list.resize();
		}
	});

}
elgg.register_hook_handler('init', 'system', workflow.init);


/**
 * Workflow list initialization
 *
 * @return void
 */
elgg.provide('workflow.list');

workflow.list.init = function() {

	// workflow layout?
	if ($(".workflow-lists-container").length == 0) {
		return;
	}

	$(".workflow-lists").sortable({
		items:                'div.workflow-list.elgg-state-draggable',
		connectWith:          '.workflow-lists',
		handle:               '.workflow-list-handle',
		forcePlaceholderSize: true,
		placeholder:          'workflow-list-placeholder',
		opacity:              0.8,
		revert:               500,
		stop:                 workflow.list.move
	});

	$('.elgg-form-workflow-add-list-popup .elgg-button-submit').live('click', workflow.list.add);
	$('li.elgg-menu-item-delete a.workflow-list-delete-button').live('click', workflow.list.remove);
	$('.elgg-form-workflow-list-add-card .elgg-input-text').focusin(function(){
		$(this).val('');
		$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'block');
	});
	$('.elgg-form-workflow-list-add-card .elgg-input-text').focusout(function(){
		if ( $(this).val() == '' ) {
			$(this).val(elgg.echo("workflow:list:add_card"));
			$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
		}
	});
	$('.elgg-form-workflow-list-add-card .elgg-icon-delete').live('click', function(){
		$(this).val(elgg.echo("workflow:list:add_card"));
		$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
	});
//	$('.elgg-widget-edit > form ').live('submit', elgg.ui.widgets.saveSettings);
//	$('a.elgg-widget-collapse-button').live('click', elgg.ui.widgets.collapseToggle);

};
elgg.register_hook_handler('init', 'system', workflow.list.init);

/**
 * Persist the list's new position
 *
 * @param {Object} event
 * @param {Object} ui
 *
 * @return void
 */
workflow.list.move = function(event, ui) {

	// workflow-list-<guid>
	var guidString = ui.item.attr('id');
	guidString = guidString.substr(guidString.indexOf('workflow-list-') + "workflow-list-".length);

	elgg.action('workflow/list/move', {
		data: {
			list_guid: guidString,
			position: ui.item.index()
		}
	});

	// @hack fixes jquery-ui/opera bug where draggable elements jump
	ui.item.css('top', 0);
	ui.item.css('left', 0);
};

/**
 * Adds a new list
 *
 * Makes Ajax call to persist new list and inserts the list html
 *
 * @param {Object} event
 * @return void
 */
workflow.list.add = function(event) {
	list_title = $('.elgg-form-workflow-add-list-popup .elgg-input-text').val();
	elgg.action('workflow/list/add', {
		data: {
			user_guid: elgg.get_logged_in_user_guid(),
			container_guid: elgg.get_page_owner_guid(),
			list_title: list_title,
		},
		success: function(json) {
			$('.workflow-lists').append(json.output);
			workflow.list.resize();
			$('.workflow-lists-container').animate({ scrollLeft: $('.workflow-lists-container').width()});
		}
	});
	$('#add-list').hide();
	event.preventDefault();
	return false;
};

/**
 * Removes a list from the layout
 *
 * Event callback the uses Ajax to delete the list and removes its HTML
 *
 * @param {Object} event
 * @return void
 */
workflow.list.remove = function(event) {
	var $list = $(this).closest('.workflow-list');

	$list.remove();
	workflow.list.resize();

	// delete the widget through ajax
	elgg.action($(this).attr('href'));

	event.preventDefault();
	//return false;
};

/**
 * Workflow card initialization
 *
 * @return void
 */
elgg.provide('workflow.card');

workflow.card.init = function() {

	// workflow layout?
	if ($(".workflow-lists-container").length == 0) {
		return;
	}
/*
	$(".workflow-lists").sortable({
		items:                'div.workflow-list.elgg-state-draggable',
		connectWith:          '.workflow-lists',
		handle:               '.workflow-list-handle',
		forcePlaceholderSize: true,
		placeholder:          'workflow-list-placeholder',
		opacity:              0.8,
		revert:               500,
		stop:                 workflow.list.move
	});
*/
	$('.elgg-form-workflow-list-add-card .elgg-button-submit').live('click', workflow.card.add);
/*
	$('li.elgg-menu-item-delete a.workflow-list-delete-button').live('click', workflow.list.remove);
	$('.elgg-form-workflow-list-add-card .elgg-input-text').live('click', function(){
		$(this).val('');
		$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').show();
	});
	$('.elgg-form-workflow-list-add-card .elgg-icon-delete').live('click', function(){
		$(this).val(elgg.echo("workflow:list:add_card"));
		$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').hide();
	});
//	$('.elgg-widget-edit > form ').live('submit', elgg.ui.widgets.saveSettings);
//	$('a.elgg-widget-collapse-button').live('click', elgg.ui.widgets.collapseToggle);
*/
};
elgg.register_hook_handler('init', 'system', workflow.card.init);

/**
 * Adds a new card
 *
 * Makes Ajax call to persist new card and inserts the card html
 *
 * @param {Object} event
 * @return void
 */
workflow.card.add = function(event) {
	workflow_list = $(this).parent().find('[name=workflow_list]').val();
	card_title = $(this).parent().find('.elgg-input-text').val();

	if (card_title) {
		elgg.action('workflow/card/add', {
			data: {
				user_guid: elgg.get_logged_in_user_guid(),
				container_guid: workflow_list,
				card_title: card_title,
			},
			success: function(json) {
				$('#workflow-list-content-' + workflow_list + ' .workflow-cards').append(json.output);
				//$('.workflow-lists-container').animate({ scrollLeft: $('.workflow-lists-container').width()});

			}
		});
	}

	$(this).parent().find('.elgg-input-text').val(elgg.echo("workflow:list:add_card"));
	$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').hide();
	event.preventDefault();
	return false;
};

///**
// * Toggle the collapse state of the widget
// *
// * @param {Object} event
// * @return void
// */
//elgg.ui.widgets.collapseToggle = function(event) {
//	$(this).toggleClass('elgg-widget-collapsed');
//	$(this).parent().parent().find('.elgg-body').slideToggle('medium');
//	event.preventDefault();
//};

///**
// * Save a widget's settings
// *
// * Uses Ajax to save the settings and updates the HTML.
// *
// * @param {Object} event
// * @return void
// */
//elgg.ui.widgets.saveSettings = function(event) {
//	$(this).parent().slideToggle('medium');
//	var $widgetContent = $(this).parent().parent().children('.elgg-widget-content');

//	// stick the ajax loader in there
//	var $loader = $('#elgg-widget-loader').clone();
//	$loader.attr('id', '#elgg-widget-active-loader');
//	$loader.removeClass('hidden');
//	$widgetContent.html($loader);

//	var default_widgets = $("input[name='default_widgets']").val() || 0;
//	if (default_widgets) {
//		$(this).append('<input type="hidden" name="default_widgets" value="1">');
//	}

//	elgg.action('widgets/save', {
//		data: $(this).serialize(),
//		success: function(json) {
//			$widgetContent.html(json.output);
//		}
//	});
//	event.preventDefault();
//};

///**
// * Set the min-height so that all widget column bottoms are the same
// *
// * This addresses the issue of trying to drag a widget into a column that does
// * not have any widgets or many fewer widgets than other columns.
// *
// * @param {String} selector
// * @return void
// */
//elgg.ui.widgets.setMinHeight = function(selector) {
//	var maxBottom = 0;
//	$(selector).each(function() {
//		var bottom = parseInt($(this).offset().top + $(this).height());
//		if (bottom > maxBottom) {
//			maxBottom = bottom;
//		}
//	})
//	$(selector).each(function() {
//		var bottom = parseInt($(this).offset().top + $(this).height());
//		if (bottom < maxBottom) {
//			var newMinHeight = parseInt($(this).height() + (maxBottom - bottom));
//			$(this).css('min-height', newMinHeight + 'px');
//		}
//	})
//};

/**
 * Reposition popups
 */
elgg.ui.addListPopup = function(hook, type, params, options) {
	if (params.target.attr('id') == 'add-list') {
		options.my = 'right top';
		options.at = 'right bottom';
		return options;
	}
	return null;
};
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.addListPopup);

/**
 * Resize lists

		if (!$.isFunction(scrollbarWidth)) { // in case of function already exist
			function scrollbarWidth() {
				if (!$._scrollbarWidth) {
					var $body = $('body');
					var w = $body.css('overflow', 'hidden').width();
					$body.css('overflow','scroll');
					w -= $body.width();
					if (!w) w=$body.width()-$body[0].clientWidth; // IE in standards mode
					$body.css('overflow','');
					$._scrollbarWidth = w+1;
				}
				return $._scrollbarWidth;
			}
		}

 */
workflow.list.resize = function() {
	var WorkflowWidth = $('.workflow-lists-container').width();
	var CountLists = $('.workflow-list').length;
	var ListWidth = 0; var i = 0;
	if ( (parseInt(workflow_min_width_list) + 5 + 4) * CountLists > (WorkflowWidth - 5) ) {
		ListWidth = parseInt(workflow_min_width_list);
		$('.workflow-lists').width( (ListWidth + 5 + 4) * CountLists - 5); // margin + border minus last margin doesn't displayed
	} else {
		ListWidth = (WorkflowWidth - (9*CountLists) + 5 ) / CountLists;
		$('.workflow-lists').width(WorkflowWidth);
	}
	$('.workflow-list, .workflow-list-placeholder').width(ListWidth);

}

// End of js for elgg-workflow plugin
