<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow edit card popup
 *
 */

$card_guid = get_input('card_guid');
$card = get_entity($card_guid);

if (!elgg_instanceof($card, 'object', 'workflow_card') || !$card->canEdit()) {
	register_error(elgg_echo('workflow:unknown_card'));
	forward(REFERRER);
}

$vars = workflow_card_prepare_form_vars($card);

echo '<div id="card-forms">' .
	elgg_view_form('workflow/card/edit_card', array(), $vars) .
	elgg_view('page/elements/comments', $vars) .
	'</div>';
