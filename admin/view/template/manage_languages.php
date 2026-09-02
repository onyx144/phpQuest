<?php
defined('GD_ACCESS') or die('You can not access the file directly!');
require_once(ROOT . '/admin/view/template/blocks/header.php');
require_once(ROOT . '/admin/view/template/blocks/nav.php');
?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="header">
                    <div class="header-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1 class="header-title text-truncate">Manage Languages Dictionary</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title mb-0">Select Language</h5>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-add-language">
                                            <i class="fas fa-plus"></i> Add New Language
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="language-select" class="form-label">Language:</label>
                                        <select class="form-select" id="language-select">
                                            <option value="0">-- Select Language --</option>
                                            <?php if (!empty($languages)): ?>
                                                <?php foreach ($languages as $lang): ?>
                                                    <option value="<?php echo $lang['id']; ?>" <?php echo ($selected_lang_id == $lang['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($lang['lang_name'] . ' (' . $lang['lang_abbr'] . ')'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $dict_pages_for_select = !empty($available_dict_pages) && is_array($available_dict_pages) ? $available_dict_pages : ['game'];
                if (!in_array('game', $dict_pages_for_select, true)) {
                    array_unshift($dict_pages_for_select, 'game');
                }
                if (!in_array('chat_messages', $dict_pages_for_select, true)) {
                    $dict_pages_for_select[] = 'chat_messages';
                }
                ?>

                <?php if ($selected_lang && empty($is_chat_messages_page) && !empty($words_with_english)): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title mb-0">
                                            Words Dictionary: <?php echo htmlspecialchars($selected_lang['lang_name']); ?>
                                            <span class="badge bg-dark">page: <?php echo htmlspecialchars($dict_page_scope ?? 'game'); ?></span>
                                            <?php if ($selected_lang['lang_abbr'] == 'en'): ?>
                                                <span class="badge bg-info">English</span>
                                            <?php endif; ?>
                                        </h5>
                                    </div>
                                    <?php if ($selected_lang): ?>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-sm btn-success" id="btn-add-word">
                                            <i class="fas fa-plus"></i> Add Word
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info" id="btn-import-json">
                                            <i class="fas fa-file-import"></i> Import JSON
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" id="btn-export-json">
                                            <i class="fas fa-file-export"></i> Export JSON
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get" action="/language" class="mb-4">
                                    <input type="hidden" name="lang_id" value="<?php echo (int)$selected_lang_id; ?>">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-6 col-lg-4">
                                            <label for="search-field" class="form-label mb-0">Search by Field (Code) or Word:</label>
                                            <input type="text" class="form-control" id="search-field" name="search" 
                                                   value="<?php echo htmlspecialchars($search); ?>" 
                                                   placeholder="Enter field code or word...">
                                        </div>
                                        <div class="col-md-4 col-lg-3">
                                            <label for="dict-page-scope" class="form-label mb-0">Page:</label>
                                            <select class="form-select dict-page-scope-select" id="dict-page-scope" name="dict_page_scope">
                                                <?php foreach ($dict_pages_for_select as $dict_page_option): ?>
                                                    <option value="<?php echo htmlspecialchars((string) $dict_page_option); ?>" <?php echo ((string) ($dict_page_scope ?? 'game') === (string) $dict_page_option) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars((string) $dict_page_option === 'chat_messages' ? 'Chat Messages' : (string) $dict_page_option); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                            <?php if ($search !== ''): ?>
                                            <a href="/language?lang_id=<?php echo (int)$selected_lang_id; ?>&dict_page_scope=<?php echo urlencode((string) ($dict_page_scope ?? 'game')); ?>" class="btn btn-outline-secondary">Clear</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                                <?php if ($this->pagination && (int) $this->pagination->total > 0): ?>
                                <?php
                                $pg = $this->pagination;
                                $show_from = ($pg->page - 1) * $pg->limit + 1;
                                $show_to = min($pg->page * $pg->limit, (int) $pg->total);
                                ?>
                                <p class="text-muted small mb-2">
                                    Showing <strong><?php echo (int) $show_from; ?>–<?php echo (int) $show_to; ?></strong> of <strong><?php echo (int) $pg->total; ?></strong> entries
                                    <?php if ((int) ceil($pg->total / max(1, (int) $pg->limit)) > 1): ?>
                                        — use pagination below for more.
                                    <?php endif; ?>
                                </p>
                                <?php endif; ?>
                                <p class="text-muted small mb-2">
                                    Only entries with <code>page</code> = <strong><?php echo htmlspecialchars($dict_page_scope ?? 'game'); ?></strong> are listed (same scope for export/import).
                                    Use <i class="fas fa-edit"></i> to edit text and page tag; changing page moves the row out of this list.
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover card-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 18%;">Field (Code)</th>
                                                <th style="width: 28%;">Word in <?php echo htmlspecialchars($selected_lang['lang_name']); ?></th>
                                                <th style="width: 26%;">English Equivalent</th>
                                                <th style="width: 16%;">Page</th>
                                                <th style="width: 12%;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="words-table-body">
                                            <?php foreach ($words_with_english as $word): ?>
                                                <?php $missing_tr = !empty($word['missing_in_selected']); ?>
                                                <tr data-word-id="<?php echo (int) ($word['id'] ?? 0); ?>" data-field="<?php echo htmlspecialchars($word['field']); ?>">
                                                    <td>
                                                        <code><?php echo htmlspecialchars($word['field']); ?></code>
                                                    </td>
                                                    <td>
                                                        <div class="word-val-static">
                                                            <?php if ($missing_tr): ?>
                                                                <span class="word-val-display text-muted fst-italic">empty</span>
                                                                <br><small class="text-muted">Defined in English only — add a translation here.</small>
                                                            <?php else: ?>
                                                                <span class="word-val-display"><?php echo htmlspecialchars(($word['val'] ?? '') !== '' ? $word['val'] : '(empty)'); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm word-val-input d-none" 
                                                               value="<?php echo htmlspecialchars($word['val'] ?? ''); ?>" 
                                                               data-field="<?php echo htmlspecialchars($word['field']); ?>">
                                                    </td>
                                                    <td>
                                                        <div class="english-val-static">
                                                            <span class="english-val-display"><?php echo htmlspecialchars(($word['english_val'] ?? '') !== '' ? $word['english_val'] : '(empty)'); ?></span>
                                                            <?php if ($missing_tr): ?>
                                                                <br><small class="text-success">In English</small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $pageVal = isset($word['page']) ? trim((string) $word['page']) : '';
                                                        ?>
                                                        <div class="page-cell">
                                                            <?php if ($pageVal !== ''): ?>
                                                                <span class="page-display"><span class="badge bg-info text-dark"><?php echo htmlspecialchars($pageVal); ?></span></span>
                                                            <?php else: ?>
                                                                <span class="page-display"><span class="badge bg-secondary">No page</span></span>
                                                            <?php endif; ?>
                                                            <input type="text" class="form-control form-control-sm page-input d-none mt-1"
                                                                   value="<?php echo htmlspecialchars($pageVal); ?>"
                                                                   placeholder="Page id (optional)">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary edit-word-btn" 
                                                                data-word-id="<?php echo (int) ($word['id'] ?? 0); ?>" 
                                                                data-field="<?php echo htmlspecialchars($word['field']); ?>"
                                                                data-val="<?php echo htmlspecialchars($word['val']); ?>"
                                                                data-page="<?php echo htmlspecialchars($pageVal, ENT_QUOTES, 'UTF-8'); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-success save-word-btn d-none" 
                                                                data-word-id="<?php echo (int) ($word['id'] ?? 0); ?>" 
                                                                data-field="<?php echo htmlspecialchars($word['field']); ?>">
                                                            <i class="fas fa-save"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary cancel-edit-btn d-none">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <?php if ($this->pagination): ?>
                                    <?php $pagination_html = $this->pagination->render(); ?>
                                    <?php if ($pagination_html !== ''): ?>
                                    <nav class="mt-3 d-flex justify-content-center" aria-label="Dictionary pages">
                                        <?php echo $pagination_html; ?>
                                    </nav>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif ($selected_lang && empty($is_chat_messages_page) && empty($words_with_english)): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="get" action="/language" class="mb-3">
                                    <input type="hidden" name="lang_id" value="<?php echo (int)$selected_lang_id; ?>">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-6 col-lg-4">
                                            <label for="search-field-empty" class="form-label mb-0">Search by Field (Code) or Word:</label>
                                            <input type="text" class="form-control" id="search-field-empty" name="search" 
                                                   value="<?php echo htmlspecialchars($search); ?>" 
                                                   placeholder="Enter field code or word...">
                                        </div>
                                        <div class="col-md-4 col-lg-3">
                                            <label for="dict-page-scope-empty" class="form-label mb-0">Page:</label>
                                            <select class="form-select dict-page-scope-select" id="dict-page-scope-empty" name="dict_page_scope">
                                                <?php foreach ($dict_pages_for_select as $dict_page_option): ?>
                                                    <option value="<?php echo htmlspecialchars((string) $dict_page_option); ?>" <?php echo ((string) ($dict_page_scope ?? 'game') === (string) $dict_page_option) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars((string) $dict_page_option === 'chat_messages' ? 'Chat Messages' : (string) $dict_page_option); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                            <?php if ($search !== ''): ?>
                                            <a href="/language?lang_id=<?php echo (int)$selected_lang_id; ?>&dict_page_scope=<?php echo urlencode((string) ($dict_page_scope ?? 'game')); ?>" class="btn btn-outline-secondary">Clear</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                                <div class="alert alert-info mb-0">
                                    <?php if ($search !== ''): ?>
                                    No words match your search. <a href="/language?lang_id=<?php echo (int)$selected_lang_id; ?>">Clear search</a> to see all words.
                                    <?php else: ?>
                                    No words found for this language. Click "Add Word" to start adding words.
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($selected_lang && !empty($is_chat_messages_page)): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title mb-0">
                                            Chat Messages: <?php echo htmlspecialchars($selected_lang['lang_name']); ?>
                                            <span class="badge bg-dark">page: chat_messages</span>
                                            <?php if ($selected_lang['lang_abbr'] == 'en'): ?>
                                                <span class="badge bg-info">English</span>
                                            <?php endif; ?>
                                        </h5>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-sm btn-success" id="btn-add-chat-message">
                                            <i class="fas fa-plus"></i> Add Word
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info" id="btn-import-chat-json">
                                            <i class="fas fa-file-import"></i> Import JSON
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" id="btn-export-chat-json">
                                            <i class="fas fa-file-export"></i> Export JSON
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get" action="/language" class="mb-4">
                                    <input type="hidden" name="lang_id" value="<?php echo (int)$selected_lang_id; ?>">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-6 col-lg-4">
                                            <label for="chat-search-field" class="form-label mb-0">Search by ID or Message:</label>
                                            <input type="text" class="form-control" id="chat-search-field" name="search"
                                                   value="<?php echo htmlspecialchars($search); ?>"
                                                   placeholder="Enter message id or text...">
                                        </div>
                                        <div class="col-md-4 col-lg-3">
                                            <label for="dict-page-scope-chat" class="form-label mb-0">Page:</label>
                                            <select class="form-select dict-page-scope-select" id="dict-page-scope-chat" name="dict_page_scope">
                                                <?php foreach ($dict_pages_for_select as $dict_page_option): ?>
                                                    <option value="<?php echo htmlspecialchars((string) $dict_page_option); ?>" <?php echo ((string) ($dict_page_scope ?? 'game') === (string) $dict_page_option) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars((string) $dict_page_option === 'chat_messages' ? 'Chat Messages' : (string) $dict_page_option); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                            <?php if ($search !== ''): ?>
                                            <a href="/language?lang_id=<?php echo (int)$selected_lang_id; ?>&dict_page_scope=chat_messages" class="btn btn-outline-secondary">Clear</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                                <p class="text-muted small mb-2">
                                    Bot message templates from <code>chat_messages_description</code>, grouped by <code>message_default_id</code>.
                                    Saving a translation updates every existing copy for this language, and new chat messages will use the latest saved text.
                                </p>
                                <?php if (!empty($chat_messages_with_english)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover card-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">Message ID</th>
                                                <th style="width: 40%;">Message in <?php echo htmlspecialchars($selected_lang['lang_name']); ?></th>
                                                <th style="width: 38%;">English Equivalent</th>
                                                <th style="width: 12%;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="chat-messages-table-body">
                                            <?php foreach ($chat_messages_with_english as $chat_msg): ?>
                                                <?php $chat_missing = !empty($chat_msg['missing_in_selected']); ?>
                                                <tr data-message-default-id="<?php echo (int) $chat_msg['message_default_id']; ?>">
                                                    <td>
                                                        <code><?php echo (int) $chat_msg['message_default_id']; ?></code>
                                                    </td>
                                                    <td>
                                                        <div class="chat-val-static" style="max-height: 6.5rem; overflow: auto; white-space: pre-wrap; word-break: break-word;">
                                                            <?php if ($chat_missing): ?>
                                                                <span class="chat-val-display text-muted fst-italic">empty</span>
                                                                <br><small class="text-muted">Defined in English only — add a translation here.</small>
                                                            <?php else: ?>
                                                                <span class="chat-val-display"><?php echo htmlspecialchars(($chat_msg['val'] ?? '') !== '' ? $chat_msg['val'] : '(empty)'); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <textarea class="form-control form-control-sm chat-val-input d-none" rows="4"><?php echo htmlspecialchars($chat_msg['val'] ?? ''); ?></textarea>
                                                    </td>
                                                    <td>
                                                        <div class="chat-english-static" style="max-height: 6.5rem; overflow: auto; white-space: pre-wrap; word-break: break-word;">
                                                            <span class="chat-english-display"><?php echo htmlspecialchars(($chat_msg['english_val'] ?? '') !== '' ? $chat_msg['english_val'] : '(empty)'); ?></span>
                                                            <?php if ($chat_missing): ?>
                                                                <br><small class="text-success">In English</small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary edit-chat-message-btn">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-success save-chat-message-btn d-none"
                                                                data-message-default-id="<?php echo (int) $chat_msg['message_default_id']; ?>">
                                                            <i class="fas fa-save"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary cancel-chat-edit-btn d-none">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-info mb-0">
                                    No chat message templates found in <code>chat_messages_description</code>.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add New Language -->
<div class="modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLanguageModalLabel">Add New Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-language-form">
                    <div class="mb-3">
                        <label for="lang_code" class="form-label">Language Code (e.g., 'ru', 'fr'):</label>
                        <input type="text" class="form-control" id="lang_code" name="lang_code" required maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label for="lang_name" class="form-label">Language Name (e.g., 'Russian', 'French'):</label>
                        <input type="text" class="form-control" id="lang_name" name="lang_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="lang_abbr" class="form-label">Language Abbreviation (e.g., 'ru', 'fr'):</label>
                        <input type="text" class="form-control" id="lang_abbr" name="lang_abbr" required maxlength="5">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-language-btn">Save Language</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Word -->
<div class="modal fade" id="addWordModal" tabindex="-1" aria-labelledby="addWordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWordModalLabel">Add New Word</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-word-form">
                    <input type="hidden" id="add-word-lang-id" value="<?php echo $selected_lang_id; ?>">
                    <div class="mb-3">
                        <label for="word_field" class="form-label">Field (Code):</label>
                        <input type="text" class="form-control" id="word_field" name="word_field" required>
                        <small class="form-text text-muted">Unique identifier for this word (e.g., 'welcome_message')</small>
                    </div>
                    <div class="mb-3">
                        <label for="word_val" class="form-label">Word in <?php echo !empty($selected_lang) ? htmlspecialchars($selected_lang['lang_name']) : 'Language'; ?>:</label>
                        <input type="text" class="form-control" id="word_val" name="word_val" required>
                    </div>
                    <div class="mb-3">
                        <label for="word_english_val" class="form-label">English Equivalent:</label>
                        <input type="text" class="form-control" id="word_english_val" name="word_english_val" required>
                    </div>
                    <div class="mb-3">
                        <label for="word_page" class="form-label">Page:</label>
                        <input type="text" class="form-control" id="word_page" name="word_page" value="<?php echo htmlspecialchars($dict_page_scope ?? 'game'); ?>">
                        <small class="form-text text-muted">Default matches this dictionary scope (usually <code>game</code>).</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-word-btn">Save Word</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Import JSON -->
<div class="modal fade" id="importJsonModal" tabindex="-1" aria-labelledby="importJsonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importJsonModalLabel">Import Words from JSON</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="import-json-form">
                    <input type="hidden" id="import-json-lang-id" value="<?php echo $selected_lang_id; ?>">
                    <div class="mb-3">
                        <label for="json_data" class="form-label">JSON Data:</label>
                        <textarea class="form-control" id="json_data" name="json_data" rows="15" required></textarea>
                        <small class="form-text text-muted">
                            String values apply to <code>page = <?php echo htmlspecialchars($dict_page_scope ?? 'game'); ?></code> (this screen).<br>
                            Object without <code>page</code>: <code>{"field1": {"val": "…", "english": "…"}}</code> — same scope. Add <code>"page": "…"</code> to target another page explicitly.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="import-json-btn">Import JSON</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Chat Message -->
<div class="modal fade" id="addChatMessageModal" tabindex="-1" aria-labelledby="addChatMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addChatMessageModalLabel">Add Chat Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-chat-message-form">
                    <input type="hidden" id="add-chat-lang-id" value="<?php echo (int) $selected_lang_id; ?>">
                    <div class="mb-3">
                        <label for="chat_message_default_id" class="form-label">Message ID:</label>
                        <input type="number" class="form-control" id="chat_message_default_id" name="chat_message_default_id" required min="1">
                        <small class="form-text text-muted">Unique <code>message_default_id</code> for this bot template.</small>
                    </div>
                    <div class="mb-3">
                        <label for="chat_message_val" class="form-label">Message in <?php echo !empty($selected_lang) ? htmlspecialchars($selected_lang['lang_name']) : 'Language'; ?>:</label>
                        <textarea class="form-control" id="chat_message_val" name="chat_message_val" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="chat_message_english_val" class="form-label">English Equivalent:</label>
                        <textarea class="form-control" id="chat_message_english_val" name="chat_message_english_val" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-chat-message-add-btn">Save Word</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Import Chat JSON -->
<div class="modal fade" id="importChatJsonModal" tabindex="-1" aria-labelledby="importChatJsonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importChatJsonModalLabel">Import Chat Messages from JSON</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="import-chat-json-form">
                    <input type="hidden" id="import-chat-json-lang-id" value="<?php echo (int) $selected_lang_id; ?>">
                    <div class="mb-3">
                        <label for="chat_json_data" class="form-label">JSON Data:</label>
                        <textarea class="form-control" id="chat_json_data" name="chat_json_data" rows="15" required></textarea>
                        <small class="form-text text-muted">
                            Applies to <code>chat_messages_description</code>.<br>
                            Format: <code>{"1": {"val": "…", "english": "…"}, "2": "text for this language"}</code>
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="import-chat-json-btn">Import JSON</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    console.log('Language management script loaded');
    
    // Language selection change
    $('#language-select').on('change', function() {
        var langId = $(this).val();
        var pageScope = <?php echo json_encode((string) ($dict_page_scope ?? 'game')); ?>;
        if (langId > 0) {
            window.location.href = '/language?lang_id=' + langId + '&dict_page_scope=' + encodeURIComponent(pageScope);
        } else {
            window.location.href = '/language';
        }
    });

    $(document).on('change', '.dict-page-scope-select', function() {
        $(this).closest('form').submit();
    });

    // Open modals via JavaScript (works with both Bootstrap 4 and 5)
    $('#btn-add-language').on('click', function() {
        console.log('Opening add language modal');
        var modal = $('#addLanguageModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Bootstrap 5
            var bsModal = new bootstrap.Modal(modal[0]);
            bsModal.show();
        } else {
            // Bootstrap 4
            modal.modal('show');
        }
    });

    $('#btn-add-word').on('click', function() {
        console.log('Opening add word modal');
        var modal = $('#addWordModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Bootstrap 5
            var bsModal = new bootstrap.Modal(modal[0]);
            bsModal.show();
        } else {
            // Bootstrap 4
            modal.modal('show');
        }
    });

    $('#btn-import-json').on('click', function() {
        console.log('Opening import JSON modal');
        var modal = $('#importJsonModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Bootstrap 5
            var bsModal = new bootstrap.Modal(modal[0]);
            bsModal.show();
        } else {
            // Bootstrap 4
            modal.modal('show');
        }
    });

    $('#btn-add-chat-message').on('click', function() {
        var modal = $('#addChatMessageModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var bsModal = new bootstrap.Modal(modal[0]);
            bsModal.show();
        } else {
            modal.modal('show');
        }
    });

    $('#btn-import-chat-json').on('click', function() {
        var modal = $('#importChatJsonModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var bsModal = new bootstrap.Modal(modal[0]);
            bsModal.show();
        } else {
            modal.modal('show');
        }
    });

    // Export JSON
    $('#btn-export-json').on('click', function() {
        var langId = <?php echo $selected_lang_id ? $selected_lang_id : 0; ?>;
        
        if (!langId || langId <= 0) {
            alert('Please select a language first');
            return;
        }

        var formData = new FormData();
        formData.append('op', 'exportWordsJson');
        formData.append('lang_id', langId);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success && json.data) {
                    // Создаем JSON строку с красивым форматированием
                    var jsonString = JSON.stringify(json.data, null, 2);
                    
                    // Создаем blob и скачиваем файл
                    var blob = new Blob([jsonString], { type: 'application/json' });
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'language_' + langId + '_export.json';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Dictionary exported successfully',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    var errorMsg = json.error || 'Failed to export dictionary';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMsg = thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: errorMsg
                    });
                } else {
                    alert('AJAX Error: ' + errorMsg);
                }
            }
        });
    });

    // Add new language
    $('#save-language-btn').on('click', function() {
        var langCode = $('#lang_code').val();
        var langName = $('#lang_name').val();
        var langAbbr = $('#lang_abbr').val();

        if (!langCode || !langName || !langAbbr) {
            alert('Please fill in all fields');
            return;
        }

        var formData = new FormData();
        formData.append('op', 'addLanguage');
        formData.append('lang_code', langCode);
        formData.append('lang_name', langName);
        formData.append('lang_abbr', langAbbr);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Language added successfully',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            var modal = $('#addLanguageModal');
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                var bsModal = bootstrap.Modal.getInstance(modal[0]);
                                if (bsModal) bsModal.hide();
                            } else {
                                modal.modal('hide');
                            }
                            location.reload();
                        });
                    } else {
                        alert('Language added successfully');
                        $('#addLanguageModal').modal('hide');
                        location.reload();
                    }
                } else {
                    var errorMsg = json.error || 'Failed to add language';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMsg = thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: errorMsg
                    });
                } else {
                    alert('AJAX Error: ' + errorMsg);
                }
            }
        });
    });

    // Edit word (используем делегирование)
    $(document).on('click', '.edit-word-btn', function() {
        var row = $(this).closest('tr');
        row.find('.word-val-static').addClass('d-none');
        row.find('.word-val-input').removeClass('d-none');
        row.find('.page-display').addClass('d-none');
        row.find('.page-input').removeClass('d-none');
        row.find('.edit-word-btn').addClass('d-none');
        row.find('.save-word-btn').removeClass('d-none');
        row.find('.cancel-edit-btn').removeClass('d-none');
    });

    // Cancel edit (используем делегирование)
    $(document).on('click', '.cancel-edit-btn', function() {
        var row = $(this).closest('tr');
        var $editBtn = row.find('.edit-word-btn');
        var originalVal = $editBtn.data('val');
        var originalPage = typeof $editBtn.data('page') !== 'undefined' ? $editBtn.data('page') : '';
        if (originalPage === null) {
            originalPage = '';
        }
        row.find('.word-val-input').val(originalVal);
        row.find('.page-input').val(originalPage);
        row.find('.word-val-static').removeClass('d-none');
        row.find('.word-val-input').addClass('d-none');
        row.find('.page-display').removeClass('d-none');
        row.find('.page-input').addClass('d-none');
        row.find('.edit-word-btn').removeClass('d-none');
        row.find('.save-word-btn').addClass('d-none');
        row.find('.cancel-edit-btn').addClass('d-none');
    });

    // Save word (используем делегирование для динамически добавляемых элементов)
    $(document).on('click', '.save-word-btn', function() {
        var btn = $(this);
        var row = btn.closest('tr');
        var wordId = btn.data('word-id');
        var field = btn.data('field');
        var val = row.find('.word-val-input').val();
        var pageVal = row.find('.page-input').val();
        if (typeof pageVal === 'undefined' || pageVal === null) {
            pageVal = '';
        }
        var langId = <?php echo $selected_lang_id ? $selected_lang_id : 0; ?>;

        if (!langId || langId <= 0) {
            alert('Please select a language first');
            return;
        }

        var formData = new FormData();
        formData.append('op', 'updateWord');
        formData.append('word_id', wordId);
        formData.append('field', field);
        formData.append('val', val);
        formData.append('page', pageVal);
        formData.append('lang_id', langId);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success) {
                    row.find('.word-val-static').removeClass('d-none').empty().append(
                        $('<span class="word-val-display"></span>').text(val || '(empty)')
                    );
                    row.find('.edit-word-btn').data('val', val);
                    var pTrim = (pageVal || '').trim();
                    row.find('.edit-word-btn').data('page', pTrim);
                    var $pd = row.find('.page-display');
                    if (pTrim) {
                        $pd.html('<span class="badge bg-info text-dark"></span>');
                        $pd.find('.badge').text(pTrim);
                    } else {
                        $pd.html('<span class="badge bg-secondary">No page</span>');
                    }
                    row.find('.english-val-static small').remove();
                    row.find('.word-val-input').addClass('d-none');
                    row.find('.page-display').removeClass('d-none');
                    row.find('.page-input').addClass('d-none');
                    row.find('.edit-word-btn').removeClass('d-none');
                    row.find('.save-word-btn').addClass('d-none');
                    row.find('.cancel-edit-btn').addClass('d-none');
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Word updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: json.error || 'Failed to update word'
                        });
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
                    });
                }
            }
        });
    });

    // Add new word (обработчик для модального окна)
    $('#save-word-btn').on('click', function() {
        var langId = $('#add-word-lang-id').val();
        var field = $('#word_field').val();
        var val = $('#word_val').val();
        var englishVal = $('#word_english_val').val();

        if (!langId || langId <= 0) {
            alert('Please select a language first');
            return;
        }
        if (!field || !val || !englishVal) {
            alert('Please fill in all required fields');
            return;
        }

        var formData = new FormData();
        formData.append('op', 'addWord');
        formData.append('lang_id', langId);
        formData.append('field', field);
        formData.append('val', val);
        formData.append('english_val', englishVal);
        formData.append('page', $('#word_page').val() || '');

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Word added successfully',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            var modal = $('#addWordModal');
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                var bsModal = bootstrap.Modal.getInstance(modal[0]);
                                if (bsModal) bsModal.hide();
                            } else {
                                modal.modal('hide');
                            }
                            location.reload();
                        });
                    } else {
                        alert('Word added successfully');
                        $('#addWordModal').modal('hide');
                        location.reload();
                    }
                } else {
                    var errorMsg = json.error || 'Failed to add word';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMsg = thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: errorMsg
                    });
                } else {
                    alert('AJAX Error: ' + errorMsg);
                }
            }
        });
    });

    // Import JSON
    $('#import-json-btn').on('click', function() {
        var jsonData = $('#json_data').val();
        var langId = $('#import-json-lang-id').val();

        if (!langId || langId <= 0) {
            alert('Please select a language first');
            return;
        }
        if (!jsonData || jsonData.trim() === '') {
            alert('Please enter JSON data');
            return;
        }

        try {
            var parsed = JSON.parse(jsonData);
        } catch (e) {
            var errorMsg = 'Please check your JSON format: ' + e.message;
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid JSON',
                    text: errorMsg
                });
            } else {
                alert('Invalid JSON: ' + errorMsg);
            }
            return;
        }

        var formData = new FormData();
        formData.append('op', 'importWordsJson');
        formData.append('lang_id', langId);
        formData.append('json_data', jsonData);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success) {
                    var successMsg = json.message || 'Words imported successfully';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: successMsg,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            var modal = $('#importJsonModal');
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                var bsModal = bootstrap.Modal.getInstance(modal[0]);
                                if (bsModal) bsModal.hide();
                            } else {
                                modal.modal('hide');
                            }
                            location.reload();
                        });
                    } else {
                        alert(successMsg);
                        $('#importJsonModal').modal('hide');
                        location.reload();
                    }
                } else {
                    var errorMsg = json.error || 'Failed to import words';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMsg = thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: errorMsg
                    });
                } else {
                    alert('AJAX Error: ' + errorMsg);
                }
            }
        });
    });

    $('#chat-search-field').on('input', function() {
        var q = $.trim($(this).val()).toLowerCase();
        $('#chat-messages-table-body tr').each(function() {
            var $row = $(this);
            if (!q) {
                $row.removeClass('d-none');
                return;
            }
            var hay = $row.text().toLowerCase();
            $row.toggleClass('d-none', hay.indexOf(q) === -1);
        });
    });

    $(document).on('click', '.edit-chat-message-btn', function() {
        var row = $(this).closest('tr');
        row.data('original-message', row.find('.chat-val-input').val());
        row.find('.chat-val-static').addClass('d-none');
        row.find('.chat-val-input').removeClass('d-none');
        row.find('.edit-chat-message-btn').addClass('d-none');
        row.find('.save-chat-message-btn').removeClass('d-none');
        row.find('.cancel-chat-edit-btn').removeClass('d-none');
        row.find('.chat-val-input').trigger('focus');
    });

    $(document).on('click', '.cancel-chat-edit-btn', function() {
        var row = $(this).closest('tr');
        var originalVal = row.data('original-message');
        if (typeof originalVal !== 'undefined') {
            row.find('.chat-val-input').val(originalVal);
        }
        row.find('.chat-val-static').removeClass('d-none');
        row.find('.chat-val-input').addClass('d-none');
        row.find('.edit-chat-message-btn').removeClass('d-none');
        row.find('.save-chat-message-btn').addClass('d-none');
        row.find('.cancel-chat-edit-btn').addClass('d-none');
    });

    $(document).on('click', '.save-chat-message-btn', function() {
        var btn = $(this);
        var row = btn.closest('tr');
        var messageDefaultId = btn.data('message-default-id');
        var val = row.find('.chat-val-input').val();
        var langId = <?php echo $selected_lang_id ? (int) $selected_lang_id : 0; ?>;

        if (!langId || langId <= 0) {
            alert('Please select a language first');
            return;
        }

        var formData = new FormData();
        formData.append('op', 'updateChatMessage');
        formData.append('message_default_id', messageDefaultId);
        formData.append('lang_id', langId);
        formData.append('message', val);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success) {
                    var $static = row.find('.chat-val-static');
                    $static.removeClass('d-none').empty().append(
                        $('<span class="chat-val-display"></span>').text(val || '(empty)')
                    );
                    row.data('original-message', val);
                    row.find('.chat-english-static small').remove();
                    row.find('.chat-val-input').addClass('d-none');
                    row.find('.edit-chat-message-btn').removeClass('d-none');
                    row.find('.save-chat-message-btn').addClass('d-none');
                    row.find('.cancel-chat-edit-btn').addClass('d-none');

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Chat message updated for this language',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: json.error || 'Failed to update chat message'
                        });
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
                    });
                }
            }
        });
    });

    $('#btn-export-chat-json').on('click', function() {
        var langId = <?php echo $selected_lang_id ? (int) $selected_lang_id : 0; ?>;

        if (!langId || langId <= 0) {
            alert('Please select a language first');
            return;
        }

        var formData = new FormData();
        formData.append('op', 'exportChatMessagesJson');
        formData.append('lang_id', langId);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success && json.data) {
                    var jsonString = JSON.stringify(json.data, null, 2);
                    var blob = new Blob([jsonString], { type: 'application/json' });
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'chat_messages_' + langId + '_export.json';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Chat messages exported successfully',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    var errorMsg = json.error || 'Failed to export chat messages';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMsg = thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: errorMsg
                    });
                } else {
                    alert('AJAX Error: ' + errorMsg);
                }
            }
        });
    });

    $('#save-chat-message-add-btn').on('click', function() {
        var langId = $('#add-chat-lang-id').val();
        var messageDefaultId = $('#chat_message_default_id').val();
        var val = $('#chat_message_val').val();
        var englishVal = $('#chat_message_english_val').val();

        if (!langId || langId <= 0) {
            alert('Please select a language first');
            return;
        }
        if (!messageDefaultId || !val || !englishVal) {
            alert('Please fill in all required fields');
            return;
        }

        var formData = new FormData();
        formData.append('op', 'addChatMessage');
        formData.append('lang_id', langId);
        formData.append('message_default_id', messageDefaultId);
        formData.append('val', val);
        formData.append('english_val', englishVal);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Chat message saved successfully',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            var modal = $('#addChatMessageModal');
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                var bsModal = bootstrap.Modal.getInstance(modal[0]);
                                if (bsModal) bsModal.hide();
                            } else {
                                modal.modal('hide');
                            }
                            location.reload();
                        });
                    } else {
                        alert('Chat message saved successfully');
                        $('#addChatMessageModal').modal('hide');
                        location.reload();
                    }
                } else {
                    var errorMsg = json.error || 'Failed to save chat message';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMsg = thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: errorMsg
                    });
                } else {
                    alert('AJAX Error: ' + errorMsg);
                }
            }
        });
    });

    $('#import-chat-json-btn').on('click', function() {
        var jsonData = $('#chat_json_data').val();
        var langId = $('#import-chat-json-lang-id').val();

        if (!langId || langId <= 0) {
            alert('Please select a language first');
            return;
        }
        if (!jsonData || jsonData.trim() === '') {
            alert('Please enter JSON data');
            return;
        }

        try {
            JSON.parse(jsonData);
        } catch (e) {
            var errorMsg = 'Please check your JSON format: ' + e.message;
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid JSON',
                    text: errorMsg
                });
            } else {
                alert('Invalid JSON: ' + errorMsg);
            }
            return;
        }

        var formData = new FormData();
        formData.append('op', 'importChatMessagesJson');
        formData.append('lang_id', langId);
        formData.append('json_data', jsonData);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success) {
                    var successMsg = json.message || 'Chat messages imported successfully';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: successMsg,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            var modal = $('#importChatJsonModal');
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                var bsModal = bootstrap.Modal.getInstance(modal[0]);
                                if (bsModal) bsModal.hide();
                            } else {
                                modal.modal('hide');
                            }
                            location.reload();
                        });
                    } else {
                        alert(successMsg);
                        $('#importChatJsonModal').modal('hide');
                        location.reload();
                    }
                } else {
                    var errorMsg = json.error || 'Failed to import chat messages';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMsg = thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: errorMsg
                    });
                } else {
                    alert('AJAX Error: ' + errorMsg);
                }
            }
        });
    });

    // Очистка форм при закрытии модальных окон (работает для Bootstrap 4 и 5)
    $('#addLanguageModal').on('hidden.bs.modal hidden', function () {
        $('#add-language-form')[0].reset();
    });
    $('#addWordModal').on('hidden.bs.modal hidden', function () {
        $('#add-word-form')[0].reset();
    });
    $('#importJsonModal').on('hidden.bs.modal hidden', function () {
        $('#import-json-form')[0].reset();
    });
    $('#addChatMessageModal').on('hidden.bs.modal hidden', function () {
        $('#add-chat-message-form')[0].reset();
    });
    $('#importChatJsonModal').on('hidden.bs.modal hidden', function () {
        $('#import-chat-json-form')[0].reset();
    });
});
</script>

<?php require_once(ROOT . '/admin/view/template/blocks/footer.php'); ?>

