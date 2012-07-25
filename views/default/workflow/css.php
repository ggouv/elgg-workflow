/* CSS for list popup */
.elgg-form-workflow-list-add-list-popup .elgg-input-plaintext {
	height: 28px;
	resize: none;
	overflow: hidden;
}
/* CSS for list in group view */
#add-list {
	position: absolute;
	width: 200px;
}
.workflow-lists-container {
	overflow-x: auto;
}
.workflow-list.elgg-module {
	overflow: visible;
}
.workflow-list {
	float: left;
	border: 2px solid #DEDEDE;
	position: relative;
	width: <?php echo elgg_get_plugin_setting('min_width_list', 'elgg-workflow'); ?>;
	max-width: <?php echo elgg_get_plugin_setting('max_width_list', 'elgg-workflow'); ?>;
}
.workflow-list:hover {
	background-color: #CCCCCC;
}
.workflow-list:first-child {
	margin-left: 0px;
}
.workflow-list-placeholder {
	float: left;
	border: 2px dashed #dedede;
	margin-right: -1px;
	max-width: <?php echo elgg_get_plugin_setting('max_width_list', 'elgg-workflow'); ?>;
}

.workflow-list > .elgg-head {
	background-color: #EEEEEE;
	height: 26px;
	overflow: hidden;
}
.workflow-list > .elgg-head h3 {
	color: #666666;
	float: left;
	padding: 4px 45px 0 0;
}
.workflow-list.elgg-state-draggable .workflow-list-handle {
	cursor: move;
}
.workflow-list .elgg-icon-workflow-list {
	margin: 0 0 4px 4px;
	vertical-align: middle;
	background-position: -5px -540px;
}
.elgg-menu-workflow-list > li {
	display: inline-block;
	height: 18px;
	padding: 2px 2px 0 0;
	position: absolute;
	width: 18px;
}
.elgg-menu-workflow-list > .elgg-menu-item-delete {
	right: 5px;
}
.elgg-menu-workflow-list > .elgg-menu-item-settings {
	right: 25px;
}
.workflow-list-edit {
	background-color: #F9F9F9;
	border-bottom: 2px solid #DEDEDE;
	display: none;
	padding: 2%;
	width: 96%;
}
.workflow-list > .elgg-body {
	background-color: #EEEEEE;
	overflow: hidden;
	width: 100%;
}
.workflow-list > .elgg-foot {
	background-color: #EEEEEE;
	overflow: hidden;
	width: 100%;
}
.workflow-list-footer {
	padding: 5px 2px 0;
}
.elgg-form-workflow-list-add-card .elgg-input-plaintext {
	border: 1px solid transparent;
	background-color: transparent;
	cursor: pointer;
	height: 28px;
	resize: none;
	overflow: hidden;
}
.elgg-form-workflow-list-add-card .elgg-input-plaintext:hover {
	background-color: #DEDEDE;
}
.elgg-form-workflow-list-add-card .elgg-input-plaintext:focus {
	cursor: text;
	background-color: white;
	border: 1px solid #4690D6;
}
.elgg-form-workflow-list-add-card .elgg-icon-delete {
	margin-top: 3px;
	cursor: pointer;
}
.elgg-form-workflow-list-add-card .elgg-button-submit {
	margin: 0 5px 5px 1px;
}

/* CSS for card in board view */
.workflow-cards {
	min-height: 30px;
}
.workflow-card {
	float: left;
	background-color: white;
	border: 1px solid #DEDEDE;
	padding: 2px;
	position: relative;
	margin: 2px;
	-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
}
.workflow-card:hover {
	border: 1px solid #CCCCCC;
}
.workflow-card-none {
	border: 1px solid transparent;
	padding: 0px;
	position: absolute;
	height: 22px;
	background-color: transparent;
	z-index: -1;
}
.workflow-card-none:hover {
	border: 1px solid transparent;
}
.workflow-card.elgg-state-draggable .workflow-card-handle {
	cursor: move;
}
.workflow-card h3 {
	font-size: 1.1em;
}
.workflow-card h3 a {
	color: #0054A7;
}
.workflow-card-placeholder {
	float: left;
	border: 2px dashed #dedede;
	margin: 2px;
}
.workflow-card.ui-sortable-helper {
	-moz-transform: rotate(1deg);
		-webkit-transform: rotate(1deg);
		-o-transform: rotate(1deg);
		-ms-transform: rotate(1deg);
		transform: rotate(1deg);
	-webkit-box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.2);
		-moz-box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.2);
		box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.2); 
}
.workflow-card-info div {
	color: #666;
	float: left;
	padding: 0 3px;
	font-size: 0.9em;
	height: 18px;
	margin: 2px 2px 0 0;
	background-color: #EEEEEE;
	-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
}
.workflow-card-info span, #card-forms .elgg-input-checkboxes span {
	vertical-align: middle;
	background-size: 100% auto;
	height: 12px;
	width: 12px;
	margin: 0 1px 0 0;
}
.workflow-card-info .elgg-icon-workflow-info {
	background-position: 0 -352px;
}
.workflow-card-info .elgg-icon-workflow-speech-bubble {
	background-position: 0 -838px;
	margin: 0 3px 0 0;
}
.workflow-card-info .workflow-card-comment {
	color: #4690D6;
}
.workflow-card-info .workflow-card-duedate-overdue {
	background-color: red;
}
.workflow-card-info .elgg-icon-workflow-calendar {
	background-size: 100% auto;
	background-position: 0 -68px;
	margin: -2px 3px 0 0;
}
.workflow-card-info .elgg-icon-workflow-checklist {
	background-position: 0 -406px;
	margin: -1px 3px 0 0;
}
.workflow-card-info .workflow-card-checklist-complete {
	background-color: #89C23C;
}
.workflow-card-assignedto {
	float: right;
	margin-top: 2px;
	height: 25px;
}
/* CSS for card popup */
#card-forms {
	float: left;
}
#card-forms textarea {
	resize: vertical;
	height: 80px
}
#card-forms .elgg-comments {
	float: right;
	width: 380px;
	margin: 0 0 0 20px;
}
#card-forms .elgg-form-workflow-card-edit-card {
	float: left;
	width: 380px;
	background-color: #EEE;
	padding: 10px;
}
#card-forms .elgg-input-checkboxes {
	background-color: white;
}
#card-forms .elgg-input-checkboxes > li {
	padding: 0 5px;
}
#card-forms .elgg-input-checkboxes > li:hover {
	background-color: #4690D6;
	cursor: move;
}
#card-forms .elgg-input-checkboxes label {
	font-weight: normal;
	font-size: 100%;
	cursor: move;
}
#card-forms .elgg-input-checkboxes .elgg-icon-delete {
	background-position: 0 -203px;
}
#card-forms .elgg-input-checkboxes .elgg-icon-delete:hover {
	background-position: 0 -190px;
}
#card-forms .card-checklist .elgg-input-plaintext {
	border: 1px solid transparent;
	background-color: transparent;
	cursor: pointer;
	height: 28px;
	resize: none;
	overflow: hidden;
}
#card-forms .card-checklist .elgg-input-plaintext:hover {
	background-color: #DEDEDE;
}
#card-forms .card-checklist .elgg-input-plaintext:focus {
	cursor: text;
	background-color: white;
	border: 1px solid #4690D6;
}
#card-forms .card-checklist .elgg-icon-delete {
	margin-top: 3px;
	cursor: pointer;
}
#card-forms .card-checklist .elgg-button-submit {
	margin: 0 5px 15px 1px;
}
#card-forms .duedate {
	clear: both;
}
#card-forms .elgg-foot .elgg-subtext {
	float: right;
	text-align: right;
}
