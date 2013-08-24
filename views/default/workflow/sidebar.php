<?php
/**
 * Brainstorm group sidebar
 */
elgg_load_library('workflow:utilities');

$board_guid = elgg_extract('board_guid', $vars);

$board = get_entity($board_guid);
$user_guid = elgg_get_logged_in_user_guid();

echo '<div class="workflow-sidebar">';

if ($board_guid && $board->getOwnerGUID() != $user_guid) {

	// get participants
	$all_assignedto = workflow_get_board_participants($board_guid);
	$content = '';
	foreach ($all_assignedto as $user) {
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
elgg_set_page_owner_guid($board->container_guid);

$options['joins'][] = "JOIN {$dbprefix}entities e ON e.guid = rv.object_guid";
$options['wheres'][] = "e.container_guid = " . elgg_get_page_owner_guid();
$options['wheres'][] = "(rv.view IN ('river/object/workflow_river/create', 'river/object/workflow_river/modified'))";

if ($board_guid) {
	$metastring = get_metastring_id('board_guid');
	$board_string = get_metastring_id($board_guid);

	if ($board_string) { // if board_string doesn't exist that mind no card and list are created > board just created.
		$options['joins'][] = "LEFT JOIN {$dbprefix}metadata d ON d.entity_guid = e.guid";
		$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings m ON m.id = d.value_id";
		$options['wheres'][] = "d.name_id = {$metastring} AND d.value_id = {$board_string}";
		//$options['wheres'][] = "rv.object_guid = {$board_guid}";

		$defaults = array(
			//'offset' => (int) get_input('offset', 0),
			'limit' => 10,
			'pagination' => FALSE,
			'count' => FALSE,
		);
		$options = array_merge($defaults, $options);
		$items = elgg_get_river($options);

		$content = '<ul class="elgg-river elgg-list">';
		if (is_array($items)) {
			foreach ($items as $item) {
				$content .= "<li id='item-river-{$item->id}' class='elgg-list-item board-{$board_guid}' datetime=\"{$item->posted}\">";
					$content .= elgg_view('river/item', array('item' => $item, 'short' => true));
				$content .= '</li>';
			}
		}
		$content .= '</ul>';

		$title = elgg_echo('workflow:sidebar:last_activity_on_this_board');
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

	$content = '<ul class="elgg-river elgg-list">';
	if (is_array($items)) {
		foreach ($items as $item) {
			$object = $item->getObjectEntity();
			$content .= "<li id='item-river-{$item->id}' class='elgg-list-item board-{$object->board_guid}' datetime=\"{$item->posted}\">";
				$content .= elgg_view('river/item', array('item' => $item, 'short' => 'group'));
			$content .= '</li>';
		}
	}
	$content .= '</ul>';

	$title = elgg_echo('workflow:sidebar:last_activity_all_board', array(elgg_get_page_owner_entity()->name));

	if ($content) {
		 echo elgg_view_module('aside', $title, $content, array('class' => 'river'));
	} else {
		 echo elgg_view_module('aside', '', '', array('class' => 'river'));
	}
}

echo '</div>';