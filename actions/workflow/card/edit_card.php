<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow card edit card action
 *
 */

$card_guid = (int) get_input('entity_guid');
$title = get_input('title');
$desc = get_input('description');
$assignedto = (array)get_input('members'); // ui autocomplete result
$assignedtome = get_input('assignedtome', false);
$checklist = get_input('checklist');
$checklist_checked = get_input('checklist_checked');
$duedate = get_input('duedate');
$tags = get_input('tags');


// start a new sticky form session in case of failure
elgg_make_sticky_form('card');

$card = get_entity($card_guid);

if (!$card) {
	register_error(elgg_echo('workflow:unknown_card'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

$list = get_entity($card->list_guid);
$board = get_entity($list->board_guid);

if ($card && $list && $board && $card->canEdit()) {
	$card->title = $title;
	$card->description = $desc;
	$card->checklist = $checklist;
	$card->checklist_checked = $checklist_checked;
	$card->duedate = $duedate;
	$card->tags = $tags;
	$card->access_id = $board->access_id;

	if ($card->save()) {

		// add assignedtome in assigned user
		if ($assignedtome) {
			$assignedto = array_unique(array_merge($assignedto, array($user->getGUID())));
		}

		// Remove unassigned user
		elgg_load_library('workflow:utilities');
		$assigned_users = elgg_get_entities_from_relationship(array(
			'relationship' => 'assignedto',
			'relationship_guid'=> $card_guid,
			'limit' => 0
		));
		foreach($assigned_users as $assigned_user) {
			if (!in_array($assigned_user->guid, $assignedto)) remove_entity_relationship($card_guid, 'assignedto', $assigned_user->guid);
		}
		// Assign to users
		foreach ($assignedto as $assignedto_user) {
			$assignedto_user_entity = get_entity($assignedto_user);
			if (can_write_to_container($assignedto_user, $board->container_guid)) {
				if (add_entity_relationship($card_guid, 'assignedto', $assignedto_user)) {
					notify_assigned_user($user, $assignedto_user_entity, $card, $list, $board);
				}
			} else {
				register_error(elgg_echo('workflow:card:assign:notingroup', array($assignedto_user_entity->name)));
			}
		}

		elgg_clear_sticky_form('card');
		system_message(elgg_echo('workflow:card:edit:success'));

		echo json_encode(array(
			'card' => elgg_view_entity($card, array('view_type' => 'group'))
		));
	} else {
		register_error(elgg_echo('workflow:card:edit:failure'));
	}

} else {
	register_error(elgg_echo('workflow:card:edit:cannot_edit'));
}
