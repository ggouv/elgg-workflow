<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow board object view
 *
 */

$full = elgg_extract('full_view', $vars, FALSE);
$board = elgg_extract('entity', $vars, FALSE);

if (!$board) {
	return;
}

$owner = $board->getOwnerEntity();
$container = $board->getContainerEntity();
$categories = elgg_view('output/categories', $vars);

$description = elgg_view('output/longtext', array('value' => $board->description, 'class' => 'pbs'));

$owner_link = elgg_view('output/url', array(
	'href' => "board/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('workflow:board:created_by', array($owner_link));

$date = elgg_view_friendly_time($board->time_created);

$comments_count = $board->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $board->getURL() . '#comments',
		'text' => $text,
		'is_trusted' => true,
	));
} else {
	$comments_link = '';
}

$lists = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtypes' => 'workflow_list',
	'metadata_name' => 'board_guid',
	'metadata_value' => $board->guid,
	'limit' => 0
));

$cards_count = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtypes' => 'workflow_card',
	'metadata_name' => 'board_guid',
	'metadata_value' => $board->guid,
	'limit' => 0,
	'count' => true
));
$board_info = elgg_echo('workflow:board:info', array(count($lists), $cards_count));

// get participants
$all_assignedto = workflow_get_board_participants($board->guid);
if ($all_assignedto) {
	$participants = '<p class="mbs">' . elgg_echo('workflow:sidebar:assignedto_user') . '</p>';
	foreach ($all_assignedto as $user) {
		$participants .= elgg_view_entity_icon($user, 'small');
	}
} else {
	$participants = '<p class="mbs">' . elgg_echo('workflow:sidebar:assignedto_user:none') . '</p>';
}

// last action
$annotation = $board->getAnnotations('workflow_river', 1, 0, 'desc');
if ( $annotation && $item = elgg_get_river(array('annotation_id' => $annotation[0]['id'])) ) { // annotation could be deleted
	$last_action_string = '<p class="mbs mtm">' . elgg_echo('workflow:board:last_action') . '</p>';
	$last_action_string .= "<ul><li id='item-river-{$item[0]->id}' class='elgg-list-item' datetime='{$item[0]->posted}'>" .
				elgg_view('river/item', array('item' => $item[0], 'size' => 'tiny', 'short' => true)) . '</li></ul>';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'workflow',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {

	$params = array(
		'entity' => $board,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$body = <<<HTML
<div class="board row-fluid">
	<div class="span6">$summary $description</div>
	<div class="elgg-heading-basic pam span6">
		<p>$board_info</p>
		$participants
		$last_action_string
	</div>
</div>
HTML;

	echo elgg_view('object/elements/full', array(
		'entity' => $board,
		'summary' => '',
		'body' => $body,
	));

} elseif (elgg_in_context('gallery')) {
	echo <<<HTML
<div class="bookmarks-gallery-item">
	<h3>$bookmark->title</h3>
	<p class='subtitle'>$owner_link $date</p>
</div>
HTML;
} else {
	// brief view
	$excerpt = elgg_get_excerpt($board->description);
	if ($excerpt) {
		$excerpt = " - $excerpt";
	}

	if (strlen($url) > 25) {
		$bits = parse_url($url);
		if (isset($bits['host'])) {
			$display_text = $bits['host'];
		} else {
			$display_text = elgg_get_excerpt($url, 100);
		}
	}

	$link = elgg_view('output/url', array(
		'href' => $board->address,
		'text' => $display_text,
	));

	$content = elgg_view_icon('push-pin-alt') . "$link{$excerpt}";

	$params = array(
		'entity' => $board,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $content,
	);
	$params = $params + $vars;
	$body = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($owner_icon, $body);
}
