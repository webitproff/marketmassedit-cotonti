<!-- BEGIN: MAIN -->
<div class="container-fluid py-4">
    <h2>{PHP.L.marketmassedit_title}</h2>
    {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
	
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {TAB_MAINLIST_ACTIVE}" href="{URL_MAINLIST}">{PHP.L.marketmassedit_tab_mainlist}</a>
		</li>
        <li class="nav-item">
            <a class="nav-link {TAB_MASSEDIT_ACTIVE}" href="{URL_MASSEDIT}">{PHP.L.marketmassedit_tab_massedit}</a>
		</li>
	</ul>
	
    <!-- ВКЛАДКА СПИСОК ТОВАРОВ -->
	<!-- IF {PHP.tab} == 'mainlist' -->
	<div class="card filter-section p-3 mb-4" style="border: 5px var(--bs-dark-border-subtle) solid">
		<form method="get" action="{SEARCH_ACTION_URL}" class="mb-3">
			<!-- скрытые поля, чтобы всегда оставаться в плагине -->
			<input type="hidden" name="m" value="other">
			<input type="hidden" name="p" value="marketmassedit">
			<input type="hidden" name="tab" value="mainlist">
			
			<div class="row g-2 align-items-end">
				<div class="col-12 col-lg-3 d-flex flex-column h-100">
					<label class="form-label">{PHP.L.marketmassedit_search_sq}</label>
					<div class="flex-grow-1">{SEARCH_SQ}</div>
				</div>
				<div class="col-12 col-lg-2 d-flex flex-column h-100">
					<label class="form-label">{PHP.L.marketmassedit_filter_id}</label>
					<div class="flex-grow-1">{SEARCH_FILTER_ID}</div>
				</div>
				<div class="col-12 col-lg-3 d-flex flex-column h-100">
					<label class="form-label">{PHP.L.marketmassedit_search_cat}</label>
					<div class="flex-grow-1 filterSelect">{SEARCH_CAT_SELECT2}</div>
				</div>
				<div class="col-12">
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="search_in" id="search_in_title" value="title" {SEARCH_IN_TITLE_CHECKED}>
						<label class="form-check-label" for="search_in_title">{PHP.L.marketmassedit_search_in_title}</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="search_in" id="search_in_full" value="full" {SEARCH_IN_FULL_CHECKED}>
						<label class="form-check-label" for="search_in_full">{PHP.L.marketmassedit_search_in_full}</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="search_in" id="search_in_pcod" value="pcod" {SEARCH_IN_PCOD_CHECKED}>
						<label class="form-check-label" for="search_in_pcod">{PHP.L.marketmassedit_search_in_pcod}</label>
					</div>
				</div>
				<div class="col-12 col-lg-3 d-flex flex-column h-100">
					<button type="submit" class="btn btn-outline-primary w-100 mt-auto">
						<i class="fa-solid fa-filter me-1"></i>{PHP.L.marketmassedit_search_btn}
					</button>
				</div>
				<div class="col-12 col-lg-3 d-flex flex-column h-100">
					<a class="btn btn-outline-danger w-100 mt-auto" href="{URL_MAINLIST}">
						<i class="fa-solid fa-broom me-1"></i>{PHP.L.marketmassedit_search_reset}
					</a>
				</div>
			</div>
		</form>
		<!-- IF {SEARCH_RESULT_MSG} -->
		<div class="alert alert-info" role="alert">{SEARCH_RESULT_MSG}</div>
		<!-- ENDIF -->
	</div>
	
	
    <div class="table-responsive" id="mainlist-items-table">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width:60px">{PHP.L.marketmassedit_col_id}</th>
                    <th>{PHP.L.marketmassedit_col_title}</th>
                    <th>{PHP.L.marketmassedit_col_cat}</th>
                    <th>{PHP.L.marketmassedit_col_costdflt}</th>
                    <th>{PHP.L.marketmassedit_col_cost_usd}</th>
                    <th>{PHP.L.marketmassedit_col_state}</th>
				</tr>
			</thead>
            <tbody>
                <!-- BEGIN: MAINLIST_ROW -->
                <tr>
                    <td>{LIST_ROW_ID}</td>
                    <td><a href="{LIST_ROW_TITLE_URL}" target="_blank">{LIST_ROW_TITLE}</a></td>
                    <td>{LIST_ROW_CAT}</td>
                    <td>{LIST_ROW_COSTDFLT}</td>
                    <td>{LIST_ROW_COST_USD}</td>
                    <td>{LIST_ROW_STATE}</td>
				</tr>
                <!-- END: MAINLIST_ROW -->
                <!-- BEGIN: MAINLIST_EMPTY -->
                <tr><td colspan="6" class="text-center">{PHP.L.marketmassedit_no_items}</td></tr>
                <!-- END: MAINLIST_EMPTY -->
			</tbody>
		</table>
	</div>
	
    <!-- IF {PAGINATION} -->
    <nav class="mt-3">
        <div class="text-center mb-2">{PHP.L.Total}: {TOTAL_ENTRIES}, {PHP.L.Onpage}: {ENTRIES_ON_CURRENT_PAGE}</div>
        <ul class="pagination justify-content-center">{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}</ul>
	</nav>
    <!-- ENDIF -->
	
    <!-- IF {HIGHLIGHT_ACTIVE} -->
    <style>
        .search-highlight {
		font-weight: bold; letter-spacing: 1px; padding: 2px;
		color: #000 !important; background-color: #ffc107 !important; border-radius: 5px;
        }
	</style>
    <script>
        (function() {
            var words = {HIGHLIGHT_WORDS};
            var scope = '{HIGHLIGHT_SCOPE}';
            if (words && Array.isArray(words) && words.length && scope) {
                var escapedWords = words.map(function(w) {
                    return (typeof w === 'string') ? w.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') : '';
				}).filter(function(w) { return w.length > 0; });
                if (escapedWords.length === 0) return;
                var regex = new RegExp(escapedWords.join('|'), 'gi');
                function highlightText(node) {
                    if (node.nodeType === 3) {
                        var val = node.nodeValue;
                        var match = val.match(regex);
                        if (match) {
                            var span = document.createElement('span');
                            span.innerHTML = val.replace(regex, function(matched) {
                                return '<mark class="search-highlight">' + matched + '</mark>';
							});
                            node.parentNode.replaceChild(span, node);
						}
						} else if (node.nodeType === 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
                        for (var i = 0; i < node.childNodes.length; i++) {
                            highlightText(node.childNodes[i]);
						}
					}
				}
                var container = document.querySelector(scope);
                if (container) highlightText(container);
			}
		})();
	</script>
    <!-- ENDIF -->
    <!-- ENDIF -->
	
	
	<!-- ВКЛАДКА МАССОВОЕ РЕДАКТИРОВАНИЕ -->
	<!-- IF {PHP.tab} == 'massedit' -->
	<div class="card filter-section p-3 mb-4" style="border: 5px var(--bs-dark-border-subtle) solid">
		<form method="get" action="{SEARCH_ACTION_URL}" class="mb-3">
			<input type="hidden" name="m" value="other">
			<input type="hidden" name="p" value="marketmassedit">
			<input type="hidden" name="tab" value="massedit">
			<div class="row g-2 align-items-end">
				<div class="col-12 col-lg-3"><label class="form-label">{PHP.L.marketmassedit_search_sq}</label>{SEARCH_SQ}</div>
				<div class="col-12 col-lg-2"><label class="form-label">{PHP.L.marketmassedit_filter_id}</label>{SEARCH_FILTER_ID}</div>
				<div class="col-12 col-lg-3"><label class="form-label">{PHP.L.marketmassedit_search_cat}</label><div class="filterSelect">{SEARCH_CAT_SELECT2}</div></div>
				<div class="col-12">
					<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="search_in" value="title" {SEARCH_IN_TITLE_CHECKED}> <label>{PHP.L.marketmassedit_search_in_title}</label></div>
					<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="search_in" value="full" {SEARCH_IN_FULL_CHECKED}> <label>{PHP.L.marketmassedit_search_in_full}</label></div>
					<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="search_in" value="pcod" {SEARCH_IN_PCOD_CHECKED}> <label>{PHP.L.marketmassedit_search_in_pcod}</label></div>
				</div>
				<div class="col-12 col-lg-2 d-flex flex-column h-100">
					<label class="form-label">{PHP.L.marketmassedit_filter_state}</label>
					<div class="flex-grow-1">{FILTER_STATE_SELECT}</div>
				</div>
				<div class="col-12 col-lg-3"><button type="submit" class="btn btn-outline-primary w-100"><i class="fa-solid fa-filter me-1"></i>{PHP.L.marketmassedit_search_btn}</button></div>
				<div class="col-12 col-lg-3"><a class="btn btn-outline-danger w-100" href="{URL_MASSEDIT}"><i class="fa-solid fa-broom me-1"></i>{PHP.L.marketmassedit_search_reset}</a></div>
			</div>
		</form>
		<!-- IF {SEARCH_RESULT_MSG} --><div class="alert alert-info">{SEARCH_RESULT_MSG}</div><!-- ENDIF -->
	</div>
	
	<form method="post" action="{MANAGE_FORM_URL}">
		<div class="table-responsive">
			<table class="table table-bordered align-middle">
				<thead>
					<tr>
						<!-- IF {SHOW_ID} --><th>{PHP.L.marketmassedit_col_id}</th><!-- ENDIF -->
						<!-- IF {SHOW_TITLE} --><th class="table-success">{PHP.L.marketmassedit_col_title}</th><!-- ENDIF -->
						<!-- IF {SHOW_ALIAS} --><th>{PHP.L.marketmassedit_col_alias}</th><!-- ENDIF -->
						<!-- IF {SHOW_METATITLE} --><th>{PHP.L.marketmassedit_col_metatitle}</th><!-- ENDIF -->
						<!-- IF {SHOW_METADESC} --><th>{PHP.L.marketmassedit_col_metadesc}</th><!-- ENDIF -->
						<!-- IF {SHOW_CAT} --><th>{PHP.L.marketmassedit_col_cat}</th><!-- ENDIF -->
						<!-- IF {SHOW_COSTDFLT} --><th>{PHP.L.marketmassedit_col_costdflt}</th><!-- ENDIF -->
						<!-- IF {SHOW_COST_USD} --><th>{PHP.L.marketmassedit_col_cost_usd}</th><!-- ENDIF -->
						<!-- IF {SHOW_STATE} --><th>{PHP.L.marketmassedit_col_state}</th><!-- ENDIF -->
						<!-- IF {SHOW_PCOD} --><th>{PHP.L.marketmassedit_col_pcod}</th><!-- ENDIF -->
						<!-- IF {SHOW_DATENOW} --><th>{PHP.L.marketmassedit_col_datenow}</th><!-- ENDIF -->
						<!-- IF {SHOW_UPDATED} --><th>{PHP.L.marketmassedit_col_updated}</th><!-- ENDIF -->
						<!-- IF {SHOW_DELETE} --><th>{PHP.L.marketmassedit_col_delete}</th><!-- ENDIF -->
						<!-- IF {SHOW_EXTRAFIELDS} -->
                        <!-- BEGIN: EXTRA_HEADER --><th class="table-info">{EXTRA_HEADER_TITLE}</th><!-- END: EXTRA_HEADER -->
						<!-- ENDIF -->
						<!-- IF {PHP|cot_plugin_active('xtradbrowmarket')} -->
						<!-- IF {SHOW_XTRA} -->
						<!-- BEGIN: XTRA_HEADER --><th class="table-warning">{XTRA_HEADER_TITLE}</th><!-- END: XTRA_HEADER -->
						<!-- ENDIF -->
						<!-- ENDIF -->
					</tr>
				</thead>
				<tbody>
					<!-- BEGIN: MANAGE_ROW -->
					<tr>
						<!-- IF {SHOW_ID} --><td><a href="{MANAGE_URL}" target="_blank">{MANAGE_ID}</a><input type="hidden" name="ids[]" value="{MANAGE_ID}"></td><!-- ENDIF -->
						<!-- IF {SHOW_TITLE} --><td class="table-success"><input type="text" name="title[{MANAGE_ID}]" value="{MANAGE_TITLE}" class="form-control"></td><!-- ENDIF -->
						<!-- IF {SHOW_ALIAS} --><td><input type="text" name="alias[{MANAGE_ID}]" value="{MANAGE_ALIAS}" class="form-control"></td><!-- ENDIF -->
						<!-- IF {SHOW_METATITLE} --><td><input type="text" name="metatitle[{MANAGE_ID}]" value="{MANAGE_METATITLE}" class="form-control"></td><!-- ENDIF -->
						<!-- IF {SHOW_METADESC} --><td><input type="text" name="metadesc[{MANAGE_ID}]" value="{MANAGE_METADESC}" class="form-control"></td><!-- ENDIF -->
						<!-- IF {SHOW_CAT} --><td>{MANAGE_CAT}</td><!-- ENDIF -->
						<!-- IF {SHOW_COSTDFLT} --><td><input type="text" name="costdflt[{MANAGE_ID}]" value="{MANAGE_COSTDFLT}" class="form-control" style="width:100px"></td><!-- ENDIF -->
						<!-- IF {SHOW_COST_USD} --><td><input type="text" name="cost_usd[{MANAGE_ID}]" value="{MANAGE_COST_USD}" class="form-control" style="width:100px"></td><!-- ENDIF -->
						<!-- IF {SHOW_STATE} --><td>{MANAGE_STATE}</td><!-- ENDIF -->
						<!-- IF {SHOW_PCOD} --><td><input type="text" name="pcod[{MANAGE_ID}]" value="{MANAGE_PCOD}" class="form-control"></td><!-- ENDIF -->
						<!-- IF {SHOW_DATENOW} --><td class="text-center">{MANAGE_DATENOW}</td><!-- ENDIF -->
						<!-- IF {SHOW_UPDATED} --><td class="text-nowrap small">{MANAGE_UPDATED}</td><!-- ENDIF -->
						<!-- IF {SHOW_DELETE} --><td>{MANAGE_DELETE}</td><!-- ENDIF -->
						<!-- IF {SHOW_EXTRAFIELDS} -->
                        <!-- BEGIN: EXTRA_COLUMN --><td class="table-info">{EXTRA_COLUMN_HTML}</td><!-- END: EXTRA_COLUMN -->
						<!-- ENDIF -->
						<!-- IF {PHP|cot_plugin_active('xtradbrowmarket')} -->
						<!-- IF {SHOW_XTRA} -->
						<!-- BEGIN: XTRA_COLUMN --><td class="table-warning">{XTRA_COLUMN_HTML}</td><!-- END: XTRA_COLUMN -->
						<!-- ENDIF -->
						<!-- ENDIF -->
					</tr>
					<!-- END: MANAGE_ROW -->
					<!-- BEGIN: MANAGE_EMPTY -->
					<tr><td colspan="20" class="text-center">{PHP.L.marketmassedit_no_items}</td></tr>
					<!-- END: MANAGE_EMPTY -->
				</tbody>
			</table>
		</div>
		<!-- IF {PAGINATION} --><nav class="mt-3"><div class="text-center mb-2">{PHP.L.Total}: {TOTAL_ENTRIES}, {PHP.L.Onpage}: {ENTRIES_ON_CURRENT_PAGE}</div><ul class="pagination justify-content-center">{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}</ul></nav><!-- ENDIF -->
		<button type="submit" class="btn btn-success">{PHP.L.marketmassedit_save}</button>
	</form>
	<!-- ENDIF -->
</div>
<!-- END: MAIN -->