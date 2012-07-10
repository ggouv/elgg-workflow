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
	'my_workflow' => "My Workflow",
	'workflow:owner' => "%s's workflow",

 	'item:object:workflow_list' => "Workflow lists",
 	'item:object:workflow_card' => "Workflow cards",

	'workflow:add_list' => "Add list",

	'workflow:group' => "Group workflow",
	'groups:enableworkflow' => "Enable group workflow",

	'workflow:list:none' => "No list created yet",

	'workflow:list:delete' => "Delete this list",
	'workflow:list:edit' => "Edit this list",
	'workflow:list:delete:confirm' => "Are you sure to delete this list and all of his cards?",
	'workflow:card:delete:confirm' => "Are you sure to delete this card?",

	/**
	* River
	**/
	'river:create:object:workflow_list' => "%s added a new list %s",
	'river:create:object:workflow_card' => "%s added a new card %s",
	'river:comment:object:workflow_card' => "%s commented card %s",

	/**
	 * Form fields
	 */
	'workflow:list:add_list' => "Add list",
	'workflow:list:add_card' => "Add card",

	'workflow:assignedto' => "Assigned to",
	'workflow:checklist' => "Check list",
	'workflow:checklist:add_item' => "Add item",
	'workflow:duedate' => "Due date",
	'workflow:card:number' => "Card number %d",
	'workflow:card:added' => "Added %s by %s",

	/**
	 * Status and error messages
	 */

	'workflow:unknown_card' => "Unknown card",

	'workflow:list:move:failure' => "Error with server connexion. Move list doesn't saved.",
	'workflow:list:add:failure' => "Error with server connexion. Add list doesn't saved.",
	'workflow:list:add:cannotadd' => "You cannot add a list.",
	'workflow:list:add:success' => "List successfully added.",
	'workflow:list:delete:failure' => "Delete list doesn't saved.",
	'workflow:list:delete:success' => "List deleted.",

	'workflow:card:move:failure' => "Error. Move card doesn't saved.",
	'workflow:card:add:failure' => "Error with server connexion. Add card doesn't saved.",
	'workflow:card:add:cannotadd' => "You cannot add a card.",
	'workflow:card:add:success' => "Card successfully added.",
	'workflow:card:delete:success' => "Card deleted.",
	'workflow:card:delete:failure' => "Delete card doesn't saved.",
	'workflow:card:edit:success' => "Card changes successfully saved.",
	'workflow:card:edit:failure' => "Card changes don't saved.",
	'workflow:card:edit:cannot_edit' => "You cannot edit this card.",

/*
	'tasks:noaccess' => 'No access to task',
	'tasks:cantedit' => 'You cannot edit this task',
	'tasks:saved' => 'Task saved',
	'tasks:notsaved' => 'Task could not be saved',
	'tasks:error:no_title' => 'You must specify a title for this task.',
	'tasks:delete:success' => 'The task was successfully deleted.',
	'tasks:delete:failure' => 'The task could not be deleted.',

	'tasks:lists:noaccess' => 'No access to task list',
	'tasks:lists:cantedit' => 'You cannot edit this task list',
	'tasks:lists:saved' => 'Task list saved',
	'tasks:lists:notsaved' => 'Task list could not be saved',
	'tasks:lists:error:no_title' => 'You must specify a title for this task list.',
	'tasks:lists:delete:success' => 'The task list was successfully deleted.',
	'tasks:lists:delete:failure' => 'The task list could not be deleted.',
	
	/**
	 * Task
	 */
/*
	'tasks:strapline:new' => 'Reported %s by %s',
	'tasks:strapline:assigned' => 'Assigned %s to %s',
	'tasks:strapline:unassigned' => 'Unassigned %s to %s',
	'tasks:strapline:active' => 'Assigned %s to %s',
	'tasks:strapline:done' => 'Done %s by %s',
	'tasks:strapline:closed' => 'Closed %s by %s',
	'tasks:strapline:reopened' => 'Reopened %s by %s',
	
	/**
	 * Task list
	 */
/*
	'tasks:lists:strapline' => 'Created %s by %s',
	'tasks:lists:deadline' => 'Deadline in %s',
	
	'tasks:lists:graph:total' => '%s tasks',
	'tasks:lists:graph:remaining' => '%s remaining',
	'tasks:lists:graph:assigned' => '%s assigned',
	'tasks:lists:graph:active' => '%s active',
	
	/**
	 * Change history
	 */
/*
	'tasks:history:assign' => "assgined herself this task",
	'tasks:history:activate' => "set this as her active task",
	'tasks:history:deactivate' => "unset this as her active task",
	'tasks:history:mark_as_done' => "set this task as done",
	'tasks:history:reopen' => "reopened this task",
	'tasks:history:leave' => "left to do this task",
	'tasks:history:close' => "closed this task (won't do)",

	/**
	 * Widget
	 **/
/*
	'tasks:active' => "Active tasks",
	'tasks:num' => 'Number of tasks to display',
	'tasks:widget:description' => "This is a list of your tasks.",

	/**
	 * Submenu items
	 */
/*
	'tasks:label:view' => "View task",
	'tasks:label:edit' => "Edit task",
	
	'tasks:lists:label:view' => "View task list",
	'tasks:lists:label:edit' => "Edit task list",
	
	/**
	 * Sidebar items
	 */
	 'workflow:sidebar:assignedto_user' => "Participants:",
/*
	'tasks:sidebar:this' => "This list",
	'tasks:sidebar:children' => "List tasks",
	'tasks:sidebar:parent' => "List",

	'tasks:newchild' => "Create a task in this list",
	'tasks:backtoparent' => "Back to '%s'",
*/

	/**
	 * Settings
	 */
	'workflow:settings:min_list_column' => "Minimum list's width",
	'workflow:settings:max_list_column' => "Maximum list's width",
	'workflow:settings:defaut_max_list_column' => "Defaut vaule of maximum list's width is 242.667<br />Set empty to no limit.",
);

add_translation("en", $english);
