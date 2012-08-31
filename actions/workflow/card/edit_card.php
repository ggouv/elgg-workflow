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
$assignedto = (array)get_input('members');
$checklist = get_input('checklist');
$checklist_checked = get_input('checklist_checked');
$duedate = get_input('duedate');
$tags = get_input('tags');

$user = elgg_get_logged_in_user_guid();

// start a new sticky form session in case of failure
elgg_make_sticky_form('card');

if (!$card_guid) {
	register_error(elgg_echo('workflow:unknown_card'));
	forward(REFERER);
}

$card = get_entity($card_guid);
$list = get_entity($card->list_guid);
$board = get_entity($list->board_guid);

if ($card->canEdit()) {
	$card->title = $title;
	$card->description = $desc;
	$card->checklist = $checklist;
	$card->checklist_checked = $checklist_checked;
	$card->duedate = $duedate;
	$card->tags = $tags;
	$card->access_id = $board->access_id;

	if ($card->save()) {
		
		// Remove unassigned user
		$assigned_users = elgg_get_entities_from_relationship(array(
			'relationship' => 'assignedto',
			'relationship_guid'=> $card_guid,
		));
		foreach($assigned_users as $assigned_user) {
			if (!in_array($assigned_user->guid, $assignedto)) remove_entity_relationship($card_guid, 'assignedto', $assigned_user->guid);
		}
		// Assign to users
		foreach ($assignedto as $assignedto_user) {
			add_entity_relationship($card_guid, 'assignedto', $assignedto_user);
		}
		
		elgg_clear_sticky_form('card');
		system_message(elgg_echo('workflow:card:edit:success'));
		
		$board->set('workflow_last_action', time());
	
		echo json_encode(array(
			'card' => elgg_view_entity($card, array('view_type' => 'group')),
			'sidebar' => elgg_view('workflow/sidebar', array('board_guid' => $board->guid)),
		));
	} else {
		register_error(elgg_echo('workflow:card:edit:failure'));
	}

} else {
	register_error(elgg_echo('workflow:card:edit:cannot_edit'));
}
