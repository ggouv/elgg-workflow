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

$card_guid = get_input('card_guid');
$card = get_entity($card_guid);

if (!elgg_instanceof($card, 'object', 'workflow_card')) {
	echo elgg_echo('workflow:unknown_card');
	return true;
}
?>
<div id="card-forms">
	<div class="elgg-form elgg-form-workflow-card-edit-card">

		<h2><?php echo $card->title; ?></h2>

		<?php if ($card->description) { ?>
			<div class="mts"><?php echo $card->description; ?></div>
		<?php } ?>

		<?php if ($card->assignedto) { ?>
		<div class="mts">
			<label><?php echo elgg_echo('workflow:assignedto'); ?></label><br />
			<?php $assignedto = (array) $card->assignedto;
				if ($assignedto) {
					// echo '<div class="workflow-card-assignedto">';
					foreach ( $assignedto as $user_guid) {
						$user = get_entity($user_guid);
						echo elgg_view_entity_icon($user, 'tiny', array('use_hover' => false));
					}
					// echo '</div>';
				}
			?>
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
					$duedate = explode('-', $duedate);
					$duedate_timestamp = gmmktime(23, 59, 59, $duedate[1], $duedate[2], $duedate[0]);
					if ( $duedate_timestamp <= time() ) $overdue = 'overdue';
					echo "<div class='$overdue'>" . gmdate(elgg_echo('workflow:card:view_popup:duedate'), $duedate_timestamp) . "</div>";
				}
			?>
		</div>
		<?php } ?>

		<?php if ($card->tags) { ?>
		<div>
			<label><?php echo elgg_echo('tags'); ?></label><br />
			<?php echo elgg_view('output/tags', array('value' => $tags)); ?>
		</div>
		<?php } ?>

		<?php $categories = elgg_view('output/categories', $vars);
			if ($categories) {
				echo $categories;
			}
		?>

		<div class="elgg-foot">
			<div class="elgg-subtext">
				<?php
					echo elgg_view('output/url', array(
						'href' => $card->getURL(),
						'text' => elgg_echo('workflow:card:number', array($card_guid)),
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

	</div>

	<?php echo elgg_view('page/elements/comments', array(
		'entity' => $card,
		'show_add_form' => false,
	)); ?>

</div>
