<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/admin/view/template/blocks/header.php');
    require_once(ROOT . '/admin/view/template/blocks/nav.php');
?>
	<div class="main-content">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-12 col-lg-10 col-xl-8">
					<div class="header">
    					<div class="header-body">
    						<div class="row align-items-center">
    							<div class="col">
                                    <!-- <h6 class="header-pretitle">Overview</h6> -->
    								<h1 class="header-title text-truncate">Settings</h1>
    							</div>
                                <div class="col-auto">
                                    <div class="btn btn-primary ms-2 wf-save-settings">Save</div>
                                </div>
    						</div>
    					</div>
    				</div>

    				<form action="/" method="POST" class="wf-form-settings">
    					<div class="row">
    						<?php
    							$sql = "SELECT `id`, `value`, `comment`, `type`, `values` FROM `admin_settings` WHERE `is_update_admin` = {?} ORDER BY `id`";
    							$fields = $this->db->select($sql, [1]);
    							if ($fields) {
    								foreach ($fields as $field) {
    									if ($field['type'] == 'input') {
    										/*echo '<div class="col-12 col-md-6">
					    							<div class="form-group" data-id="' . $field['id'] . '">
					    								<label class="form-label">' . $field['comment'] . '</label>
					    								<input type="text" class="form-control" value="' . $field['value'] . '" autocomplete="off">
					    							</div>
					    						</div>';*/
					    					echo '<div class="col-12">
					    							<div class="form-group" data-id="' . $field['id'] . '">
					    								<label class="form-label">' . $field['comment'] . '</label>
					    								<input type="text" class="form-control" value="' . $field['value'] . '" autocomplete="off">
					    							</div>
					    						</div>';
    									}
    								}
    							}
    						?>
    					</div>
    				</form>

    				<div class="header" style="margin-top: 100px;">
    					<div class="header-body">
    						<div class="row align-items-center">
    							<div class="col">
                                    <!-- <h6 class="header-pretitle">Overview</h6> -->
    								<h1 class="header-title text-truncate">Sources</h1>
    							</div>
                                <div class="col-auto">
                                    <a href="/add-source" class="btn btn-primary ms-2">Add source</a>
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
    									<th>Source</th>
    									<th><i class="far fa-trash-alt"></i></th>
    								</tr>
    							</thead>
    							<tbody class="list fs-base">
    								<?php
    									$sql = "SELECT * FROM `admin_source` ORDER BY `source_name`";
    									$sources = $this->db->select($sql);
    									if ($sources && count($sources) > 0) {
    										$count_source = 1;
    										foreach ($sources as $source) {
    											echo '<tr data-id="' . $source['id'] . '">
    													<td onclick="window.location.href = \'/edit-source?id=' . $source['id'] . '\'">' . $count_source . '</td>
    													<td onclick="window.location.href = \'/edit-source?id=' . $source['id'] . '\'">' . $source['source_name'] . '</td>
    													<td class="wf-remove-source"><i class="far fa-trash-alt"></i></td>
    												</tr>';

    											$count_source++;
    										}
    									} else {
    										echo '<tr><td colspan="3">Empty Results</td></tr>';
    									}
    								?>
    							</tbody>
    						</table>
    					</div>
    				</div>

    				<div class="header" style="margin-top: 100px;">
    					<div class="header-body">
    						<div class="row align-items-center">
    							<div class="col">
                                    <!-- <h6 class="header-pretitle">Overview</h6> -->
    								<h1 class="header-title text-truncate">Names of the game</h1>
    							</div>
                                <div class="col-auto">
                                    <a href="/add-gamename" class="btn btn-primary ms-2">Add game name</a>
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
    									<th>Game name</th>
    									<th><i class="far fa-trash-alt"></i></th>
    								</tr>
    							</thead>
    							<tbody class="list fs-base">
    								<?php
    									$sql = "SELECT * FROM `admin_game_names` ORDER BY `game_name`";
    									$gamenames = $this->db->select($sql);
    									if ($gamenames && count($gamenames) > 0) {
    										$count_gamename = 1;
    										foreach ($gamenames as $gamename) {
    											echo '<tr data-id="' . $gamename['id'] . '">
    													<td onclick="window.location.href = \'/edit-gamename?id=' . $gamename['id'] . '\'">' . $count_gamename . '</td>
    													<td onclick="window.location.href = \'/edit-gamename?id=' . $gamename['id'] . '\'">' . $gamename['game_name'] . '</td>
    													<td class="wf-remove-gamename"><i class="far fa-trash-alt"></i></td>
    												</tr>';

    											$count_gamename++;
    										}
    									} else {
    										echo '<tr><td colspan="3">Empty Results</td></tr>';
    									}
    								?>
    							</tbody>
    						</table>
    					</div>
    				</div>
				</div>
			</div>
		</div>
	</div>
<?php
	require_once(ROOT . '/admin/view/template/blocks/footer.php');
