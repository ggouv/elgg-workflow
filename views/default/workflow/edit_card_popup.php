<?php
/**
 *	Elgg-workflow plugin
 *	@package elgg-workflow
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-workflow
 *
 *	Elgg-workflow edit card popup
 *
 */

elgg_load_library('workflow:utilities');

$card_guid = get_input('card_guid');
$card = get_entity($card_guid);

if ($card) {

	elgg_set_page_owner_guid($card->getContainerGUID());

	// If this is an archived card, we cannot edit.
	$archive = false;
	if ($card->getSubtype() == 'workflow_card_archived') $archive = true;

	echo '<div id="card-forms"><div class="edit-card-form">';
	if ($card->canEdit() && !$archive) {
		$vars = array_merge(workflow_card_prepare_form_vars($card), array('preview' => 'toggle'));
		echo elgg_view_form('workflow/card/edit_card', array(), $vars);
	} else { // Cannot edit. Back to view card.

		echo elgg_view_form(
			'workflow/card/view_card',
			array('class' => 'elgg-form-workflow-card-edit-card'),
			array('card' => $card, 'archive' => $archive)
		);

		$vars = array(
			'entity' => $card,
			'show_add_form' => $archive ? false : true, //@todo make option ?
			'preview' => 'toggle',
		);
	}
	echo '</div><div class="comments-part">' . elgg_view('page/elements/comments', $vars) . '</div>';
	echo '</div>';

if ($card->canEdit() && !$archive) {
?>

<div class="elgg-foot">
	<div class="pam">
		<?php

		echo elgg_view('input/submit', array(
			'value' => elgg_echo("save"),
			'id' => 'workflow-edit-card-submit',
			'data-card_guid' => $card->getGUID(),
			'class' => 'elgg-button elgg-button-submit float mrm'
		));

		echo elgg_view('output/group', array(
			'class' => 'output-group float mlm',
			'group' => array(
				elgg_view('output/url', array(
					'text' => elgg_echo('workflow:action:archive'),
					'class' => 'elgg-button elgg-button-action float prm',
					'href' => elgg_add_action_tokens_to_url('action/workflow/card/archive?card_guid=' . $card->getGUID())
				)),
				elgg_view('output/dropdown_menu', array(
					'class' => 'invert',
					'menu' => array(
						elgg_view('output/url', array(
							'text' => '<span class="elgg-icon elgg-icon-delete"></span>' . elgg_echo("delete"),
							'href' => '#',
							'onclick' => "elgg.workflow.card.remove({$card->getGUID()});"
						))
					)
				))
		)));

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
</div>

<?php } ?>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		if ($.isFunction(elgg.markdown_wiki.view)) {
			elgg.markdown_wiki.view();
			elgg.markdown_wiki.editor();
		}
		elgg.ui.initDatePicker();
		elgg.userpicker.init();

		var $cF = $('#card-info-popup .edit-card-form'),
			cFH = $cF.innerHeight();
		$('#card-info-popup > .elgg-body').bind('scroll', function() {
			if ($(this).scrollTop() > cFH + $('#card-info-popup > .elgg-body > .elgg-foot').height() - $('#card-info-popup .edit-card-form').height()) {
				$cF.addClass('fixed');
			} else {
				$cF.removeClass('fixed');
			}
		});

		$('#workflow-edit-card-submit').click(elgg.workflow.card.change);
		$('#card-info-popup .elgg-comments .elgg-button-submit').click(function() {
			var form = $(this).closest('form'),
				card_guid = form[0].entity_guid.value;

			elgg.workflow.addCommentonCard(card_guid, 1);
		});

		// checklist
		$("#card-forms .card-checklist.sortable").sortable({
			items:						'.elgg-input-checkboxes > li',
			forcePlaceholderSize:		true,
			placeholder:				'elgg-input-checkboxes-placeholder',
			revert:						500
		});

		var plaintext = $('#card-forms .card-checklist .elgg-input-plaintext'),
			addChecklistItem = function() {
				plaintext.parent().find('.elgg-input-checkboxes')
					.append('<li><label><input type="checkbox" class="elgg-input-checkbox" name="checklist_checked[]" value="">&nbsp;' + plaintext.val() + '</label><span class="elgg-icon elgg-icon-delete float-alt"></span></li>');
				plaintext.val('');
			};
		plaintext.focusin(function(){
			if ( $(this).val() == elgg.echo("workflow:checklist:add_item") ) {
				$(this).val('');
			}
			$(this).parent().children('.elgg-button-submit, .elgg-icon-delete').css('display', 'block');
		}).focusout(function(){
			if ( $(this).val() == '' ) {
				$(this).val(elgg.echo("workflow:checklist:add_item"));
				$(this).parent().children('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
			}
		}).keydown(function(e){
			if (e.keyCode == 13) {
				if ($(this).val()) addChecklistItem();
				return false;
			}
		});
		$('#card-forms .elgg-input-checkboxes .elgg-icon-delete').click(function(){
			$(this).closest('li').remove();
		});
		$('#card-forms .card-checklist > .elgg-icon-delete').click(function(){
			plaintext.val(elgg.echo("workflow:checklist:add_item"));
			$(this).parent().children('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
		});
		$('#card-forms .card-checklist .elgg-button-submit').click(function() {
			addChecklistItem();
			plaintext.val(elgg.echo("workflow:checklist:add_item"));
			plaintext.parent().children('.elgg-button-submit, .elgg-icon-delete').css('display', 'none');
		});
	});
</script>

<?php
} else {
	echo elgg_echo('workflow:unknown_card');
}
