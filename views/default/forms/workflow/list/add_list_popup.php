<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow add list popup form
 *
 */
?>

<div class='mbs'>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/plaintext', array('name' => 'list_title', 'value' => elgg_echo("workflow:list:add_list"))); ?>
</div>

<?php echo elgg_view('input/hidden', array(
	'name' => 'board_guid',
	'value' => (int)get_input('board_guid')
)); ?>

<?php echo elgg_view('input/submit', array(
	'value' => elgg_echo("save"),
	'id' => 'workflow-list-submit',
)); ?>
