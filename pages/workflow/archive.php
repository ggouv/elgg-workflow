<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow archive-list board view
 *
 */
$board_guid = get_input('board_guid');
$board = get_entity($board_guid);
$subtype = get_input('subtype');

//$user_guid = elgg_get_logged_in_user_guid(); @todo

if (!$board) {
	forward(REFERER);
}

if (!$subtype || !in_array($subtype, array('workflow_list', 'workflow_card'))) {
	$subtype = 'workflow_list';
}

elgg_set_page_owner_guid($board->getContainerGUID());
$container = elgg_get_page_owner_entity();

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "workflow/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "workflow/owner/$container->username");
}

elgg_push_breadcrumb($board->title, $board->getURL());

elgg_push_breadcrumb(elgg_echo('workflow:archive'));

elgg_register_menu_item('title', array(
	'name' => 'board',
	'href' => $board->getURL(),
	'text' => elgg_echo('workflow:board:back'),
	'link_class' => 'elgg-button elgg-button-action',
));

$title = elgg_echo('workflow:board:archive', array($board->title));

// show disable entities
access_show_hidden_entities(true);
$content = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtypes' => $subtype,
	'metadata_name' => 'board_guid',
	'metadata_value' => $board_guid,
	'wheres' => "e.enabled='no'",
	'view_type' => 'group',
	'split_items' => 3,
	'list_class' => 'workflow-card-list',
	'limit' => 30,
	'pagination' => true
));

if (!$content) {
	$content = elgg_echo('workflow:archive:none');
}

$sidebar .= elgg_view('workflow/sidebar');

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
	'filter_override' => elgg_view('workflow/nav_archive', array('selected' => $subtype)),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
