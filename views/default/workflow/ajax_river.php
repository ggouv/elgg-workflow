<?php
/**
 * Workflow ajax activity
 */

$entity_guid = get_input('entity_guid') ;
$time_posted = get_input('time_posted', false);

$entity = get_entity($entity_guid);

if ($entity) {

	$options['wheres'][] = "(rv.view IN ('river/object/workflow_river/create'))";
	if ($time_posted) $options['posted_time_upper'] = (int) $time_posted-1;

	$content = '';

	if ($entity->getSubtype() == 'workflow_board') {

		elgg_set_page_owner_guid($entity->container_guid); // set page owner to not show "in group" in river items

		$board_string = get_metastring_id($entity_guid);

		if ($board_string) { // if board_string doesn't exist that mind no card and list are created > board just created.
			$options['wheres'][] = "rv.object_guid = {$entity_guid}";

			$defaults = array(
				//'offset' => (int) get_input('offset', 0),
				'limit' => 30,
				'pagination' => FALSE,
				'count' => FALSE,
			);
			$options = array_merge($defaults, $options);
			$items = elgg_get_river($options);

			if (is_array($items)) {
				foreach ($items as $item) {
					$content .= "<li id='item-river-{$item->id}' class='elgg-list-item board-{$entity_guid}' datetime=\"{$item->posted}\">";
						$content .= elgg_view('river/item', array('item' => $item, 'short' => true));
					$content .= '</li>';
				}
			}

		}

	} else {

		elgg_set_page_owner_guid($entity->getGUID()); // set page owner to not show "in group" in river items

		global $CONFIG;
		$dbprefix = $CONFIG->dbprefix;

		$options['joins'][] = "JOIN {$dbprefix}entities e ON e.guid = rv.object_guid";
		$options['wheres'][] = "e.container_guid = " . $entity->getGUID();

		$defaults = array(
			//'offset' => (int) get_input('offset', 0),
			'limit' => 30,
			'pagination' => FALSE,
			'count' => FALSE,
		);
		$options = array_merge($defaults, $options);
		$items = elgg_get_river($options);

		if (is_array($items)) {
			foreach ($items as $item) {
				$object = $item->getObjectEntity();
				$content .= "<li id='item-river-{$item->id}' class='elgg-list-item board-{$object->board_guid}' datetime=\"{$item->posted}\">";
					$content .= elgg_view('river/item', array('item' => $item, 'short' => 'group'));
				$content .= '</li>';
			}
		}

	}
}

if ($content) {
	$content .= '<li class="moreItem">';
	$content .= '<div class="response-loader hidden"></div>' . elgg_echo('deck_river:more');
	$content .= '</li>';
	echo $content;
} else {
	echo '<li class="end" style="text-align:center;">' . elgg_echo('river:end') . '</li>';
}
