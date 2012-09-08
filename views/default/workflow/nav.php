<?php
/**
 * Workflow navigation navigation
 */
$user = elgg_get_logged_in_user_entity();

$tabs = array(
	'all' => array(
		'title' => elgg_echo('workflow:board:all'),
		'url' => "workflow/all",
		'selected' => $vars['selected'] == 'all',
	),
	'assigned-cards-all' => array(
		'title' => elgg_echo('workflow:assigned-cards:all'),
		'url' => "workflow/assigned-cards/all",
		'selected' => $vars['selected'] == 'assigned-cards/all',
	),
	'assigned-cards-owner' => array(
		'title' => elgg_echo('workflow:assigned-cards:owner'),
		'url' => "workflow/assigned-cards/owner/" . $user->name,
		'selected' => $vars['selected'] == 'assigned-cards/owner',
	),
	'assigned-cards-friends' => array(
		'title' => elgg_echo('workflow:assigned-cards:friends'),
		'url' => "workflow/assigned-cards/friends/" . $user->name,
		'selected' => $vars['selected'] == 'assigned-cards/friends',
	)
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
