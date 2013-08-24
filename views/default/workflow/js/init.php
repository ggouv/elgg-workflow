
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow js/init
 *
 */

/**
 * Elgg-workflow initialization
 *
 * @return void
 */
elgg.provide('elgg.workflow');

elgg.workflow.init = function() {
	$(document).ready(elgg.workflow.reload);

	// for extensible template
	$(window).bind('resize.workflow', function() {
		if ( $('.workflow-lists-container').length ) {
			elgg.workflow.list.resize();
		}
	});

};
elgg.register_hook_handler('init', 'system', elgg.workflow.init);



/**
 * Elgg-workflow re-initialization for ajax call
 *
 * @return void
 */
elgg.workflow.reload = function() {
	// workflow layout?
	if ($('.workflow-lists-container').length) {

		$('body').addClass('fixed-workflow');

		// highlight object
		var url = elgg.parse_url(elgg.normalize_url(decodeURIComponent(window.location.href)), 'path');

		if (url.match('/card/(.*)/') !== null) {
			var card = $('.workflow-list').not('.my-assigned-cards').find('#workflow-card-'+url.match('/card/(.*)/')[1]);

			card.parents('.elgg-body').scrollTo(card, function() {
				card.effect("pulsate", function() {
					card.css('border','1px solid #00FF00');
				});
			});
		}
		if (url.match('/list/(.*)/') !== null) {
			var wlist = $('#workflow-list-'+url.match('/list/(.*)/')[1]);

			wlist.effect('pulsate', function() {
				wlist.css('border','2px solid #00FF00');
			});
		}

		elgg.workflow.list.init();
		elgg.workflow.card.init();
		elgg.workflow.list.resize();
		elgg.workflow.list.resize(); //do it again cause scrollbar. @todo find another way to fix that.

	} else {
		$('body').removeClass('fixed-workflow');
	}
};



/**
 * Plugin hook for elgg-deck_river
 */
elgg.workflow.deck_river = function(hook, type, params, options) {
	params.TheColumn.find('.elgg-river').html(params.activity);
	elgg.workflow.card.popup();
	return false;
};
elgg.register_hook_handler('deck-river', 'load:column:workflow', elgg.workflow.deck_river);
elgg.register_hook_handler('deck-river', 'refresh:column:workflow', elgg.workflow.deck_river);

