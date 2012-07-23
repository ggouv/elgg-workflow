<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow list add action
 *
 */

$user_guid = elgg_get_logged_in_user_guid();
$board_guid = (int) get_input('board_guid', null);
$list_title = get_input('list_title', elgg_echo('workflow:list:title:default'));

$board = get_entity($board_guid);
$container_guid = $board->container_guid;

if (is_group_member( $container_guid, $user_guid ) || $user_guid == $container_guid) {

	$nbr_lists = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_list',
		'metadata_name' => 'parent_guid',
		'metadata_value' => $board_guid,
		'count' => true,
		'limit' => 0
	));

	$list = new ElggObject;
	$list->subtype = "workflow_list";
	$list->container_guid = $container_guid;
	$list->parent_guid = $board_guid;
	$list->title = $list_title;
	$list->access_id = $board->access_id;
	$list->order = $nbr_lists;

	if ($list->save()) {
		system_message(elgg_echo('workflow:list:add:success'));
		add_to_river('river/object/workflow_list/create','create', $user_guid, $list->getGUID());

		echo elgg_view_entity($list, array('view_type' => 'group'));
	} else {
		register_error(elgg_echo('workflow:list:add:failure'));
	}

} else {
	register_error(elgg_echo('workflow:list:add:cannotadd'));
}

