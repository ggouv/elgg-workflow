
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow js/card
 *
 */



/**
 * Workflow card initialization
 *
 * @return void
 */
elgg.provide('elgg.workflow.card');

elgg.workflow.card.init = function() {
	// workflow layout?
	if ($(".workflow-lists-container").length) {

		$(".workflow-lists").sortable({
			items:                'div.workflow-card.elgg-state-draggable',
			connectWith:          '.workflow-cards',
			handle:               '.workflow-card-handle',
			forcePlaceholderSize: true,
			placeholder:          'workflow-card-placeholder',
			revert:               300,
			dropOnEmpty:          true,
			update:                 elgg.workflow.card.move
		});

		$('.elgg-form-workflow-list-add-card .elgg-button-submit').click(function(e){
			elgg.workflow.card.add($(this).closest('form'));
			return false;
		});

		elgg.workflow.card.popup();
	}

};



/**
 * Persist the card's new position
 *
 * @param {Object} event
 * @param {Object} ui
 *
 * @return void
 */
elgg.workflow.card.move = function(event, ui) {
	var card_guidString = ui.item.attr('id').replace(/workflow-card-/, ''),
		list_guidString = ui.item.closest('.workflow-list').attr('id').replace(/workflow-list-/, '');

	// hack for empty list and sortable jquery.ui (dropOnEmpty doesn't work cause multiple div)
	pos = 0;
	ui.item.closest('.workflow-list').find('.workflow-card').each(function() {
		if ( $(this).attr('id') == 'workflow-card-'+card_guidString ) return false;
		if (!$(this).hasClass('workflow-card-none')) pos++;
	});

	elgg.action('workflow/card/move', {
		data: {
			card_guid: card_guidString,
			list_guid: list_guidString,
			position: pos
		},
		success: function(json) {
			var riverItem = $(json.output.river).filter('.elgg-list-item').attr('id'),
				riverItemDom = $('.elgg-module-aside.river #' + riverItem);

			if (riverItemDom.length) {
				riverItemDom.html(json.output.river);
			} else {
				$('.elgg-module-aside.river > .elgg-body').prepend(json.output.river);
			}
			elgg.workflow.card.popup();
			elgg.workflow.list.resize();
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
	var workflow_list = form.find('[name=workflow_list]').val(),
		input_add_card = form.find('.elgg-input-plaintext'),
		card_title = input_add_card.val(),
		river = $('.elgg-module-aside.river');

	if (card_title) {
		elgg.action(form.attr('action'), {
			data: form.serialize(),
			success: function(json) {
				var riverItem = $(json.output.river).filter('.elgg-list-item').attr('id'),
					$bodyList = $('#workflow-list-' + workflow_list+' > .elgg-body');

				$('#workflow-list-content-' + workflow_list + ' .workflow-cards').append(json.output.card);

				if (river.find('#' + riverItem).length) {
					river.find('#' + riverItem).html(json.output.river);
				} else {
					river.children('.elgg-body').prepend(json.output.river);
				}
				if (!form.find('.elgg-input-plaintext').is(':focus')) {
					elgg.workflow.list.resize();
					form.find('div').addClass('hidden');
				}
				$bodyList.scrollTo($bodyList.height())
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
 * Prepare edit card
 *
 * @return void
 */
elgg.workflow.card.popup = function() {
	$('.workflow-edit-card').die().live('click', function() {

		if (!$('#card-info-popup').length) {
			if (elgg.deck_river.createPopup) {
				elgg.deck_river.createPopup('card-info-popup', elgg.echo('deck_river:workflow:card-info-header'));
			} else {
				$('.elgg-page-body').append(
					$('<div>', {id: 'card-info-popup', 'class': 'elgg-module-popup'}).draggable({
						handle: '.elgg-head',
						stack: '.elgg-module-popup'
					}).append(
						$('<div>', {'class': 'elgg-head'}).append(
							$('<h3>').html(elgg.echo('workflow:card-info-header')).after(
							$('<a>', {href: '#'}).append(
								$('<span>', {'class': 'elgg-icon elgg-icon-delete-alt'})
							).click(function() {
								$('#card-info-popup').remove();
							})
						)).after(
							$('<div>', {'class': 'elgg-body'}).append(
								$('<div>', {'class': 'elgg-ajax-loader'})
				))));
			}
		} else {
			$('#card-info-popup > .elgg-body').html($('<div>', {'class': 'elgg-ajax-loader'}));
		}

		elgg.post('ajax/view/workflow/edit_card_popup', {
			dataType: "html",
			data: {
				card_guid: $(this).data('guid') || $(this).closest('.workflow-card').attr('id').replace(/workflow-card-/, '')
			},
			success: function(response) {
				$('#card-info-popup > .elgg-body').html(response);
			},
			error: function() {
				$('#card-info-popup > .elgg-body').html(elgg.echo('workflow:ajax:erreur'));
			}
		});
		return false;

	}).closest('.workflow-card').not('.elgg-river-object').droppable({
		accept: '.user-info-popup',
		drop: function(e, ui) {
			var card_guid = $(this).attr('id').replace(/workflow-card-/, '');

			elgg.action('workflow/card/assign_user', {
				data: {
					card_guid: card_guid,
					member: ui.helper.attr('title')
				},
				success: function(json) {
					$('#workflow-card-'+card_guid).replaceWith(json.output.card);
					$('.elgg-sidebar .workflow-sidebar').replaceWith(json.output.sidebar);
					elgg.workflow.card.popup();
				},
				error: function() {}
			});
		},
		over: function(e, ui) {
			ui.helper.addClass('canDrop');
		},
		out: function(e, ui) {
			ui.helper.removeClass('canDrop');
		}
	});

}

/**
 * Save data of card or new comment
 *
 * Event callback the uses Ajax to save data or comment
 *
 * @param {Object} event
 * @return void
 */
elgg.workflow.card.popupForms = function() {
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

	if (form.attr('action').match('/comments/')) {
		elgg.workflow.addCommentonCard(card_guid, 1);
		return true;
	} else {
		elgg.action(form.attr('action'), {
			data: form.serialize() + '&' + $.param({checklist: checklistItems}),
			success: function(json) {
				if (form.attr('action').match('/view_card')) {
					$('.elgg-page-body .river-workflow').append(
						$('<li>', {id: 'elgg-object-'+card_guid, 'class': 'elgg-item'}).append(
							$('<div>', {id: 'workflow-card-'+card_guid, 'class': 'elgg-module workflow-card mrs'})
					));
					$('#card-forms').replaceWith(json.output.card_popup);
				} else {
					$('#card-info-popup').remove();
				}
				$('.elgg-sidebar .workflow-sidebar').replaceWith(json.output.sidebar);
				if ($('#workflow-card-'+card_guid).closest('.river-workflow').length == 1
						&& $(json.output.card).find('img[alt="'+elgg.get_logged_in_user_entity().name+'"]').length == 0) {
					$('#workflow-card-'+card_guid).remove();
				} else {
					$('#workflow-card-'+card_guid).replaceWith(json.output.card);
				}
				elgg.workflow.card.popup();
			}
		});
	}

	return false;
};



/**
 * Increment or decrement number of comments a card from the layout
 *
 * Event callback the uses Ajax to delete the list and removes its HTML
 *
 * @param integer the guid of the card
 * @param integer add or remove 1
 * @return void
 */
elgg.workflow.addCommentonCard = function(card_guid, valueToAdd) {
		var card = $('#workflow-card-'+card_guid),
			desc = card.find('.workflow-card-description'),
			comm = card.find('.workflow-card-comment');

		if (comm.length == 0) {
			if (desc.length == 0) {
				card.find('.workflow-card-info').prepend('<div class="workflow-card-comment">0</div>');
			} else {
				desc.after('<div class="workflow-card-comment">0</div>');
			}
		}
		comm = card.find('.workflow-card-comment');
		var val = parseInt(comm.text())+valueToAdd;
		if (val <= 0) {
			comm.remove();
		} else {
			comm.html('<span class="elgg-icon elgg-icon-workflow-speech-bubble"></span>'+ val );
		}
	}


/**
 * Removes a card from the layout
 *
 * Event callback the uses Ajax to delete the list and removes its HTML
 *
 * @param {Object} event
 * @return void
 */
elgg.workflow.card.remove = function() {
	if (confirm(elgg.echo('workflow:card:delete:confirm'))) {
		card = $(this).parent().find('input[name=entity_guid]').val();
		// delete the card through ajax
		elgg.action('workflow/card/delete', {
			data: {
				card_guid: card
			},
			success: function(json) {
				$('#workflow-card-'+card).remove();
				$('.elgg-sidebar .workflow-sidebar').replaceWith(json.output.sidebar);
				$('#card-info-popup').remove();
				elgg.workflow.list.resize();
			}
		});
	}
	return false;
};



// End of js for elgg-workflow plugin
