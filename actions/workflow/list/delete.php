<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow list delete action
 *
 */

$deleted_list_guid = (int) get_input('list_guid');

$deleted_list = get_entity($deleted_list_guid);
$board_guid = $deleted_list->board_guid;

if ($deleted_list && $deleted_list->canWritetoContainer()) {

	elgg_load_library('workflow:utilities');
	$user_guid = elgg_get_logged_in_user_guid();

	$message = elgg_echo('river:delete:object:workflow_list:message', array($deleted_list->title));
	$annotation_id = workflow_create_annotation($board_guid, $message, $user_guid, $deleted_list->access_id);

	if ($annotation_id['new'] == true) {
		$id = add_to_river('river/object/workflow_river/create','create', $user_guid, $board_guid, '', 0, $annotation_id['id']);
		$item = elgg_get_river(array('id' => $id));
	} else {
		$item = elgg_get_river(array('annotation_id' => $annotation_id['id']));
	}

	// delete cards of this list. We doesn't delete archived card
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'list_guid',
		'metadata_value' => $deleted_list_guid,
		'limit' => 0
	));
	foreach($cards as $card) {
		delete_entity($card->guid);
	}

	// delete list
	delete_entity($deleted_list_guid);
	$lists = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_list',
		'metadata_name' => 'board_guid',
		'metadata_value' => $deleted_list->board_guid,
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

	system_message(elgg_echo('workflow:list:delete:success'));


	//elgg_set_page_owner_guid($container_guid);
	echo json_encode(array(
		'river' => "<li id='item-river-{$item[0]->id}' class='elgg-list-item' datetime=\"{$item[0]->posted}\">" .
						elgg_view('river/item', array('item' => $item[0], 'size' => 'tiny', 'short' => true)) . '</li>'
	));
	forward(REFERER);
}

register_error(elgg_echo('workflow:list:delete:failure'));
forward(REFERER);
