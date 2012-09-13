<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow utilities
 *
 */

function workflow_board_prepare_form_vars($board = null) {
	$user = elgg_get_logged_in_user_guid();
	
	$values = array(
		'title' => get_input('title', ''),
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $board,
	);

	if ($board) {
		foreach (array_keys($values) as $field) {
			if (isset($board->$field)) {
				$values[$field] = $board->$field;
			}
		}

		$values['order'] = $board->order;
	}

	if (elgg_is_sticky_form('board')) {
		$sticky_values = elgg_get_sticky_values('board');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('board');

	return $values;
}


function workflow_card_prepare_form_vars($card = null) {
	$user = elgg_get_logged_in_user_guid();
	
	$values = array(
		'title' => get_input('title', ''),
		'description' => '',
		'assignedto' => '',
		'checklist' => '',
		'checklist_checked' => '',
		'duedate' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $card,
	);

	if ($card) {
		foreach (array_keys($values) as $field) {
			if (isset($card->$field)) {
				$values[$field] = $card->$field;
			}
		}

		$values['order'] = $card->order;
	}

	if (elgg_is_sticky_form('card')) {
		$sticky_values = elgg_get_sticky_values('card');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('card');

	return $values;
}

function workflow_get_board_participants($board_guid) {
	if (!$board_guid) return false;

	// get all cards of the board
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'board_guid',
		'metadata_value' => $board_guid,
		'limit' => 0
	));
	
	// get all users assignedto
	$all_assignedto = array();
	$all_assignedto_guid = array();
	foreach($cards as $card) {
		$assigned_users = elgg_get_entities_from_relationship(array(
			'relationship' => 'assignedto',
			'relationship_guid'=> $card->guid,
		));
		if ($assigned_users) {
			foreach ($assigned_users as $user) {
				if ( !in_array($user->guid, $all_assignedto_guid) ) {
					$all_assignedto[] = $user;
					$all_assignedto_guid[] = $user->guid;
				}
			}
		}
	}
	
	return $all_assignedto;
}

function workflow_create_annotation($board_guid, $message, $user_guid = 0, $access = 2) {
	if (!$user_guid) $usre_guid = elgg_get_logged_in_user_guid();
	$board = get_entity($board_guid);
	
	$annotations = $board->getAnnotations('workflow_river', 1, 0, 'desc');
	$annotation = $annotations[0];
	
	if ($annotation->owner_guid == $user_guid && (time() - $annotation->time_created) <= 3600) { // less than one hour
		$message = $annotation->value . '<br>' . $message;
		update_annotation($annotation->id, 'workflow_river', $message, '', $user_guid, $access);		
		return array('new' => false, 'id' => $annotation->id);
	} else {
		$annotation_id = create_annotation($board_guid, 'workflow_river', $message, '', $user_guid, $access);
		return array('new' => true, 'id' => $annotation_id);
	}
}