<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 **/

elgg_register_event_handler('init', 'system', 'workflow_init');

/**
 * Initialize elgg-workflow plugin.
 */
function workflow_init() {

	// register a library of helper functions
	$root = dirname(__FILE__);
	elgg_register_library('workflow:utilities', "$root/lib/utilities.php");
	elgg_register_js('jquery.scrollTo',"/mod/elgg-workflow/views/default/workflow/jquery.scrollTo-min.js");
	elgg_load_js('jquery.scrollTo');

	elgg_register_ajax_view('workflow/edit_card_popup');
	elgg_register_ajax_view('workflow/view_card_popup');

	// register global menu
	$item = new ElggMenuItem('workflow', elgg_echo('workflow'), 'workflow/all');
	elgg_register_menu_item('site', $item);
	$item = new ElggMenuItem('my_workflow', elgg_echo('my_workflow'), 'workflow/owner');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('workflow', 'workflow_page_handler');

	// Register URL handler
	elgg_register_entity_url_handler('object', 'workflow_board', 'workflow_board_url_handler');
	elgg_register_entity_url_handler('object', 'workflow_list', 'workflow_list_url_handler');
	elgg_register_entity_url_handler('object', 'workflow_card', 'workflow_card_url_handler');

	// Extend view
	elgg_extend_view('css/elgg', 'workflow/css');
	elgg_extend_view('js/elgg', 'workflow/js');

	// add to groups
	add_group_tool_option('workflow', elgg_echo('groups:enableworkflow'), true);
	//elgg_extend_view('groups/tool_latest', 'workflow/group_module');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'workflow_owner_block_menu');

	// actions for board
	$action_base = "$root/actions/workflow/board";
	elgg_register_action('workflow/board/edit_board', "$action_base/edit_board.php");
	elgg_register_action('workflow/delete', "$action_base/delete.php");
	// actions for list
	$action_base = "$root/actions/workflow/list";
	elgg_register_action('workflow/list/move', "$action_base/move.php");
	elgg_register_action('workflow/list/add_list_popup', "$action_base/add.php");
	elgg_register_action('workflow/list/delete', "$action_base/delete.php");
	// actions for card
	$action_base = "$root/actions/workflow/card";
	elgg_register_action('workflow/list/add_card', "$action_base/add.php");
	elgg_register_action('workflow/card/move', "$action_base/move.php");
	elgg_register_action('workflow/card/delete', "$action_base/delete.php");
	elgg_register_action('workflow/card/edit_card', "$action_base/edit_card.php");

	// Register entity type
	elgg_register_entity_type('object', 'workflow_board');
	elgg_register_entity_type('object', 'workflow_list');
	elgg_register_entity_type('object', 'workflow_card');

	// Register entity menu
	elgg_register_plugin_hook_handler('register', 'menu:workflow_list', 'workflow_list_entity_menu_setup');

	// Register hook for elgg-deck_river
	elgg_register_plugin_hook_handler('deck-river', 'column:workflow', 'workflow_deck_river_column');

/*

	// Register widget
	elgg_register_widget_type('brainstorm', elgg_echo('brainstorm:widget:title'), elgg_echo('brainstorm:widget:description'));

	// Register granular notification for this type
	register_notification_object('object', 'brainstorm', elgg_echo('brainstorm:new'));

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'brainstorm_notify_message');


*/
}

/**
 * Dispatcher for elgg-workflow plugin.
 * URLs take the form of :
 *  All boards:        workflow/all
 *  User's board:      workflow/owner/<username> (user board are private. No friend's board view)
 *  Group boards:      workflow/group/<guid>
 *
 *  Add board:         workflow/add/<guid>
 *
 *  Board view:        workflow/board/<guid>/<title> (title is ignored)
 *
 * card and list are viewed in board (simple object view doesn't make sense)
 *  View owner's card:            workflow/board/<guid>/card/<guid>/<title> (title is ignored)
 *  View owner's list:            workflow/board/<guid>/list/<guid>/<title> (title is ignored)
 *
 *  Cards assigned to all:        workflow/assigned-cards/all
 *  Cards assigned to user:       workflow/assigned-cards/owner/<username>
 *  Cards assigned to friends:    workflow/assigned-cards/friends/<username>
 *
 * @param array $page
 */
function workflow_page_handler($page) {

	elgg_load_library('workflow:utilities');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('workflow'), 'workflow/all');

	$base_dir = dirname(__FILE__) . '/pages/workflow';

	switch ($page[0]) {
		default:
		case 'all':
			include "$base_dir/world.php";
			break;
		case 'owner':
			include "$base_dir/owner.php";
			break;
		case 'group':
			group_gatekeeper();
			include "$base_dir/owner.php";
			break;
		case 'add':
			include "$base_dir/new.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$base_dir/edit.php";
			break;
		case 'board':
			set_input('board_guid', $page[1]);
			include "$base_dir/board.php";
			break;
		case 'assigned-cards':
			switch ($page[1]) {
				default:
				case 'all':
					include "$base_dir/assigned-cards/world.php";
					break;
				case 'owner':
					include "$base_dir/assigned-cards/owner.php";
					break;
				case 'friends':
					include "$base_dir/assigned-cards/owner.php";
					break;
			}
			break;
	}

	elgg_pop_context();

	return true;
}


/**
 * Override the workflow board url
 * 
 * @param ElggObject $entity workflow_board
 * @return string
 */
function workflow_board_url_handler($entity) {
	$title = elgg_get_friendly_title($entity->title);
	$container = get_entity($entity->container_guid);
	if (elgg_instanceof($container, 'user')) {
		return "workflow/board/$entity->guid/$title";
	} elseif (elgg_instanceof($container, 'group')) {
		return "workflow/board/$entity->guid/$title";
	}
}


/**
 * Override the workflow list url
 * 
 * @param ElggObject $entity workflow_list
 * @return string
 */
function workflow_list_url_handler($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "workflow/board/{$entity->board_guid}/list/{$entity->guid}/$title";
}


/**
 * Override the workflow card url
 * 
 * @param ElggObject $entity workflow_card
 * @return string
 */
function workflow_card_url_handler($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "workflow/board/{$entity->board_guid}/card/{$entity->guid}/$title";
}


/**
 * Add a menu item to the user ownerblock
 */
function workflow_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "workflow/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('workflow', elgg_echo('workflow'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->workflow_enable != "no") {
			$url = "workflow/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('workflow', elgg_echo('workflow:group'), $url);
			if (elgg_in_context('workflow')) $item->setSelected();
			$return[] = $item;
		}
	}

	return $return;
}


/**
 * Add links/info to list entity menu particular to workflow plugin
 */
function workflow_list_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'workflow') {
		return $return;
	}

	$workflow_list = $params['entity'];
	$show_edit = elgg_extract('show_edit', $params, true);

	if ($workflow_list->canWritetoContainer()) {
		$delete = array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete-alt'),
			'title' => elgg_echo('workflow:list:delete'),
			'href' => "action/workflow/list/delete?list_guid={$workflow_list->guid}",
			'is_action' => true,
			'class' => 'workflow-list-delete-button',
			'id' => "workflow-list-delete-button-{$workflow_list->guid}",
			'priority' => 900
		);
		$return[] = ElggMenuItem::factory($delete);

		if ($show_edit) {
			$edit = array(
				'name' => 'settings',
				'text' => elgg_view_icon('settings-alt'),
				'title' => elgg_echo('workflow:list:edit'),
				'href' => "#workflow-list-edit-{$workflow_list->guid}",
				'class' => "workflow-list-edit-button",
				'rel' => 'toggle',
				'priority' => 800,
			);
			$return[] = ElggMenuItem::factory($edit);
		}
	}

	return $return;
}


/**
 * Hooks for elgg-deck_river
 */
function workflow_deck_river_column($hook, $type, $return, $params) {
	if ($params['query'] == 'activity') {
		$assigned_cards = elgg_list_entities_from_relationship(array(
				'type' => 'object',
				'subtype' => 'workflow_card',
				'relationship' => 'assignedto',
				'relationship_guid' => $params['owner'],
				'inverse_relationship' => true,
				'view_type' => 'group',
				'list_class' => 'river-workflow',
				'limit' => 0,
				'pagination' => false
			));
		return $assigned_cards;
	} else if ($params['query'] == 'title') {
		return array(
			'column_title' => elgg_echo('river:workflow'),
			'column_subtitle' => get_entity($params['owner'])->name,
			'break' => true
		);
	} else {
		return false;
	}
}