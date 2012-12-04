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

elgg_load_library('workflow:utilities');

$card_guid = get_input('card_guid');
$card = get_entity($card_guid);
elgg_set_page_owner_guid($card->getContainerGUID());

if (!$card) {
	access_show_hidden_entities(true);
	$card = get_entity($card_guid);
	
	if ($card) { // this is an archived card. Cannot edit.
		echo elgg_view('workflow/view_card_popup', array('card' => $card, 'archive' => true));
	} else {
		echo elgg_echo('workflow:unknown_card');
	}
	
} else {

	if ($card->canEdit()) {
		$vars = array_merge(workflow_card_prepare_form_vars($card), array('preview' => 'toggle'));
		echo '<div id="card-forms">' .
			elgg_view_form('workflow/card/edit_card', array(), $vars) .
			'<div class="comments-part">' . elgg_view('page/elements/comments', $vars) . '</div>' .
		'</div>';
	} else { // Cannot edit. Back to view card.
		echo elgg_view('workflow/view_card_popup', array('card' => $card));
		return true;
	}
}
