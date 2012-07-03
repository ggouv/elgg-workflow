
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
 * Elgg-workflow initialization
 *
 * @return void
 */
elgg.provide('elgg.workflow');

elgg.workflow.init = function() {
	$(document).ready(function() {
		if ( $('.workflow-lists-container').length == 0) {
			//return;
		}
		elgg.workflow.list.resize();
		elgg.workflow.list.resize(); //do it again cause scrollbar. @todo find another way to fix that.

		// highlight object
		if (typeof(highlight) !== 'undefined') {
			$(window).scrollTo($('#workflow-'+highlight), 'slow', function() {
				$('#workflow-'+highlight).css('border','2px solid red');
			});
		}

	});

	// for extensible template
	$(window).bind("resize", function() {
		if ( $('.workflow-lists-container').length ) {
			elgg.workflow.list.resize();
		}
	});

}
elgg.register_hook_handler('init', 'system', elgg.workflow.init);


/**
 * Workflow list initialization
 *
 * @return void
 */
elgg.provide('elgg.workflow.list');

elgg.workflow.list.init = function() {

	// workflow layout?
	if ($(".workflow-lists-container").length == 0) {
		//return;
	}

	$(".workflow-lists-container").sortable({
		items:                'div.workflow-list.elgg-state-draggable',
		connectWith:          '.workflow-lists',
		handle:               '.workflow-list-handle',
		forcePlaceholderSize: true,
		placeholder:          'workflow-list-placeholder',
		opacity:              0.8,
		revert:               500,
		stop:                 elgg.workflow.list.move
	});

	// add list popup
	$('.elgg-form-workflow-list-add-list-popup .elgg-button-submit').live('click', elgg.workflow.list.add);
	$('.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext').focusin(function(){
		if ( $(this).val() == elgg.echo("workflow:add_list") ) $(this).val('');
	});
	$('.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext').focusout(function(){
		if ( $(this).val() == '' ) $(this).val(elgg.echo("workflow:add_list"));
	});
	// delete list button 
	$('li.elgg-menu-item-delete a.workflow-list-delete-button').live('click', elgg.workflow.list.remove);
	// add card from list footer
	elgg.workflow.list.addCard();

};
elgg.register_hook_handler('init', 'system', elgg.workflow.list.init);

/**
 * Persist the list's new position
 *
 * @param {Object} event
 * @param {Object} ui
 *
 * @return void
 */
elgg.workflow.list.move = function(event, ui) {
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
elgg.workflow.list.add = function(event) {
	list_title = $('.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext').val();
	elgg.action('workflow/list/add', {
		data: {
			user_guid: elgg.get_logged_in_user_guid(),
			container_guid: elgg.get_page_owner_guid(),
			list_title: list_title,
		},
		success: function(json) {
			if ( $('.workflow-lists').length == 0 ) {
				$('.workflow-lists-container p').remove();
				$('.workflow-lists-container').append('<div class="workflow-lists ui-sortable"></div>');
			}
			$('.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext').val(elgg.echo("workflow:add_list"));
			$('.workflow-lists').append(json.output);
			elgg.workflow.list.addCard();
			elgg.workflow.list.resize();
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
elgg.workflow.list.remove = function(event) {
	if (confirm(elgg.echo('workflow:list:delete:confirm'))) {
		var $list = $(this).closest('.workflow-list');

		$list.remove();
		elgg.workflow.list.resize();

		// delete the widget through ajax
		elgg.action($(this).attr('href'), {
			success: function(json) {
				$('.elgg-sidebar .elgg-module-aside').replaceWith(json.output.sidebar);
			}
		});
	}
	event.preventDefault();
};

/**
 * Reposition popup add list
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
 */
elgg.workflow.list.resize = function() {
	var WorkflowWidth = $('.workflow-lists-container').width();
	var CountLists = $('.workflow-list').length;
	var ListWidth = 0; var i = 0;
	workflow_min_width_list = 200;
	if ( (parseInt(workflow_min_width_list) + 5 + 4) * CountLists > (WorkflowWidth - 5) ) {
		ListWidth = parseInt(workflow_min_width_list);
		$('.workflow-lists').width( (ListWidth + 5 + 4) * CountLists - 5); // margin + border minus last margin doesn't displayed
	} else {
		ListWidth = (WorkflowWidth - (9*CountLists) + 5 ) / CountLists;
		$('.workflow-lists').width(WorkflowWidth);
	}
	$('.workflow-list, .workflow-list-placeholder').width(ListWidth);
}
/**
 * Attach event on text area to add card on list
 */
elgg.workflow.list.addCard = function() {
	$('.elgg-form-workflow-list-add-card .elgg-input-plaintext').focusin(function(){
		if ( $(this).val() == elgg.echo("workflow:list:add_card") ) {
			$(this).val('');
			$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'block');
		}
	});
	$('.elgg-form-workflow-list-add-card .elgg-input-plaintext').focusout(function(){
		if ( $(this).val() == '' ) {
			$(this).val(elgg.echo("workflow:list:add_card"));
			$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
		}
	});
	$('.elgg-form-workflow-list-add-card .elgg-icon-delete').live('click', function(){
		$(this).val(elgg.echo("workflow:list:add_card"));
		$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
	});
}

/**
 * Workflow card initialization
 *
 * @return void
 */
elgg.provide('elgg.workflow.card');

elgg.workflow.card.init = function() {
	// workflow layout?
	if ($(".workflow-lists-container").length == 0) {
		//return;
	}

	$(".workflow-lists").sortable({
		items:                'div.workflow-card.elgg-state-draggable',
		connectWith:          '.workflow-cards',
		handle:               '.workflow-card-handle',
		forcePlaceholderSize: true,
		placeholder:          'workflow-card-placeholder',
		revert:               500,
		dropOnEmpty: true,
		stop:                 elgg.workflow.card.move
	});

	$('.elgg-form-workflow-list-add-card .elgg-button-submit').live('click', elgg.workflow.card.add);
	
	elgg.workflow.card.popup();

};
elgg.register_hook_handler('init', 'system', elgg.workflow.card.init);

/**
 * Persist the card's new position
 *
 * @param {Object} event
 * @param {Object} ui
 *
 * @return void
 */
elgg.workflow.card.move = function(event, ui) {
	// workflow-card-<guid>
	var card_guidString = ui.item.attr('id');
	card_guidString = card_guidString.substr(card_guidString.indexOf('workflow-card-') + "workflow-card-".length);
	// workflow-list-<guid>
	var list_guidString = ui.item.parents('.workflow-list').attr('id');
	list_guidString = list_guidString.substr(list_guidString.indexOf('workflow-list-') + "workflow-list-".length);

	// hack for empty list and sortable jquery.ui (dropOnEmpty doesn't work cause multiple div)
	pos = 0;
	ui.item.parents('.workflow-list').find('.workflow-card').each(function() {
		if ( $(this).attr('id') == 'workflow-card-'+card_guidString ) return false;
		if (!$(this).hasClass('workflow-card-none')) pos++;
	});

	elgg.action('workflow/card/move', {
		data: {
			card_guid: card_guidString,
			list_guid: list_guidString,
			position: pos
		}
	});

	// @hack fixes jquery-ui/opera bug where draggable elements jump
	ui.item.css('top', 0);
	ui.item.css('left', 0);
};

/**
 * Adds a new card
 *
 * Makes Ajax call to persist new card and inserts the card html
 *
 * @param {Object} event
 * @return void
 */
elgg.workflow.card.add = function(event) {
	workflow_list = $(this).parent().find('[name=workflow_list]').val();
	input_add_card = $(this).parent().find('.elgg-input-plaintext');
	card_title = input_add_card.val();

	if (card_title) {
		elgg.action('workflow/card/add', {
			data: {
				user_guid: elgg.get_logged_in_user_guid(),
				container_guid: elgg.get_page_owner_guid(),
				parent_guid: workflow_list,
				card_title: card_title,
			},
			success: function(json) {
				$('#workflow-list-content-' + workflow_list + ' .workflow-cards').append(json.output);
				elgg.workflow.card.popup();
			}
		});
	}

	if ( input_add_card.is(':focus') ) {
		input_add_card.val('');
	} else {
		input_add_card.val(elgg.echo("workflow:list:add_card"));
		$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').hide();
	}

	event.preventDefault();
};

/**
 * Prepare fancybox for edit card
 *
 * @return void
 */
elgg.workflow.card.popup = function() {
	$('.workflow-edit-card').fancybox({
		'autoDimensions': false,
		'autoScale': true,
		'width': 810,
		'scrolling': 'no',
		'onComplete': function() {
			$('#fancybox-wrap').css('top', '20px');
			$('#fancybox-content, #fancybox-content > div').css('height', $('#card-forms').height());

			elgg.ui.initDatePicker();
			elgg.userpicker.init();

			$('.elgg-userpicker-remove, .ui-menu-item').live('click', function() {
				$('#fancybox-content, #fancybox-content > div').css('height', $('#card-forms').height());
			});
			$('#fancybox-content .elgg-button-submit').die().live('click', elgg.workflow.card.popupForms);
			$('#fancybox-content .elgg-button-delete').die().live('click', elgg.workflow.card.remove);
		},
		'onCleanup': function() {
			$('#fancybox-content .elgg-button-submit, #fancybox-content .elgg-button-delete').die();
		}
	});
};

/**
 * Save data of card or new comment
 *
 * Event callback the uses Ajax to save data or comment
 *
 * @param {Object} event
 * @return void
 */
elgg.workflow.card.popupForms = function(event) {
	form = $(this).closest('form');
	var card_guid = form[0].entity_guid.value;
	var data = form.serialize();

	elgg.action(form.attr('action'), {
		data: data,
		success: function(json) {
			$.fancybox.close();
			if (card_guid && json.output && json.status == 0) { // card modified
				$('#workflow-card-'+card_guid).replaceWith(json.output.card);
				$('.elgg-sidebar .elgg-module-aside').replaceWith(json.output.sidebar);
				elgg.workflow.card.popup();
			} else if (json.output == '' && json.status == 0) { // card commented
				if ( $('#workflow-card-'+card_guid+' .workflow-card-comment').length == 0 ) {
					if ( $('#workflow-card-'+card_guid+' .workflow-card-description').length == 0 ) {
						$('#workflow-card-'+card_guid+' .workflow-card-info').prepend('<div class="workflow-card-comment">0</div>');
					} else {
						$('#workflow-card-'+card_guid+' .workflow-card-description').after('<div class="workflow-card-comment">0</div>');
					}
				}
				TxtComment = $('#workflow-card-'+card_guid+' .workflow-card-comment');
				TxtComment.html('<span class="elgg-icon elgg-icon-workflow-speech-bubble"></span>'+(parseInt(TxtComment.text())+1));
			}
		}
	});

	event.preventDefault();
};

/**
 * Removes a card from the layout
 *
 * Event callback the uses Ajax to delete the list and removes its HTML
 *
 * @param {Object} event
 * @return void
 */
elgg.workflow.card.remove = function(event) {
	if (confirm(elgg.echo('workflow:card:delete:confirm'))) {
		card = $(this).parent().find('input[name=entity_guid]').val();
		
		// delete the card through ajax
		elgg.action('workflow/card/delete', {
			data: {
				card_guid: card
			},
			success: function(json) {
				$('#workflow-card-'+card).remove();
				$('.elgg-sidebar .elgg-module-aside').replaceWith(json.output.sidebar);
				$.fancybox.close();
			}
		});
	}
	event.preventDefault();
};

// End of js for elgg-workflow plugin
