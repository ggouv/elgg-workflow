<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow archive/dearchive card action
 *
 */

$archived_card_guid = (int) get_input('card_guid');

$archived_card = get_entity($archived_card_guid);
$board = get_entity($archived_card->board_guid);

$user_guid = elgg_get_logged_in_user_guid();

if ($archived_card && can_write_to_container($user_guid, $archived_card->container_guid)) {

	elgg_load_library('workflow:utilities');

	if ($archived_card->getSubtype() == 'workflow_card') {
		if (workflow_archive($archived_card)) {
			system_message(elgg_echo('workflow:card:archived:success'));
		}
	} else if ($archived_card->getSubtype() == 'workflow_card_archived') { // dearchive card
		if (workflow_dearchive($archived_card)) {
			// replace in good position
			workflow_move_card($archived_card, $archived_card->list_guid, $archived_card->order);
			system_message(elgg_echo('workflow:card:dearchived:success'));
		}
	} else {
		register_error(elgg_echo('workflow:card:archive:failure'));
	}

	/*echo json_encode(array(
		'sidebar' => elgg_view('workflow/sidebar', array('board_guid' => $board_guid)),
	));*/
	forward($board->getURL());
}

register_error(elgg_echo('workflow:card:archive:failure'));
forward($board->getURL());
