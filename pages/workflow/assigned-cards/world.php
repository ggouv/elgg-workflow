<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow world assigned-cards view
 *
 */

elgg_push_breadcrumb(elgg_echo('workflow:assigned-cards:all'));

$title = elgg_echo('workflow:assigned-cards:all');

$cards = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'workflow_card',
	'relationship' => 'assignedto',
	'inverse_relationship' => true,
	'limit' => 30,
));

$all_assignedto = array();
$all_assignedto_guid = array();
if ($cards) {
	foreach ($cards as $card) {
		if ( !in_array($card->guid, $all_assignedto_guid) ) {
			$all_assignedto[] = $card;
			$all_assignedto_guid[] = $card->guid;
		}
	}
}

$content = elgg_view_entity_list($all_assignedto, array(
	'view_type' => 'group',
	'split_items' => 3,
	'list_class' => 'workflow-card-list',
	'limit' => 30,
	'pagination' => true
));

if (!$content) {
	$content = elgg_echo('workflow:card:none');
}

$sidebar .= elgg_view('workflow/sidebar');

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
	'filter_override' => elgg_view('workflow/nav', array('selected' => 'assigned-cards/all')),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
