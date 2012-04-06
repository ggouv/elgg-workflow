<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow edit card forms
 *
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use extract()
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$duedate = elgg_extract('duedate', $vars, '');
$assignedto = elgg_extract('assignedto', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$card_guid = elgg_extract('guid', $vars, null);
$user = elgg_get_logged_in_user_guid();

$card = get_entity($card_guid);
global $fb;
$list = get_entity($card->parent_guid);
$group_guid = $list->container_guid;
$group_members = get_group_members($group_guid);
foreach ($group_members as $members) {
	$group_members_guid[] = $members->guid;
}
$fb->info($group_members_guid);

?>

<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>
<div>
	<label><?php echo elgg_echo('workflow:duedate'); ?></label>
	<?php echo elgg_view('input/date', array('name' => 'duedate', 'value' => $duedate)); ?>
</div>
<div>
	<label><?php echo elgg_echo('workflow:assignedto'); ?></label>
	<?php echo elgg_view('input/userpicker', array('name' => 'assignedto', 'value' => $group_members_guid)); ?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>
<?php

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

?>
<div>
	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</div>

<div class="elgg-foot">
	<?php
	
	echo elgg_view('input/hidden', array('name' => 'entity_guid', 'value' => $card_guid));
	
	echo elgg_view('input/submit', array('value' => elgg_echo("save")));
	
	?>
</div>
