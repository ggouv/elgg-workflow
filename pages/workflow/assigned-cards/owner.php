<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow owner assigned-cards view
 *
 */

$user = elgg_get_logged_in_user_entity();

elgg_push_breadcrumb(elgg_echo('workflow:assigned-cards:owner'));

$title = elgg_echo('workflow:assigned-cards:owner');

$content = elgg_list_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'workflow_card',
	'relationship' => 'assignedto',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => true,
	'view_type' => 'group',
	'split_items' => 3,
	'list_class' => 'workflow-card-list',
	'limit' => 30,
	'pagination' => true
));

if (!$content) {
	$content = elgg_echo('workflow:card:none');
}

$sidebar .= elgg_view('workflow/sidebar');

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
	'filter_override' => elgg_view('workflow/nav', array('selected' => 'assigned-cards/owner')),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
