<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow owner boards view
 *
 */

$owner = elgg_get_page_owner_entity();

if ($owner->type == 'group') {

	elgg_push_breadcrumb($owner->name);
	
	if ($owner->canEdit()) {
		elgg_register_title_button();
	}
	
	$title = elgg_echo('workflow:board:owner', array($owner->name));
	
	$content = elgg_list_entities(array(
		'type' => 'object',
		'subtypes' => 'workflow_board',
		'container_guid' => $owner->guid,
		'limit' => 0
	));
	
	if (!$content) {
		$content = elgg_echo('workflow:board:none');
	}
	
	$sidebar .= elgg_view('workflow/sidebar');
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
	);
	
	$body = elgg_view_layout('workflow', $params);
	
	echo elgg_view_page($title, $body);
	
} else if ($owner->canEdit()) {

	gatekeeper();
	
	$board = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => 'workflow_board',
		'owner_guid' => $owner->guid,
		'container_guid' => $owner->guid,
		'limit' => 1
	));
	
	if (!$board) {
		$board = new ElggObject;
		$board->subtype = "workflow_board";
		$board->container_guid = $owner->guid;
		$board->title = elgg_echo('my_workflow');
		$board->description = elgg_echo('my_workflow');
		$board->access_id = 0;
		$board->save();
	} else {
		$board= $board[0];
	}

	forward($board->getURL());
	
} else {
	forward(REFERER);
}