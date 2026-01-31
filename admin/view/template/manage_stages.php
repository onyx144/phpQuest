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
                                <h1 class="header-title text-truncate">Manage Game Stages</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Teams List</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover card-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Code</th>
                                                <th>Team Name</th>
                                                <th>Current Stage</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($teams)): ?>
                                                <?php foreach ($teams as $team): ?>
                                                    <tr class="team-row <?php echo ($selected_team_id == $team['id']) ? 'table-active' : ''; ?>" 
                                                        data-team-id="<?php echo $team['id']; ?>" 
                                                        style="cursor: pointer;">
                                                        <td><?php echo htmlspecialchars($team['id']); ?></td>
                                                        <td><?php echo htmlspecialchars($team['code']); ?></td>
                                                        <td><?php echo htmlspecialchars($team['team_name']); ?></td>
                                                        <td>
                                                            <span class="badge bg-info team-current-stage" data-team-id="<?php echo $team['id']; ?>">
                                                                <?php echo htmlspecialchars($team['last_dashboard'] ?: 'accept_new_mission'); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge <?php echo ($selected_team_id == $team['id']) ? 'bg-success' : 'bg-secondary'; ?>">
                                                                <?php echo ($selected_team_id == $team['id']) ? 'Selected' : 'Click to select'; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">No teams found</td>
                                                </tr>
                                            <?php endif; ?>
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
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Manage Stages</h5>
                            </div>
                            <div class="card-body" id="team-stages-container">
                                <?php if ($selected_team): ?>
                                    <div class="mb-3">
                                        <h6 id="selected-team-name">Team: <?php echo htmlspecialchars($selected_team['team_name']); ?> (<?php echo htmlspecialchars($selected_team['code']); ?>)</h6>
                                        <p class="text-muted mb-0">Current Stage: <strong id="selected-team-stage"><?php echo htmlspecialchars($selected_team['last_dashboard'] ?: 'accept_new_mission'); ?></strong></p>
                                    </div>
                                    
                                    <div class="stages-manager">
                                        <h6 class="mb-3">Select Stage:</h6>
                                        <div class="stages-checkboxes" id="stages-checkboxes-container">
                                            <?php foreach ($stages as $stage_key => $stage_name): ?>
                                                <div class="form-check mb-2">
                                                    <input 
                                                        class="form-check-input stage-checkbox" 
                                                        type="checkbox" 
                                                        id="stage_<?php echo $selected_team['id']; ?>_<?php echo $stage_key; ?>"
                                                        data-team-id="<?php echo $selected_team['id']; ?>"
                                                        data-stage="<?php echo $stage_key; ?>"
                                                        <?php echo ($selected_team['last_dashboard'] == $stage_key) ? 'checked' : ''; ?>
                                                    >
                                                    <label class="form-check-label" for="stage_<?php echo $selected_team['id']; ?>_<?php echo $stage_key; ?>">
                                                        <?php echo htmlspecialchars($stage_name); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Please click on a team from the list to manage its stages.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var currentTeamId = <?php echo $selected_team_id ? $selected_team_id : 0; ?>;
    var stages = <?php echo json_encode($stages); ?>;

    // Клик по строке команды
    $(document).on('click', '.team-row', function() {
        var teamId = $(this).data('team-id');
        
        // Убираем выделение со всех строк
        $('.team-row').removeClass('table-active');
        $('.team-row .badge').removeClass('bg-success').addClass('bg-secondary').text('Click to select');
        
        // Выделяем выбранную строку
        $(this).addClass('table-active');
        $(this).find('.badge').removeClass('bg-secondary').addClass('bg-success').text('Selected');
        
        // Загружаем данные команды
        loadTeamData(teamId);
    });

    // Загрузка данных команды через AJAX
    function loadTeamData(teamId) {
        var formData = new FormData();
        formData.append('op', 'getTeamData');
        formData.append('team_id', teamId);

        $.ajax({
            url: '/admin/ajax/ajax.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {
                if (json.success && json.team) {
                    currentTeamId = teamId;
                    var team = json.team;
                    var currentStage = team.last_dashboard || 'accept_new_mission';

                    // Обновляем информацию о команде
                    var teamInfoHtml = '<div class="mb-3">' +
                        '<h6 id="selected-team-name">Team: ' + escapeHtml(team.team_name) + ' (' + escapeHtml(team.code) + ')</h6>' +
                        '<p class="text-muted mb-0">Current Stage: <strong id="selected-team-stage">' + escapeHtml(currentStage) + '</strong></p>' +
                        '</div>';

                    // Создаем чекбоксы стадий
                    var checkboxesHtml = '<div class="stages-manager">' +
                        '<h6 class="mb-3">Select Stage:</h6>' +
                        '<div class="stages-checkboxes" id="stages-checkboxes-container">';

                    for (var stageKey in stages) {
                        var isChecked = (currentStage == stageKey) ? 'checked' : '';
                        checkboxesHtml += '<div class="form-check mb-2">' +
                            '<input class="form-check-input stage-checkbox" type="checkbox" ' +
                            'id="stage_' + teamId + '_' + stageKey + '" ' +
                            'data-team-id="' + teamId + '" ' +
                            'data-stage="' + stageKey + '" ' + isChecked + '>' +
                            '<label class="form-check-label" for="stage_' + teamId + '_' + stageKey + '">' +
                            escapeHtml(stages[stageKey]) +
                            '</label>' +
                            '</div>';
                    }

                    checkboxesHtml += '</div></div>';

                    // Обновляем контейнер
                    $('#team-stages-container').html(teamInfoHtml + checkboxesHtml);
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: json.error || 'Failed to load team data'
                        });
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error('AJAX error:', thrownError);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load team data'
                    });
                }
            }
        });
    }

    // Обработка изменения чекбоксов стадий
    $(document).on('change', '.stage-checkbox', function() {
        var checkbox = $(this);
        var teamId = checkbox.data('team-id');
        var stage = checkbox.data('stage');
        var isChecked = checkbox.is(':checked');

        // Снимаем все остальные чекбоксы для этой команды
        if (isChecked) {
            $('.stage-checkbox[data-team-id="' + teamId + '"]').not(checkbox).prop('checked', false);
            
            // Обновляем текущую стадию в интерфейсе
            $('#selected-team-stage').text(stage);
            $('.team-current-stage[data-team-id="' + teamId + '"]').text(stage);

            // Отправляем AJAX запрос для обновления БД
            var formData = new FormData();
            formData.append('op', 'updateTeamStage');
            formData.append('team_id', teamId);
            formData.append('stage', stage);

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
                        console.log('Stage updated successfully');
                        // Показываем уведомление об успехе
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Stage updated successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    } else {
                        console.error('Error updating stage:', json.error);
                        // Возвращаем чекбокс в исходное состояние при ошибке
                        checkbox.prop('checked', false);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: json.error || 'Failed to update stage'
                            });
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error('AJAX error:', thrownError);
                    checkbox.prop('checked', false);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update stage'
                        });
                    }
                }
            });
        } else {
            // Если сняли галочку, возвращаем предыдущую стадию
            checkbox.prop('checked', true);
        }
    });

    // Функция для экранирования HTML
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});
</script>

<?php require_once(ROOT . '/admin/view/template/blocks/footer.php'); ?>

