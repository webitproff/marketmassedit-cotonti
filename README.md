
# Market Mass Edit — mass editing for the Market module

**Plugin for Cotonti v1.+**  
Provides a convenient interface for quick viewing and mass editing of market items.  
Allows changing titles, aliases, meta tags, categories, prices, statuses, product codes, dates, and any extra fields for multiple items simultaneously.


<img width="1536" height="1024" alt="Market Mass Edit — Plugin for Cotonti mass editing product ittems for the Market module" src="https://github.com/user-attachments/assets/6518bcbe-c678-409a-bcf4-b4190d28bffa" />


---

## Table of Contents

- [History and problem solved](#history-and-problem-solved)
- [Features](#features)
- [Requirements](#requirements)
- [Plugin structure](#plugin-structure)
- [Installation](#installation)
- [Plugin configuration in admin panel](#plugin-configuration-in-admin-panel)
- [Usage](#usage)
- [Administration panel (two tabs)](#administration-panel-two-tabs)
- [Detailed description of each file](#detailed-description-of-each-file)
- [Example of use](#example-of-use)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Links](#links)

---

## History and problem solved

Managing a large product catalog in Cotonti often requires frequent updates of meta tags, prices, statuses, and other fields for many items at once. The built‑in editing tools allow changing only one item at a time, which slows down the administrator's work.

**Market Mass Edit** solves this problem: it displays all items in a single table with filtering and search capabilities, and then allows mass editing. The interface is customisable — you can hide unnecessary columns and focus only on the fields you need to update.

The plugin operates strictly through the standard `market` module API, preserving data integrity and access permission checks.

---

## Features

- **Two administration tabs:**
  - «Item list» — browse items with search and filtering.
  - «Mass editing» — a table with the ability to simultaneously modify fields.
- **Powerful search and filtering:**
  - Search by title, text, product code.
  - Filter by exact item ID.
  - Category selection via a dropdown with Select2 support (including nesting).
  - Filter by status (all / pending / published / drafts).
- **Flexible configuration of displayed columns** — the administrator chooses which fields to show in the editing table:
  - ID, Title, Alias, Meta Title, Meta Description, Category, Price (base), Price (USD), Status, Product Code, Update Date, Modified, Delete.
  - A separate option enables/disables extra fields.
- **Mass editing** — changes are applied only to fields whose values have actually been modified (comparison with current data), preventing false updates.
- **Mass deletion** — ability to mark items for deletion directly from the mass editing table.
- **Pagination** — on both tabs, with filter parameters preserved when navigating.
- **Plugin settings** — number of items per page.
- **Full localization** — Russian and English languages out of the box.

---

## Requirements

- **Cotonti v1.+** (current branch)
- **Module `market`** (must be installed and active)
- **PHP 8.5+**
- **MySQL 8.4+**
- Administrator rights to access the plugin control panel.

---

## Plugin structure

```
marketmassedit/
├── marketmassedit.setup.php           # Plugin registration and settings description
├── marketmassedit.admin.php           # Administration panel (tools hook)
├── lang/
│   ├── marketmassedit.ru.lang.php     # Russian language file
│   └── marketmassedit.en.lang.php     # English language file
└── tpl/
    └── marketmassedit.admin.tpl       # Admin panel template
```

---

## Installation

1. Download the plugin ZIP archive from the [official repository](https://github.com/webitproff/marketmassedit-cotonti).
2. Unpack the archive into the `plugins/` folder of your site.
3. Go to the Cotonti admin panel → **Extensions**.
4. Find **«Market Mass Edit»** and click **«Install»**.

The plugin is ready to use immediately — go to **Tools → Market Mass Edit**.

---

## Plugin configuration in admin panel

All settings are available at **Extensions → Market Mass Edit → Configuration**.

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `perpage` | string | `20` | Number of items displayed per page in the list/table. |
| `show_id` | radio | `1` | Show «ID» column. |
| `show_title` | radio | `1` | Show «Title» column. |
| `show_alias` | radio | `1` | Show «Alias» column. |
| `show_metatitle` | radio | `1` | Show «Meta Title» column. |
| `show_metadesc` | radio | `1` | Show «Meta Description» column. |
| `show_cat` | radio | `1` | Show «Category» column. |
| `show_costdflt` | radio | `1` | Show «Price (base)» column. |
| `show_cost_usd` | radio | `1` | Show «Price (USD)» column. |
| `show_state` | radio | `1` | Show «Status» column. |
| `show_pcod` | radio | `1` | Show «Product Code» column. |
| `show_datenow` | radio | `1` | Show «Update Date» checkbox. |
| `show_updated` | radio | `1` | Show «Modified» column. |
| `show_delete` | radio | `1` | Show «Delete» column. |
| `show_extrafields` | radio | `1` | Show extra fields columns. |

Changing any of these options instantly affects the display of the mass editing table.

---

## Usage

### Browsing and searching items

1. Go to **Tools → Market Mass Edit**.
2. The **«Item list»** tab will open.
3. You can:
   - Enter a search query in the «Search» field.
   - Select a category from the dropdown.
   - Specify an exact item ID.
   - Choose the search scope: title, title+text, product code.
4. Click **«Filter»** — the table will update according to the criteria.

Results are shown in a table with columns: ID, Title (link to the item), Category, Price (base), Price (USD), Status. Pagination with highlighting of found words is provided.

### Mass editing of items

1. Go to the **«Mass editing»** tab.
2. Use filters if needed (search, category, ID, status) to narrow down the list of items.
3. Adjust column visibility in the plugin configuration (see above) — hide the fields you do not intend to change.
4. Make changes directly in the table fields. Each field can be edited independently.
5. Click **«Save changes»**.

**Important:** The plugin updates only those fields whose value actually differs from the current one. This saves resources and prevents false trigger firings (e.g., modification date update).

**Mass deletion:** in the «Delete» column, switch the radio button to «Yes» for items you want to delete. When saving, they will be removed using the standard `market` module procedure (cache clearing, file handling, etc.).

---

## Administration panel (two tabs)

The panel is accessible via **Admin → Tools → Market Mass Edit**.

### «Item list» tab
- Search form with filtering by phrase, category, and ID.
- Table with links to items, categories, prices, and statuses.
- Pagination preserving search parameters.
- Highlighting of found words in titles and text (when search is active).

### «Mass editing» tab
- A similar search and filtering form (including status filter).
- Table with columns selected by the administrator.
- Editing fields: text fields, dropdowns (category, status), checkboxes, date selectors.
- Extra fields are automatically loaded and displayed according to their type.
- The «Save changes» button applies all made edits.
- Pagination retains filters and page number after saving.

---

## Detailed description of each file

### `marketmassedit.setup.php`

Registers the plugin in Cotonti and describes its settings.  
- **`[BEGIN_COT_EXT]` section** contains metadata: code, name, category, version, dependencies (`market`), access rights.  
- **`[BEGIN_COT_EXT_CONFIG]` section** lists all configuration parameters with order, type, default value, and description. These parameters automatically appear in the plugin settings form.

### `marketmassedit.admin.php`

The main file, connected via the `tools` hook. Implements all admin panel logic:

1. Includes necessary dependencies (`marketmassedit`, `market`).
2. Determines the current tab and handles POST save requests.
3. **«Item list» tab:** builds an SQL query with a dynamic WHERE clause based on filters, fetches data, constructs a table, generates pagination and word highlighting, passes variables to the template.
4. **«Mass editing» tab:**
   - On `POST` with `a=update` starts a processing loop: loads current data for the submitted IDs, compares each field and writes only changed ones. Handles deletion via `MarketControlService`.
   - On normal display builds a table, respecting column visibility settings. For each column, a corresponding input field is created (text, select, date, checkbox).
   - Supports extra fields via `cot_build_extrafields()`.

### `marketmassedit.functions.php`

This plugin does not use a separate functions file. Categories are displayed using the standard `market` module function — `cot_market_selectcat_select2()`, which supports Select2 and the category blacklist defined in the `market` module settings.

### Language files

**`marketmassedit.ru.lang.php`** and **`marketmassedit.en.lang.php`** contain all interface text strings: plugin name, tab names, field labels, messages, setting descriptions.

### Template `marketmassedit.admin.tpl`

HTML template with XTemplate blocks, using Bootstrap 5 (built into Cotonti). Includes:

- Tab switching via `nav-tabs`.
- Search forms with hidden fields to preserve admin area parameters.
- Data tables with conditional blocks for each column (`IF {SHOW_...}`).
- Pagination blocks.
- JavaScript for highlighting search words.

---

## Example of use

**Task:** update prices and status for 50 items in the «Electric Scooters» category, and also fix product codes.

1. Open the «Market Mass Edit» panel, «Mass editing» tab.
2. In the filter, select the «Electric Scooters» category, status «All».
3. In the plugin settings, enable the «Price (base)», «Price (USD)», «Status», «Product Code» columns; others can be temporarily hidden for convenience.
4. Make the necessary changes in the table cells.
5. Click «Save changes». The plugin will process only the fields that were changed and display the message «Items updated: 50».

---

## Troubleshooting

- **Filter does not find items.** Make sure the search criteria are correct. Check whether a status filter is active that might hide the desired items.
- **No visible result after saving changes.** You may have changed a field whose value matches the current one — in this case, the plugin deliberately does not execute a database query. Check the message about the number of updated records.
- **Extra fields are not displayed.** Enable the «Show extra fields» option in the plugin settings. Make sure that extra fields are indeed registered in the `market` module.
- **Category dropdown is empty.** Check the administrator's read permissions for categories. It is possible that the `market` module settings contain a category blacklist (`marketblacktreecatspage`) that excludes all visible categories.
- **After filtering, the URL gets messed up.** Use the «Filter» button instead of direct URL entry. The plugin correctly handles the form's hidden fields.
- **Incorrect operation of the category dropdown (Select2).**  
  The category selection in search and editing forms is implemented via the standard `cot_market_selectcat_select2()` function, which uses the **Select2** library already included in Cotonti. For correct display and functioning, your admin theme must include styles and scripts similar to those provided below.  

  **Required styles (add to your admin theme's CSS):**  
  ```css
  /* Main container for a single select2 (not multiple) */
  .select2-container--default .select2-selection--single {
    background-color: var(--bs-body-bg, #fff);
    border: 1px solid var(--bs-border-color, #ced4da);
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    height: 38px;
    display: flex;
    align-items: center;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }
  .select2-container--default .select2-selection--single:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    outline: 0;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--bs-body-color, #212529);
    line-height: 1.5;
    padding-left: 0;
    padding-right: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
    right: 0.75rem;
  }
  .select2-container--default .select2-results__options {
    max-height: 50vh !important;
    overflow-y: auto;
    background-color: var(--bs-body-bg, #fff);
    color: var(--bs-body-color, #212529);
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: var(--bs-link-hover-color, #FF5722);
    color: #fff;
  }
  .select2-container--default .select2-results__group {
    font-weight: bold;
    padding: 0.375rem 0.75rem;
    color: var(--bs-body-color, #212529);
    background-color: var(--bs-sidebar-bg, #f8f9fa);
  }
  /* Dark theme */
  [data-bs-theme="dark"] .select2-container--default .select2-selection--single {
    background-color: var(--bs-body-bg, #212529);
    border-color: var(--bs-sidebar-border, #444);
  }
  [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--bs-body-color, #e9ecef);
  }
  [data-bs-theme="dark"] .select2-container--default .select2-results__options {
    background-color: var(--bs-body-bg, #212529);
    color: var(--bs-body-color, #e9ecef);
  }
  [data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #343a40 !important;
    color: #fff;
  }
  [data-bs-theme="dark"] .select2-container--default .select2-results__group {
    color: var(--bs-body-color, #e9ecef);
  }
  ```

  **Required JavaScript (include in your admin theme):**  
  ```javascript
  $(document).ready(function () {
    const selectName = 'c';
    const selectSelector = 'select[name="' + selectName + '"]';
    $(selectSelector).select2({
      placeholder: "---",
      width: '100%',
      templateResult: function (state) {
        if (!state.id) return state.text;
        const depth = parseInt(state.element?.dataset?.depth || '0', 10);
        const text = state.text.trim();
        const indent = '\u00A0'.repeat(depth * 3);
        return depth === 0 ? $('<span><strong>' + text + '</strong></span>') : $('<span>' + indent + text + '</span>');
      },
      templateSelection: function (state) {
        return state.id ? state.text : state.text;
      }
    });
    $(selectSelector).on('select2:select', function (e) {
      const selectedText = e.params.data.text.trim();
      const containerId = $(this).attr('name');
      const renderedSelector = '#select2-' + containerId + '-container';
      $(renderedSelector).attr('title', selectedText);
    });
    const selectedOption = $(selectSelector).find(':selected');
    if (selectedOption.length) {
      const cleanTitle = selectedOption.text().trim();
      const containerId = $(selectSelector).attr('name');
      const renderedSelector = '#select2-' + containerId + '-container';
      $(renderedSelector).attr('title', cleanTitle);
    }
  });
  ```

  If after adding the styles and scripts the dropdown still displays incorrectly, check that your admin theme does not contain other styles for Select2 that may override these. Make sure jQuery and Select2 are loaded before the above code executes.

---

## License

BSD. Free use and modification with preservation of copyright.

---

## Links

- [Official GitHub repository](https://github.com/webitproff/marketmassedit-cotonti)
- [Plugin page on Cotonti Marketplace](https://abuyfile.com/ru/market/cotonti/plugs/marketmassedit)
- [Support forum](https://abuyfile.com/forums/cotonti/custom/plugs/)


---


# Market Mass Edit — массовое редактирование товаров модуля Market

**Плагин для Cotonti v1.+**  
Предоставляет удобный интерфейс для быстрого просмотра и массового редактирования товаров.  
Позволяет изменять заголовки, алиасы, мета-теги, категории, цены, статусы, артикулы, даты и любые дополнительные поля (экстраполя) одновременно для нескольких товаров.

---

## Оглавление

- [История создания и решаемая проблема](#история-создания-и-решаемая-проблема)
- [Возможности](#возможности)
- [Требования](#требования)
- [Структура плагина](#структура-плагина)
- [Установка](#установка)
- [Настройка плагина в админке](#настройка-плагина-в-админке)
- [Использование](#использование)
- [Административная панель (две вкладки)](#административная-панель-две-вкладки)
- [Подробное описание каждого файла](#подробное-описание-каждого-файла)
- [Пример использования](#пример-использования)
- [Устранение неполадок](#устранение-неполадок)
- [Лицензия](#лицензия)
- [Ссылки](#ссылки)

---

## История создания и решаемая проблема

Управление большим каталогом товаров в Cotonti требует частого обновления мета‑тегов, цен, статусов и других полей сразу у многих позиций. Штатные средства редактирования позволяют изменять только по одному товару за раз, что замедляет работу администратора.

**Market Mass Edit** решает эту задачу: он выводит все товары в единой таблице с фильтрацией и поиском, а затем позволяет массово вносить изменения. Интерфейс настраивается — можно скрыть ненужные колонки и сосредоточиться только на тех полях, которые требуется обновить.

Плагин работает строго через стандартное API модуля `market`, не нарушая целостность данных и проверки прав доступа.

---

## Возможности

- **Две вкладки администрирования:**
  - «Список товаров» — просмотр товаров с поиском и фильтрацией.
  - «Массовое редактирование» — таблица с возможностью одновременного изменения полей.
- **Мощный поиск и фильтрация:**
  - Поиск по заголовку, тексту, артикулу.
  - Фильтр по точному ID товара.
  - Выбор категории через выпадающий список с поддержкой Select2 (включая вложенность).
  - Фильтр по статусу (все / на рассмотрении / опубликованы / черновики).
- **Гибкая настройка отображаемых колонок** — администратор выбирает, какие поля показывать в таблице редактирования:
  - ID, Заголовок, Алиас, Meta Title, Meta Description, Категория, Цена (осн.), Цена (USD), Статус, Артикул, Обновить дату, Изменён, Удалить.
  - Отдельная опция включает/отключает экстраполя.
- **Массовое редактирование** — изменения применяются только к тем полям, значения которых действительно изменились (сравнение с текущими данными), что исключает ложные обновления.
- **Массовое удаление** — возможность пометить товары на удаление прямо из таблицы массового редактирования.
- **Пагинация** — на обеих вкладках, с сохранением параметров фильтра при переходах.
- **Настройки плагина** — количество элементов на странице.
- **Полная локализация** — русский и английский языки из коробки.

---

## Требования

- **Cotonti v1.+** (актуальная ветка)
- **Модуль `market`** (должен быть установлен и активен)
- **PHP 8.5+**
- **MySQL 8.4+**
- Права администратора для доступа к панели управления плагином.

---

## Структура плагина

```
marketmassedit/
├── marketmassedit.setup.php           # Регистрация плагина и описание настроек
├── marketmassedit.admin.php           # Административная панель (хук tools)
├── lang/
│   ├── marketmassedit.ru.lang.php     # Русский языковой файл
│   └── marketmassedit.en.lang.php     # Английский языковой файл
└── tpl/
    └── marketmassedit.admin.tpl       # Шаблон админ‑панели
```

---

## Установка

1. Скачайте ZIP‑архив плагина из [официального репозитория](https://github.com/webitproff/marketmassedit-cotonti).
2. Распакуйте архив в папку `plugins/` вашего сайта.
3. Зайдите в админ‑панель Cotonti → **Расширения**.
4. Найдите **«Market Mass Edit»** и нажмите **«Установить»**.

Плагин сразу готов к работе — перейдите в **Инструменты → Market Mass Edit**.

---

## Настройка плагина в админке

Все параметры доступны по пути **Расширения → Market Mass Edit → Конфигурация**.

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|-------------|----------|
| `perpage` | string | `20` | Количество товаров, отображаемых на одной странице списка/таблицы. |
| `show_id` | radio | `1` | Показывать колонку «ID». |
| `show_title` | radio | `1` | Показывать колонку «Заголовок». |
| `show_alias` | radio | `1` | Показывать колонку «Алиас». |
| `show_metatitle` | radio | `1` | Показывать колонку «Meta Title». |
| `show_metadesc` | radio | `1` | Показывать колонку «Meta Description». |
| `show_cat` | radio | `1` | Показывать колонку «Категория». |
| `show_costdflt` | radio | `1` | Показывать колонку «Цена (осн.)». |
| `show_cost_usd` | radio | `1` | Показывать колонку «Цена (USD)». |
| `show_state` | radio | `1` | Показывать колонку «Статус». |
| `show_pcod` | radio | `1` | Показывать колонку «Артикул». |
| `show_datenow` | radio | `1` | Показывать колонку «Обновить дату». |
| `show_updated` | radio | `1` | Показывать колонку «Изменён». |
| `show_delete` | radio | `1` | Показывать колонку «Удалить». |
| `show_extrafields` | radio | `1` | Показывать колонки дополнительных полей (экстраполя). |

Изменение любой из этих опций мгновенно влияет на отображение таблицы массового редактирования.

---

## Использование

### Просмотр и поиск товаров

1. Перейдите в **Инструменты → Market Mass Edit**.
2. Откроется вкладка **«Список товаров»**.
3. Вы можете:
   - Ввести поисковый запрос в поле «Поиск».
   - Выбрать категорию из выпадающего списка.
   - Указать точный ID товара.
   - Выбрать область поиска: заголовок, заголовок+описание, артикул.
4. Нажмите **«Фильтр»** — таблица обновится в соответствии с критериями.

Результаты отображаются в таблице с колонками: ID, Заголовок (ссылка на товар), Категория, Цена (осн.), Цена (USD), Статус. Предусмотрена пагинация с подсветкой найденных слов.

### Массовое редактирование товаров

1. Перейдите на вкладку **«Массовое редактирование»**.
2. При необходимости используйте фильтры (поиск, категория, ID, статус), чтобы сузить список товаров.
3. Настройте видимость колонок в конфигурации плагина (см. выше) — скройте те поля, которые не собираетесь менять.
4. Внесите изменения прямо в поля таблицы. Каждое поле можно редактировать независимо.
5. Нажмите **«Сохранить изменения»**.

**Важно:** Плагин обновляет только те поля, значение которых действительно отличается от текущего. Это экономит ресурсы и предотвращает ложные срабатывания триггеров (например, обновление даты изменения).

**Массовое удаление:** в столбце «Удалить» переключите радиокнопку в «Да» для товаров, которые нужно удалить. При сохранении они будут удалены через стандартную процедуру модуля `market` (с очисткой кеша, обработкой файлов и т.д.).

---

## Административная панель (две вкладки)

Панель доступна через **Админка → Инструменты → Market Mass Edit**.

### Вкладка «Список товаров»
- Форма поиска с возможностью фильтрации по фразе, категории и ID.
- Таблица со ссылками на товары, категориями, ценами и статусами.
- Пагинация с сохранением параметров поиска.
- Подсветка найденных слов в заголовках и тексте (при включённом поиске).

### Вкладка «Массовое редактирование»
- Аналогичная форма поиска и фильтрации (включая фильтр по статусу).
- Таблица с выбранными администратором колонками.
- Поля для редактирования: текстовые поля, выпадающие списки (категория, статус), чекбоксы, селекторы дат.
- Экстраполя автоматически подгружаются и отображаются в соответствии с их типом.
- Кнопка «Сохранить изменения» применяет все сделанные правки.
- Пагинация сохраняет фильтры и номер страницы после сохранения.

---

## Подробное описание каждого файла

### `marketmassedit.setup.php`

Регистрирует плагин в Cotonti и описывает его настройки.  
- **Секция `[BEGIN_COT_EXT]`** содержит метаданные: код, имя, категорию, версию, зависимости (`market`), права доступа.  
- **Секция `[BEGIN_COT_EXT_CONFIG]`** перечисляет все параметры конфигурации с указанием порядка, типа, значения по умолчанию и описания. Эти параметры автоматически появляются в форме настроек плагина.

### `marketmassedit.admin.php`

Главный файл, подключаемый через хук `tools`. Реализует всю логику админ‑панели:

1. Подключает необходимые зависимости (`marketmassedit`, `market`).
2. Определяет текущую вкладку и обрабатывает POST‑запросы сохранения.
3. **Вкладка «Список товаров»:** формирует SQL‑запрос с динамическим WHERE в зависимости от фильтров, получает данные, строит таблицу, генерирует пагинацию, подсветку слов, передаёт переменные в шаблон.
4. **Вкладка «Массовое редактирование»:**
   - При `POST` с `a=update` запускает цикл обработки: загружает текущие данные для переданных ID, сравнивает каждое поле и записывает только изменившиеся. Обрабатывает удаление через `MarketControlService`.
   - При обычном отображении строит таблицу, учитывая настройки видимости колонок. Для каждой колонки создаётся соответствующее поле ввода (текст, select, дата, чекбокс).
   - Поддерживает экстраполя через вызов `cot_build_extrafields()`.

### `marketmassedit.functions.php`

В плагине не используется отдельный файл функций. Категории отображаются стандартной функцией модуля `market` — `cot_market_selectcat_select2()`, которая поддерживает Select2 и черный список категорий, заданный в настройках модуля `market`.

### Языковые файлы

**`marketmassedit.ru.lang.php`** и **`marketmassedit.en.lang.php`** содержат все текстовые строки интерфейса: названия плагина, вкладок, подписи полей, сообщения, описания настроек.

### Шаблон `marketmassedit.admin.tpl`

HTML‑шаблон с блоками XTemplate, использующий Bootstrap 5 (встроен в Cotonti). Включает:

- Переключение вкладок через `nav-tabs`.
- Формы поиска с скрытыми полями для сохранения параметров админки.
- Таблицы данных с условными блоками для каждой колонки (`IF {SHOW_...}`).
- Блоки пагинации.
- JavaScript для подсветки поисковых слов.

---

## Пример использования

**Задача:** необходимо обновить цены и статус у 50 товаров из категории «Электросамокаты», а также исправить артикулы.

1. Откройте панель «Market Mass Edit», вкладка «Массовое редактирование».
2. В фильтре выберите категорию «Электросамокаты», статус «Все».
3. В настройках плагина включите показ колонок «Цена (осн.)», «Цена (USD)», «Статус», «Артикул»; остальные можно временно скрыть для удобства.
4. Внесите нужные изменения в ячейках таблицы.
5. Нажмите «Сохранить изменения». Плагин обработает только те поля, которые были изменены, и выведет сообщение «Обновлено товаров: 50».

---

## Устранение неполадок

- **Фильтр не находит товары.** Убедитесь, что критерии поиска заданы корректно. Проверьте, не активен ли фильтр по статусу, скрывающий нужные товары.
- **После сохранения изменений не видно результата.** Возможно, вы изменили поле, значение которого совпадает с текущим — в этом случае плагин специально не выполняет запрос к базе. Проверьте сообщение о количестве обновлённых записей.
- **Не отображаются экстраполя.** Включите опцию «Показывать экстраполя» в настройках плагина. Убедитесь, что в модуле `market` действительно зарегистрированы дополнительные поля.
- **Выпадающий список категорий пуст.** Проверьте права администратора на чтение категорий. Возможно, в настройках модуля `market` задан черный список категорий (`marketblacktreecatspage`), который исключает все видимые категории.
- **После фильтрации сбивается адресная строка.** Используйте кнопку «Фильтр» вместо прямого ввода URL. Плагин корректно обрабатывает скрытые поля формы.
- **Некорректная работа выпадающего списка категорий (Select2).**  
  Выбор категорий в формах поиска и редактирования реализован через штатную функцию `cot_market_selectcat_select2()`, использующую библиотеку **Select2**, которая уже включена в Cotonti. Для корректного отображения и функционирования в теме вашей админки должны быть определены стили и скрипты, аналогичные приведённым ниже.  

  **Необходимые стили (добавьте в CSS вашей админ-темы):**  
  ```css
  /* Основной контейнер для одиночного select2 (не multiple) */
  .select2-container--default .select2-selection--single {
    background-color: var(--bs-body-bg, #fff);
    border: 1px solid var(--bs-border-color, #ced4da);
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    height: 38px;
    display: flex;
    align-items: center;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }
  .select2-container--default .select2-selection--single:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    outline: 0;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--bs-body-color, #212529);
    line-height: 1.5;
    padding-left: 0;
    padding-right: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
    right: 0.75rem;
  }
  .select2-container--default .select2-results__options {
    max-height: 50vh !important;
    overflow-y: auto;
    background-color: var(--bs-body-bg, #fff);
    color: var(--bs-body-color, #212529);
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: var(--bs-link-hover-color, #FF5722);
    color: #fff;
  }
  .select2-container--default .select2-results__group {
    font-weight: bold;
    padding: 0.375rem 0.75rem;
    color: var(--bs-body-color, #212529);
    background-color: var(--bs-sidebar-bg, #f8f9fa);
  }
  /* Тёмная тема */
  [data-bs-theme="dark"] .select2-container--default .select2-selection--single {
    background-color: var(--bs-body-bg, #212529);
    border-color: var(--bs-sidebar-border, #444);
  }
  [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--bs-body-color, #e9ecef);
  }
  [data-bs-theme="dark"] .select2-container--default .select2-results__options {
    background-color: var(--bs-body-bg, #212529);
    color: var(--bs-body-color, #e9ecef);
  }
  [data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #343a40 !important;
    color: #fff;
  }
  [data-bs-theme="dark"] .select2-container--default .select2-results__group {
    color: var(--bs-body-color, #e9ecef);
  }
  ```

  **Необходимый JavaScript (подключите в админ-теме):**  
  ```javascript
  $(document).ready(function () {
    const selectName = 'c';
    const selectSelector = 'select[name="' + selectName + '"]';
    $(selectSelector).select2({
      placeholder: "---",
      width: '100%',
      templateResult: function (state) {
        if (!state.id) return state.text;
        const depth = parseInt(state.element?.dataset?.depth || '0', 10);
        const text = state.text.trim();
        const indent = '\u00A0'.repeat(depth * 3);
        return depth === 0 ? $('<span><strong>' + text + '</strong></span>') : $('<span>' + indent + text + '</span>');
      },
      templateSelection: function (state) {
        return state.id ? state.text : state.text;
      }
    });
    $(selectSelector).on('select2:select', function (e) {
      const selectedText = e.params.data.text.trim();
      const containerId = $(this).attr('name');
      const renderedSelector = '#select2-' + containerId + '-container';
      $(renderedSelector).attr('title', selectedText);
    });
    const selectedOption = $(selectSelector).find(':selected');
    if (selectedOption.length) {
      const cleanTitle = selectedOption.text().trim();
      const containerId = $(selectSelector).attr('name');
      const renderedSelector = '#select2-' + containerId + '-container';
      $(renderedSelector).attr('title', cleanTitle);
    }
  });
  ```

  Если после добавления стилей и скриптов список всё ещё отображается некорректно, проверьте, что в вашей админ-теме не используются другие стили для Select2, которые могут переопределять данные. Перепроверьте, что jQuery и Select2 загружаются до выполнения приведённого кода.

---

## Лицензия

BSD. Свободное использование и модификация при сохранении авторских прав.

---

## Ссылки

- [Официальный репозиторий GitHub](https://github.com/webitproff/marketmassedit-cotonti)
- [Страница плагина на Cotonti Marketplace](https://abuyfile.com/ru/market/cotonti/plugs/marketmassedit)
- [Поддержка на форуме](https://abuyfile.com/forums/cotonti/custom/plugs/)
