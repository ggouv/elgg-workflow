<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow list move action
 *
 */

$list_guid = get_input('list_guid');
$position = get_input('position');
$owner_guid = elgg_get_logged_in_user_guid();

$moved_list = get_entity($list_guid);

if ($moved_list && $moved_list->canEdit($owner_guid)) {

	$lists = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_list',
		'metadata_name' => 'board_guid',
		'metadata_value' => $moved_list->board_guid,
		'limit' => 0
	));

	// sort the list and remove the list that's being moved from the array
	$sorted_lists = array();
	foreach ($lists as $index => $list) {
		if ($list->guid != $list_guid) {
			$sorted_lists[$list->order] = $list;
		}
	}
	ksort($sorted_lists);

	// split the array in two and recombine with the moved list in middle
	$before = array_slice($sorted_lists, 0, $position);
	array_push($before, $moved_list);
	$after = array_slice($sorted_lists, $position);
	$lists = array_merge($before, $after);
	ksort($lists);

	// redefine order for each list
	$order = 0;
	foreach ($lists as $list) {
		$list->order = $order;
		$order += 1;
	}
	
	$board = get_entity($moved_list->board_guid);
	$board->set('workflow_last_action', time());
	
	system_message(elgg_echo('workflow:list:move:success'));
	forward(REFERER);
}

register_error(elgg_echo('workflow:list:move:failure'));
forward(REFERER);
