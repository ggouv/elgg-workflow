<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow list add action
 *
 */

$container_guid = get_input('container_guid', elgg_get_page_owner_guid());
$user_guid = get_input('owner_guid', elgg_get_logged_in_user_guid());
$list_title = get_input('list_title', 'List');

$container = get_entity($container_guid);
global $fb; $fb->info($list_title);
/*
if ($container->canEdit()) {
	$list = new ElggObject;
	$list->subtype = "tasklist";
	$list->container_guid = $container_guid;
	$list->title = $list_title;

	if ($list->save()) {
		echo 'ee';
	} else {
		register_error(elgg_echo('workflow:list:add:failure'));
	}
} else {
	register_error(elgg_echo('workflow:list:add:cannotadd'));
}
*/
