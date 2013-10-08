<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow edit card form
 *
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use extract()
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$assignedto = elgg_extract('assignedto', $vars, '');
$checklist = elgg_extract('checklist', $vars, '');
$checklist_checked = elgg_extract('checklist_checked', $vars, '');
$duedate = elgg_extract('duedate', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$card_guid = elgg_extract('guid', $vars, null);

$card = get_entity($card_guid);
$list = get_entity($card->list_guid);
$board = get_entity($card->board_guid);
$group = $card->getContainerEntity();

$user_guid = elgg_get_logged_in_user_guid();

?>
<div class="elgg-body">
	<label>
		<?php
			$list_link = elgg_view('output/url', array(
				'href' => $list->getURL(),
				'text' => $list->title ? $list->title : $list->name,
				'is_trusted' => true,
			));
			$list_string = elgg_echo('river:in:workflow_list', array($list_link));

			$board_link = elgg_view('output/url', array(
				'href' => $board->getURL(),
				'text' => $board->title ? $board->title : $board->name,
				'is_trusted' => true,
			));
			$board_string = elgg_echo('river:inboard', array($board_link));

			$group_link = elgg_view('output/url', array(
				'href' => $group->getURL(),
				'text' => $group->name,
				'is_trusted' => true,
			));
			$group_string = elgg_echo('river:ingroup', array($group_link));

			echo ucfirst($list_string) . '&nbsp;' . $board_string . '&nbsp;' . $group_string;
		?>
	</label><br/><br/>
	<div>
		<label><?php echo elgg_echo('title'); ?></label><br />
		<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
	</div>

	<div>
		<label><?php echo elgg_echo('description'); ?></label>
		<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc, 'preview' => 'toggle')); ?>
	</div>

	<div>
	<?php $assignedto = elgg_get_entities_from_relationship(array(
				'relationship' => 'assignedto',
				'relationship_guid'=> $card_guid
			));
			foreach ($assignedto as $user) {
				$users[] = $user->guid;
			}

		if ($user_guid != $card->container_guid) { ?>
			<label><?php echo elgg_echo('workflow:assignedto'); ?></label>
			<?php echo elgg_view('input/userpicker', array(
				'name' => 'assignedto',
				'value' => $users
			));
		} else { // this card is on a private board ?>
			<label><?php echo elgg_echo('workflow:assignedtome'); ?></label>
			<?php
			if ($users[0] == $user_guid) {
				$checked = true;
			} else {
				$checked = false;
			}
			echo elgg_view('input/checkbox', array(
				'name' => 'assignedtome',
				'checked' => $checked
				));
			echo '<br/>' . elgg_echo('workflow:assignedtome:help');
		} ?>
	</div>

	<div class="duedate">
		<label><?php echo elgg_echo('workflow:duedate'); ?></label>
		<?php echo elgg_view('input/date', array('name' => 'duedate', 'value' => $duedate)); ?>
	</div>

	<div class="card-checklist sortable clearfix">
		<label><?php echo elgg_echo('workflow:checklist'); ?></label>
		<?php
			if ($checklist != null) {
				if (!is_array($checklist)) $checklist = array('0' => $checklist);
				if ($checklist_checked === '0') $checklist_checked = array('0');
				$checklist = array_flip($checklist);
				$checklist_view = elgg_view('input/checkboxes', array(
					'options' => $checklist,
					'value' => $checklist_checked,
					'name' => 'checklist_checked',
					'align' => 'vertical',
				));
				$checklist_icons = elgg_view_icon('delete', 'float-alt');
				echo preg_replace('/<\/li>/',"$checklist_icons</li>", $checklist_view);
			} else {
				echo '<ul class="elgg-input-checkboxes elgg-vertical"></ul>';
			}
			echo elgg_view('input/plaintext', array(
				'name' => 'checklist_item',
				'value' => elgg_echo('workflow:checklist:add_item'),
				'class' => 'mbs mts',
			));
			echo elgg_view('input/button', array(
				'value' => elgg_echo('workflow:checklist:add_item'),
				'class' => 'elgg-button-submit hidden float',
			));
			echo elgg_view_icon('delete', 'hidden float');
		?>
	</div>

	<?php $categories = elgg_view('input/categories', $vars);
		if ($categories) {
			echo $categories;
		}
	?>

	<?php echo elgg_view('input/hidden', array('name' => 'entity_guid', 'value' => $card_guid)); ?>

</div>

