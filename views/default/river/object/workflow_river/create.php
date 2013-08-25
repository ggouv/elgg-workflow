<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow new card or list river entity
 *
 */
$short = elgg_extract('short', $vars, false);

$subject = $vars['item']->getSubjectEntity();
$entity = $vars['item']->getObjectEntity();
$board = get_entity($entity->board_guid);
$container = $entity->getContainerEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$echo = 'river:create:object:workflow_card_list:summary';

if ($short) {
	$board_link = $group_string = '';

	if ($board && $short === 'group') {
		$board_link = elgg_view('output/url', array(
			'href' => $board->getURL(),
			'text' => $board->title ? $board->title : $board->name,
			'class' => 'elgg-river-object',
			'is_trusted' => true,
		));
	} else {
		$echo = '%s&nbsp;';
	}
} else if ($board) {
	$board_link = elgg_view('output/url', array(
		'href' => $board->getURL(),
		'text' => $board->title ? $board->title : $board->name,
		'class' => 'elgg-river-object',
		'is_trusted' => true,
	));

	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	));
	$group_string = elgg_echo('river:ingroup', array($group_link));
}

$summary = elgg_echo($echo, array($subject_link, $board_link, $group_string));

elgg_load_library('workflow:utilities');
$message = workflow_read_annotation($vars['item']->annotation_id);

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'summary' => $summary,
	'message' => $message,
));