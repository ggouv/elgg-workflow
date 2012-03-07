<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow admin settings
 *
 */

// set default value

if (!isset($vars['entity']->min_width_list)) {
	$vars['entity']->min_width_list = '200';
}

$min_width_list_string = elgg_echo('workflow:settings:min_list_column');
$min_width_list_view = elgg_view('input/text', array(
	'name' => 'params[min_width_list]',
	'value' => $vars['entity']->min_width_list,
	'class' => 'elgg-input-thin',
));

// display html

echo <<<__HTML
<br />
<div><label>$min_width_list_string</label><br />$min_width_list_view</div>
__HTML;
