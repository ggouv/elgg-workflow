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


/**
 * Move card in same liste or between list
 * @param  [ElggObject]    $moved_card   The card moved
 * @param  [GUID]          $list_guid    GUID of the destination list
 * @param  [integer]       $position     Position in the list, default first
 * @return [bool]                        Return treu/false depend on success
 */
function workflow_move_card($moved_card, $list_guid, $position = 0) {
	// get cards from orginal list
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'list_guid',
		'metadata_value' => $moved_card->list_guid,
		'limit' => 0
	));

	// sort the card and remove the card that's being moved from the array
	$sorted_cards = array();
	foreach ($cards as $card) {
		if ($card->guid != $moved_card->getGUID()) {
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
		$moved_card->list_guid = $list_guid;

	}
	return true;
}



/**
 * Archive board, list or card
 *
 * @param [ElggObject] ElggObject of the board, the list or the card
 *
 * @return true|false Depending on success
 */
function workflow_archive($entity) {
	// Check if it's a board, a list or a card. Security.
	if ($entity && in_array($entity->getSubtype(), array('workflow_board', 'workflow_list', 'workflow_card'))) {
		global $CONFIG;
		$archive_subtype = $entity->getSubtype() . '_archived';
		$archive_subtype = add_subtype('object', $archive_subtype); // create subtype if doesn't exist
		return update_data("UPDATE {$CONFIG->dbprefix}entities
								SET subtype = '$archive_subtype'
								WHERE {$CONFIG->dbprefix}entities.guid = {$entity->getGUID()}");
	}

	// error
	return false;
}



/**
 * De-archive board, list or card
 *
 * @param [ElggObject] ElggObject of the board, the list or the card
 *
 * @return true|false Depending on success
 */
function workflow_dearchive($entity) {
	// Check if it's an archived board, list or card. Security.
	if ($entity && in_array($entity->getSubtype(), array('workflow_board_archived', 'workflow_list_archived', 'workflow_card_archived'))) {
		global $CONFIG;
		// Note: subtype exist because it was already created when object was created before been archived
		$subtype = get_subtype_id('object', str_replace('_archived', '', $entity->getSubtype()));
		return update_data("UPDATE {$CONFIG->dbprefix}entities
								SET subtype = '$subtype'
								WHERE {$CONFIG->dbprefix}entities.guid = {$entity->getGUID()}");
	}

	// error
	return false;
}