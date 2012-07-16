<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow board delete action
 *
 */

$deleted_list_guid = get_input('list_guid');
$container_guid = get_input('container_guid', elgg_get_page_owner_guid());

$deleted_list = get_entity($deleted_list_guid);
$container = get_entity($container_guid);

if (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $deleted_list->getOwnerGuid()) {

	// delete cards of this list
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'parent_guid',
		'metadata_value' => $deleted_list_guid,
		'limit' => 0
	));
	foreach($cards as $card) {
		delete_entity($card->guid);
	}
	// delete list
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
	echo json_encode(array(
		'sidebar' => elgg_view('workflow/sidebar', array('container_guid' => $deleted_list->container_guid)),
	));
	forward(REFERER);
}

register_error(elgg_echo('workflow:list:delete:failure'));
forward(REFERER);
