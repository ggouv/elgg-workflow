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
	background-color: #DEDEDE;
	padding: 2px;
	position: relative;
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
	margin-left: 5px;
	max-width: <?php echo elgg_get_plugin_setting('max_width_list', 'elgg-workflow'); ?>;
}
.workflow-list-placeholder:nth-child(2) {
	margin-left: 0px;
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
	top: 4px;
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
.elgg-form-workflow-list-add-card .elgg-input-text {
	border: 1px solid transparent;
	background-color: transparent;
	cursor: pointer;
}
.elgg-form-workflow-list-add-card .elgg-input-text:focus {
	cursor: text;
}
.elgg-form-workflow-list-add-card .elgg-icon-delete {
	margin-top: 2px;
	cursor: pointer;
	display: none;
	float: left;
}
.elgg-form-workflow-list-add-card .elgg-button-submit {
	display: none;
	float: left;
}

/* CSS for card in group view */
.workflow-cards {
	min-height: 28px;
}
.workflow-card {
	float: left;
	background-color: white;
	border: 1px solid #DEDEDE;
	padding: 2px;
	position: relative;
	margin: 2px;
	z-index: 1;
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
	z-index: 0;
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
	color: #AAA;
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
.workflow-card-info span {
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
	background-color: tomato;
	color: #666;
}
.workflow-card-info .elgg-icon-workflow-calendar {
	background-size: 100% auto;
	background-position: 0 -68px;
	margin: -2px 3px 0 0;
}
.workflow-card-info .elgg-icon-workflow-checklist {
	background-position: 0 -406px;
	margin: -2px 3px 0 0;
}
.workflow-card-info .workflow-card-checklist-complete {
	background-color: Palegreen;
}
.workflow-card-assignedto {
	float: right;
	margin-top: 2px;
	height: 25px;
}
#card-forms {
	float: left;
}
#fancybox-content .elgg-comments {
	float: right;
	width: 380px;
	margin: 0 0 0 20px;
}
#fancybox-content .elgg-form-workflow-card-edit-card {
	float: left;
	width: 380px;
	background-color: #EEE;
	padding: 10px;
}
