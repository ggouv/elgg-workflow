<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow new card river entity
 *
 */

$object = $vars['item']->getObjectEntity();
$excerpt = elgg_get_excerpt($object->description, '100');

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $excerpt
));
