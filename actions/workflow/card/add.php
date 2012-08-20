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

if (!$container_guid || !$list_guid) {
	register_error(elgg_echo('workflow:card:add:cannotadd'));
	forward(REFERER);
}

if (is_group_member( $container_guid, $user_guid ) || $user_guid == $container_guid) {

	$nbr_cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'parent_guid',
		'metadata_value' => $list_guid,
		'count' => true,
	));

	$card = new ElggObject;
	$card->subtype = "workflow_card";
	$card->container_guid = $container_guid;
	$card->board_guid = $list->parent_guid;
	$card->parent_guid = $list_guid;
	$card->title = $card_title;
	$card->access_id = $list->access_id;
	$card->order = $nbr_cards;

	if ($card->save()) {
		system_message(elgg_echo('workflow:card:add:success'));
		add_to_river('river/object/workflow_card/create','create', $user_guid, $card->getGUID());

		echo elgg_view_entity($card, array('view_type' => 'group'));
	} else {
		register_error(elgg_echo('workflow:card:add:failure'));
	}


} else {
	register_error(elgg_echo('workflow:card:add:cannotadd'));
}

