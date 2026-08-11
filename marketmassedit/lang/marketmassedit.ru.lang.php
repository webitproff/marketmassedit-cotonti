<?php
/**
 * Russian language file for plugin Market Mass Edit
 * File: plugins/marketmassedit/lang/marketmassedit.ru.lang.php
 *
 * Все текстовые строки, используемые плагином в интерфейсе Cotonti:
 * - название и описание плагина (info_name, info_desc)
 * - подписи к полям, фильтрам и кнопкам
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

defined('COT_CODE') or die('Wrong URL.');

// ========================
// ИНФОРМАЦИЯ О ПЛАГИНЕ (АДМИНКА) – системные ключи
// ========================
$L['info_name']  = 'Market Mass Edit';
$L['info_desc']  = 'Массовое редактирование товаров: заголовки, алиасы, мета-теги, цены, категории, статусы и экстраполя.';
$L['info_notes'] = 'Позволяет одновременно изменять заголовки, алиасы, мета-теги, цены, категории, статусы и любые дополнительные поля товаров.';

// ========================
// TITLES AND DESCRIPTIONS (same values, pulled by other keys)
// ========================
$L['marketmassedit_title'] = $L['info_name'];
$L['marketmassedit_desc']  = $L['info_desc'];
$L['marketmassedit_name']  = $L['info_name'];

// ========================
// ВКЛАДКИ
// ========================
$L['marketmassedit_tab_mainlist'] = 'Список товаров';
$L['marketmassedit_tab_massedit'] = 'Массовое редактирование';

// ========================
// СООБЩЕНИЯ
// ========================
$L['marketmassedit_updated']           = 'Обновлено товаров: %d';
$L['marketmassedit_nothing_selected']  = 'Не выбраны товары или не отмечены поля для изменения.';
$L['marketmassedit_deleted']           = 'Удалено товаров: %d';

// ========================
// ФИЛЬТРЫ И КНОПКИ
// ========================
$L['marketmassedit_filter_cat']       = 'Категория';
$L['marketmassedit_filter_title']     = 'Название содержит';
$L['marketmassedit_filter_id_from']   = 'ID от';
$L['marketmassedit_filter_id_to']     = 'до';
$L['marketmassedit_filter_submit']    = 'Применить фильтр';
$L['marketmassedit_filter_id']        = 'ID товара';
$L['marketmassedit_save']             = 'Сохранить изменения';
$L['marketmassedit_no_items']         = 'Нет товаров, удовлетворяющих условиям фильтра.';
$L['marketmassedit_filter_state']     = 'Статус';

// ========================
// ПОИСК
// ========================
$L['marketmassedit_search_sq']          = 'Поиск';
$L['marketmassedit_search_cat']         = 'Категория';
$L['marketmassedit_search_btn']         = 'Фильтр';
$L['marketmassedit_search_reset']       = 'Сбросить';
$L['marketmassedit_search_in_title']    = 'Название';
$L['marketmassedit_search_in_full']     = 'Название и описание';
$L['marketmassedit_search_in_pcod']     = 'Артикул';
$L['marketmassedit_search_result_msg']  = 'Найдено %s (показано %s) по запросу: <strong>%s</strong>';
$L['marketmassedit_search_result_none'] = 'По запросу <strong>%s</strong> ничего не найдено';
$L['marketmassedit_search_declen']      = ['позиция', 'позиции', 'позиций'];

// ========================
// НАЗВАНИЯ КОЛОНОК ТАБЛИЦЫ
// ========================
$L['marketmassedit_col_id']        = 'ID';
$L['marketmassedit_col_title']     = 'Заголовок';
$L['marketmassedit_col_alias']     = 'Алиас';
$L['marketmassedit_col_metatitle'] = 'Meta Title';
$L['marketmassedit_col_metadesc']  = 'Meta Description';
$L['marketmassedit_col_cat']       = 'Категория';
$L['marketmassedit_col_costdflt']  = 'Цена (осн.)';
$L['marketmassedit_col_cost_usd']  = 'Цена (USD)';
$L['marketmassedit_col_state']     = 'Статус';
$L['marketmassedit_col_pcod']      = 'Артикул';
$L['marketmassedit_col_datenow']   = 'Обновить дату';
$L['marketmassedit_col_updated']   = 'Изменён';
$L['marketmassedit_col_delete']    = 'Удалить';

// ========================
// НАСТРОЙКИ ПЛАГИНА (АДМИНКА)
// ========================
$L['cfg_perpage']          = 'Товаров в списке/таблице';
$L['cfg_perpage_hint']     = 'Элементы на странице в списке массового редактирования';
$L['cfg_show_id']          = 'Показывать колонку ID';
$L['cfg_show_id_hint']     = 'Установка скрывает или показывает поле ID товара';
$L['cfg_show_title']       = 'Показывать колонку Заголовок';
$L['cfg_show_title_hint']  = 'Установка скрывает или показывает поле Заголовок';
$L['cfg_show_alias']       = 'Показывать колонку Алиас';
$L['cfg_show_alias_hint']  = 'Установка скрывает или показывает поле Алиас';
$L['cfg_show_metatitle']   = 'Показывать колонку Meta Title';
$L['cfg_show_metatitle_hint'] = 'Установка скрывает или показывает поле Meta Title';
$L['cfg_show_metadesc']    = 'Показывать колонку Meta Description';
$L['cfg_show_metadesc_hint'] = 'Установка скрывает или показывает поле Meta Description';
$L['cfg_show_cat']         = 'Показывать колонку Категория';
$L['cfg_show_cat_hint']    = 'Установка скрывает или показывает поле Категория';
$L['cfg_show_costdflt']    = 'Показывать колонку Цена (осн.)';
$L['cfg_show_costdflt_hint'] = 'Установка скрывает или показывает поле Цена (осн.)';
$L['cfg_show_cost_usd']    = 'Показывать колонку Цена (USD)';
$L['cfg_show_cost_usd_hint'] = 'Установка скрывает или показывает поле Цена (USD)';
$L['cfg_show_state']       = 'Показывать колонку Статус';
$L['cfg_show_state_hint']  = 'Установка скрывает или показывает поле Статус';
$L['cfg_show_pcod']        = 'Показывать колонку Артикул';
$L['cfg_show_pcod_hint']   = 'Установка скрывает или показывает поле Артикул';
$L['cfg_show_datenow']     = 'Показывать колонку Обновить дату';
$L['cfg_show_datenow_hint'] = 'Установка скрывает или показывает поле Обновить дату';
$L['cfg_show_updated']     = 'Показывать колонку Изменён';
$L['cfg_show_updated_hint'] = 'Установка скрывает или показывает поле Изменён';
$L['cfg_show_delete']      = 'Показывать колонку Удалить';
$L['cfg_show_delete_hint'] = 'Установка скрывает или показывает поле Удалить';
$L['cfg_show_extrafields']  = 'Показывать экстраполя';
$L['cfg_show_extrafields_hint'] = 'Установка скрывает или показывает дополнительные поля (экстраполя)';