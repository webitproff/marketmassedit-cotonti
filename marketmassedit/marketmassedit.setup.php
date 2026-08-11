<?php
/* ====================
[BEGIN_COT_EXT]
Code=marketmassedit
Name=Market Mass Edit
Category=administration
Description=Mass editing of market items (title, alias, meta, category, price, state, extrafields)
Version=2.0.0
Date=2026-08-11
Author=webitproff
Copyright=(c) webitproff 2026
Notes=Requires market module
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345
Requires_modules=market
Recommends_plugins=xtradbrowmarket
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
perpage=01:string::20:Items per page in mass edit list
show_id=02:radio::1:Show ID column
show_title=03:radio::1:Show Title column
show_alias=04:radio::1:Show Alias column
show_metatitle=05:radio::1:Show Meta Title column
show_metadesc=06:radio::1:Show Meta Description column
show_cat=07:radio::1:Show Category column
show_costdflt=08:radio::1:Show Price (base) column
show_cost_usd=09:radio::1:Show Price (USD) column
show_state=10:radio::1:Show Status column
show_pcod=11:radio::1:Show Product Code column
show_datenow=12:radio::1:Show Update Date checkbox
show_updated=13:radio::1:Show Last Modified column
show_delete=14:radio::1:Show Delete column
show_extrafields=15:radio::1:Show Extra fields columns
[END_COT_EXT_CONFIG]
==================== */

/**
 * Market Mass Edit setup & config file
 * File: plugins/marketmassedit/marketmassedit.setup.php
 *
 * marketmassedit plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Date: Aug 11Th, 2026
 * @package marketmassedit
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/marketmassedit-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

