<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow group board view
 *
 */

$group = elgg_get_page_owner_entity();
$user = elgg_get_logged_in_user_entity();

if (!$group || $group->type != 'group') {
	forward('workflow/all');
}

// access check for closed groups
group_gatekeeper();

elgg_push_breadcrumb($group->name);

elgg_register_title_button('workflow', 'add_list');

$title = elgg_echo('workflow:owner', array($group->name));

$lists = elgg_get_entities(array(
	'type' => 'object',
	'subtypes' => 'tasklist',
	'container_guid' => $group->guid,
));

$num_lists = count($lists);

if ( $group->isMember($user) ) {
	$can_edit_class = 'elgg-state-draggable';
}

$content = "<div class='workflow-lists'>";
for ($list_index = 1; $list_index <= $num_lists; $list_index++) {
	$content .= "<div id='workflow-list-$list_index' class='workflow-list $can_edit_class'>";
	$content .= elgg_view_entity($lists[$list_index-1], array('full_view' => 'group'));
	$content .= '</div>';
}
$content .= "</div>";

if (!$lists) {
	$content = '<p>' . elgg_echo('workflow:list:none') . '</p>';
}
/*
$filter_context = '';
if (elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$sidebar = elgg_view('tasks/sidebar/navigation');
$sidebar .= elgg_view('tasks/sidebar');
*/
$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
);
/*
if (elgg_instanceof($owner, 'group')) {
	$params['filter'] = '';
}
*/
$body = elgg_view_layout('workflow', $params);

echo elgg_view_page($title, $body);
