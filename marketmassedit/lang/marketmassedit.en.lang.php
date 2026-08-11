<?php
/**
 * English language file for plugin Market Mass Edit
 * File: plugins/marketmassedit/lang/marketmassedit.en.lang.php
 *
 * All text strings used by the plugin in the Cotonti interface.
 *
 * marketmassedit plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * ReadMeMore:       https://abuyfile.com/ru/market/cotonti/plugs/marketmassedit
 * Support:          https://abuyfile.com/forums/cotonti/custom/plugs/
 * Source:           https://github.com/webitproff/marketmassedit-cotonti
 *
 * Date: Aug 11Th, 2026
 * @package marketmassedit
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/marketmassedit-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

// ========================
// PLUGIN INFO (ADMIN)
// ========================
$L['info_name']  = 'Market Mass Edit';
$L['info_desc']  = 'Mass editing of market items: titles, aliases, meta tags, prices, categories, statuses and extra fields.';
$L['info_notes'] = 'Allows simultaneous editing of titles, aliases, meta tags, prices, categories, statuses and any extra fields.';

// ========================
// TITLES AND DESCRIPTIONS (same values, pulled by other keys)
// ========================
$L['marketmassedit_title'] = $L['info_name'];
$L['marketmassedit_desc']  = $L['info_desc'];
$L['marketmassedit_name']  = $L['info_name'];


// ========================
// TABS
// ========================
$L['marketmassedit_tab_mainlist'] = 'Item list';
$L['marketmassedit_tab_massedit'] = 'Mass editing';

// ========================
// MESSAGES
// ========================
$L['marketmassedit_updated']           = 'Items updated: %d';
$L['marketmassedit_nothing_selected']  = 'No items selected or no fields marked for change.';
$L['marketmassedit_deleted']           = 'Items deleted: %d';

// ========================
// FILTERS AND BUTTONS
// ========================
$L['marketmassedit_filter_cat']       = 'Category';
$L['marketmassedit_filter_title']     = 'Title contains';
$L['marketmassedit_filter_id_from']   = 'ID from';
$L['marketmassedit_filter_id_to']     = 'to';
$L['marketmassedit_filter_submit']    = 'Apply filter';
$L['marketmassedit_filter_id']        = 'Item ID';
$L['marketmassedit_save']             = 'Save changes';
$L['marketmassedit_no_items']         = 'No items match the filter criteria.';
$L['marketmassedit_filter_state']     = 'Status';

// ========================
// SEARCH
// ========================
$L['marketmassedit_search_sq']          = 'Search';
$L['marketmassedit_search_cat']         = 'Category';
$L['marketmassedit_search_btn']         = 'Filter';
$L['marketmassedit_search_reset']       = 'Reset';
$L['marketmassedit_search_in_title']    = 'Title';
$L['marketmassedit_search_in_full']     = 'Title and description';
$L['marketmassedit_search_in_pcod']     = 'Product code';
$L['marketmassedit_search_result_msg']  = 'Found %s (showing %s) for: <strong>%s</strong>';
$L['marketmassedit_search_result_none'] = 'Nothing found for <strong>%s</strong>';
$L['marketmassedit_search_declen']      = ['item', 'items', 'items'];

// ========================
// TABLE COLUMN HEADINGS
// ========================
$L['marketmassedit_col_id']        = 'ID';
$L['marketmassedit_col_title']     = 'Title';
$L['marketmassedit_col_alias']     = 'Alias';
$L['marketmassedit_col_metatitle'] = 'Meta Title';
$L['marketmassedit_col_metadesc']  = 'Meta Description';
$L['marketmassedit_col_cat']       = 'Category';
$L['marketmassedit_col_costdflt']  = 'Price (base)';
$L['marketmassedit_col_cost_usd']  = 'Price (USD)';
$L['marketmassedit_col_state']     = 'Status';
$L['marketmassedit_col_pcod']      = 'Product Code';
$L['marketmassedit_col_datenow']   = 'Update date';
$L['marketmassedit_col_updated']   = 'Modified';
$L['marketmassedit_col_delete']    = 'Delete';

// ========================
// PLUGIN SETTINGS (ADMIN)
// ========================
$L['cfg_perpage']              = 'Items per page/list';
$L['cfg_perpage_hint']         = 'Number of items per page in mass edit list';
$L['cfg_show_id']              = 'Show ID column';
$L['cfg_show_id_hint']         = 'Toggles visibility of the item ID column';
$L['cfg_show_title']           = 'Show Title column';
$L['cfg_show_title_hint']      = 'Toggles visibility of the Title column';
$L['cfg_show_alias']           = 'Show Alias column';
$L['cfg_show_alias_hint']      = 'Toggles visibility of the Alias column';
$L['cfg_show_metatitle']       = 'Show Meta Title column';
$L['cfg_show_metatitle_hint']  = 'Toggles visibility of the Meta Title column';
$L['cfg_show_metadesc']        = 'Show Meta Description column';
$L['cfg_show_metadesc_hint']   = 'Toggles visibility of the Meta Description column';
$L['cfg_show_cat']             = 'Show Category column';
$L['cfg_show_cat_hint']        = 'Toggles visibility of the Category column';
$L['cfg_show_costdflt']        = 'Show Price (base) column';
$L['cfg_show_costdflt_hint']   = 'Toggles visibility of the base Price column';
$L['cfg_show_cost_usd']        = 'Show Price (USD) column';
$L['cfg_show_cost_usd_hint']   = 'Toggles visibility of the USD Price column';
$L['cfg_show_state']           = 'Show Status column';
$L['cfg_show_state_hint']      = 'Toggles visibility of the Status column';
$L['cfg_show_pcod']            = 'Show Product Code column';
$L['cfg_show_pcod_hint']       = 'Toggles visibility of the Product Code column';
$L['cfg_show_datenow']         = 'Show Update Date column';
$L['cfg_show_datenow_hint']    = 'Toggles visibility of the Update Date column';
$L['cfg_show_updated']         = 'Show Modified column';
$L['cfg_show_updated_hint']    = 'Toggles visibility of the Modified column';
$L['cfg_show_delete']          = 'Show Delete column';
$L['cfg_show_delete_hint']     = 'Toggles visibility of the Delete column';
$L['cfg_show_extrafields']     = 'Show extra fields';
$L['cfg_show_extrafields_hint'] = 'Toggles visibility of extra fields columns';