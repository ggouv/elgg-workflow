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

function workflow_create_annotation($board_guid, $action, $user_guid = 0, $access = 2) {
	if (!$user_guid) $user_guid = elgg_get_logged_in_user_guid();
	$board = get_entity($board_guid);

	$annotations = $board->getAnnotations('workflow_river', 1, 0, 'desc');
	$annotation = $annotations[0];

	if ($annotation->owner_guid == $user_guid && (time() - $annotation->time_created) <= 3600) { // less than one hour
		$annotation_array = unserialize($annotation->value);
		$annotation_array[] = $action;
		update_annotation($annotation->id, 'workflow_river', serialize($annotation_array), '', $user_guid, $access);
		return array('new' => false, 'id' => $annotation->id);
	} else {
		$annotation_array[] = $action;
		$annotation_id = create_annotation($board_guid, 'workflow_river', serialize($annotation_array), '', $user_guid, $access);
		return array('new' => true, 'id' => $annotation_id);
	}
}

function workflow_read_annotation($annotation_id) {
	$annotation_array = array_reverse(unserialize(elgg_get_annotation_from_id($annotation_id)->value));

	$rand = rand(); // make this for elgg-deck-column
	$count = count($annotation_array);
	if ($count > 2) {
		$message = $annotation_array[0] . '<br><a rel="toggle" href="#workflow-shorted-message-' . $annotation_id . '-' . $rand .'">' . elgg_echo('workflow:card:shorted:message', array($count - 1)) . '</a>';
		unset($annotation_array[0]);
		$message .= '<div id="workflow-shorted-message-' . $annotation_id . '-' . $rand .'" class="hidden">';
		foreach($annotation_array as $annotation_item) {
			$message .= $annotation_item . '<br>';
		}
		$message .= '</div>';
	} else if ($count > 1) {
		$message = $annotation_array[0] . '<br>' . $annotation_array[1];
	} else {
		$message = $annotation_array[0];
	}

	return $message;
}


/**
 * Return personal board of a user, create it if doesn't exist
 * @param  ElggUser          $owner_guid
 * @return ElggObject        board entity
 */
function workflow_get_user_board($owner_guid = false) {
	if (!$owner_guid) $owner_guid = elgg_get_page_owner_guid();

	$board = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => 'workflow_board',
		'owner_guid' => $owner_guid,
		'container_guid' => $owner_guid,
		'limit' => 1
	));

	if (!$board) {
		$board = new ElggObject;
		$board->subtype = "workflow_board";
		$board->container_guid = $owner_guid;
		$board->title = elgg_echo('my_workflow');
		$board->description = elgg_echo('my_workflow');
		$board->access_id = 0;
		$board->save();
	} else {
		$board= $board[0];
	}

	return $board;
}


/**
 * Send mail to assigned user
 * @param  [ElggUser] $user             Entity of the user who assigned
 * @param  [ElggUser] $assignedto_user  Entity of the assigned user
 * @param  [ElggObject] $card           Entity of the card
 * @param  [ElggObject] $list           Entity of the list
 * @param  [ElggObject] $board          Entity of the board
 */
function notify_assigned_user($user, $assignedto_user, $card, $list, $board) {
	// Send mail. A user can assignto a card himself. Don't send mail in this case.
	if ($user->getGUID() != $assignedto_user->getGUID()) {
		$user_view = elgg_view('output/link', array(
			'href' => $user->getURL(),
			'text' => $user->name
		));
		$card_view = elgg_view('output/link', array(
			'href' => $card->getURL(),
			'text' => $card->title
		));
		notify_user(
			$assignedto_user->getGUID(),
			$user->getGUID(),
			elgg_echo('workflow:notify:assigned:subject', array($user->name, $card->title)),
			elgg_echo('workflow:notify:assigned:body', array(
					$user_view,
					$card->title,
					$list->title,
					$board->title,
					$board->getContainerEntity()->title,
					"\n\n" . '<div style="background-color: #FAFAFA;font-size: 1.4em;padding: 10px;">' . $card->description . '</div>' . "\n")
			),
			array('method' => "email")
		);
	}
}