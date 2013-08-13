<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow add card form from list
 *
 */

$workflow_list = elgg_extract('workflow_list', $vars);

echo elgg_view('input/hidden', array(
	'name' => 'workflow_list',
	'value' => $workflow_list->guid,
));

echo elgg_view('input/plaintext', array(
	'name' => 'title',
	'value' => elgg_echo('workflow:list:add_card'),
	'class' => 'mbs',
));

echo '<div class="hidden">';

echo elgg_view('input/submit', array(
	'value' => elgg_echo('workflow:list:add_card'),
	'class' => 'elgg-button-submit workflow-card-submit float',
));
echo elgg_view_icon('delete', 'float');

echo '</div>';
