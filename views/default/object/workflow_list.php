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
	if ($workflow_list->canWritetoContainer()) {
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
	$cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => 'workflow_card',
		'metadata_name' => 'list_guid',
		'metadata_value' => $workflow_list->guid,
		'limit' => 0
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
