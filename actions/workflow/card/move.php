<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow card move action
 *
 */
$card_guid = get_input('card_guid');
$list_guid = get_input('list_guid');
$position = get_input('position');

$moved_card = get_entity($card_guid);
$user_guid = elgg_get_logged_in_user_guid();

if ($moved_card && $moved_card->canWritetoContainer()) {

	// get cards from orginal list
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'list_guid',
		'metadata_value' => $moved_card->list_guid,
		'limit' => 0
	));

	// sort the list and remove the list that's being moved from the array
	$sorted_cards = array();
	foreach ($cards as $index => $card) {
		if ($card->guid != $card_guid) {
			$sorted_cards[$card->order] = $card;
		}
	}
	ksort($sorted_cards);

	// check if the card ordered in the same list
	if ( $moved_card->list_guid == $list_guid ) {

		// split the array in two and recombine with the moved card in middle
		$before = array_slice($sorted_cards, 0, $position);
		array_push($before, $moved_card);
		$after = array_slice($sorted_cards, $position);
		$cards = array_merge($before, $after);
		ksort($cards);

		// redefine order for each card
		$order = 0;
		foreach ($cards as $card) {
			$card->order = $order; // @todo don't work with $card->save(); for just member of group 
			$order += 1;
		}

		system_message(elgg_echo('workflow:card:move:success'));

	} else { // not in the same list

	// order orginal list
		$cards = array_merge(array(),$sorted_cards);
		$order = 0;
		foreach ($cards as $card) {
			$card->order = $order;
			$order += 1;
		}

	// order destination list
		// get cards from destination list
		$cards = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => 'workflow_card',
			'metadata_name' => 'list_guid',
			'metadata_value' => $list_guid,
			'limit' => 0
		));

		// sort the list and remove the list that's being moved from the array
		$sorted_cards = array();
		foreach ($cards as $index => $card) {
			$sorted_cards[$card->order] = $card;
		}
		ksort($sorted_cards);

		// split the array in two and recombine with the moved card in middle
		$before = array_slice($sorted_cards, 0, $position);
		array_push($before, $moved_card);
		$after = array_slice($sorted_cards, $position);
		$cards = array_merge($before, $after);
		ksort($cards);

		// redefine order for each card
		$order = 0;
		foreach ($cards as $card) {
			$card->order = $order;
			$order += 1;
		}

		// define list_guid's card to destination list
		$original_list = $moved_card->list_guid;
		$moved_card->list_guid = $list_guid;
		
		if ($moved_card->save()) {
			system_message(elgg_echo('workflow:card:move:success'));
		
			$list = get_entity($list_guid);
			elgg_load_library('workflow:utilities');
			$annotation_id = workflow_create_annotation($list->board_guid, array($card_guid, 'move', $original_list, $list_guid), $user_guid, $list->access_id);
		
			if ($annotation_id['new'] == true) {
				$id = add_to_river('river/object/workflow_river/create','update', $user_guid, $card->getGUID(), '', 0, $annotation_id['id']);
				$item = elgg_get_river(array('id' => $id));
			} else {
				$item = elgg_get_river(array('annotation_id' => $annotation_id['id']));
			}
		
			elgg_set_page_owner_guid($container_guid);
			$echo['river'] = "<li id='item-river-{$item[0]->id}' class='elgg-list-item' datetime=\"{$item[0]->posted}\">" .
								elgg_view('river/item', array('item' => $item[0], 'size' => 'tiny', 'short' => true)) . '</li>';
		
			echo json_encode($echo);
		}

	}
	forward(REFERER);
}

register_error(elgg_echo('workflow:card:move:failure'));
forward(REFERER);
