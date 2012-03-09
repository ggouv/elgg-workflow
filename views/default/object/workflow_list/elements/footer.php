<?php
/**
 * workflow_list add card
 *
 * @uses $vars['workflow_list']
 */

$workflow_list = elgg_extract('workflow_list', $vars);
?>

<div class="workflow-list-footer pts pls prs" id="workflow-list-footer-<?php echo $workflow_list->guid; ?>">
	<?php echo elgg_view_form('workflow/list/add_card', array(), $vars); ?>
</div>
