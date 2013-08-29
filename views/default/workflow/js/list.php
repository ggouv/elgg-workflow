
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow js/list
 *
 */



/**
 * Workflow list initialization
 *
 * @return void
 */
elgg.provide('elgg.workflow.list');

elgg.workflow.list.init = function() {

	// workflow layout?
	if ($(".workflow-lists-container").length) {

		$(".workflow-lists-container").sortable({
			items:                'div.workflow-list.elgg-state-draggable',
			connectWith:          '.workflow-lists',
			handle:               '.workflow-list-handle',
			forcePlaceholderSize: true,
			placeholder:          'workflow-list-placeholder',
			opacity:              0.8,
			revert:               300,
			update:                 elgg.workflow.list.move
		});

		// focus on list popup
		$('.elgg-menu-item-add-list .elgg-button-action').click(function() {
			$('.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext').focus();
		});
		// add list popup
		$('.elgg-form-workflow-list-add-list-popup .elgg-button-submit').click(function(e){
			elgg.workflow.list.add($(this).closest('form'));
			return false;
		});
		$('.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext').focusin(function(){
			if ( $(this).val() == elgg.echo("workflow:add_list") ) $(this).val('');
		}).focusout(function(){
			if ( $(this).val() == '' ) $(this).val(elgg.echo("workflow:add_list"));
		}).keydown(function(e){
			if (e.keyCode == 13) {
				if ($(this).val()) elgg.workflow.list.add($(this).closest('form'));
				return false;
			}
		});
		// delete list button
		$('li.elgg-menu-item-delete a.workflow-list-delete-button').live('click', elgg.workflow.list.remove);
		// add card from list footer
		elgg.workflow.list.addCard();

	}

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
	return options;
};
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.addListPopup);



/**
 * Persist the list's new position
 *
 * @param {Object} event
 * @param {Object} ui
 *
 * @return void
 */
elgg.workflow.list.move = function(event, ui) {

	elgg.action('workflow/list/move', {
		data: {
			list_guid: ui.item.attr('id').replace(/workflow-list-/, ''),
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
	if (form.find('.elgg-input-plaintext').val() == elgg.echo('workflow:add_list')) return false;

	elgg.action(form.attr('action'), {
		data: form.serialize(),
		success: function(json) {

			if (!$('.workflow-lists').length && json.output.list !== '') {
				$('.workflow-lists-container').html('').append($('<div>', {'class': 'workflow-lists ui-sortable'}));
			}

			form.find('.elgg-input-plaintext').val(elgg.echo('workflow:add_list'));
			$('.workflow-lists').append(json.output.list);

			// add activity h3
			if (!$('.elgg-sidebar .elgg-module-river .elgg-head').length) {
				$('.elgg-sidebar .elgg-module-river').prepend('<div class="elgg-head mbs"><h3>' + elgg.echo('workflow:sidebar:last_activity_on_this_board') + '</h3></div>');
			}

			var riverItemDom = $('.elgg-module-river #' + $(json.output.river).filter('.elgg-list-item').attr('id'));

			if (riverItemDom.length) {
				riverItemDom.replaceWith(json.output.river);
			} else {
				$('.river-workflow').prepend(json.output.river);
			}
			elgg.workflow.list.addCard();
			elgg.workflow.list.resize();
			$('li.elgg-menu-item-delete a.workflow-list-delete-button').die().live('click', elgg.workflow.list.remove);
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
elgg.workflow.list.remove = function() {
	if (confirm(elgg.echo('workflow:list:delete:confirm'))) {
		var list = $(this).closest('.workflow-list');

		// delete the widget through ajax
		elgg.action($(this).attr('href'), {
			success: function(json) {
				list.remove();
				elgg.workflow.list.resize();

				var riverItemDom = $('.elgg-module-river #' + $(json.output.river).filter('.elgg-list-item').attr('id'));
				if (riverItemDom.length) {
					riverItemDom.replaceWith(json.output.river);
				} else {
					$('.river-workflow').prepend(json.output.river);
				}
				if (!$('.workflow-list').length) {
					$('.workflow-lists-container').html('').append('<p>' + elgg.echo('workflow:list:none') + '</p>');
				}
			}
		});
	}
	return false;
};



/**
 * Resize lists
 */
elgg.workflow.list.resize = function() {
	if ($('.workflow-lists-container .workflow-list').length) {
		var WorkflowWidth = $('.workflow-lists-container'),
			CountLists = $('.workflow-list').length,
			ListWidth = 0,
			offset = 0;
		workflow_min_width_list = 200; // @todo ? Plugins options ?

		if ( (parseInt(workflow_min_width_list) + 5 + 4) * CountLists > (WorkflowWidth.width() - 5) ) {
			ListWidth = parseInt(workflow_min_width_list);
			$('.workflow-lists').width( (ListWidth + 5 + 4) * CountLists - 5); // margin + border minus last margin doesn't displayed
			function scrollbarWidth() {
				if (!$._scrollbarWidth) {
					var $body = $('body'),
						w = $body.css('overflow', 'hidden').width();

					$body.css('overflow','scroll');
					w -= $body.width();
					if (!w) w=$body.width()-$body[0].clientWidth; // IE in standards mode
					$body.css('overflow','');
					$._scrollbarWidth = w+1;
				}
				return $._scrollbarWidth;
			}
			offset = scrollbarWidth();
		} else {
			ListWidth = Math.floor((WorkflowWidth.width() - (9*CountLists) + 5 ) / CountLists);
			$('.workflow-lists').width(WorkflowWidth.width());
		}
		$('.workflow-list, .workflow-list-placeholder').width(ListWidth);

		var b = $('.workflow-list > .elgg-body'),
			h = Math.floor(
				$(window).height()
					- $('.workflow-lists > div:first-child > .elgg-body').offset().top
					- $('.workflow-lists > div:first-child .elgg-foot').height()
					- 5 - offset
			);

		if (WorkflowWidth.height() + WorkflowWidth.offset().top < h) {
			WorkflowWidth.height($(window).height() - WorkflowWidth.offset().top);
			b.css('max-height', 'none');
		} else {
			WorkflowWidth.height('auto');
			b.css('max-height', h);
		}

		//sidebar
		var maxHeight = 0,
			river = $('.workflow-sidebar .elgg-river');

		river.height($(window).height()- river.offset().top);
	}
};



/**
 * Attach event on text area to add card on list
 */
elgg.workflow.list.addCard = function() {
	var echo = elgg.echo('workflow:list:add_card');

	$('.elgg-form-workflow-list-add-card .elgg-input-plaintext').focusin(function(){
		var $this = $(this);

		if ($this.val() == echo) $this.val('');
		$this.parent().find('div').removeClass('hidden')
			.closest('.workflow-list').children('.elgg-body').css('max-height', '-=27');
	}).focusout(function(){
		var $this = $(this);

		if ($this.val() == '') {
			$this.val(echo)
				.parent().find('div').addClass('hidden')
					.closest('.workflow-list').children('.elgg-body').css('max-height', '+=27');
		}
	}).keydown(function(e){
		if (e.keyCode == 13) {
			if ($(this).val()) elgg.workflow.card.add($(this).closest('form'));
			return false;
		}
	});

	$('.elgg-form-workflow-list-add-card .elgg-icon-delete').die().live('click', function(){
		$(this).parent().addClass('hidden')
			.parent().find('.elgg-input-plaintext').val(echo)
				.closest('.workflow-list').children('.elgg-body').css('max-height', '+=27');
	});
};

