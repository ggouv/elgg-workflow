<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow archive list action
 *
 */

$archived_list_guid = (int) get_input('list_guid');

$archived_list = get_entity($archived_list_guid);
$board_guid = $archived_list->board_guid;

$user_guid = elgg_get_logged_in_user_guid();

if ($archived_list && can_write_to_container($user_guid, $archived_list->container_guid)) {

	elgg_load_library('workflow:utilities');

	// archive cards of this list
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'list_guid',
		'metadata_value' => $archived_list_guid,
		'limit' => 0
	));
	foreach($cards as $card) {
		workflow_archive($card);
	}

	// delete list
	workflow_archive($archived_list);
	$lists = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_list',
		'metadata_name' => 'board_guid',
		'metadata_value' => $archived_list->board_guid,
		'limit' => 0
	));
	$sorted_lists = array();
	foreach ($lists as $list) {
		$sorted_lists[$list->order] = $list;
	}
	ksort($sorted_lists);

	// redefine order for each list
	$order = 0;
	foreach ($sorted_lists as $list) {
		$list->order = $order;
		$order += 1;
	}

	system_message(elgg_echo('workflow:list:archived:success'));
	/*echo json_encode(array(
		'sidebar' => elgg_view('workflow/sidebar', array('board_guid' => $board_guid)),
	));*/
	forward(REFERER);
}

register_error(elgg_echo('workflow:list:delete:failure'));
forward(REFERER);
