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

function workflow_card_prepare_form_vars($card = null) {
	$user = elgg_get_logged_in_user_guid();
	
	$values = array(
		'title' => get_input('title', ''),
		'description' => '',
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

/*
function todolist_prepare_form_vars($todolist = null, $parent_guid = 0) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'startdate' => '',
		'enddate' => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $tasklist,
		'parent_guid' => $parent_guid,
	);

	if ($tasklist) {
		foreach (array_keys($values) as $field) {
			if (isset($tasklist->$field)) {
				$values[$field] = $tasklist->$field;
			}
		}
	}

	if (elgg_is_sticky_form('tasklist')) {
		$sticky_values = elgg_get_sticky_values('tasklist');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('tasklist');

	return $values;
}

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $task
 * @return array
 *//*
function todo_prepare_form_vars($todo = null, $list_guid = 0) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'priority' => '',
		'elapsed_time' => '',
		'remaining_time' => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $task,
		'list_guid' => $list_guid,
	);

	if ($task) {
		foreach (array_keys($values) as $field) {
			if (isset($task->$field)) {
				$values[$field] = $task->$field;
			}
		}
	}

	if (elgg_is_sticky_form('task')) {
		$sticky_values = elgg_get_sticky_values('task');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('task');

	return $values;
}

/*
function tasks_get_entities($options) {
	$default = array(
		'type' => 'object',
		'subtype' => 'task',
	);
	
	$options = array_merge($default, $options);
	return elgg_get_entities_from_metadata($options);
}

function tasks_get_actions_from_state($state){
	switch($state) {
		
		case 'new':
		case 'unassigned':
		case 'reopened':
			$actions = array(
				'assign',
				'assign_and_activate',
				'mark_as_done',
				'close',
			);
			break;
			
		case 'assigned':
			$actions = array(
				'activate',
				'leave',
				'mark_as_done',
				'close',
			);
			break;
			
		case 'active':
			$actions = array(
				'deactivate',
				'leave',
				'mark_as_done',
				'close',
			);
			break;
			
		case 'done':
		case 'closed':
			$actions = array(
				'reopen',
			);
			break;
			
	}
	
	return $actions;
}

function tasks_prepare_radio_options($state) {
	
	$actions = tasks_get_actions_from_state($state);
	
	$actions_labels = array(
		elgg_echo("tasks:state:action:noaction", array($state)) => '',
	);
	
	foreach($actions as $action) {
		$actions_labels[elgg_echo("tasks:state:action:$action")] = $action;
	}
	
	return $actions_labels;
}
				
function tasks_get_state_from_action($action){
	$actions_states = array(
		'assign' => 'assigned',
		'leave' => 'unassigned',
		'activate' => 'active',
		'deactivate' => 'assigned',
		'assign_and_activate' => 'active',
		'mark_as_done' => 'done',
		'close' => 'closed',
		'reopen' => 'reopened',
	);
	return $actions_states[$action];
}
		*/
