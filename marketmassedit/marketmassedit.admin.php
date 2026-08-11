<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * Market Mass Edit admin panel – tabs: mainlist (view/search) and massedit (mass editing)
 *
 * Filename: plugins/marketmassedit/marketmassedit.admin.php
 * marketmassedit plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Date: Aug 11Th, 2026
 * @package marketmassedit
 * @version 2.0.0
 * @author webitproff
 * @copyright (c) webitproff 2026 | https://github.com/webitproff/marketmassedit-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

use cot\modules\market\inc\MarketDictionary;
use cot\modules\market\inc\MarketControlService;

require_once cot_langfile('marketmassedit', 'plug');
require_once cot_langfile('market', 'module');

$tab = cot_import('tab', 'G', 'ALP') ?: 'mainlist';
$a   = cot_import('a',   'G', 'ALP');

$t = new XTemplate(cot_tplfile('marketmassedit.admin', 'plug', true));

$t->assign([
    'TAB_MAINLIST_ACTIVE' => $tab === 'mainlist' ? 'active' : '',
    'TAB_MASSEDIT_ACTIVE' => $tab === 'massedit' ? 'active' : '',
    'URL_MAINLIST'        => cot_url('admin', ['m'=>'other','p'=>'marketmassedit','tab'=>'mainlist']),
    'URL_MASSEDIT'        => cot_url('admin', ['m'=>'other','p'=>'marketmassedit','tab'=>'massedit']),
]);

/* ========== Вкладка: Список товаров ========== */
if ($tab === 'mainlist') {
    $perPage = (int) Cot::$cfg['plugin']['marketmassedit']['perpage'] ?: 20;
    list($pg, $d, $durl) = cot_import_pagenav('d', $perPage);

    // Параметры поиска
    $sq = cot_import('sq', 'G', 'TXT');
    $sq = ($sq !== null) ? trim($sq) : '';
    $c  = cot_import('c', 'G', 'TXT');
    $search_in = cot_import('search_in', 'G', 'ALP', 8);
    if (!in_array($search_in, ['title', 'full', 'pcod'])) {
        $search_in = 'title';
    }
    $filter_id = cot_import('filter_id', 'G', 'INT');

    $highlight_words = [];
    if (!empty($sq)) {
        $highlight_words = preg_split('/\s+/', $sq, -1, PREG_SPLIT_NO_EMPTY);
    }

    $urlParams = ['m'=>'other', 'p'=>'marketmassedit', 'tab'=>'mainlist'];
    if (!empty($sq)) $urlParams['sq'] = $sq;
    if (!empty($c))  $urlParams['c']  = $c;
    if ($search_in != 'title') $urlParams['search_in'] = $search_in;
    if (!empty($filter_id)) $urlParams['filter_id'] = $filter_id;

    $sqlwhere = "fieldmrkt_title IS NOT NULL AND fieldmrkt_title != ''";
    $params = [];

    if (!empty($sq)) {
        $sq_escaped = "%$sq%";
        if ($search_in == 'title') {
            $sqlwhere .= " AND fieldmrkt_title LIKE :sq";
        } elseif ($search_in == 'full') {
            $sqlwhere .= " AND (fieldmrkt_title LIKE :sq OR fieldmrkt_text LIKE :sq)";
        } elseif ($search_in == 'pcod') {
            $sqlwhere .= " AND fieldmrkt_pcod LIKE :sq";
        }
        $params['sq'] = $sq_escaped;
    }

    if (!empty($filter_id)) {
        $sqlwhere .= " AND fieldmrkt_id = :fid";
        $params['fid'] = $filter_id;
    }

    if (!empty($c)) {
        $catsub = cot_structure_children('market', $c);
        if (!empty($catsub)) {
            $sqlwhere .= " AND fieldmrkt_cat IN ('" . implode("','", $catsub) . "')";
        }
    }

    $total = Cot::$db->query(
        "SELECT COUNT(*) FROM $db_market WHERE $sqlwhere", $params
    )->fetchColumn();

    $items = Cot::$db->query(
        "SELECT fieldmrkt_id, fieldmrkt_title, fieldmrkt_cat, fieldmrkt_costdflt, fieldmrkt_cost_usd, fieldmrkt_state
         FROM $db_market WHERE $sqlwhere
         ORDER BY fieldmrkt_id DESC
         LIMIT $d, $perPage",
        $params
    )->fetchAll();

    $searchMsg = '';
    if (!empty($sq) || !empty($filter_id)) {
        $totalFound = (int)$total;
        $currentPageCount = count($items);
        $queryDesc = [];
        if (!empty($sq)) $queryDesc[] = '«'.htmlspecialchars($sq).'»';
        if (!empty($filter_id)) $queryDesc[] = 'ID '.$filter_id;
        if ($totalFound > 0) {
            $totalStr = cot_declension($totalFound, Cot::$L['marketmassedit_search_declen']);
            $currentStr = cot_declension($currentPageCount, Cot::$L['marketmassedit_search_declen']);
            $searchMsg = sprintf(Cot::$L['marketmassedit_search_result_msg'], $totalStr, $currentStr, implode(', ', $queryDesc));
        } else {
            $searchMsg = sprintf(Cot::$L['marketmassedit_search_result_none'], implode(', ', $queryDesc));
        }
    }

    $t->assign([
        'SEARCH_ACTION_URL'  => cot_url('admin'),
        'SEARCH_SQ'          => cot_inputbox('text', 'sq', !empty($sq) ? htmlspecialchars($sq) : '', 'class="form-control" autofocus'),
        'SEARCH_CAT_SELECT2' => cot_market_selectcat_select2($c, 'c'),
        'SEARCH_FILTER_ID'   => cot_inputbox('number', 'filter_id', !empty($filter_id) ? $filter_id : '', 'class="form-control" placeholder="ID"'),
        'SEARCH_RESULT_MSG'  => $searchMsg,
        'SEARCH_IN_TITLE_CHECKED' => ($search_in == 'title') ? 'checked="checked"' : '',
        'SEARCH_IN_FULL_CHECKED'  => ($search_in == 'full')  ? 'checked="checked"' : '',
        'SEARCH_IN_PCOD_CHECKED'  => ($search_in == 'pcod')  ? 'checked="checked"' : '',
    ]);

    if (!empty($items)) {
        foreach ($items as $row) {
            $catTitle = Cot::$structure['market'][$row['fieldmrkt_cat']]['title'] ?? $row['fieldmrkt_cat'];
            $itemUrl  = cot_url('market', ['c' => $row['fieldmrkt_cat'], 'id' => $row['fieldmrkt_id']]);
            $t->assign([
                'LIST_ROW_ID'        => $row['fieldmrkt_id'],
                'LIST_ROW_TITLE'     => htmlspecialchars($row['fieldmrkt_title']),
                'LIST_ROW_TITLE_URL' => $itemUrl,
                'LIST_ROW_CAT'       => htmlspecialchars($catTitle),
                'LIST_ROW_COSTDFLT'  => (float)$row['fieldmrkt_costdflt'],
                'LIST_ROW_COST_USD'  => (float)$row['fieldmrkt_cost_usd'],
                'LIST_ROW_STATE'     => cot_market_status($row['fieldmrkt_state']),
            ]);
            $t->parse('MAIN.MAINLIST_ROW');
        }
    } else {
        $t->parse('MAIN.MAINLIST_EMPTY');
    }

    $pagenav = cot_pagenav('admin', $urlParams, $d, $total, $perPage, 'd');
    $t->assign(cot_generatePaginationTags($pagenav));

    $t->assign([
        'HIGHLIGHT_WORDS'  => json_encode($highlight_words, JSON_UNESCAPED_UNICODE),
        'HIGHLIGHT_ACTIVE' => !empty($sq),
        'HIGHLIGHT_SCOPE'  => '#mainlist-items-table',
    ]);
}

/* ========== Вкладка: Массовое редактирование ========== */
if ($tab === 'massedit') {
    // Гарантируем, что массив экстраполей для market существует
    if (!isset($cot_extrafields[$db_market]) || !is_array($cot_extrafields[$db_market])) {
        $cot_extrafields[$db_market] = [];
    }
    $perPage = (int) Cot::$cfg['plugin']['marketmassedit']['perpage'] ?: 20;
    list($pg, $d, $durl) = cot_import_pagenav('d', $perPage);

    $sq = cot_import('sq', 'G', 'TXT');
    $sq = ($sq !== null) ? trim($sq) : '';
    $c  = cot_import('c', 'G', 'TXT');
    $search_in = cot_import('search_in', 'G', 'ALP', 8);
    if (!in_array($search_in, ['title', 'full', 'pcod'])) {
        $search_in = 'title';
    }
    $filter_id = cot_import('filter_id', 'G', 'INT');
    $filter = cot_import('filter', 'G', 'ALP');
    $filter = empty($filter) ? 'all' : $filter;

    $urlParams = ['m'=>'other', 'p'=>'marketmassedit', 'tab'=>'massedit'];
    if (!empty($sq)) $urlParams['sq'] = $sq;
    if (!empty($c))  $urlParams['c']  = $c;
    if ($search_in != 'title') $urlParams['search_in'] = $search_in;
    if (!empty($filter_id)) $urlParams['filter_id'] = $filter_id;
    if ($filter != 'all') $urlParams['filter'] = $filter;

    // Сохранение изменений
    if ($a === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $ids = isset($_POST['ids']) ? array_map('intval', $_POST['ids']) : [];
        $titles     = $_POST['title']     ?? [];
        $aliases    = $_POST['alias']     ?? [];
        $metatitles = $_POST['metatitle'] ?? [];
        $metadescs  = $_POST['metadesc']  ?? [];
        $cats       = $_POST['cat']       ?? [];
        $costdflt   = $_POST['costdflt']  ?? [];
        $cost_usd   = $_POST['cost_usd']  ?? [];
        $states     = $_POST['state']     ?? [];
        $pcods      = $_POST['pcod']      ?? [];
        $datenow    = $_POST['datenow']   ?? [];
        $deletes    = $_POST['delete']    ?? [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $currentData = Cot::$db->query(
            "SELECT * FROM $db_market WHERE fieldmrkt_id IN ($placeholders)", $ids
        )->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);

        $updatedCount = 0;
        $deletedCount = 0;

        foreach ($ids as $id) {
            $id = (int)$id;
            $current = $currentData[$id] ?? null;
            if (!$current) continue;

            if (isset($deletes[$id]) && $deletes[$id] == 1) {
                $marketService = MarketControlService::getInstance();
                if ($marketService->delete($id) !== false) {
                    $deletedCount++;
                }
                continue;
            }

            $update = [];
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_title']) && isset($titles[$id]) && trim($titles[$id]) !== $current['fieldmrkt_title'])
                $update['fieldmrkt_title'] = trim($titles[$id]);
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_alias']) && isset($aliases[$id]) && trim($aliases[$id]) !== $current['fieldmrkt_alias'])
                $update['fieldmrkt_alias'] = trim($aliases[$id]);
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_metatitle']) && isset($metatitles[$id]) && trim($metatitles[$id]) !== $current['fieldmrkt_metatitle'])
                $update['fieldmrkt_metatitle'] = trim($metatitles[$id]);
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_metadesc']) && isset($metadescs[$id]) && trim($metadescs[$id]) !== $current['fieldmrkt_metadesc'])
                $update['fieldmrkt_metadesc'] = trim($metadescs[$id]);
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_cat']) && isset($cats[$id]) && trim($cats[$id]) !== $current['fieldmrkt_cat'])
                $update['fieldmrkt_cat'] = trim($cats[$id]);
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_costdflt']) && isset($costdflt[$id]) && (float)$costdflt[$id] != (float)$current['fieldmrkt_costdflt'])
                $update['fieldmrkt_costdflt'] = (float)$costdflt[$id];
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_cost_usd']) && isset($cost_usd[$id]) && (float)$cost_usd[$id] != (float)$current['fieldmrkt_cost_usd'])
                $update['fieldmrkt_cost_usd'] = (float)$cost_usd[$id];
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_state']) && isset($states[$id]) && (int)$states[$id] !== (int)$current['fieldmrkt_state'])
                $update['fieldmrkt_state'] = (int)$states[$id];
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_pcod']) && isset($pcods[$id]) && trim($pcods[$id]) !== $current['fieldmrkt_pcod'])
                $update['fieldmrkt_pcod'] = trim($pcods[$id]);
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_datenow']) && !empty($datenow[$id])) {
                $update['fieldmrkt_date'] = Cot::$sys['now'];
            }

            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_extrafields']) && !empty($cot_extrafields[$db_market])) {
                foreach ($cot_extrafields[$db_market] as $exfld) {
                    $fname = $exfld['field_name'];
                    if (isset($_POST['extra_'.$fname][$id])) {
                        $newVal = $_POST['extra_'.$fname][$id];
                        $oldVal = $current['fieldmrkt_'.$fname] ?? null;
                        if ($newVal != $oldVal) $update['fieldmrkt_'.$fname] = $newVal;
                    }
                }
            }

            if (!empty($update)) {
                $update['fieldmrkt_updated'] = Cot::$sys['now'];
                Cot::$db->update($db_market, $update, "fieldmrkt_id = $id");
                $updatedCount++;
            }
        }

        // Хук для сохранения данных других плагинов (например, xtradbrowmarket)
        /* === Hook === */
        foreach (cot_getextplugins('marketmassedit.massedit.save') as $pl) {
            include $pl;
        }
        /* ===== */

        $msg = '';
        if ($updatedCount > 0) $msg .= sprintf(Cot::$L['marketmassedit_updated'], $updatedCount) . ' ';
        if ($deletedCount > 0) $msg .= sprintf(Cot::$L['marketmassedit_deleted'], $deletedCount);
        if (!empty($msg)) cot_message($msg);

        $backUrl = cot_url('admin', array_merge($urlParams, ['d'=>$durl]));
        $backUrl = str_replace('&amp;', '&', $backUrl);
        cot_redirect($backUrl);
    }

    // SQL-запрос с учётом фильтра по статусу
    $sqlwhere = "fieldmrkt_title IS NOT NULL AND fieldmrkt_title != ''";
    if ($filter == 'valqueue') {
        $sqlwhere .= ' AND fieldmrkt_state = ' . MarketDictionary::STATE_PENDING;
    } elseif ($filter == 'validated') {
        $sqlwhere .= ' AND fieldmrkt_state = ' . MarketDictionary::STATE_PUBLISHED;
    } elseif ($filter == 'drafts') {
        $sqlwhere .= ' AND fieldmrkt_state = ' . MarketDictionary::STATE_DRAFT;
    } elseif ($filter == 'expired') {
        $sqlwhere .= ' AND fieldmrkt_expire > 0 AND fieldmrkt_expire < ' . (int) Cot::$sys['now'];
    }

    $params = [];

    if (!empty($sq)) {
        $sq_escaped = "%$sq%";
        if ($search_in == 'title') {
            $sqlwhere .= " AND fieldmrkt_title LIKE :sq";
        } elseif ($search_in == 'full') {
            $sqlwhere .= " AND (fieldmrkt_title LIKE :sq OR fieldmrkt_text LIKE :sq)";
        } elseif ($search_in == 'pcod') {
            $sqlwhere .= " AND fieldmrkt_pcod LIKE :sq";
        }
        $params['sq'] = $sq_escaped;
    }
    if (!empty($filter_id)) {
        $sqlwhere .= " AND fieldmrkt_id = :fid";
        $params['fid'] = $filter_id;
    }
    if (!empty($c)) {
        $catsub = cot_structure_children('market', $c);
        if (!empty($catsub)) {
            $sqlwhere .= " AND fieldmrkt_cat IN ('" . implode("','", $catsub) . "')";
        }
    }

    $total = Cot::$db->query("SELECT COUNT(*) FROM $db_market WHERE $sqlwhere", $params)->fetchColumn();

    // Динамический набор полей на основе настроек
    $selectFields = ['fieldmrkt_id', 'fieldmrkt_alias'];
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_title']))     $selectFields[] = 'fieldmrkt_title';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_alias']))     $selectFields[] = 'fieldmrkt_alias';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_metatitle'])) $selectFields[] = 'fieldmrkt_metatitle';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_metadesc']))  $selectFields[] = 'fieldmrkt_metadesc';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_cat']))       $selectFields[] = 'fieldmrkt_cat';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_costdflt']))  $selectFields[] = 'fieldmrkt_costdflt';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_cost_usd']))  $selectFields[] = 'fieldmrkt_cost_usd';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_state']))     $selectFields[] = 'fieldmrkt_state';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_pcod']))      $selectFields[] = 'fieldmrkt_pcod';
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_datenow']) || !empty(Cot::$cfg['plugin']['marketmassedit']['show_updated'])) {
        $selectFields[] = 'fieldmrkt_date';
        $selectFields[] = 'fieldmrkt_updated';
    }
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_extrafields']) && !empty($cot_extrafields[$db_market])) {
        foreach ($cot_extrafields[$db_market] as $exfld) {
            $selectFields[] = 'fieldmrkt_' . $exfld['field_name'];
        }
    }

    $selectFields = array_unique($selectFields);
    $needXtraJoin = false;
    /* === Hook === */
    foreach (cot_getextplugins('marketmassedit.massedit.sql.fields') as $pl) {
        include $pl;
    }
    /* ===== */

    // Разделяем поля: свои ($marketFields) и добавленные другими плагинами ($joinFields)
    $marketFields = [];
    $joinFields = [];
    foreach ($selectFields as $field) {
        if (strpos($field, '.') !== false) {
            $joinFields[] = $field;
        } else {
            $marketFields[] = $field;
        }
    }

    // Защита от отсутствующих колонок – только для полей таблицы $db_market
    $realColumns = Cot::$db->query("SHOW COLUMNS FROM $db_market")->fetchAll(PDO::FETCH_COLUMN);
    $marketFields = array_intersect($marketFields, $realColumns);

    // Объединяем обратно
    $selectFields = array_merge($marketFields, $joinFields);
    $sqlFields = implode(', ', $selectFields);

    if ($needXtraJoin) {
        $items = Cot::$db->query(
            "SELECT $sqlFields FROM $db_market
             LEFT JOIN $needXtraJoin ON $db_market.fieldmrkt_id = $needXtraJoin.itempagid
             WHERE $sqlwhere ORDER BY fieldmrkt_id DESC LIMIT $d, $perPage",
            $params
        )->fetchAll();
    } else {
        $items = Cot::$db->query(
            "SELECT $sqlFields FROM $db_market WHERE $sqlwhere ORDER BY fieldmrkt_id DESC LIMIT $d, $perPage",
            $params
        )->fetchAll();
    }

    $searchMsg = '';
    if (!empty($sq) || !empty($filter_id)) {
        $totalFound = (int)$total;
        $currentPageCount = count($items);
        $queryDesc = [];
        if (!empty($sq)) $queryDesc[] = '«'.htmlspecialchars($sq).'»';
        if (!empty($filter_id)) $queryDesc[] = 'ID '.$filter_id;
        if ($totalFound > 0) {
            $totalStr = cot_declension($totalFound, Cot::$L['marketmassedit_search_declen']);
            $currentStr = cot_declension($currentPageCount, Cot::$L['marketmassedit_search_declen']);
            $searchMsg = sprintf(Cot::$L['marketmassedit_search_result_msg'], $totalStr, $currentStr, implode(', ', $queryDesc));
        } else {
            $searchMsg = sprintf(Cot::$L['marketmassedit_search_result_none'], implode(', ', $queryDesc));
        }
    }

    $t->assign([
        'SEARCH_ACTION_URL'  => cot_url('admin'),
        'FILTER_STATE_SELECT' => cot_selectbox($filter, 'filter',
            ['all', 'valqueue', 'validated', 'drafts'],
            [Cot::$L['All'], Cot::$L['adm_lang_market_valqueue'], Cot::$L['adm_lang_market_validated'], Cot::$L['market_drafts']],
            false),
        'SEARCH_SQ'          => cot_inputbox('text', 'sq', !empty($sq) ? htmlspecialchars($sq) : '', 'class="form-control" autofocus'),
        'SEARCH_CAT_SELECT2' => cot_market_selectcat_select2($c, 'c'),
        'SEARCH_FILTER_ID'   => cot_inputbox('number', 'filter_id', !empty($filter_id) ? $filter_id : '', 'class="form-control" placeholder="ID"'),
        'SEARCH_RESULT_MSG'  => $searchMsg,
        'SEARCH_IN_TITLE_CHECKED' => ($search_in == 'title') ? 'checked="checked"' : '',
        'SEARCH_IN_FULL_CHECKED'  => ($search_in == 'full')  ? 'checked="checked"' : '',
        'SEARCH_IN_PCOD_CHECKED'  => ($search_in == 'pcod')  ? 'checked="checked"' : '',
    ]);

    // Флаги видимости колонок
    $t->assign([
        'SHOW_ID'          => !empty(Cot::$cfg['plugin']['marketmassedit']['show_id']),
        'SHOW_TITLE'       => !empty(Cot::$cfg['plugin']['marketmassedit']['show_title']),
        'SHOW_ALIAS'       => !empty(Cot::$cfg['plugin']['marketmassedit']['show_alias']),
        'SHOW_METATITLE'   => !empty(Cot::$cfg['plugin']['marketmassedit']['show_metatitle']),
        'SHOW_METADESC'    => !empty(Cot::$cfg['plugin']['marketmassedit']['show_metadesc']),
        'SHOW_CAT'         => !empty(Cot::$cfg['plugin']['marketmassedit']['show_cat']),
        'SHOW_COSTDFLT'    => !empty(Cot::$cfg['plugin']['marketmassedit']['show_costdflt']),
        'SHOW_COST_USD'    => !empty(Cot::$cfg['plugin']['marketmassedit']['show_cost_usd']),
        'SHOW_STATE'       => !empty(Cot::$cfg['plugin']['marketmassedit']['show_state']),
        'SHOW_PCOD'        => !empty(Cot::$cfg['plugin']['marketmassedit']['show_pcod']),
        'SHOW_DATENOW'     => !empty(Cot::$cfg['plugin']['marketmassedit']['show_datenow']),
        'SHOW_UPDATED'     => !empty(Cot::$cfg['plugin']['marketmassedit']['show_updated']),
        'SHOW_DELETE'      => !empty(Cot::$cfg['plugin']['marketmassedit']['show_delete']),
        'SHOW_EXTRAFIELDS' => !empty(Cot::$cfg['plugin']['marketmassedit']['show_extrafields']),
    ]);

    // Хук для дополнительных флагов видимости (например, SHOW_XTRA)
    /* === Hook === */
    foreach (cot_getextplugins('marketmassedit.massedit.flags') as $pl) {
        include $pl;
    }
    /* ===== */

    // Заголовки экстраполей
    if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_extrafields']) && !empty($cot_extrafields[$db_market])) {
        foreach ($cot_extrafields[$db_market] as $exfld) {
            $t->assign('EXTRA_HEADER_TITLE', htmlspecialchars(cot_extrafield_title($exfld, 'market_')));
            $t->parse('MAIN.EXTRA_HEADER');
        }
    }
    // Хук для дополнительных заголовков таблицы (например, xtradbrowmarket)
    /* === Hook === */
    foreach (cot_getextplugins('marketmassedit.massedit.headers') as $pl) {
        include $pl;
    }
    /* ===== */

    // Строки таблицы
    if (!empty($items)) {
        foreach ($items as $row) {
            $id = $row['fieldmrkt_id'];
            $itemUrl = cot_url('market', !empty($row['fieldmrkt_alias'])
                ? ['c' => $row['fieldmrkt_cat'], 'al' => $row['fieldmrkt_alias']]
                : ['c' => $row['fieldmrkt_cat'], 'id' => $row['fieldmrkt_id']]
            );

            $assign = [];
            $assign['MANAGE_ID']   = $id;
            $assign['MANAGE_URL']  = $itemUrl;

            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_title']))     $assign['MANAGE_TITLE']     = htmlspecialchars($row['fieldmrkt_title'] ?? '');
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_alias']))     $assign['MANAGE_ALIAS']     = htmlspecialchars($row['fieldmrkt_alias'] ?? '');
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_metatitle'])) $assign['MANAGE_METATITLE'] = htmlspecialchars($row['fieldmrkt_metatitle'] ?? '');
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_metadesc']))  $assign['MANAGE_METADESC']  = htmlspecialchars($row['fieldmrkt_metadesc'] ?? '');
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_cat']))       $assign['MANAGE_CAT']       = cot_selectbox_structure('market', $row['fieldmrkt_cat'], 'cat['.$id.']');
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_costdflt']))  $assign['MANAGE_COSTDFLT']  = (float)$row['fieldmrkt_costdflt'];
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_cost_usd']))  $assign['MANAGE_COST_USD']  = (float)$row['fieldmrkt_cost_usd'];
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_state']))     $assign['MANAGE_STATE']     = cot_selectbox($row['fieldmrkt_state'], 'state['.$id.']', [0,1,2],
                [Cot::$L['market_status_published'], Cot::$L['market_status_pending'], Cot::$L['market_status_draft']], false);
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_pcod']))      $assign['MANAGE_PCOD']      = htmlspecialchars($row['fieldmrkt_pcod'] ?? '');
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_datenow']))   $assign['MANAGE_DATENOW']   = cot_checkbox(0, 'datenow['.$id.']', Cot::$L['Yes'], '', '1');
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_updated']))   $assign['MANAGE_UPDATED']   = cot_date('datetime_medium', $row['fieldmrkt_updated']);
            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_delete']))    $assign['MANAGE_DELETE']    = cot_radiobox(0, 'delete['.$id.']', [1, 0], [Cot::$L['Yes'], Cot::$L['No']]);

            $t->assign($assign);

            // Хук для добавления дополнительных колонок (например, xtradbrowmarket)
            /* === Hook === */
            foreach (cot_getextplugins('marketmassedit.massedit.loop') as $pl) {
                include $pl;
            }
            /* ===== */

            if (!empty(Cot::$cfg['plugin']['marketmassedit']['show_extrafields']) && !empty($cot_extrafields[$db_market])) {
                foreach ($cot_extrafields[$db_market] as $exfld) {
                    $fname = $exfld['field_name'];
                    $value = $row['fieldmrkt_'.$fname] ?? '';
                    $inputName = 'extra_'.$fname.'['.$id.']';
                    $fieldHtml = cot_build_extrafields($inputName, $exfld, $value);
                    $t->assign('EXTRA_COLUMN_HTML', $fieldHtml);
                    $t->parse('MAIN.MANAGE_ROW.EXTRA_COLUMN');
                }
            }
            $t->parse('MAIN.MANAGE_ROW');
        }
    } else {
        $t->parse('MAIN.MANAGE_EMPTY');
    }

    $pagenav = cot_pagenav('admin', $urlParams, $d, $total, $perPage, 'd');
    $t->assign(cot_generatePaginationTags($pagenav));
    $t->assign('MANAGE_FORM_URL', cot_url('admin', ['m'=>'other','p'=>'marketmassedit','tab'=>'massedit','a'=>'update','d'=>$durl]));
}

cot_display_messages($t);
$t->parse('MAIN');
$pluginBody = $t->text('MAIN');