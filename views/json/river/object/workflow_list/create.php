<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow new list river entity
 *
 */
global $jsonexport;

$short = elgg_extract('short', $vars, false);

$subject = $vars['item']->getSubjectEntity();
$object = $vars['item']->getObjectEntity();
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
	$board_string = $group_string = '';
	
	if ($board && $short === 'group') {
		$board_link = elgg_view('output/url', array(
			'href' => $board->getURL(),
			'text' => $board->title ? $board->title : $board->name,
			'class' => 'elgg-river-object',
			'is_trusted' => true,
		));
		$board_string = elgg_echo('river:inboard', array($board_link));
	}
} else if ($board) {
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

$summary = elgg_echo('river:create:object:workflow_list', array($subject_link, $object_link, $board_string, $group_string));

$vars['item']->summary = $summary;

$vars['item']->message = '';

$jsonexport['activity'][] = $vars['item'];
