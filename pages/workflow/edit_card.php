<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow edit card page
 *
 */
 
$card_guid = get_input('guid');
$card = get_entity($card_guid);

if (!elgg_instanceof($card, 'object', 'workflow_card') || !$card->canEdit()) {
	register_error(elgg_echo('workflow:unknown_card'));
	forward(REFERRER);
}

$title = elgg_echo('workflow:card:edit');
elgg_push_breadcrumb($title);

$vars = workflow_card_prepare_form_vars($card);

$content = elgg_view_form('workflow/card/edit_card', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
