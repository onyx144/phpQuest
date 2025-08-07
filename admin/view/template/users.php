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
                                    <!-- <h6 class="header-pretitle">Overview</h6> -->
                                    <h1 class="header-title text-truncate">Users</h1>
                                </div>
                                <div class="col-auto">
                                    <a href="/add-user" class="btn btn-primary ms-2">Add User</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-nowrap card-table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Status</th>
                                        <th>Login</th>
                                        <th>Password</th>
                                        <th>Role</th>
                                        <th>Source</th>
                                        <th><i class="far fa-trash-alt"></i></th>
                                    </tr>
                                </thead>
                                <tbody class="list fs-base">
                                    <?php
                                        $user_count = $this->start + 1;
                                        foreach ($users as $user_item) {
                                            $source_text = '';

                                            if ($user_item['role_id'] == 3 && !empty($user_item['source_id'])) {
                                                $sql = "SELECT `source_name` FROM `admin_source` WHERE `id` = {?}";
                                                $isset_source_name = $this->db->selectCell($sql, [$user_item['source_id']]);
                                                if ($isset_source_name) {
                                                    $source_text = $isset_source_name;
                                                }
                                            }

                                            echo '<tr data-id="' . $user_item['id'] . '">
                                                    <td onclick="window.location.href = \'/edit-user?id=' . $user_item['id'] . '\'">' . $user_count . '</td>
                                                    <td onclick="window.location.href = \'/edit-user?id=' . $user_item['id'] . '\'">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="list-checkbox custom-control-input wf_change_user_status" id="listCheckboxOne_' . $user_item['id'] . '"' . (!empty($user_item['status']) ? '" checked="checked"' : '') . ' disabled="disabled">
                                                            <label class="custom-control-label" for="listCheckboxOne_' . $user_item['id'] . '"></label>
                                                        </div>
                                                    </td>
                                                    <td onclick="window.location.href = \'/edit-user?id=' . $user_item['id'] . '\'">' . $user_item['login'] . '</td>
                                                    <td onclick="window.location.href = \'/edit-user?id=' . $user_item['id'] . '\'">' . ($user_item['id'] == 1 ? '' : $user_item['password']) . '</td>
                                                    <td onclick="window.location.href = \'/edit-user?id=' . $user_item['id'] . '\'">' . ($user_item['role_id'] == 2 ? 'Admin' : 'Only view source') . '</td>
                                                    <td onclick="window.location.href = \'/edit-user?id=' . $user_item['id'] . '\'">' . $source_text . '</td>
                                                    ' . ($user_item['id'] == 1 ? '<td></td>' : '<td class="wf-remove-user"><i class="far fa-trash-alt"></i></td>') . '
                                                </tr>';

                                            $user_count++;
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($qt_users > $this->settings['limit']) { ?>
                            <div class="card-footer d-flex justify-content-center"><?php echo $this->pagination->render(); ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    require_once(ROOT . '/admin/view/template/blocks/footer.php');
