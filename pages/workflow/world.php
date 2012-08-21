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

$title = elgg_echo('workflow:board:all');

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtypes' => 'workflow_board',
	'limit' => 20
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
