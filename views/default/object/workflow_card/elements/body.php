<?php
/**
 * workflow_card body
 *
 * @uses $vars['workflow_card']
 */

$workflow_card = elgg_extract('workflow_card', $vars);

if ($workflow_card->description) {
	$description = "<div class='workflow-card-description'>" . elgg_view_icon('workflow-info') . "</div>";
}

$comments_count = $workflow_card->countComments();
if ($comments_count != 0) {
	$comment = "<div class='workflow-card-comment'>" . elgg_view_icon('workflow-speech-bubble') . $comments_count . "</div>";
}

$duedate = $workflow_card->duedate;
if ($duedate) {
	$duedate = explode('-', $duedate);
	$duedate_timestamp = gmmktime(23, 59, 59, $duedate[1], $duedate[2], $duedate[0]);
	if ( $duedate_timestamp <= time() ) $overdue = '-overdue';
	$due_date_string = "<div class='workflow-card-duedate$overdue'>" . elgg_view_icon('workflow-calendar') . gmdate('M d', $duedate_timestamp) . "</div>";
}

$checklist = count($workflow_card->checklist);
$checklist_checked = count($workflow_card->checklist_checked);
if ($checklist != 0) {
	if ( $checklist_checked >= $checklist ) $complete = '-complete';
	$checklist_string = "<div class='workflow-card-checklist$complete'>" . elgg_view_icon('workflow-checklist') . $checklist_checked . '/' . $checklist . "</div>";
}

echo '<div class="workflow-card-info">' . $description . $comment . $due_date_string . $checklist_string . '</div>';

$assignedto = (array) $workflow_card->assignedto;
if ($assignedto) {
	echo '<div class="workflow-card-assignedto">';
	foreach ( $assignedto as $user_guid) {
		$user = get_entity($user_guid);
		echo elgg_view_entity_icon($user, 'tiny', array('use_hover' => false));
	}
	echo '</div>';
}
