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
	'workflow:owner' => "%s's workflow",
	'workflow:add_list' => "Add list",

	'workflow:group' => "Group workflow",
	'groups:enableworkflow' => "Enable group workflow",

	'workflow:list:none' => "No list created yet",
/*
	'tasks' => "Tasks",
	'tasks:owner' => "%s's tasks",
	'tasks:friends' => "Friends' tasks",
	'tasks:all' => "All site tasks",
	'tasks:add' => "Add task",
	'tasks:addlist' => "Add list",
	
	'tasks:lists' => "Task lists",
	'tasks:lists:owner' => "%s's task lists",
	'tasks:lists:friends' => "Friends' task lists",
	'tasks:lists:all' => "All site task lists",

	'tasks:group' => "Group tasks",
	'groups:enabletasks' => 'Enable group tasks',

	'tasks:edit' => "Edit this task",
	'tasks:delete' => "Delete this task",
	'tasks:view' => "View task",
	
	'tasks:lists:add' => "Add a task list",
	'tasks:lists:edit' => "Edit this task list",
	'tasks:lists:delete' => "Delete this task list",
	'tasks:lists:view' => "View task list",

	'tasks:via' => "via tasks",
	'item:object:tasklist' => 'Task lists',
	'item:object:task' => 'Tasks',
	'tasks:nogroup' => 'This group does not have any tasks yet',
	'tasks:more' => 'More tasks',
	'tasks:none' => 'No tasks created yet',
	'tasks:lists:none' => 'No task list created yet',
	
	'tasks:priority:low' => 'Low priority',
	'tasks:priority:normal' => 'Normal priority',
	'tasks:priority:high' => 'High prioritiy',

	/**
	* River
	**/
/*
	'river:create:object:task' => '%s created a task %s',
	'river:create:object:tasklist' => '%s created a task list %s',
	'river:update:object:task' => '%s updated a task %s',
	'river:update:object:tasklist' => '%s updated a task list %s',
	'river:comment:object:task' => '%s commented on a task titled %s',
	'river:comment:object:tasklist' => '%s commented on a task list titled %s',

	/**
	 * Form fields
	 */
/*
	'tasks:title' => 'Name',
	'tasks:description' => 'Description',
	'tasks:list' => 'List',
	'tasks:priority' => 'Priority',
	'tasks:tags' => 'Tags',
	'tasks:elapsed_time' => 'Elapsed time',
	'tasks:remaining_time' => 'Remaining time (time to finish the task)',
	'tasks:access_id' => 'Who can see this task?',
	
	'tasks:changehistory' => 'Change history',
	'tasks:comments:post' => 'Save changes',
	
	'tasks:state:actions' => 'Actions',
	'tasks:state:action:noaction' => 'Leave as <em>%s</em>',
	'tasks:state:action:assign' => 'Accept this task',
	'tasks:state:action:leave' => 'Leave this task',
	'tasks:state:action:activate' => 'Set as your active task',
	'tasks:state:action:deactivate' => 'Unset as your active task',
	'tasks:state:action:assign_and_activate' => 'Accept this task and set as your active one',
	'tasks:state:action:mark_as_done' => 'Mark this task as done',
	'tasks:state:action:close' => 'Close this task',
	'tasks:state:action:reopen' => 'Reopen this task',
	
	'tasks:assigned' => 'Assigned tasks',
	'tasks:unassigned' => 'Unassigned tasks',
	'tasks:closed' => 'Closed tasks',
	
	'tasks:lists:title' => 'Name',
	'tasks:lists:description' => 'Description',
	'tasks:lists:startdate' => 'Start date',
	'tasks:lists:enddate' => 'End date (deadline)',
	'tasks:lists:tags' => 'Tags',
	'tasks:lists:access_id' => 'Who can see this task list?',

	/**
	 * Status and error messages
	 */

	'workflow:list:move:failure' => "Error with server connexion. Move doesn't saved.",
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
	'workflow:settings:min_list_column' => "Minimum width of columns",
);

add_translation("en", $english);
