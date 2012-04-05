<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow card delete action
 *
 */

$deleted_card_guid = get_input('card_guid');
$container_guid = get_input('container_guid', elgg_get_page_owner_guid());

$deleted_list = get_entity($deleted_list_guid);
$container = get_entity($container_guid);

if (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $deleted_list->getOwnerGuid()) {
	delete_entity($deleted_list_guid);

	$lists = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => 'workflow_list',
		'container_guid' => $moved_list->container_guid,
	));

	$sorted_lists = array();
	foreach ($lists as $list) {
		$sorted_lists[$list->order] = $list;
	}
	ksort($sorted_lists);

	// redefine order for each list
	$order = 0;
	foreach ($sorted_lists as $list) {
		$list->order = $order;
		$order += 1;
	}

	system_message(elgg_echo('workflow:list:delete:success'));
	forward(REFERER);
}

register_error(elgg_echo('workflow:list:delete:failure'));
forward(REFERER);
