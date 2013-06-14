<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow assign user action
 *
 */

$card_guid = (int) get_input('card_guid');
$assignedto = (string) get_input('member');

$user = elgg_get_logged_in_user_guid();

if (!$card_guid) {
	register_error(elgg_echo('workflow:unknown_card'));
	forward(REFERER);
}

$card = get_entity($card_guid);
$list = get_entity($card->list_guid);
$board = get_entity($list->board_guid);

if ($card && $list && $board && $card->canEdit()) {

	if ($assignedto_user = get_user_by_username($assignedto)) {
		// Assign to users
		add_entity_relationship($card_guid, 'assignedto', $assignedto_user->getGUID());

		system_message(elgg_echo('workflow:card:assign:success'));

		echo json_encode(array(
			'card' => elgg_view_entity($card, array('view_type' => 'group')),
			'sidebar' => elgg_view('workflow/sidebar', array('board_guid' => $board->guid)),
		));
	} else {
		register_error(elgg_echo('workflow:card:assign:failure'));
	}

} else {
	register_error(elgg_echo('workflow:card:edit:cannot_edit'));
}