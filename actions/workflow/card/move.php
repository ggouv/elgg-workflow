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

if ( $moved_card && is_group_member( $moved_card->container_guid, elgg_get_logged_in_user_guid() ) ) {

	// get cards from orginal list
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'parent_guid',
		'metadata_value' => $moved_card->parent_guid,
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
	if ( $moved_card->parent_guid == $list_guid ) {

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
			'metadata_name' => 'parent_guid',
			'metadata_value' => $list_guid,
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

	// define parent_guid's card to destination list
		$moved_card->parent_guid = $list_guid;
		$moved_card->save();

	}
	forward(REFERER);
}

register_error(elgg_echo('workflow:card:move:failure'));
forward(REFERER);
