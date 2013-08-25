<?php
/**
 * Workflow sidebar
 */


$board_guid = elgg_extract('board_guid', $vars);

$board = get_entity($board_guid);
$user_guid = elgg_get_logged_in_user_guid();

echo '<div class="workflow-sidebar">';

if ($board_guid && $board->getContainerGUID() != $user_guid) {

	elgg_load_library('workflow:utilities');

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

	$title = elgg_echo('workflow:sidebar:last_activity_on_this_board');
} else {
	$board = elgg_get_page_owner_entity(); // In fact this is not a board, but a group
	$title = elgg_echo('workflow:sidebar:last_activity_all_board');
}

$content = '<div class="workflow-river">';
$content .= '<ul class="column-header hidden" data-board_guid="' . $board->getGUID() . '">';
$content .= '</ul>';
$content .= '<ul class="elgg-river elgg-list river-workflow">';
$content .= '</ul></div>';

echo elgg_view_module('', $title, $content, array('class' => 'river'));

echo '</div>';