<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow board delete action
 *
 */

$board_guid = get_input('guid');

$board = get_entity($board_guid);

if (!$board) {
	system_message(elgg_echo('workflow:board:delete:failed'));
	forward(REFERRER);
}

if ($board->canWritetoContainer()) {

	// delete all cards of this board
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'board_guid',
		'metadata_value' => $board_guid,
		'limit' => 0
	));
	foreach($cards as $card) {
		delete_entity($card->guid);
	}
	
	// delete all lists
	$lists = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_list',
		'metadata_name' => 'board_guid',
		'metadata_value' => $board_guid,
		'limit' => 0
	));
	foreach($lists as $list) {
		delete_entity($list->guid);
	}

	// and delete board
	delete_entity($board_guid);
	
	system_message(elgg_echo('workflow:board:delete:success'));
	forward(REFERER);
}

register_error(elgg_echo('workflow:bord:delete:failure'));
forward(REFERER);
