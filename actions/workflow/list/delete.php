<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow list delete action
 *
 */

$list_guid = get_input('list_guid');
$container_guid = get_input('container_guid', elgg_get_page_owner_guid());

$list = get_entity($list_guid);
$container = get_entity($container_guid);

if (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $list->getOwnerGuid()) {
	delete_entity($list_guid);
	system_message(elgg_echo('workflow:list:delete:success'));
	forward(REFERER);
}

register_error(elgg_echo('workflow:list:delete:failure'));
forward(REFERER);
