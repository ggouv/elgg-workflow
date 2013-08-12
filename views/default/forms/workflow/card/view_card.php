<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow view card popup
 *
 */

elgg_load_library('workflow:utilities');

$card = elgg_extract('card', $vars, null);
$archive = elgg_extract('archive', $vars, false);

if (!$card) {
	echo elgg_echo('workflow:unknown_card');
	return true;
}

$list = get_entity($card->list_guid);
$board = get_entity($card->board_guid);
$group = $card->getContainerEntity();

?>


<h2><?php echo $card->title; ?></h2>
<label><?php
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
?></label>

<?php if ($card->description) { ?>
	<div class="mts"><?php echo elgg_view('output/longtext', array('value' => $card->description)); ?></div>
<?php } ?>

<?php
	$assignedto = elgg_get_entities_from_relationship(array(
		'relationship' => 'assignedto',
		'relationship_guid'=> $card->getGUID()
	));
	if ($assignedto) { ?>
<div class="mts">
	<label><?php echo elgg_echo('workflow:assignedto'); ?></label><br />
	<div class="ptm">
	<?php
	 foreach ($assignedto as $user) {
			echo elgg_view_entity_icon($user, 'tiny', array('use_hover' => false));
		}
	?>
	</div>
</div>
<?php } ?>

<?php if ($card->checklist) { ?>
<div class="card-checklist mts">
	<label><?php echo elgg_echo('workflow:checklist'); ?></label>
	<?php $checklist = array_flip($card->checklist);
		echo elgg_view('input/checkboxes', array(
			'options' => $checklist,
			'value' => $card->checklist_checked,
			'name' => 'checklist_checked',
			'align' => 'vertical',
			'disabled' => true,
		));
	?>
</div>
<?php } ?>

<?php if ($card->duedate) { ?>
<div class="duedate mts">
	<label><?php echo elgg_echo('workflow:duedate'); ?></label><br />
	<?php $duedate = $card->duedate;
		if ($duedate) {
			$duedate = explode('/', $duedate);
			$duedate_timestamp = gmmktime(23, 59, 59, $duedate[1], $duedate[0], $duedate[2]);
			if ($duedate_timestamp <= time()) $overdue = 'overdue';
			echo "<div class='$overdue mts'>" . strftime(elgg_echo('workflow:card:view_popup:duedate'), $duedate_timestamp) . "</div>";
		}
	?>
</div>
<?php } ?>

<?php $categories = elgg_view('output/categories', $vars);
	if ($categories) {
		echo $categories;
	}
?>

<div class="elgg-foot">
	<?php
		echo elgg_view('input/hidden', array('name' => 'entity_guid', 'value' => $card->getGUID()));

		if ($card->getContainerEntity()->canWriteToContainer()) {
			echo elgg_view('input/submit', array(
				'value' => elgg_echo('workflow:assignedtome'),
				'id' => 'workflow-edit-card-submit'
			));
		}
	?>
	<div class="elgg-subtext">
		<?php
			echo elgg_view('output/url', array(
				'href' => $card->getURL(),
				'text' => elgg_echo('workflow:card:number', array($card->getGUID())),
				'is_trusted' => true,
			));
			$creator = get_entity($card->owner_guid);
			$creator_link = elgg_view('output/url', array(
				'href' => "profile/$creator->username",
				'text' => $creator->name,
				'is_trusted' => true,
			));
			echo  '<br/>' . elgg_echo('workflow:card:added', array(elgg_view_friendly_time($card->time_created), $creator_link));
		?>
	</div>
</div>

