<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow view for workflow_list object
 *
 */

$workflow_list = elgg_extract('entity', $vars, FALSE);

if (!$workflow_list) {
	return TRUE;
}

$view_type = elgg_extract('view_type', $vars, FALSE);
$container = $workflow_list->getContainerEntity();
$user = elgg_get_logged_in_user_entity();

if ($view_type == 'group') {

	$workflow_list_id = "workflow-list-$workflow_list->guid";
	$workflow_list_class = " workflow-list mls";

	$edit_area = '';
	$can_edit = is_group_member($container->guid, $user->guid);
	if ($can_edit) {
		$controls = elgg_view('object/workflow_list/elements/controls', array(
			'workflow_list' => $workflow_list,
			'show_edit' => $edit_area != '',
			));
		$edit_area = elgg_view('object/workflow_list/elements/settings', array(
			'workflow_list' => $workflow_list,
		));
		$workflow_list_footer = elgg_view('object/workflow_list/elements/footer', array(
			'workflow_list' => $workflow_list,
		));

		$workflow_list_class .= " elgg-state-draggable";
	} else {
		$workflow_list_class .= " elgg-state-fixed";
	}

	// get cards of this list
	$cards = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'container_guid' => $workflow_list->guid,
	));

	$sorted_cards = array();
	foreach ($cards as $card) {
		$sorted_cards[$card->order] = $card;
	}
	ksort($sorted_cards);

	$num_cards = count($cards);

	$content = "<div class='workflow-cards'>";
	for ($card_index = 1; $card_index <= $num_cards; $card_index++) {
		$cardguid = $sorted_cards[$card_index-1]->guid;
		$content .= elgg_view_entity($sorted_cards[$card_index-1], array('view_type' => 'group'));
	}
	$content .= '<div class="workflow-card-none elgg-module workflow-card elgg-state-draggable"><div class="elgg-body"></div></div>'; // hack for empty list and sortable jquery.ui
	$content .= "</div>";

	$title = elgg_view_icon('workflow-list') . $workflow_list->title;

$workflow_list_header = <<<HEADER
	<div class="workflow-list-handle clearfix"><h3>$title</h3>
	$controls
	</div>
HEADER;

$workflow_list_body = <<<BODY
	$edit_area
	<div class="workflow-list-content" id="workflow-list-content-$workflow_list->guid">
		$content
	</div>
BODY;

	echo elgg_view('page/components/module', array(
		'class' => $workflow_list_class,
		'id' => $workflow_list_id,
		'body' => $workflow_list_body,
		'header' => $workflow_list_header,
		'footer' => $workflow_list_footer,
	));
}
/*
$icon = elgg_view('icon/default', array('entity' => $tasklist, 'size' => 'small'));

$owner = get_entity($tasklist->owner_guid);
$owner_link = elgg_view('output/url', array(
	'href' => "tasks/owner/$owner->username",
	'text' => $owner->name,
));

$date = elgg_view_friendly_time($tasklist->time_created);
$strapline = elgg_echo("tasks:lists:strapline", array($date, $owner_link));
$tags = elgg_view('output/tags', array('tags' => $tasklist->tags));

$comments_count = $tasklist->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $tasklist->getURL() . '#tasklist-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'tasks',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$strapline $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}
global $fb; $fb->info($full);
if ($full) {/*
	$body = elgg_view('output/longtext', array('value' => $tasklist->description));

	$params = array(
		'entity' => $tasklist,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
	
	$list_body .= elgg_view('tasks/tasklist_graph', array(
		'entity' => $tasklist,
	));

	$info = elgg_view_image_block($icon, $list_body);
	
	
	$assigned_tasks = elgg_list_entities_from_metadata(array(
		'container_guid' => $tasklist->guid,
		'metadata_name' => 'status',
		'metadata_values' => array('assigned', 'active'),
		'full_view' => false,
		'offset' => (int) get_input('assigned_offset'),
		'offset_key' => 'assigned_offset',
	));
	if($assigned_tasks) {
		$assigned_tasks = elgg_view_module('info', elgg_echo('tasks:assigned'), $assigned_tasks);
	}
	
	$unassigned_tasks = elgg_list_entities_from_metadata(array(
		'container_guid' => $tasklist->guid,
		'metadata_name' => 'status',
		'metadata_values' => array('new', 'unassigned', 'reopened'),
		'full_view' => false,
		'offset' => (int) get_input('unassigned_offset'),
		'offset_key' => 'unassigned_offset',
	));
	if($unassigned_tasks) {
		$unassigned_tasks = elgg_view_module('info', elgg_echo('tasks:unassigned'), $unassigned_tasks);
	}
	
	$closed_tasks = elgg_list_entities_from_metadata(array(
		'container_guid' => $tasklist->guid,
		'metadata_name' => 'status',
		'metadata_values' => array('done', 'closed'),
		'full_view' => false,
		'offset' => (int) get_input('closed_offset'),
		'offset_key' => 'closed_offset',
	));
	if($closed_tasks) {
		$closed_tasks = elgg_view_module('info', elgg_echo('tasks:closed'),	$closed_tasks);
	}
		

	echo <<<HTML
$info
$body
<div class="mtl">
$assigned_tasks
$unassigned_tasks
$closed_tasks
</div>
HTML;
*

	$container = $tasklist->getContainerEntity();
	$user = elgg_get_logged_in_user_entity();

	$tasklist_id = "tasklist-$tasklist->guid";
	$tasklist_class = " tasklist";

	$edit_area = '';
	$can_edit = is_group_member($container->guid, $user->guid);
	if ($can_edit) {
		$controls = elgg_view('object/tasklist/elements/controls', array(
			'tasklist' => $tasklist,
			'show_edit' => $edit_area != '',
			));
		$edit_area = elgg_view('object/tasklist/elements/settings', array(
			'tasklist' => $tasklist,
			'show_access' => $show_access,
		));

		$tasklist_class .= " elgg-state-draggable";
	} else {
		$tasklist_class .= " elgg-state-fixed";
	}

	$title = elgg_view_icon('list') . $tasklist->title;

$tasklist_header = <<<HEADER
	<div class="tasklist-handle clearfix"><h3>$title</h3>
	$controls
	</div>
HEADER;

	$content = elgg_list_entities(array(
		'container_guid' => $tasklist->guid,
		'full_view' => false,
	));

$tasklist_body = <<<BODY
	$edit_area
	<div class="elgg-tasklist-content" id="elgg-tasklist-content-$tasklist->guid">
		$content
	</div>
BODY;

	echo elgg_view('page/components/module', array(
		'class' => $tasklist_class,
		'id' => $tasklist_id,
		'body' => $tasklist_body,
		'header' => $tasklist_header,
	));

} else {
	// brief view

	$content = elgg_view('tasks/tasklist_graph', array(
	'entity' => $tasklist,
	));

	$params = array(
	'entity' => $tasklist,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'tags' => false,
	'content' => $content,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($icon, $list_body);

}*/
