<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow card add action
 *
 */

$list_guid = (int) get_input('workflow_list', null);
$card_title = get_input('title', 'Card');

$list = get_entity($list_guid);
$container_guid = $list->container_guid;
$user_guid = elgg_get_logged_in_user_guid();

if (!$container_guid || !$list_guid || !$card_title) {
	register_error(elgg_echo('workflow:card:add:cannotadd'));
	forward(REFERER);
}

if ($list || $list->canWritetoContainer()) {

	$nbr_cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'list_guid',
		'metadata_value' => $list_guid,
		'count' => true,
		'limit' => 0
	));

	$card = new ElggObject;
	$card->subtype = "workflow_card";
	$card->container_guid = $container_guid;
	$card->board_guid = $list->board_guid;
	$card->list_guid = $list_guid;
	$card->title = $card_title;
	$card->access_id = $list->access_id;
	$card->order = $nbr_cards;

	if ($card->save()) {
		system_message(elgg_echo('workflow:card:add:success'));
		if ($id = add_to_river('river/object/workflow_card/create','create', $user_guid, $card->getGUID())) {
			
			$board = get_entity($list->board_guid);
			$board->set('workflow_last_action', time());
	
			$item = elgg_get_river(array('id' => $id));
			elgg_set_page_owner_guid($container_guid);
			$echo['river'] = "<li id='item-river-{$item[0]->guid}' class='elgg-list-item' datetime=\"{$item[0]->posted}\">" . 
								elgg_view('river/item', array('item' => $item[0], 'size' => 'tiny', 'short' => true)) . '</li>';
		}

		$echo['card'] = elgg_view_entity($card, array('view_type' => 'group'));
		echo json_encode($echo);
	} else {
		register_error(elgg_echo('workflow:card:add:failure'));
	}


} else {
	register_error(elgg_echo('workflow:card:add:cannotadd'));
}

