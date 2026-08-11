<?php
/**
 * Market Mass Edit 
 *
 * Filename: plugins/marketmassedit/inc/marketmassedit.functions.php
 *
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

require_once cot_incfile('market', 'module');

/**
 * Получить список экстраполей для товаров с их типами и настройками.
 * @return array
 */
function marketmassedit_get_extrafields()
{
    global $cot_extrafields, $db_market;
    $fields = [];
    if (!empty($cot_extrafields[$db_market])) {
        foreach ($cot_extrafields[$db_market] as $exfld) {
            $fields[] = [
                'name' => $exfld['field_name'],
                'title' => cot_extrafield_title($exfld, 'market_'),
                'type' => $exfld['field_type'],
                'params' => $exfld['field_params'] ?? '',
                'default' => $exfld['field_default'] ?? '',
            ];
        }
    }
    return $fields;
}

/**
 * Применить массовые изменения к выбранным товарам.
 * @param array $ids Массив ID товаров
 * @param array $changes Ассоциативный массив полей и их новых значений (без префикса fieldmrkt_)
 * @return int Количество обновлённых записей
 */
function marketmassedit_apply_changes($ids, $changes)
{
    global $db, $db_market;
    if (empty($ids) || empty($changes)) {
        return 0;
    }
    $ids = array_map('intval', $ids);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sets = [];
    $params = [];
    foreach ($changes as $field => $value) {
        $sets[] = "`fieldmrkt_$field` = ?";
        $params[] = $value;
    }
    $params = array_merge($params, $ids);
    $sql = "UPDATE $db_market SET " . implode(', ', $sets) . " WHERE fieldmrkt_id IN ($placeholders)";
    return $db->query($sql, $params)->rowCount();
}