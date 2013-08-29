<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow card move action
 *
 */
$card_guid = get_input('card_guid');
$list_guid = get_input('list_guid');
$position = get_input('position');

$moved_card = get_entity($card_guid);
$user_guid = elgg_get_logged_in_user_guid();

if ($moved_card && $moved_card->canEdit()) {

	elgg_load_library('workflow:utilities');

	// define list_guid's card to destination list
	$original_list = get_entity($moved_card->list_guid);

	if (workflow_move_card($moved_card, $list_guid, $position)) {

		// check if the card ordered in the same list
		if ( $original_list->getGUID() == $list_guid ) {

			system_message(elgg_echo('workflow:card:move:success'));

		} else { // not in the same list

			$list_dest = get_entity($list_guid);

			if ($moved_card->save()) {
				system_message(elgg_echo('workflow:card:move:success'));

				// write annotation
				$list = get_entity($list_guid);
				$moved_card_link = elgg_view('output/url', array(
					'href' => $moved_card->getURL(),
					'text' => $moved_card->title,
					'class' => 'elgg-river-object workflow-edit-card',
					'rel' => 'popup',
					'data-guid' => $moved_card->getGUID(),
					'is_trusted' => true,
				));
				$original_list_link = elgg_view('output/url', array(
					'href' => $original_list->getURL(),
					'text' => $original_list->title,
					'class' => 'elgg-river-object',
					'is_trusted' => true,
				));
				$list_dest_link = elgg_view('output/url', array(
					'href' => $list_dest->getURL(),
					'text' => $list_dest->title,
					'class' => 'elgg-river-object',
					'is_trusted' => true,
				));
				$message = elgg_echo('river:create:object:workflow_card:move:message', array($moved_card_link, $original_list_link, $list_dest_link));
				$annotation_id = workflow_create_annotation($list->board_guid, $message, $user_guid, $list->access_id);

				if ($annotation_id['new'] == true) {
					$id = add_to_river('river/object/workflow_river/create','create', $user_guid, $list->board_guid, '', 0, $annotation_id['id']);
					$item = elgg_get_river(array('id' => $id));
				} else {
					$item = elgg_get_river(array('annotation_id' => $annotation_id['id']));
				}

				elgg_set_page_owner_guid($container_guid);
				$echo['river'] = "<li id='item-river-{$item[0]->id}' class='elgg-list-item' datetime=\"{$item[0]->posted}\">" .
									elgg_view('river/item', array('item' => $item[0], 'size' => 'tiny', 'short' => true)) . '</li>';

				echo json_encode($echo);
			}

		}
		forward(REFERER);
	}
}

register_error(elgg_echo('workflow:card:move:failure'));
forward(REFERER);
