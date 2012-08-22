
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

		// highlight object
		var url = elgg.parse_url(elgg.normalize_url(decodeURIComponent(window.location.href)), 'path');
		if (url.match('/card/(.*)/') !== null) {
			$(window).scrollTo($('#workflow-card-'+url.match('/card/(.*)/')[1]), 'slow', function() {
				$('#workflow-card-'+url.match('/card/(.*)/')[1]).delay(300).animate({opacity: 0}, 100, function() {
					$(this).delay(100).animate({opacity: 1}, 100, function() {
						$(this).delay(100).animate({opacity: 0}, 100, function() {
							$(this).delay(100).animate({opacity: 1}, 100, function() {
								$(this).css('border','1px solid #00FF00');
							});
						});
					});
				});
			});
		}
		if (url.match('/list/(.*)/') !== null) {
			$(window).scrollTo($('#workflow-list-'+url.match('/list/(.*)/')[1]), 'slow', function() {
				$('#workflow-list-'+url.match('/list/(.*)/')[1]).delay(300).animate({opacity: 0}, 100, function() {
					$(this).delay(100).animate({opacity: 1}, 100, function() {
						$(this).delay(100).animate({opacity: 0}, 100, function() {
							$(this).delay(100).animate({opacity: 1}, 100, function() {
								$(this).css('border','2px solid #00FF00');
							});
						});
					});
				});
			});
		}

		elgg.workflow.list.resize();
		elgg.workflow.list.resize(); //do it again cause scrollbar. @todo find another way to fix that.

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
		update:                 elgg.workflow.list.move
	});

	// focus on list popup
	$('.elgg-menu-item-add-list .elgg-button-action').live('click', function() {
		$('.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext').focus();
	});
	// add list popup
	$('.elgg-form-workflow-list-add-list-popup .elgg-button-submit').live('click', function(e){
		e.preventDefault();
		elgg.workflow.list.add($(this).closest('form'));
	});
	$('.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext').focusin(function(){
		if ( $(this).val() == elgg.echo("workflow:add_list") ) $(this).val('');
	}).focusout(function(){
		if ( $(this).val() == '' ) $(this).val(elgg.echo("workflow:add_list"));
	}).keydown(function(e){
		if (e.keyCode == 13) {
			e.preventDefault();
			if ($(this).val()) elgg.workflow.list.add($(this).closest('form'));
		}
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
elgg.workflow.list.add = function(form) {
	if (form.find('.elgg-input-plaintext').val() == elgg.echo("workflow:add_list")) return;
	elgg.action(form.attr('action'), {
		data: form.serialize(),
		success: function(json) {
			if ( $('.workflow-lists').length == 0 && json.output.list !== '') {
				$('.workflow-lists-container > p').remove();
				$('.workflow-lists-container').append('<div class="workflow-lists ui-sortable"></div>');
			}
			form.find('.elgg-input-plaintext').val(elgg.echo("workflow:add_list"));
			$('.workflow-lists').append(json.output.list);
			if ($('.elgg-sidebar .elgg-module-aside.river .elgg-head').length == 0) {
				$('.elgg-sidebar .elgg-module-aside.river').prepend('<div class="elgg-head mbs"><h3>' + elgg.echo('workflow:sidebar:last_activity_on_this_board') + '</h3></div>');
			}
			$('.elgg-sidebar .elgg-module-aside.river > .elgg-body').prepend(json.output.river);
			elgg.workflow.list.addCard();
			elgg.workflow.list.resize();
			$('.workflow-lists-container').animate({ scrollLeft: $('.workflow-lists-container').width()});
		}
	});
	$('#add-list').hide();
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
		// delete the widget through ajax
		elgg.action($(this).attr('href'), {
			success: function(json) {
				$list.remove();
				elgg.workflow.list.resize();
				$('.elgg-sidebar .elgg-module-aside').replaceWith(json.output.sidebar);
				if ( $('.workflow-list').length == 0 ) {
					$('.workflow-lists-container').empty();
					$('.workflow-lists-container').append('<p>' + elgg.echo('workflow:list:none') + '</p>');
				}
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
	var ListWidth = 0;
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
	var plaintext = $('.elgg-form-workflow-list-add-card .elgg-input-plaintext');
	plaintext.focusin(function(){
		if ( $(this).val() == elgg.echo("workflow:list:add_card") ) {
			$(this).val('');
		}
		$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'block');
	}).focusout(function(){
		if ( $(this).val() == '' ) {
			$(this).val(elgg.echo("workflow:list:add_card"));
			$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
		}
	}).keydown(function(e){
		if (e.keyCode == 13) {
			e.preventDefault();
			if ($(this).val()) elgg.workflow.card.add($(this).closest('form'));
		}
	});
	$('.elgg-form-workflow-list-add-card .elgg-icon-delete').live('click', function(){
		plaintext.val(elgg.echo("workflow:list:add_card"));
		plaintext.parent().find('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
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
		return;
	}

	$(".workflow-lists").sortable({
		items:                'div.workflow-card.elgg-state-draggable',
		connectWith:          '.workflow-cards',
		handle:               '.workflow-card-handle',
		forcePlaceholderSize: true,
		placeholder:          'workflow-card-placeholder',
		revert:               500,
		dropOnEmpty:          true,
		update:                 elgg.workflow.card.move
	});

	$('.elgg-form-workflow-list-add-card .elgg-button-submit').live('click', function(e){
		e.preventDefault();
		elgg.workflow.card.add($(this).closest('form'));
	});
	
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
elgg.workflow.card.add = function(form) {
	var workflow_list = form.find('[name=workflow_list]').val();
	var input_add_card = form.find('.elgg-input-plaintext');
	var card_title = input_add_card.val();

	if (card_title) {
		elgg.action(form.attr('action'), {
			data: form.serialize(),
			success: function(json) {
				$('#workflow-list-content-' + workflow_list + ' .workflow-cards').append(json.output.card);
				$('.elgg-sidebar .elgg-module-aside.river > .elgg-body').prepend(json.output.river);
				elgg.workflow.card.popup();
				elgg.workflow.list.resize();
			}
		});
	}

	if ( input_add_card.is(':focus') ) {
		input_add_card.val('');
	} else {
		input_add_card.val(elgg.echo("workflow:list:add_card"));
		$(this).parent().find('.elgg-button-submit, .elgg-icon-delete').hide();
	}
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
			var resizePopUp = function() {
				$('#fancybox-wrap').css('top', '20px');
				$('#fancybox-content, #fancybox-content > div').css('height', $('#card-forms').height());
			}

			resizePopUp();
			elgg.ui.initDatePicker();
			elgg.userpicker.init();

			$('.elgg-userpicker-remove, .ui-menu-item').live('click', function() {
				$('#fancybox-content, #fancybox-content > div').css('height', $('#card-forms').height());
			});
			$('#fancybox-content .elgg-foot .elgg-button-submit').die().live('click', elgg.workflow.card.popupForms);
			$('#fancybox-content .elgg-foot .elgg-button-delete').die().live('click', elgg.workflow.card.remove);

			// checklist
			$("#card-forms .card-checklist.sortable").sortable({
				items:                '.elgg-input-checkboxes > li',
				//connectWith:          '.elgg-input-checkboxes',
				//handle:               '.elgg-input-checkboxes > li span',
				forcePlaceholderSize: true,
				placeholder:          'elgg-input-checkboxes-placeholder',
				revert:               500,
			});
			var plaintext = $('#card-forms .card-checklist .elgg-input-plaintext');
			var addChecklistItem = function() {
				plaintext.parent().find('.elgg-input-checkboxes').append('<li><label><input type="checkbox" class="elgg-input-checkbox" name="checklist_checked[]" value="">&nbsp;' + plaintext.val() + '</label><span class="elgg-icon elgg-icon-delete float-alt"></span></li>');
				plaintext.val('');
				resizePopUp();
			};
			plaintext.focusin(function(){
				if ( $(this).val() == elgg.echo("workflow:checklist:add_item") ) {
					$(this).val('');
				}
				$(this).parent().children('.elgg-button-submit, .elgg-icon-delete').css('display', 'block');
				resizePopUp();
			}).focusout(function(){
				if ( $(this).val() == '' ) {
					$(this).val(elgg.echo("workflow:checklist:add_item"));
					$(this).parent().children('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
					resizePopUp();
				}
			}).keydown(function(e){
				if (e.keyCode == 13) {
					e.preventDefault();
					if ($(this).val()) addChecklistItem();
				}
			});
			$('#card-forms .elgg-input-checkboxes .elgg-icon-delete').live('click', function(){
				$(this).parents('li').remove();
			});
			$('#card-forms .card-checklist > .elgg-icon-delete').live('click', function(){
				plaintext.val(elgg.echo("workflow:checklist:add_item"));
				$(this).parent().children('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
				resizePopUp();
			});
			$('#card-forms .card-checklist .elgg-button-submit').live('click', function() {
				addChecklistItem();
				plaintext.val(elgg.echo("workflow:checklist:add_item"));
				plaintext.parent().children('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
			});

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
	var form = $(this).closest('form'),
		card_guid = form[0].entity_guid.value,
		checklist = [],
		checklistItems = [],
		i = 0;
	form.find('.card-checklist .elgg-input-checkbox').each(function() {
		$(this).val(i);
		i++;
	});
	form.find('.card-checklist .elgg-input-checkboxes label').each(function() {
		checklistItems.push($.trim($(this).text()));
	});

	if(form.attr('action').match('/comments/') && typeof elgg.galliComments.init == 'function') {
		return true;
	} else {
		elgg.action(form.attr('action'), {
			data: form.serialize() + '&' + $.param({checklist: checklistItems}),
			success: function(json) {
				$.fancybox.close();
				if (card_guid && json.output && json.status == 0) { // card modified
					$('#workflow-card-'+card_guid).replaceWith(json.output.card);
					$('.elgg-sidebar .elgg-module-aside.participants').replaceWith(json.output.sidebar);
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
	}

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


/**
 * Elgg-workflow re-initialization for ajax call
 *
 * @return void
 */
elgg.workflow.reload = function() {
	elgg.workflow.init();
	elgg.workflow.list.init();
	elgg.workflow.list.addCard();
	elgg.workflow.card.init();
	//elgg.workflow.card.popup();
	elgg.workflow.list.resize();
}

// End of js for elgg-workflow plugin
