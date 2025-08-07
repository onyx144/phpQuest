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
    								<h1 class="header-title text-truncate">Games</h1>
    							</div>
                                <div class="col-auto">
                                    Sort by 
                                    <select id="games_order_field">
                                        <option value="date"<?php if ($order == 'date') { echo ' selected="selected"'; } ?>>Date of sale</option>
                                        <option value="score"<?php if ($order == 'score') { echo ' selected="selected"'; } ?>>Score</option>
                                    </select>
                                    <select id="games_sort_field">
                                        <option value="asc"<?php if ($sort == 'asc') { echo ' selected="selected"'; } ?>>Asc (a-z)</option>
                                        <option value="desc"<?php if ($sort == 'desc') { echo ' selected="selected"'; } ?>>Desc (z-a)</option>
                                    </select>
                                </div>
    						</div>
    					</div>
    				</div>

    				<div class="card">
    					<div class="table-responsive">
    						<!-- <table class="table table-sm table-hover table-nowrap card-table table-bordered text-center"> -->
    						<table class="table table-sm table-hover card-table table-bordered text-center">
    							<thead>
    								<tr>
    									<th class="wf-nowrap">Date of Sale</th>
    									<th>Team</th>
    									<th>Status</th>
    									<th>Time</th>
    									<th>Hints</th>
    									<th>Score</th>
                                        <th>Code</th>
                                        <th>Source</th>
    								</tr>
    							</thead>
    							<tbody class="list fs-base">
    								<?php
                                        if (count($games) > 0) {
        								    foreach ($games as $game) {
                                                if (!is_null($game['create']) && $game['create'] != 'null' && !empty($game['create']) && $game['create'] != '0000-00-00 00:00:00') {
    								    	        $game_date = new DateTime($game['create']);
                                                    $print_game_date = $game_date->format('d.m.Y H:i');
                                                } else {
                                                    $print_game_date = '';
                                                }

                                                $percent_text = (!empty($game['mission_finish_seconds']) || (int) $game['progress_percent'] == 100) ? 'Complete' : $game['progress_percent'] . '%';

                                                if (!is_null($game['mission_accept_datetime']) && $game['mission_accept_datetime'] != 'null' && !empty($game['mission_accept_datetime']) && $game['mission_accept_datetime'] != '0000-00-00 00:00:00' && !is_null($game['mission_finish_datetime']) && $game['mission_finish_datetime'] != 'null' && !empty($game['mission_finish_datetime']) && $game['mission_finish_datetime'] != '0000-00-00 00:00:00') {
                                                    $old = new DateTime($game['mission_accept_datetime']);
                                                    $now = new DateTime($game['mission_finish_datetime']);

                                                    $interval = $old->diff($now);

                                                    $print_time = str_pad($interval->days, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->s, 2, '0', STR_PAD_LEFT);
                                                } elseif (!is_null($game['mission_accept_datetime']) && $game['mission_accept_datetime'] != 'null' && !empty($game['mission_accept_datetime']) && $game['mission_accept_datetime'] != '0000-00-00 00:00:00') {
                                                    $old = new DateTime($game['mission_accept_datetime']);
                                                    $now = new DateTime();

                                                    $interval = $old->diff($now);

                                                    $print_time = str_pad($interval->days, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->s, 2, '0', STR_PAD_LEFT);
                                                } else {
                                                    $print_time = '-';
                                                }

        								    	echo '<tr>
        								    	        <td class="wf-nowrap">' . $print_game_date . '</td>
        								    	        <td>' . $game['team_name'] . '</td>
        								    	        <td>' . $percent_text . '</td>
        								    	        <td>' . $print_time . '</td>
        								    	        <td>' . $game['hints_open'] . '</td>
        								    	        <td>' . $game['score'] . '</td>
                                                        <td>' . $game['code'] . '</td>
                                                        <td>' . $game['source_code'] . '</td>
        								    	    </tr>';
        								    }
                                        } else {
                                            echo '<tr><td colspan="8">Empty Results</td></tr>';
                                        }
    								?>
    							</tbody>
    						</table>
    					</div>
                        <?php if ($qt_games > $this->settings['limit']) { ?>
                            <div class="card-footer d-flex justify-content-center"><?php echo $this->pagination->render(); ?></div>
                        <?php } ?>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
<?php
	require_once(ROOT . '/admin/view/template/blocks/footer.php');
