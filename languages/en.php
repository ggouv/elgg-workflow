<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow English language
 *
 */

$english = array(

	/**
	 * Menu items and titles
	 */

	'workflow' => "Workflow",
	'my_workflow' => "My Board",
	'workflow:owner' => "%s's workflow",
	'workflow:board:all' => "All workflow boards",
	'workflow:all' => "all",
	'workflow:assigned-cards' => "Assigned cards",
	'workflow:assigned-cards:all' => "All",
	'workflow:assigned-cards:title:all' => "All assigned cards",
	'workflow:assigned-cards:title:mine' => "My assigned cards",
	'workflow:assigned-cards:title:owner' => "%s's assigned cards",
	'workflow:assigned-cards:friends' => "Friends",
	'workflow:assigned-cards:title:friends' => "Friends's Assigned cards",

	'item:object:workflow_board' => "Workflow boards",
	'item:object:workflow_list' => "Workflow lists",
	'item:object:workflow_card' => "Workflow cards",

	'workflow:add' => "Add board",
	'workflow:add_list' => "Add list",
	'workflow:refresh_board' => "Refresh",

	'workflow:group' => "Group workflows",
	'workflow:board:none' => "No board created yet",
	'workflow:board' => "Board %s",
	'workflow:board:owner' => "%s's boards",
	'workflow:board:add' => "Add new board",
	'workflow:board:created_by' => "Created by %s",
	'workflow:board:info' => "Contains %s lists and %s cards",
	'workflow:board:last_action' => "Last action",

	'workflow:list:title:default' => "A list",
	'workflow:list:none' => "No list created yet",
	'workflow:list:delete' => "Delete this list",
	'workflow:list:edit' => "Edit this list",
	'workflow:list:delete:confirm' => "Are you sure to delete this list and all of his cards?",
	'workflow:card:delete:confirm' => "Are you sure to delete this card?",
	'workflow:card:none' => "No card created yet.",

	'workflow:archive' => "Archives",
	'workflow:board:archive' => "Archives of the board %s",
	'workflow:board:back' => "Go back to the board",

	/**
	* River
	**/
	'river:inboard' => "in board %s",
	'river:in:workflow_list' => "in list %s",
	'river:create:object:workflow_card_list:summary' => "%s modified board %s %s",
	'river:create:object:workflow_list:message' => "Added list %s",
	'river:create:object:workflow_card:message' => "Added card %s %s",
	'river:comment:object:workflow_card' => "%s commented the card %s",
	'workflow:card:shorted:message' => "+%s actions",
	'river:create:object:workflow_board' => "%s added board %s",
	'river:create:object:workflow_card:move:message' => "Moved card %s form %s to %s",
	'river:delete:object:workflow_card:message' => "Deleted card \"%s\" %s",
	'river:delete:object:workflow_list:message' => "Deleted list \"%s\"",

	/**
	 * Form fields
	 */
	'groups:enableworkflow' => "Enable group workflow",

	'workflow:list:add_list' => "Add list",
	'workflow:list:add_card' => "Add card",

	'workflow:assignedto' => "Assigned to",
	'workflow:checklist' => "Check list",
	'workflow:checklist:add_item' => "Add item",
	'workflow:duedate' => "Due date",
	'workflow:card:view:duedate' => "M d",
	'workflow:card:view_popup:duedate' => "l j F y",
	'workflow:card:number' => "Card number %d",
	'workflow:card:added' => "Added %s by %s",

	/**
	 * Status and error messages
	 */

	'workflow:unknown_card' => "Unknown card",
	'workflow:not_owner_board' => "Personal board are private. You have been redirect to your own board.",

	'workflow:board:add:success' => "Board saved.",
	'workflow:board:save:failed' => "Board cannot be saved.",
	'workflow:board:delete:success' => "Board successfully moved.",
	'workflow:board:delete:failure' => "Delete board doesn't saved.",

	'workflow:list:move:failure' => "Error with server connexion. Move list doesn't saved.",
	'workflow:list:move:success' => "List successfully moved.",
	'workflow:list:add:failure' => "Error with server connexion. Add list doesn't saved.",
	'workflow:list:add:cannotadd' => "You cannot add a list.",
	'workflow:list:add:success' => "List successfully added.",
	'workflow:list:delete:confirm' => "Do you really want delete this list ?",
	'workflow:list:delete:failure' => "Delete list doesn't saved.",
	'workflow:list:delete:success' => "List deleted.",

	'workflow:card:move:success' => "Card successfully moved.",
	'workflow:card:move:failure' => "Error. Move card doesn't saved.",
	'workflow:card:add:failure' => "Error with server connexion. Add card doesn't saved.",
	'workflow:card:add:cannotadd' => "You cannot add a card.",
	'workflow:card:add:success' => "Card successfully added.",
	'workflow:card:delete:confirm' => "Do you really want delete this card ?",
	'workflow:card:delete:success' => "Card deleted.",
	'workflow:card:delete:failure' => "Delete card doesn't saved.",
	'workflow:card:edit:success' => "Card changes successfully saved.",
	'workflow:card:edit:failure' => "Card changes don't saved.",
	'workflow:card:edit:cannot_edit' => "You cannot edit this card.",

	'workflow:list:archived:success' => "List now archived.",

	/**
	 * Widget
	 **/


	/**
	 * Sidebar items
	 */
	 'workflow:sidebar:assignedto_user' => "Participants",
	 'workflow:sidebar:assignedto_user:none' => "No participants",
	 'workflow:sidebar:last_activity_all_board' => "Last activity on all %s's boards",
	 'workflow:sidebar:last_activity_on_this_board' => "Last activity on this board",

	/**
	 * Settings
	 */
	'workflow:settings:min_list_column' => "Minimum list's width",
	'workflow:settings:max_list_column' => "Maximum list's width",
	'workflow:settings:defaut_max_list_column' => "Defaut vaule of maximum list's width is 242.667<br />Set empty to no limit.",
);

add_translation("en", $english);
