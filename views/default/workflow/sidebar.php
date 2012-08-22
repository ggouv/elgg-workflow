<?php
/**
 * Brainstorm group sidebar
 */
$board_guid = elgg_extract('board_guid', $vars);

echo '<div class="workflow-sidebar">';

if ($board_guid) {
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
	$content = '';
	foreach ($all_assignedto as $user) {
		//$user = get_entity($user_guid);
		$content .= elgg_view_entity_icon($user, 'small');
	}
	
	$title = elgg_echo('workflow:sidebar:assignedto_user');
	
	if ($content) {
		 echo elgg_view_module('aside', $title, $content, array('class' => 'participants'));
	} else {
		 echo elgg_view_module('aside', '', '', array('class' => 'participants'));
	}
}

// board activity
global $CONFIG;
$dbprefix = $CONFIG->dbprefix;
$board = get_entity($board_guid);
elgg_set_page_owner_guid($board->container_guid);

$options['joins'][] = "JOIN {$dbprefix}entities e ON e.guid = rv.object_guid";
$options['wheres'][] = "e.container_guid = " . elgg_get_page_owner_guid();
$options['wheres'][] = "(rv.subtype IN ('workflow_list','workflow_card'))";

if ($board_guid) {
	$metastring = get_metastring_id('board_guid');
	$board_string = get_metastring_id($board_guid);
	
	if ($board_string) { // if board_string doesn't exist that mind no card and list are created > board just created.
		$options['joins'][] = "LEFT JOIN {$dbprefix}metadata d ON d.entity_guid = e.guid";
		$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings m ON m.id = d.value_id";
		$options['wheres'][] = "d.name_id = {$metastring} AND d.value_id = {$board_string}";
		
		$defaults = array(
			//'offset' => (int) get_input('offset', 0),
			'limit' => 10,
			'pagination' => FALSE,
			'count' => FALSE,
		);
		$options = array_merge($defaults, $options);
		$items = elgg_get_river($options);
		
		$content = '';
		if (is_array($items)) {
			foreach ($items as $item) {
				$content .= "<li id='item-river-{$item->id}' class='elgg-list-item board-{$board_guid}' datetime=\"{$item->posted}\">";
					$content .= elgg_view('river/item', array('item' => $item, 'size' => 'tiny', 'short' => true));
				$content .= '</li>';
			}
		}
		
		$title = elgg_echo('workflow:sidebar:last_activity_on_this_board', array($board->title));
	}
	
	if ($content) {
		 echo elgg_view_module('aside', $title, $content, array('class' => 'river'));
	} else {
		 echo elgg_view_module('aside', '', '', array('class' => 'river'));
	}
} else {
	$defaults = array(
		//'offset' => (int) get_input('offset', 0),
		'limit' => 10,
		'pagination' => FALSE,
		'count' => FALSE,
	);
	$options = array_merge($defaults, $options);
	$items = elgg_get_river($options);
	
	$content = '';
	if (is_array($items)) {
		foreach ($items as $item) {
			$object = $item->getObjectEntity();
			$content .= "<li id='item-river-{$item->id}' class='elgg-list-item board-{$object->board_guid}' datetime=\"{$item->posted}\">";
				$content .= elgg_view('river/item', array('item' => $item, 'size' => 'tiny', 'short' => 'group'));
			$content .= '</li>';
		}
	}
	
	$title = elgg_echo('workflow:sidebar:last_activity_all_board');
	
	if ($content) {
		 echo elgg_view_module('aside', $title, $content, array('class' => 'river'));
	} else {
		 echo elgg_view_module('aside', '', '', array('class' => 'river'));
	}
}

echo '</div>';