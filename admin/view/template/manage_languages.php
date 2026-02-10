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

                <?php if ($selected_lang && !empty($words_with_english)): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title mb-0">
                                            Words Dictionary: <?php echo htmlspecialchars($selected_lang['lang_name']); ?>
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
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                            <?php if ($search !== ''): ?>
                                            <a href="/language?lang_id=<?php echo (int)$selected_lang_id; ?>" class="btn btn-outline-secondary">Clear</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover card-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%;">Field (Code)</th>
                                                <th style="width: 35%;">Word in <?php echo htmlspecialchars($selected_lang['lang_name']); ?></th>
                                                <th style="width: 35%;">English Equivalent</th>
                                                <th style="width: 10%;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="words-table-body">
                                            <?php foreach ($words_with_english as $word): ?>
                                                <tr data-word-id="<?php echo $word['id']; ?>" data-field="<?php echo htmlspecialchars($word['field']); ?>">
                                                    <td>
                                                        <code><?php echo htmlspecialchars($word['field']); ?></code>
                                                    </td>
                                                    <td>
                                                        <?php if ($selected_lang['lang_abbr'] == 'en'): ?>
                                                            <span class="word-val-display"><?php echo htmlspecialchars($word['val'] ?: '(empty)'); ?></span>
                                                            <input type="text" class="form-control form-control-sm word-val-input d-none" 
                                                                   value="<?php echo htmlspecialchars($word['val']); ?>" 
                                                                   data-field="<?php echo htmlspecialchars($word['field']); ?>">
                                                        <?php else: ?>
                                                            <span class="word-val-display"><?php echo htmlspecialchars($word['val'] ?: '(empty)'); ?></span>
                                                            <input type="text" class="form-control form-control-sm word-val-input d-none" 
                                                                   value="<?php echo htmlspecialchars($word['val']); ?>" 
                                                                   data-field="<?php echo htmlspecialchars($word['field']); ?>">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="english-val-display"><?php echo htmlspecialchars($word['english_val'] ?: '(empty)'); ?></span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary edit-word-btn" 
                                                                data-word-id="<?php echo $word['id']; ?>" 
                                                                data-field="<?php echo htmlspecialchars($word['field']); ?>"
                                                                data-val="<?php echo htmlspecialchars($word['val']); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-success save-word-btn d-none" 
                                                                data-word-id="<?php echo $word['id']; ?>" 
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
                                    <div class="mt-3">
                                        <?php echo $this->pagination->render(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif ($selected_lang && empty($words_with_english)): ?>
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
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                            <?php if ($search !== ''): ?>
                                            <a href="/language?lang_id=<?php echo (int)$selected_lang_id; ?>" class="btn btn-outline-secondary">Clear</a>
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
                        <label for="word_page" class="form-label">Page (optional):</label>
                        <input type="text" class="form-control" id="word_page" name="word_page">
                        <small class="form-text text-muted">Page identifier where this word is used</small>
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
                            Format: <code>{"field1": "value1", "field2": "value2", ...}</code><br>
                            Or with English: <code>{"field1": {"val": "value1", "english": "english_value1"}, ...}</code>
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

<script>
$(document).ready(function() {
    console.log('Language management script loaded');
    
    // Language selection change
    $('#language-select').on('change', function() {
        var langId = $(this).val();
        if (langId > 0) {
            window.location.href = '/language?lang_id=' + langId;
        } else {
            window.location.href = '/language';
        }
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
        row.find('.word-val-display').addClass('d-none');
        row.find('.word-val-input').removeClass('d-none');
        row.find('.edit-word-btn').addClass('d-none');
        row.find('.save-word-btn').removeClass('d-none');
        row.find('.cancel-edit-btn').removeClass('d-none');
    });

    // Cancel edit (используем делегирование)
    $(document).on('click', '.cancel-edit-btn', function() {
        var row = $(this).closest('tr');
        var originalVal = row.find('.edit-word-btn').data('val');
        row.find('.word-val-input').val(originalVal);
        row.find('.word-val-display').removeClass('d-none');
        row.find('.word-val-input').addClass('d-none');
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
                    row.find('.word-val-display').text(val || '(empty)');
                    row.find('.edit-word-btn').data('val', val);
                    row.find('.word-val-display').removeClass('d-none');
                    row.find('.word-val-input').addClass('d-none');
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
});
</script>

<?php require_once(ROOT . '/admin/view/template/blocks/footer.php'); ?>

