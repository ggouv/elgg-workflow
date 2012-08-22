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
$size = elgg_extract('size', $vars, 'small');
$short = elgg_extract('short', $vars, false);

$subject = $vars['item']->getSubjectEntity();
$object = $vars['item']->getObjectEntity();
$list = get_entity($object->list_guid);
$board = get_entity($object->board_guid);
$container = $object->getContainerEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$object_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $object->title ? $object->title : $object->name,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

if ($short) {
	$list_string = $board_string = $group_string = '';
	
	if ($short === 'group') {
		$board_link = elgg_view('output/url', array(
			'href' => $board->getURL(),
			'text' => $board->title ? $board->title : $board->name,
			'class' => 'elgg-river-object',
			'is_trusted' => true,
		));
		$board_string = elgg_echo('river:inboard', array($board_link));
	}
} else {
	$list_link = elgg_view('output/url', array(
		'href' => $list->getURL(),
		'text' => $list->title ? $list->title : $list->name,
		'class' => 'elgg-river-object',
		'is_trusted' => true,
	));
	$list_string = elgg_echo('river:inlist', array($list_link));
	
	$board_link = elgg_view('output/url', array(
		'href' => $board->getURL(),
		'text' => $board->title ? $board->title : $board->name,
		'class' => 'elgg-river-object',
		'is_trusted' => true,
	));
	$board_string = elgg_echo('river:inboard', array($board_link));

	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	));
	$group_string = elgg_echo('river:ingroup', array($group_link));
}

$summary = elgg_echo('river:create:object:workflow_card', array($subject_link, $object_link, $list_string, $board_string, $group_string));

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'summary' => $summary,
	'message' => '',
	'responses' => ' ',
	'size' => $size
));