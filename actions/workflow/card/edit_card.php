<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow card edit card action
 *
 */

$card_guid = elgg_extract('guid', $vars, get_input('entity_guid'));
$title = elgg_extract('title', $vars, get_input('title'));
$desc = elgg_extract('description', $vars, get_input('description'));
$assignedto =  elgg_extract('members', $vars, get_input('members'));
$duedate = elgg_extract('duedate', $vars, get_input('duedate'));
$tags = elgg_extract('tags', $vars, get_input('tags'));
$access_id = elgg_extract('access_id', $vars, get_input('access_id', ACCESS_DEFAULT));

$user = elgg_get_logged_in_user_guid();

// start a new sticky form session in case of failure
elgg_make_sticky_form('card');

if (!$card_guid) {
	register_error(elgg_echo('workflow:card:edit:error'));
	forward(REFERER);
}

$card = get_entity($card_guid);

if ($card->canEdit()) {
	$card->title = $title;
	$card->description = $desc;
	$card->assignedto = $assignedto;
	$card->duedate = $duedate;
	$card->tags = $tags;
	$card->access_id = $access_id;

	if ($card->save()) {
		elgg_clear_sticky_form('card');
		system_message(elgg_echo('workflow:card:edit:success'));
		echo json_encode(array(
			'card' => elgg_view_entity($card, array('view_type' => 'group')),
			'sidebar' => elgg_view('workflow/sidebar', array('container_guid' => $card->container_guid)),
		));
	} else {
		register_error(elgg_echo('workflow:card:edit:failure'));
	}

} else {
	register_error(elgg_echo('workflow:card:edit:error'));
}
