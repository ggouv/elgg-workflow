<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow view card popup
 *
 */


$card_guid = get_input('card_guid');
$card = get_entity($card_guid);
elgg_set_page_owner_guid($card->getContainerGUID());

if (!$card) {
	access_show_hidden_entities(true);
	$card = get_entity($card_guid);
	
	if ($card) { // this is an archived card. Cannot edit.
		echo elgg_view('workflow/view_card_popup_content', array('card' => $card, 'archive' => true));
	}
	
} else {
	echo elgg_view('workflow/view_card_popup_content', array('card' => $card, 'archive' => false));
}