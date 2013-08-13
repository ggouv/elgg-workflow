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

if (!$card) {
	access_show_hidden_entities(true);
	$card = get_entity($card_guid);

	if ($card) { // this is an archived card. Cannot edit.
		echo elgg_view('workflow/view_card_popup', array('card' => $card, 'archive' => true));
	} else {
		echo elgg_echo('workflow:unknown_card');
	}

} else {

	elgg_set_page_owner_guid($card->getContainerGUID());

	echo '<div id="card-forms">';
	if ($card->canEdit()) {
		$vars = array_merge(workflow_card_prepare_form_vars($card), array('preview' => 'toggle'));
		echo elgg_view_form('workflow/card/edit_card', array(), $vars);
	} else { // Cannot edit. Back to view card.
		echo elgg_view_form('workflow/card/view_card', array('class' => 'elgg-form-workflow-card-edit-card'), array('card' => $card));

		$vars = array(
			'entity' => $card,
			'show_add_form' => $archive ? false : true, //@todo make option ?
			'preview' => 'toggle',
		);
	}
	echo '<div class="comments-part">' . elgg_view('page/elements/comments', $vars) . '</div>';
	echo '</div>';

?>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		if ($.isFunction(elgg.markdown_wiki.view.init)) {
			elgg.markdown_wiki.view.init();
			elgg.markdown_wiki.edit.init();
		}
		elgg.ui.initDatePicker();
		elgg.userpicker.init();

		$('#card-info-popup .elgg-foot .elgg-button-submit').click(elgg.workflow.card.popupForms);
		$('#card-info-popup .elgg-foot .elgg-button-delete').click(elgg.workflow.card.remove);

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
}
