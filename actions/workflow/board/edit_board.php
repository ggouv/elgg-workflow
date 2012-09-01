<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow board edit/add action
 *
 */

$title = strip_tags(get_input('title'));
$description = get_input('description');
$access_id = (int) get_input('access_id');
$tags = get_input('tags');
$guid = (int) get_input('guid');
$container_guid = (int) get_input('container_guid', elgg_get_page_owner_guid());

elgg_make_sticky_form('board');

if (!$title || !$container_guid) {
	register_error(elgg_echo('workflow:board:save:failed'));
	forward(REFERER);
}

$container = get_entity($container_guid);

if ($container && !$container->canWritetoContainer()) {
	register_error(elgg_echo('workflow:board:save:failed'));
	forward(REFERER);
}

if ($guid == 0) {
	$board = new ElggObject;
	$board->subtype = "workflow_board";
	$board->container_guid = $container_guid;
	$new = true;
} else {
	$board = get_entity($guid);
	if (!$board->canWritetoContainer()) {
		system_message(elgg_echo('workflow:board:save:failed'));
		forward(REFERRER);
	}
}

$board->title = $title;
$board->description = $description;
$board->access_id = $access_id;
$board->tags = string_to_tag_array($tags);

if ($board->save()) {

	elgg_clear_sticky_form('board');

	system_message(elgg_echo('workflow:board:add:success'));

	//add to river only if new
	if ($new) {
		add_to_river('river/object/workflow_board/create','create', elgg_get_logged_in_user_guid(), $board->getGUID());
	}
	
	forward($board->getURL());

} else {
	register_error(elgg_echo('workflow:board:add:failure'));
}
