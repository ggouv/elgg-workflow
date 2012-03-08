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

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$container_guid = get_input('container_guid', elgg_get_page_owner_guid());
$list_title = get_input('list_title', 'List');

$container = get_entity($container_guid);

if ($container->canEdit()) {

	$list = new ElggObject;
	$list->subtype = "workflow-list";
	$list->container_guid = $container_guid;
	$list->title = $list_title;

	if ($list->save()) {
		system_message(elgg_echo('workflow:list:add:success'));
		add_to_river('river/object/workflow-list/create','create', $user_guid, $list->getGUID());

		echo elgg_view_entity($list, array('view_type' => 'group'));
	} else {
		register_error(elgg_echo('workflow:list:add:failure'));
	}

} else {
	register_error(elgg_echo('workflow:list:add:cannotadd'));
}

