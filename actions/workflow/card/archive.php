<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow archive/dearchive card action
 *
 */

$archived_card_guid = (int) get_input('card_guid');

$archived_card = get_entity($archived_card_guid);
$board = get_entity($archived_card->board_guid);

$user_guid = elgg_get_logged_in_user_guid();

if ($archived_card && can_write_to_container($user_guid, $archived_card->container_guid)) {

	elgg_load_library('workflow:utilities');

	if ($archived_card->getSubtype() == 'workflow_card') {
		if (workflow_archive($archived_card)) {
			system_message(elgg_echo('workflow:card:archived:success'));

			$message = elgg_echo('river:archive:object:workflow_card:message', array(
						elgg_view('output/url', array(
							'href' => $archived_card->getURL(),
							'text' => $archived_card->title,
							'class' => 'elgg-river-object workflow-edit-card',
							'rel' => 'popup',
							'data-guid' => $archived_card->getGUID(),
							'is_trusted' => true,
						))
			));
			$annotation_id = workflow_create_annotation($archived_card->board_guid, $message, $user_guid, $archived_card->access_id);

			if ($annotation_id['new'] == true) {
				$id = add_to_river('river/object/workflow_river/create','create', $user_guid, $archived_card->board_guid, '', 0, $annotation_id['id']);
				$item = elgg_get_river(array('id' => $id));
			} else {
				$item = elgg_get_river(array('annotation_id' => $annotation_id['id']));
			}

			if ($item) {
				elgg_set_page_owner_guid($archived_card->container_guid);
				$echo['river'] = "<li id='item-river-{$item[0]->id}' class='elgg-list-item' datetime=\"{$item[0]->posted}\">" .
									elgg_view('river/item', array('item' => $item[0], 'size' => 'tiny', 'short' => true)) . '</li>';
				echo json_encode($echo);
			}
			forward($board->getURL());
		}
	} else if ($archived_card->getSubtype() == 'workflow_card_archived') { // dearchive card
		if (workflow_dearchive($archived_card)) {
			// replace in good position
			workflow_move_card($archived_card, $archived_card->list_guid, $archived_card->order);
			system_message(elgg_echo('workflow:card:dearchived:success'));
			// we cannot use $archived_card->getURL() because $arcived_card is in cache with subtype workflow_card_archived
			forward("workflow/board/{$board->getGUID()}/card/{$archived_card->getGUID()}/$archived_card->title");
		}
	}
}

register_error(elgg_echo('workflow:card:archive:failure'));
forward($board->getURL());
