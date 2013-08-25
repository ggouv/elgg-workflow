<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow world boards view
 *
 */

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('workflow:all'));

$title = elgg_echo('workflow:board:all');

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'workflow_board',
	'list_class' => 'workflow-card-list',
	'limit' => 20,
	'wheres' => 'e.owner_guid <> e.container_guid' // personnal board are same owner and container, so we doesn't want personnal board
));

if (!$content) {
	$content = elgg_echo('workflow:board:none');
}

$params = array(
	'content' => $content,
	'title' => $title,
	'filter_override' => elgg_view('workflow/nav', array('selected' => 'all')),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
