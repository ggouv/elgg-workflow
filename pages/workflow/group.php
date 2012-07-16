<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow group boards view
 *
 */

$group = elgg_get_page_owner_entity();
$user_guid = elgg_get_logged_in_user_guid();

if (!$group || $group->type != 'group') {
	forward('workflow/owner/' . $user_guid);
}

// access check for closed groups
group_gatekeeper();

elgg_push_breadcrumb($group->name);

elgg_register_menu_item('title', array(
	'name' => 'add_list',
	'href' => '#add-list',
	'rel' => 'popup',
	'text' => elgg_echo('workflow:add_list'),
	'link_class' => 'elgg-button elgg-button-action',
));

elgg_register_title_button();

$title = elgg_echo('workflow:owner', array($group->name));

$boards = elgg_get_entities(array(
	'type' => 'object',
	'subtypes' => 'workflow_board',
	'container_guid' => $group->guid,
	'limit' => 0
));


if (!$boards) {
	$content = $addlist . '<div class="workflow-lists-container"><p>' . elgg_echo('workflow:list:none') . '</p></div>';
}

$sidebar .= elgg_view('workflow/sidebar');

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
);

$body = elgg_view_layout('workflow', $params);

echo elgg_view_page($title, $body);
