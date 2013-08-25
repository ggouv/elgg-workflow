<?php
/**
 * Workflow ajax activity
 */

$entity_guid = get_input('entity_guid') ;

$entity = get_entity($entity_guid);

if ($entity) {

	// board activity
	global $CONFIG;
	$dbprefix = $CONFIG->dbprefix;

	$options['joins'][] = "JOIN {$dbprefix}entities e ON e.guid = rv.object_guid";
	$options['wheres'][] = "(rv.view IN ('river/object/workflow_river/create', 'river/object/workflow_river/modified'))";

	$content = '';

	if ($entity->getSubtype() == 'workflow_board') {
		elgg_set_page_owner_guid($entity->container_guid); // set page owner to not show "in group" in river items

		$metastring = get_metastring_id('board_guid');
		$board_string = get_metastring_id($entity_guid);

		if ($board_string) { // if board_string doesn't exist that mind no card and list are created > board just created.
			$options['joins'][] = "LEFT JOIN {$dbprefix}metadata d ON d.entity_guid = e.guid";
			$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings m ON m.id = d.value_id";
			$options['wheres'][] = "e.container_guid = " . $entity->getContainerGUID();
			$options['wheres'][] = "d.name_id = {$metastring} AND d.value_id = {$board_string}";
			//$options['wheres'][] = "rv.object_guid = {$board_guid}";

			$defaults = array(
				//'offset' => (int) get_input('offset', 0),
				'limit' => 10,
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

		if ($content) echo $content;

	} else {

		$options['wheres'][] = "e.container_guid = " . $entity->getGUID();

		$defaults = array(
			//'offset' => (int) get_input('offset', 0),
			'limit' => 10,
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

		echo $content;
	}
}
