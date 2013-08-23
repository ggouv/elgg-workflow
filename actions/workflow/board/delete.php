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

	// delete all lists and cards of this board
	$objects = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => array('workflow_list', 'workflow_card', 'workflow_list_archived', 'workflow_card_archived'),
		'metadata_name' => 'board_guid',
		'metadata_value' => $board_guid,
		'limit' => 0
	));
	foreach($objects as $object) {
		delete_entity($object->guid);
	}

	// and delete board
	delete_entity($board_guid);
	
	system_message(elgg_echo('workflow:board:delete:success'));
	forward(REFERER);
}

register_error(elgg_echo('workflow:bord:delete:failure'));
forward(REFERER);
