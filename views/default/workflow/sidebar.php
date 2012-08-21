<?php
/**
 * Brainstorm group sidebar
 */
$board_guid = elgg_extract('board_guid', $vars, elgg_get_page_owner_guid());

// get all cards of the board
$cards = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtypes' => 'workflow_card',
	'metadata_name' => 'board_guid',
	'metadata_value' => $board_guid,
	'limit' => 0
));


// get all users assignedto
$all_assignedto = array();
foreach($cards as $card) {
	$assignedto = $card->assignedto;
	if ($assignedto) {
		if ( is_array($assignedto) ) {
			foreach ($assignedto as $user) {
				if ( !in_array($user, $all_assignedto) ) $all_assignedto[] = $user;
			}
		} else {
			if ( !in_array($assignedto, $all_assignedto) ) $all_assignedto[] = $assignedto;
		}
	}
}
$content = '';
foreach ($all_assignedto as $user_guid) {
	$user = get_entity($user_guid);
	$content .= elgg_view_entity_icon($user, 'small');
}

$title = elgg_echo('workflow:sidebar:assignedto_user');

if ($content) {
	 echo elgg_view_module('aside', $title, $content);
} else {
	 echo elgg_view_module('aside', '', '');
}
