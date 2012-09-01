<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow edit board
 *
 */

gatekeeper();

$board_guid = (int)get_input('guid');
$board = get_entity($board_guid);

if (!$board) {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}

$container = $board->getContainerEntity();
if (!$container->canWritetoContainer()) {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}

elgg_push_breadcrumb($board->title, $board->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("workflow:board:edit");

$vars = workflow_board_prepare_form_vars($board);
$content = elgg_view_form('workflow/board/edit_board', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
