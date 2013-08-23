<?php
/**
 * Workflow navigation archive
 */

$tabs = array(
	'workflow_list_archived' => array(
		'title' => elgg_echo('item:object:workflow_list'),
		'url' => "?subtype=workflow_list_archived",
		'selected' => $vars['selected'] == 'workflow_list_archived',
	),
	'workflow_card_archived' => array(
		'title' => elgg_echo('item:object:workflow_card'),
		'url' => "?subtype=workflow_card_archived",
		'selected' => $vars['selected'] == 'workflow_card_archived',
	)
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
