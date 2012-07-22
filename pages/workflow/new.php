<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow new board
 *
 */

gatekeeper();

$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('workflow:board:add');
elgg_push_breadcrumb($title);

$vars = workflow_board_prepare_form_vars();
$content = elgg_view_form('workflow/board/edit_board', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
