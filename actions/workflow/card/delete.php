<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow card delete action
 *
 */

$deleted_card_guid = get_input('card_guid');

$deleted_card = get_entity($deleted_card_guid);
$list_guid = $deleted_card->list_guid;
$board_guid = $deleted_card->board_guid;

if ($deleted_card && $deleted_card->canWritetoContainer()) {

	elgg_load_library('workflow:utilities');
	$user_guid = elgg_get_logged_in_user_guid();

	$list = get_entity($list_guid);
	$in_string = elgg_echo('river:in:workflow_list', array(elgg_view('output/url', array(
			'href' => $list->getURL(),
			'text' => $list->title,
			'class' => 'elgg-river-object',
			'is_trusted' => true,
		))
	));
	$message = elgg_echo('river:delete:object:workflow_card:message', array($deleted_card->title, $in_string));
	$annotation_id = workflow_create_annotation($board_guid, $message, $user_guid, $list->access_id);

	if ($annotation_id['new'] == true) add_to_river('river/object/workflow_river/create','create', $user_guid, $board_guid, '', 0, $annotation_id['id']);

	delete_entity($deleted_card_guid);

	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'list_guid',
		'metadata_value' => $list_guid,
		'limit' => 0
	));

	$sorted_cards = array();
	foreach ($cards as $card) {
		$sorted_cards[$card->order] = $card;
	}
	ksort($sorted_cards);

	// redefine order for each card
	$order = 0;
	foreach ($sorted_cards as $card) {
		$card->order = $order;
		$order += 1;
	}

	system_message(elgg_echo('workflow:card:delete:success'));
	echo json_encode(array(
		'sidebar' => elgg_view('workflow/sidebar', array('board_guid' => $board_guid)),
	));
	forward(REFERER);
}

register_error(elgg_echo('workflow:card:delete:failure'));
forward(REFERER);
