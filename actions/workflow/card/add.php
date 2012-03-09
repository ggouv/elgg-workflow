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

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$container_guid = get_input('container_guid', null);
$card_title = get_input('card_title', 'Card');

if (!$container_guid) {
	register_error(elgg_echo('workflow:card:add:cannotadd'));
	forward(REFERER);
}

$container = get_entity($container_guid);

if ($container->canEdit()) {

	$nbr_cards = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'container_guid' => $container_guid,
		'count' => true,
	)); 

	$card = new ElggObject;
	$card->subtype = "workflow_card";
	$card->container_guid = $container_guid;
	$card->title = $card_title;
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

