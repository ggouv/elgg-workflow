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

$page_owner = elgg_get_page_owner_entity();
$user_guid = elgg_get_logged_in_user_guid();

elgg_push_breadcrumb(elgg_echo('workflow:assigned-cards'), "workflow/assigned-cards/all");
elgg_push_breadcrumb($page_owner->name);

if ($page_owner->guid == $user_guid) {
	$title = elgg_echo('workflow:assigned-cards:title:mine', array($page_owner->name));
} else {
	$title = elgg_echo('workflow:assigned-cards:title:owner', array($page_owner->name));
}

$content = elgg_list_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'workflow_card',
	'relationship' => 'assignedto',
	'relationship_guid' => $page_owner->guid,
	'inverse_relationship' => true,
	'view_type' => 'group',
	'split_items' => 3,
	'list_class' => 'workflow-card-list',
	'limit' => 30,
	'pagination' => true,
	'wheres' => 'e.owner_guid <> e.container_guid' // personnal card are same owner and container, so we doesn't want personnal card
));

if (!$content) {
	$content = elgg_echo('workflow:card:none');
}

$params = array(
	'content' => $content,
	'title' => $title,
	'filter_override' => elgg_view('workflow/nav', array('selected' => 'assigned-cards/owner')),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
