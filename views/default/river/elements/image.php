<?php
/**
 * Elgg river image
 *
 * Displayed next to the body of each river item
 *
 * @uses $vars['item']
 $vars['size']
 */
$size = elgg_extract('size', $vars, 'small');

$subject = $vars['item']->getSubjectEntity();

echo elgg_view_entity_icon($subject, $size);
