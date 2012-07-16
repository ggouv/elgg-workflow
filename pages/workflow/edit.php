<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow add/edit board
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
if (!$container) {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}

elgg_push_breadcrumb($board->title, $board->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("board:edit");

if ($board->canEdit()) {
	$vars = board_prepare_form_vars($board);
	$content = elgg_view_form('pages/edit', array(), $vars);
} else {
	$content = elgg_echo("pages:noaccess");
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
