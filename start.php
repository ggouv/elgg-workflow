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

	// register global menu
	$item = new ElggMenuItem('workflow', elgg_echo('workflow'), 'workflow/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('workflow', 'workflow_page_handler');

	// Register URL handler
	elgg_register_entity_url_handler('object', 'todolist', 'todolist_url');

	// Extend view
	elgg_extend_view('css/elgg', 'workflow/css');
	elgg_extend_view('js/elgg', 'workflow/js');

	// add to groups
	add_group_tool_option('workflow', elgg_echo('groups:enableworkflow'), true);
	//elgg_extend_view('groups/tool_latest', 'workflow/group_module');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'workflow_owner_block_menu');

	// actions for list
	$action_base = "$root/actions/workflow/list";
	elgg_register_action('workflow/list/move', "$action_base/move.php");
/*
	elgg_register_action('brainstorm/editidea', "$action_base/editidea.php");
	elgg_register_action("brainstorm/rateidea", "$action_base/rateidea.php");
	elgg_register_action('brainstorm/delete', "$action_base/deleteidea.php");

	elgg_register_plugin_hook_handler('register', 'menu:page', 'brainstorm_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'brainstorm_owner_block_menu');
	

	
	// Register widget
	elgg_register_widget_type('brainstorm', elgg_echo('brainstorm:widget:title'), elgg_echo('brainstorm:widget:description'));

	// Register granular notification for this type
	register_notification_object('object', 'brainstorm', elgg_echo('brainstorm:new'));

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'brainstorm_notify_message');



	// Register entity type for search
	elgg_register_entity_type('object', 'idea');

	// Groups
	add_group_tool_option('brainstorm', elgg_echo('brainstorm:enablebrainstorm'), false);
	elgg_extend_view('groups/tool_latest', 'brainstorm/group_module');
*/
}

/**
 * Dispatcher for todo plugin.
 * URLs take the form of
 *  All lists:        todo/all
 *  User's lists:     todo/owner/<username>
 *  Friend's lists:   todo/friends/<username>
 *  Group lists:      todo/group/<guid>/all
		 *  View task:        todo/view/<guid>/<title>
 		*  New task:         todo/add/<guid> (container: user, group, parent)
 		*  Edit task:        todo/edit/<guid>
 *
 * Title is ignored
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
		case 'all':
		default:
			include "$base_dir/world.php";
			break;
		case 'owner':
			include "$base_dir/owner.php";
			break;
		case 'friends':
			include "$base_dir/friends.php";
			break;
		case 'group':
			include "$base_dir/group.php";
			break;


/*
		case 'view':
			set_input('guid', $page[1]);
			include "$base_dir/view.php";
			break;
		case 'add':
			set_input('guid', $page[1]);
			include "$base_dir/new_task.php";
			break;
		case 'addlist':
			set_input('guid', $page[1]);
			include "$base_dir/new_tasklist.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$base_dir/edit_task.php";
			break;
		case 'editlist':
			set_input('guid', $page[1]);
			include("$base_dir/edit_tasklist.php");
			break;
*/
	}

	elgg_pop_context();

	return true;
}


/**
 * Override the workflow url
 * 
 * @param ElggObject $entity workflow object
 * @return string
 */
function workflow_url($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "workflow/view/$entity->guid/$title";
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
		if ($params['entity']->todo_enable != "no") {
			$url = "workflow/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('workflow', elgg_echo('workflow:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}


/**
 * Returns the body of a notification message
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 *
function brainstorm_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'idea')) {
		$descr = $entity->description;
		$title = $entity->title;
		
		$url = elgg_get_site_url() . "view/" . $entity->guid;
		if ($method == 'sms') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("brainstorm:via") . ': ' . $url . ' (' . $title . ')';
		}
		if ($method == 'email') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("brainstorm:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}
		if ($method == 'web') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("brainstorm:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}

	}
	return null;
}

/**
 * Add a page menu.
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 *
function brainstorm_page_menu($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {

		if (elgg_in_context('brainstorm')) {
			$page_owner = elgg_get_page_owner_entity();
			if (!$page_owner) {
				$page_owner = elgg_get_logged_in_user_entity();
			}
			
			if ($page_owner instanceof ElggGroup) {
				$title = elgg_echo('brainstorm:bookmarklet:group');
			} else {
				$title = elgg_echo('brainstorm:bookmarklet');
			}

			//$return[] = new ElggMenuItem('brainstorm', $title, 'brainstorm/bookmarklet/' . $page_owner->getGUID());
		}
	}

	return $return;
}
*/
