<?php
/**
 * workflow_list edit settings
 *
 * @uses $vars['workflow_list']
 */

$workflow_list = elgg_extract('workflow_list', $vars);
?>

<div class="workflow-list-edit" id="workflow-list-edit-<?php echo $workflow_list->guid; ?>">
	<?php //@todo  echo elgg_view_form('tasks/tasklist/save', array(), $vars); ?>
</div>
