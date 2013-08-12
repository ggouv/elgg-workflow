<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow view card action
 *
 */

$card_guid = (int) get_input('entity_guid');
$card = get_entity($card_guid);

if (!$card) {
	register_error(elgg_echo('workflow:unknown_card'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_guid();

$board = get_entity($card->board_guid);
$group = $card->getContainerEntity();

if ($card && $board && $group->canWritetoContainer()) {

	add_entity_relationship($card_guid, 'assignedto', $user);

	set_input('card_guid', $card_guid);

	echo json_encode(array(
		'card' => elgg_view_entity($card, array('view_type' => 'group')),
		'card_popup' => elgg_view('workflow/edit_card_popup'),
		'sidebar' => elgg_view('workflow/sidebar', array('board_guid' => $board->getGUID()))
	));

} else {
	register_error(elgg_echo('workflow:card:edit:cannot_edit'));
}
