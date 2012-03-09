#add-list {
	display: none;
	width: 200px;
}
.workflow-lists-container {
	overflow-x: auto;
}
.workflow-list {
	float: left;
	background-color: #DEDEDE;
	padding: 2px;
	position: relative;
}
.workflow-list:hover {
	background-color: #CCCCCC;
}
.workflow-list:last-child {
	margin-right: 0px;
}
.workflow-list-placeholder {
	float: left;
	border: 2px dashed #dedede;
	margin-right: 5px;
}
.workflow-list-placeholder:last-child {
	margin-right: 0px;
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
.workflow-list .elgg-icon-list {
	margin: 0 6px 4px;
	vertical-align: middle;
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
	background-color: white;
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
