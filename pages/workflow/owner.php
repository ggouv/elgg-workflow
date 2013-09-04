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
		'order_by' => 'e.last_action desc',
		'limit' => 0
	));

	if (!$content) {
		$content = elgg_echo('workflow:board:none');
	}

	$sidebar = elgg_view('workflow/sidebar');

	if (elgg_view_exists('page/layouts/content_two_right_sidebars')) {
		$body = elgg_view_layout('content_two_right_sidebars', array(
			'content' => $content,
			'title' => $title,
			'sidebar_2' => $sidebar,
			'class' => 'sidebar-2-fixed'
		));
	} else {
		$body = elgg_view_layout('content', array(
			'content' => $content,
			'title' => $title,
			'sidebar' => $sidebar,
		));
	}

	echo elgg_view_page($title, $body);

} else {

	$board = workflow_get_user_board($owner->getGUID());

	if ($board) {
		gatekeeper();
		forward($board->getURL());
	} else {
		forward(REFERER);
	}
}
