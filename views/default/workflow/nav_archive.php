<?php
/**
 * Workflow navigation archive
 */

$tabs = array(
	'workflow_list' => array(
		'title' => elgg_echo('item:object:workflow_list'),
		'url' => "?subtype=workflow_list",
		'selected' => $vars['selected'] == 'workflow_list',
	),
	'workflow_card' => array(
		'title' => elgg_echo('item:object:workflow_card'),
		'url' => "?subtype=workflow_card",
		'selected' => $vars['selected'] == 'workflow_card',
	)
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
