<?php
/**
 * workflow_list controls
 *
 * @uses $vars['workflow_list']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */

echo elgg_view_menu('workflow_list', array(
	'entity' => elgg_extract('workflow_list', $vars),
	'handler' => 'workflow',
	'params' => array(
		'show_edit' => elgg_extract('show_edit', $vars, true)
	),
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));
