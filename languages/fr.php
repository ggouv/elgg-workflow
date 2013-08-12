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

	'workflow' => "Flux de travail",
	'my_workflow' => "Mon tableau",
	'workflow:owner' => "Flux de travail de %s",
	'workflow:board:all' => "Tous les tableaux",
	'workflow:all' => "Tous les flux de travail",
	'workflow:assigned-cards' => "Fiches assignées",
	'workflow:assigned-cards:all' => "Toutes les fiches",
	'workflow:assigned-cards:title:all' => "Toutes les fiches assignées",
	'workflow:assigned-cards:title:mine' => "Mes fiches assignées",
	'workflow:assigned-cards:title:owner' => "Les fiches assignées à %s",
	'workflow:assigned-cards:friends' => "Mes abonnements",
	'workflow:assigned-cards:title:friends' => "Les fiches assignés à mes abonnements",

	'item:object:workflow_board' => "Tableaux",
	'item:object:workflow_list' => "Listes",
	'item:object:workflow_card' => "Fiches",

	'workflow:add' => "Ajouter un tableau",
	'workflow:add_list' => "Ajouter une liste",
	'workflow:refresh_board' => "Rafraîchir",

	'workflow:group' => "Flux de travail du groupe",
	'workflow:board:none' => "Aucun tableau n'a été créé pour l'instant",
	'workflow:board' => "Tableau \"%s\"",
	'workflow:board:owner' => "Tableaux de %s",
	'workflow:board:add' => "Ajouter un tableau",
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
	'deck_river:workflow:card-info-header' => "Informations sur la fiche",

	'workflow:archive' => "Archives",
	'workflow:board:archive' => "Archives du tableau %s",
	'workflow:board:back' => "Retourner au tableau",

	/**
	* River
	**/
	'river:inboard' => "dans le tableau %s",
	'river:in:workflow_list' => "dans %s",
	'river:create:object:workflow_card_list:summary' => "%s a modifié le tableau %s %s",
	'river:create:object:workflow_list:message' => "A ajouté la liste %s",
	'river:create:object:workflow_card:message' => "A ajouté la fiche %s %s",
	'river:comment:object:workflow_card' => "%s a commenté la fiche %s",
	'workflow:card:shorted:message' => "+%s actions",
	'river:create:object:workflow_board' => "%s a ajouté le tableau %s",
	'river:create:object:workflow_card:move:message' => "A déplacé la fiche %s : %s > %s",
	'river:delete:object:workflow_card:message' => "A supprimé la fiche \"%s\" %s",
	'river:delete:object:workflow_list:message' => "A supprimé la liste \"%s\"",

	/**
	 * Form fields
	 */
	'groups:enableworkflow' => "Activer les flux de travail",

	'workflow:list:add_list' => "Ajouter une liste",
	'workflow:list:add_card' => "Ajouter une fiche",

	'workflow:assignedto' => "Assigner à",
	'workflow:assignedtome' => "M'assigner à cette fiche",
	'workflow:assignedtome:help' => "Vous assigner cette fiche permet de la faire apparaître dans la colonne de vos fiches assignées dans le hub.",
	'workflow:checklist' => "Liste à cocher",
	'workflow:checklist:add_item' => "Ajouter un élément",
	'workflow:duedate' => "Date limite",
	'workflow:card:view:duedate' => "%d %b",
	'workflow:card:view_popup:duedate' => "%A %d %B %Y",
	'workflow:card:number' => "Fiche numéro %d",
	'workflow:card:added' => "Ajouté %s par %s",

	/**
	 * Status and error messages
	 */

	'workflow:unknown_card' => "Fiche inconnue.",
	'workflow:not_owner_board' => "Les tableaux personnels sont privés. Vous avez été redirigé sur votre tableau.",

	'workflow:board:add:success' => "Tableau enregistré.",
	'workflow:board:save:failed' => "Le tableau ne peut pas être enrigstré.",
	'workflow:board:delete:success' => "Le tableau a bien été supprimé.",
	'workflow:board:delete:failure' => "Le tableau n'a pas pu être sauvé.",

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
	'workflow:card:edit:cannot_edit' => "Vous ne pouvez pas modifier cette fiche.",
	'workflow:card:assign:success' => "%s a bien été assigné à la fiche.",
	'workflow:card:assign:failure' => "%s n'a pas pu être assigné.",

	'workflow:list:archived:success' => "La liste est maintenant archivée.",

	/**
	 * Widget
	 **/


	/**
	 * Sidebar items
	 */
	 'workflow:sidebar:assignedto_user' => "Participants",
	 'workflow:sidebar:assignedto_user:none' => "Pas de participant",
	 'workflow:sidebar:last_activity_all_board' => "Dernière activité sur tous les tableaux de %s",
	 'workflow:sidebar:last_activity_on_this_board' => "Dernières activités dans ce tableau",

	/**
	 * Settings
	 */
	'workflow:settings:min_list_column' => "Largeur des listes minimum",
	'workflow:settings:max_list_column' => "Largeur des listes maximum",
	'workflow:settings:defaut_max_list_column' => "La largeur de liste maximum par défaut est 242.667<br />Mettez à zéro pour pas de limite.",
);

add_translation("fr", $french);
