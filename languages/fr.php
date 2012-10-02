<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow French language
 *
 */

$french = array(

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
	'workflow:add_list' => "Ajouter une liste",
	'workflow:refresh_board' => "Rafraîchir",

	'workflow:group' => "Group workflows",
	'workflow:board:none' => "No board created yet",
	'workflow:board' => "Board %s",
	'workflow:board:owner' => "%s's boards",
	'workflow:board:add' => "Add new board",
	'workflow:board:created_by' => "Créé par %s",
	'workflow:board:info' => "Contient %s listes et %s fiches",
	'workflow:board:last_action' => "Dernière action",

	'workflow:list:title:default' => "Une liste",
	'workflow:list:none' => "Aucune liste n'a été créée.",
	'workflow:list:delete' => "Supprimer cette liste",
	'workflow:list:edit' => "Éditer cette liste",
	'workflow:list:delete:confirm' => "Êtes-vous sûr de supprimer cette liste et toutes ses fiches ?",
	'workflow:card:delete:confirm' => "Êtes-vous sûr de supprimer cette fiche ?",
	'workflow:card:none' => "Aucune fiche n'a été créée.",
	
	'workflow:archive' => "Archives",
	'workflow:board:archive' => "Archives of the board %s",
	'workflow:board:back' => "Go back to the board",

	/**
	* River
	**/
	'river:inboard' => "in board %s",
	'river:in:workflow_list' => "dans la liste %s",
	'river:create:object:workflow_card_list:summary' => "%s modified board %s %s",
	'river:create:object:workflow_list:message' => "A ajouté la liste %s",
	'river:create:object:workflow_card:message' => "A ajouté la fiche %s %s",
	'river:comment:object:workflow_card' => "%s commenté la fiche %s",
	'workflow:card:shorted:message' => "+%s actions",
	'river:create:object:workflow_board' => "%s added board %s",
	'river:create:object:workflow_card:move:message' => "A déplacé la fiche %s : %s > %s",

	/**
	 * Form fields
	 */
	'groups:enableworkflow' => "Enable group workflow",
	
	'workflow:list:add_list' => "Ajouter une liste",
	'workflow:list:add_card' => "Ajouter une fiche",

	'workflow:assignedto' => "Assigner à",
	'workflow:checklist' => "Liste à cocher",
	'workflow:checklist:add_item' => "Ajouter un élément",
	'workflow:duedate' => "Date limite",
	'workflow:card:view:duedate' => "d M",
	'workflow:card:view_popup:duedate' => "l j F y",
	'workflow:card:number' => "Fiche numéro %d",
	'workflow:card:added' => "Ajouté %s par %s",

	/**
	 * Status and error messages
	 */

	'workflow:unknown_card' => "Fiche inconnue.",
	
	'workflow:board:add:success' => "Board saved.",
	'workflow:board:save:failed' => "Board cannot be saved.",
	'workflow:board:delete:success' => "Board successfully moved.",
	'workflow:board:delete:failure' => "Delete board doesn't saved.",

	'workflow:list:move:failure' => "Erreur de connexion avec le serveur. Le déplacement de la liste n'a pas été enregistré.",
	'workflow:list:move:success' => "La liste a bien été déplacé.",
	'workflow:list:add:failure' => "Erreur de connexion avec le serveur. La liste n'a pas été ajoutée.",
	'workflow:list:add:cannotadd' => "Vous ne pouvez pas ajouter une liste.",
	'workflow:list:add:success' => "Liste ajoutée.",
	'workflow:list:delete:failure' => "La suppression de la liste n'a pas été enregistrée.",
	'workflow:list:delete:success' => "Liste supprimée.",

	'workflow:card:move:success' => "La fiche a bien été déplacé.",
	'workflow:card:move:failure' => "Erreur de connexion avec le serveur. Le déplacement de la fiche n'a pas été enregistré.",
	'workflow:card:add:failure' => "Erreur de connexion avec le serveur. La fiche n'a pas été ajoutée.",
	'workflow:card:add:cannotadd' => "Vous ne pouvez pas ajouter une fiche.",
	'workflow:card:add:success' => "Fiche ajoutée.",
	'workflow:card:delete:success' => "Fiche supprimée.",
	'workflow:card:delete:failure' => "La suppression de la fiche n'a pas été enregistrée.",
	'workflow:card:edit:success' => "Les changements de la fiche ont été enregistrés.",
	'workflow:card:edit:failure' => "Les changements de la fiche n'ont pas été enregistrés.",
	'workflow:card:edit:cannot_edit' => "Vous ne pouvez pas modifier une fiche.",

	'workflow:list:archived:success' => "La liste est maintenant archivée.",

	/**
	 * Widget
	 **/

	
	/**
	 * Sidebar items
	 */
	 'workflow:sidebar:assignedto_user' => "Participants",
	 'workflow:sidebar:assignedto_user:none' => "Pas de participant",
	 'workflow:sidebar:last_activity_all_board' => "Last activity on all %s's boards",
	 'workflow:sidebar:last_activity_on_this_board' => "Last activity on this board",

	/**
	 * Settings
	 */
	'workflow:settings:min_list_column' => "Largeur des listes minimum",
	'workflow:settings:max_list_column' => "Largeur des listes maximum",
	'workflow:settings:defaut_max_list_column' => "Defaut value of maximum list's width is 242.667<br />Set empty to no limit.",
);

add_translation("fr", $french);